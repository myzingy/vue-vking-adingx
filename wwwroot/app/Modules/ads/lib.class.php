<?php
/**
 * author vking
 * 文章
 */
namespace Modules\ads;
use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Cursor;
use FacebookAds\Object\Values\ArchivableCrudObjectEffectiveStatuses;
//广告组
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Fields\AdFields;
class lib{
    function __construct($id="") {
    	$this->model=new model();
		$id=$id?$id:I('request.id');
		if($id){
			$this->model->relation(array())->find($id);
		}
    }
	function flushAds(){
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
            AdFields::ACCOUNT_ID,
            AdFields::AD_REVIEW_FEEDBACK,
            AdFields::ADLABELS,
            //AdFields::ADSET,
            AdFields::ADSET_ID,
            AdFields::BID_AMOUNT,
            AdFields::BID_INFO,
            AdFields::BID_TYPE,
            //AdFields::CAMPAIGN,
            AdFields::CAMPAIGN_ID,
            AdFields::CONFIGURED_STATUS,
            //AdFields::CONVERSION_SPECS,
            AdFields::CREATED_TIME,
            //AdFields::CREATIVE,
            AdFields::EFFECTIVE_STATUS,
            AdFields::ID,
            AdFields::LAST_UPDATED_BY_APP_ID,
            AdFields::NAME,
            //AdFields::RECOMMENDATIONS,
            AdFields::STATUS,
            //AdFields::TRACKING_SPECS,
            AdFields::UPDATED_TIME,
            AdFields::ADSET_SPEC,
            AdFields::DATE_FORMAT,
            AdFields::DISPLAY_SEQUENCE,
            AdFields::EXECUTION_OPTIONS,
            AdFields::REDOWNLOAD,
            AdFields::FILENAME
        );
        $adsets = $account->getAds(
            $fields,
            array(
                AdFields::EFFECTIVE_STATUS =>$EFFECTIVE_STATUS,
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
            if(ArchivableCrudObjectEffectiveStatuses::ACTIVE==$_campaigns_data[AdFields::EFFECTIVE_STATUS]) {
                asyn('apido/asyn.flushAdsInsights', array(
                    'ad_id' => $_campaigns_data['id'],
                    'ac_id'=>$ac_id
                ));
            }
        }

        if(count($campaigns_data)>0){
            $this->model->addAll($campaigns_data,null,true);
        }
        if($adsets->getNext() && count($campaigns_data)==25){
            asyn('apido/asyn.flushAds',$asyn_param,null,null,2);
        }
        return $campaigns_data;
    }
    function runRules(){
        $ad_id=I('request.id',''); //ad_id;
        $type=I('request.type','');
        //$insights=new \Modules\ads\insights\lib();
        //$data=$insights->getAdsInsightsData($ad_id);
        $data=$this->getAdsData($ad_id);
        if(!$data['data']) return;
        $formatData=$data['data'];
        $exec=new \Modules\rules\exec($formatData[0],'ad');
        $exec->run();
        return $formatData;
    }
    //用于替换 getAdsInsightsData
    function getAdsData($ad_id=""){
        $where=" AI.date_stop='".date('Y-m-d',NOW_TIME)."' ";
        $ac_id=I('request.ac_id');
        if($ac_id){
            $where.=" AND ads.account_id='$ac_id' ";
        }
        if($ad_id){
            $where.=" and AI.ad_id='$ad_id' ";
        }
        $keyword_type=I('request.keyword_type');
        if($keyword_type){
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
        if($checked_adsets=I('request.checked_adsets')){
            $adsets=array();
            foreach ($checked_adsets as $r){
                $adsets[]="'{$r['id']}'";
            }
            $where.=" and AI.adset_id in (".implode(',',$adsets).")";
        }
        $data=$this->model
            ->field('ads.id,ads.name,ads.effective_status')
            ->relation(array('insights','rules_run'))
            ->join('ads_insights AI ON AI.ad_id=ads.id')
            ->where($where)
            ->order('ads.updated_time desc')
            ->select();
        $formatData=formatInsightsData($data,'ad');
        return array('data'=>$formatData);
    }
}