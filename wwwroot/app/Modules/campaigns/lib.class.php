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
	function flushCampaigns(){
        vendor("vendor.autoload");
        $fb_conf=C('fb');
        $fba=Api::init($fb_conf['app_id'],$fb_conf['app_secret'],$fb_conf['zhule']['access_tokens']);
        $api = Api::instance();
        $account =new AdAccount($fb_conf['zhule']['account_id']);
        $campaigns_data=array();
        $after=I('request.after','');
        $active=I('request.active','');
        $EFFECTIVE_STATUS=array(
            ArchivableCrudObjectEffectiveStatuses::ACTIVE,
        );
        $asyn_param=array('after'=>'');
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
                asyn('apido/asyn.flushAdsets',array(
                    'campaign_id'=>$_campaigns_data['id'],
                    'active'=>'active'
                ));
                asyn('apido/asyn.flushCampaignsInsights',array(
                    'campaign_id'=>$_campaigns_data['id']
                ));
            }

        }

        if(count($campaigns_data)>0){
            //$model=new \Modules\campaigns\model();
            //$model->truncate();
            $this->model->addAll($campaigns_data,null,true);
        }
        if($campaigns->getNext() && count($campaigns_data)==25){
            asyn('apido/asyn.flushCampaigns',$asyn_param);
        }
        return $campaigns_data;
    }
}