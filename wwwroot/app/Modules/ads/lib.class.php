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
        $adset_id=I('request.adset_id','');
        if(!$adset_id)   return;
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
        $asyn_param=array('adset_id'=>$adset_id,'after'=>'');
        if(!$active){
            array_push($EFFECTIVE_STATUS,ArchivableCrudObjectEffectiveStatuses::PAUSED);
        }else{
            $asyn_param['active']=$active;
        }

        $adset = new AdSet($adset_id);
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
        $adsets = $adset->getAds(
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
                    'ad_id' => $_campaigns_data['id']
                ));
            }
        }

        if(count($campaigns_data)>0){
            $this->model->addAll($campaigns_data,null,true);
        }
        if($adsets->getNext() && count($campaigns_data)==25){
            asyn('apido/asyn.flushAds',$asyn_param);
        }
        return $campaigns_data;
    }
}