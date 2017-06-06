<?php
/**
 * author vking
 * 文章
 */
namespace Modules\adsets;
use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Cursor;
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
        $campaign_id=I('request.campaign_id','');
        if(!$campaign_id)   return;
        vendor("vendor.autoload");
        $fb_conf=C('fb');
        $fba=Api::init($fb_conf['app_id'],$fb_conf['app_secret'],$fb_conf['zhule']['access_tokens']);
        $api = Api::instance();
        //$account =new AdAccount($fb_conf['zhule']['account_id']);
        $campaigns_data=array();
        $after=I('request.after','');
        $active=I('request.active','');
        $EFFECTIVE_STATUS=array(
            ArchivableCrudObjectEffectiveStatuses::ACTIVE,
        );
        $asyn_param=array('campaign_id'=>$campaign_id,'after'=>'');
        if(!$active){
            array_push($EFFECTIVE_STATUS,ArchivableCrudObjectEffectiveStatuses::PAUSED);
        }else{
            $asyn_param['active']=$active;
        }

        $campaign = new Campaign($campaign_id);
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
        $adsets = $campaign->getAdSets(
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
            asyn('apido/asyn.flushAds', array(
                'adset_id' => $_campaigns_data['id'],
                'active'=>'active'
            ));
            if(ArchivableCrudObjectEffectiveStatuses::ACTIVE==$_campaigns_data[AdSetFields::EFFECTIVE_STATUS]) {
                asyn('apido/asyn.flushAdsetsInsights', array(
                    'adset_id' => $_campaigns_data['id']
                ));
            }
        }


        if(count($campaigns_data)>0){
            $this->model->addAll($campaigns_data,null,true);
        }
        if($adsets->getNext() && count($campaigns_data)==25){
            asyn('apido/asyn.flushAdsets',$asyn_param);
        }
        return $campaigns_data;
    }
    function runRules(){
        $id=I('request.id',''); //md5(id+start_date);
        $type=I('request.type',''); //md5(id+start_date);
        $insights=new \Modules\adsets\insights\lib($id);
        $data=$insights->model->data();
        if(!$data['id']) return;
        $formatData=formatInsightsData([$data],'adset');
        $exec=new \Modules\rules\exec($formatData[0],'adset');
        $exec->run();
        return $formatData;
    }
}