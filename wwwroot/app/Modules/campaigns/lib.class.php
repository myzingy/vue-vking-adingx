<?php
/**
 * author vking
 * 文章
 */
namespace Modules\campaigns;
use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Cursor;
//广告系列
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\Values\CampaignObjectiveValues;
use FacebookAds\Object\Values\ArchivableCrudObjectEffectiveStatuses;
class lib{
    function __construct($id="") {
    	$this->model=new model();
        $id=$id?$id:I('request.id');
		if($id){
			$this->model->relation(array())->find($id);
		}
    }
    function flushCampaignsInit(){
        //20:00-08:00 不获取数据
        $time_s=getDayTime("20:00:00",0);
        $time_e=getDayTime("08:00:00",0);
        if(NOW_TIME > $time_s || NOW_TIME < $time_e) return;
        $acs=FBC();
        foreach ($acs as $ac){
            asyn('apido/asyn.flushCampaigns',array(
                'ac_id'=>$ac['account_id'],
                'active'=>'active'
            ),null,null,90);
            asyn('apido/asyn.flushAdsets',array(
                'ac_id'=>$ac['account_id'],
                'active'=>'active'
            ),null,null,90);
            asyn('apido/asyn.flushAds',array(
                'ac_id'=>$ac['account_id'],
                'active'=>'active'
            ),null,null,90);
            asyn('apido/asyn.flushAccounts',array(
                'ac_id'=>$ac['account_id'],
                'active'=>'active'
            ));
        }
    }
	function flushCampaigns(){
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
        $asyn_param=array('after'=>'','ac_id'=>$ac_id);
        if(!$active){
            array_push($EFFECTIVE_STATUS,ArchivableCrudObjectEffectiveStatuses::PAUSED);
            //array_push($EFFECTIVE_STATUS,ArchivableCrudObjectEffectiveStatuses::ARCHIVED);
        }else{
            $asyn_param['active']=$active;
        }
        $fields=array(
            CampaignFields::ACCOUNT_ID,
            CampaignFields::ADLABELS,
            CampaignFields::BRAND_LIFT_STUDIES,
            CampaignFields::BUDGET_REBALANCE_FLAG,
            CampaignFields::BUYING_TYPE,
            CampaignFields::CAN_CREATE_BRAND_LIFT_STUDY,
            CampaignFields::CAN_USE_SPEND_CAP,
            CampaignFields::CONFIGURED_STATUS,
            CampaignFields::CREATED_TIME,
            CampaignFields::EFFECTIVE_STATUS,
            CampaignFields::ID,
            CampaignFields::NAME,
            CampaignFields::OBJECTIVE,
            CampaignFields::RECOMMENDATIONS,
            CampaignFields::SPEND_CAP,
            CampaignFields::START_TIME,
            CampaignFields::STATUS,
            CampaignFields::STOP_TIME,
            CampaignFields::UPDATED_TIME,
            CampaignFields::EXECUTION_OPTIONS,
        );
        $campaigns = $account->getCampaigns($fields, array(
            CampaignFields::EFFECTIVE_STATUS =>$EFFECTIVE_STATUS,
            //limit => 25,
            after=>$after,
        ));
        $asyn_param['after']=$campaigns->getAfter();
        while ($campaigns->valid()) {
            $_d=$campaigns->current()->getData();
            $_campaigns_data=array();
            foreach ($fields as $i=>$fk){
                $_campaigns_data[$fk]= $_d[$fk];
            }
            array_push($campaigns_data,$_campaigns_data);
            $campaigns->next();
            if(ArchivableCrudObjectEffectiveStatuses::ACTIVE==$_campaigns_data[CampaignFields::EFFECTIVE_STATUS]) {
//                asyn('apido/asyn.flushAdsets',array(
//                    'campaign_id'=>$_campaigns_data['id'],
//                    'active'=>'active'
//                ));
                asyn('apido/asyn.flushCampaignsInsights',array(
                    'campaign_id'=>$_campaigns_data['id'],
                    'ac_id'=>$ac_id
                ));
            }

        }

        if(count($campaigns_data)>0){
            //$model=new \Modules\campaigns\model();
            //$model->truncate();
            $this->model->addAll($campaigns_data,null,true);
        }
        if($campaigns->getNext() && count($campaigns_data)==25){
            asyn('apido/asyn.flushCampaigns',$asyn_param,null,null,2);
        }
        return $campaigns_data;
    }
    //用于替换 getCampaignsInsightsData
    function getCampaignsData(){
        $where=" AI.date_stop='".date('Y-m-d',NOW_TIME)."' ";
        $ac_id=I('request.ac_id');
        if($ac_id){
            $where.=" AND campaigns.account_id='$ac_id' ";
        }
        $keyword_type=I('request.keyword_type');
        if($keyword_type=='campaign'){
            $keyword=trim(I('request.keyword'));
            if($keyword){
                $where.=" and ( AI.`{$keyword_type}_id` like '%{$keyword}%' OR AI.`{$keyword_type}_name` like '%{$keyword}%' ) ";
            }
        }
        $data=$this->model
            ->field('campaigns.id,campaigns.name,campaigns.effective_status')
            ->relation(array('insights'))
            ->join('campaigns_insights AI ON AI.campaign_id=campaigns.id')
            ->where($where)
            ->order('campaigns.updated_time desc')
            ->select();
        $formatData=formatInsightsData($data,'campaign');
        return array('data'=>$formatData);
    }
}