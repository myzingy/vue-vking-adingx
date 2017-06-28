<?php
/**
 * author vking
 * 文章
 */
namespace Modules\adsets;
use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Cursor;
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Values\ArchivableCrudObjectEffectiveStatuses;
//广告组
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\AdSetFields;
class lib{
    function __construct($id="") {
    	$this->model=new model();
		$id=$id?$id:I('request.id');
		if($id){
			$this->model->relation(array())->find($id);
		}
    }
	function flushAdsets(){
        $ac_id=I('request.ac_id');
        if(!$ac_id) return;
        $ac=FBC($ac_id);
        vendor("vendor.autoload");
        $fba=Api::init($ac['app_id'],$ac['app_secret'],$ac['access_tokens']);
        $api = Api::instance();
        $account =new AdAccount($ac['act_id']);

        $campaigns_data=array();
        $after=I('request.after','');
        $active=I('request.active','');
        $EFFECTIVE_STATUS=array(
            ArchivableCrudObjectEffectiveStatuses::ACTIVE,
        );
        $asyn_param=array('ac_id'=>$ac_id,'after'=>'');
        if(!$active){
            array_push($EFFECTIVE_STATUS,ArchivableCrudObjectEffectiveStatuses::PAUSED);
        }else{
            $asyn_param['active']=$active;
        }
        $fields= array(
            AdSetFields::ACCOUNT_ID,
            AdSetFields::BILLING_EVENT,
            AdSetFields::BUDGET_REMAINING,
            AdSetFields::CAMPAIGN_ID,
            AdSetFields::CONFIGURED_STATUS,
            AdSetFields::CREATED_TIME,
            AdSetFields::DAILY_BUDGET,
            AdSetFields::EFFECTIVE_STATUS,
            AdSetFields::FREQUENCY_CAP_RESET_PERIOD,
            AdSetFields::ID,
            AdSetFields::IS_AUTOBID,
            AdSetFields::IS_AVERAGE_PRICE_PACING,
            AdSetFields::LIFETIME_BUDGET,
            AdSetFields::LIFETIME_IMPS,
            AdSetFields::NAME,
            AdSetFields::OPTIMIZATION_GOAL,
            AdSetFields::RECURRING_BUDGET_SEMANTICS,
            AdSetFields::RTB_FLAG,
            AdSetFields::START_TIME,
            AdSetFields::STATUS,
            AdSetFields::UPDATED_TIME
        );
        $adsets = $account->getAdSets(
            $fields,
            array(
                AdSetFields::EFFECTIVE_STATUS =>$EFFECTIVE_STATUS,
                //limit => 25,
                after=>$after,
            )
        );

        $asyn_param['after']=$adsets->getAfter();
        while ($adsets->valid()) {
            $_d=$adsets->current()->getData();
            $_campaigns_data=array();
            foreach ($fields as $i=>$fk){
                $_campaigns_data[$fk]= $_d[$fk];
            }
            array_push($campaigns_data,$_campaigns_data);
            $adsets->next();
//            asyn('apido/asyn.flushAds', array(
//                'adset_id' => $_campaigns_data['id'],
//                'active'=>'active'
//            ));
            if(ArchivableCrudObjectEffectiveStatuses::ACTIVE==$_campaigns_data[AdSetFields::EFFECTIVE_STATUS]) {
                asyn('apido/asyn.flushAdsetsInsights', array(
                    'adset_id' => $_campaigns_data['id'],
                    'ac_id'=>$ac_id
                ));
            }
        }


        if(count($campaigns_data)>0){
            $this->model->addAll($campaigns_data,null,true);
        }
        if($adsets->getNext() && count($campaigns_data)==25){
            asyn('apido/asyn.flushAdsets',$asyn_param,null,null,2);
        }
        return $campaigns_data;
    }
    function runRules(){
        $adset_id=I('request.id',''); //adset_id;
        $type=I('request.type','');
        //$insights=new \Modules\adsets\insights\lib();
        //$data=$insights->getAdsetsInsightsData($adset_id);
        $data=$this->getAdsetsData($adset_id);
        $debug_ids=explode(',','23842624232450368,23842623980060368,23842622254040368,23842623986200368,23842623983240368,23842623977590368,23842620950830368');
        $debug_flag=false;
        if(in_array($adset_id,$debug_ids)!==false){
            debug("debug.runRules.{$adset_id}########==>START");
            $debug_flag=true;
        }
        if(!$data['data']) return;
        $formatData=$data['data'];
        $exec=new \Modules\rules\exec($formatData[0],'adset',$debug_flag);
        $exec->run();
        if($debug_flag){
            debug("debug.runRules.{$adset_id}########<==ENDEND");
        }
        return $formatData;
    }
    //用于替换 getAdsetsInsightsData
    function getAdsetsData($adset_id){

        $where=" AI.date_stop='".date('Y-m-d',NOW_TIME)."' ";
        $ac_id=I('request.ac_id');
        if($ac_id){
            $where.=" AND adsets.account_id='$ac_id' ";
        }
        if($adset_id){
            $where.=" and AI.adset_id='$adset_id' ";
        }

        $keyword_type=I('request.keyword_type');
        if($keyword_type=='campaign' || $keyword_type=='adset'){
            $keyword=trim(I('request.keyword'));
            if($keyword){
                //$where.=" and AI.`{$keyword_type}_name` like '%{$keyword}%' ";
                $where.=" and ( AI.`{$keyword_type}_id` like '%{$keyword}%' OR AI.`{$keyword_type}_name` like '%{$keyword}%' ) ";
            }
        }
        if($checked_campaigns=I('request.checked_campaigns')){
            $campaigns=array();
            foreach ($checked_campaigns as $r){
                $campaigns[]="'{$r['id']}'";
            }
            $where.=" and AI.campaign_id in (".implode(',',$campaigns).")";
        }
        $data=$this->model
            ->field('adsets.id,adsets.name,adsets.effective_status,adsets.daily_budget')
            ->relation(array('insights','rules_run'))
            ->join('adsets_insights AI ON AI.adset_id=adsets.id')
            ->where($where)
            ->order('adsets.updated_time desc')
            ->select();
        $formatData=formatInsightsData($data,'adset');
        return array('data'=>$formatData);
    }
    //调整fb预算
    function setBudget(){
        if(__APP__POS=='CC__DEV') return;
        $ac_id=I('request.ac_id');
        if(!$ac_id) return;
        $ac=FBC($ac_id);
        vendor("vendor.autoload");
        $fba=Api::init($ac['app_id'],$ac['app_secret'],$ac['access_tokens']);
        $api = Api::instance();

        $adset_id=I('request.adset_id');
        $budget=(int)(I('request.budget')*100);
        $adset=new AdSet($adset_id);
        $adset->save(array(
            'daily_budget'=>$budget
        ));
    }
}