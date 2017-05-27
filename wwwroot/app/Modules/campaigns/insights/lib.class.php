<?php
/**
 * author vking
 * 文章
 */
namespace Modules\campaigns\insights;
use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Cursor;
//广告系列
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\AdsInsightsFields;
use FacebookAds\Object\Values\CampaignObjectiveValues;
use FacebookAds\Object\Values\ArchivableCrudObjectEffectiveStatuses;
class lib{
    function __construct($id="") {
    	$this->model=new model();
    }
	function flushCampaignsInsights(){
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
        $fields_str=<<<END
        ["account_id"] => string(16) "1593565507558990"
        ["account_name"] => NULL
        ["action_values"] => NULL
        ["actions"] => NULL
        ["app_store_clicks"] => NULL
        ["buying_type"] => NULL
        ["call_to_action_clicks"] => NULL
        ["campaign_id"] => string(13) "6032660080764"
        ["campaign_name"] => NULL
        ["clicks"] => NULL
        ["cost_per_action_type"] => NULL
        ["cost_per_estimated_ad_recallers"] => NULL
        ["cost_per_inline_link_click"] => NULL
        ["cost_per_inline_post_engagement"] => NULL
        ["cost_per_total_action"] => NULL
        ["cost_per_unique_action_type"] => NULL
        ["cost_per_unique_click"] => NULL
        ["cost_per_unique_inline_link_click"] => NULL
        ["cpc"] => NULL
        ["cpm"] => NULL
        ["cpp"] => NULL
        ["ctr"] => NULL
        ["date_start"] => string(10) "2017-05-01"
        ["date_stop"] => string(10) "2017-05-01"
        ["deeplink_clicks"] => NULL
        ["estimated_ad_recall_rate"] => NULL
        ["frequency"] => NULL
        ["impressions"] => string(3) "133"
        ["inline_link_click_ctr"] => NULL
        ["inline_link_clicks"] => NULL
        ["inline_post_engagement"] => NULL
        ["objective"] => NULL
        ["reach"] => NULL
        ["social_clicks"] => NULL
        ["social_impressions"] => NULL
        ["social_reach"] => NULL
        ["social_spend"] => NULL
        ["spend"] => string(4) "1.25"
        ["total_action_value"] => NULL
        ["total_actions"] => NULL
        ["total_unique_actions"] => NULL
        ["unique_actions"] => NULL
        ["unique_clicks"] => NULL
        ["unique_ctr"] => NULL
        ["unique_inline_link_click_ctr"] => NULL
        ["unique_inline_link_clicks"] => NULL
        ["unique_link_clicks_ctr"] => NULL
        ["unique_social_clicks"] => NULL
        ["website_clicks"] => NULL
        ["website_ctr"] => NULL
END;
        preg_match_all("/\[\"(.*)\"\]/",$fields_str,$match);
        $fields=$match[1];
        $campaign = new Campaign($campaign_id);
        $yestoday=date('Y-m-d' , strtotime('-1 day'));
        $adsets = $campaign->getInsights(
            $fields,
            array(
                'time_range'=>array('since'=>$yestoday,'until'=>$yestoday)
            )
        );

        $asyn_param['after']=$adsets->getAfter();
        while ($adsets->valid()) {
            $campaigns_data['campaigns_insights_action_types']=array();
            $_d=$adsets->current()->getData();
            foreach ($fields as $i=>$fk){
                if(is_array($_d[$fk])){
                    foreach ($_d[$fk] as $v){
                        $v['insight_key']=$fk;
                        $campaigns_data['campaigns_insights_action_types'][]=$v;
                    }
                }else{
                    $campaigns_data[$fk]=$_d[$fk];
                }
            }
            $campaigns_data['id']=md5($campaigns_data['campaign_id'].$campaigns_data['date_start']);
            $adsets->next();
        }
        //return $campaigns_data;
        if(!empty($campaigns_data['id'])){
            $campaigns_insights=$this->model;
            $campaigns_insights->find($campaigns_data['id']);
            if($campaigns_insights->id){
                $campaigns_insights->relation(true)->delete();
            }
            $this->model->relation(true)->add($campaigns_data);
        }
        return $campaigns_data;
    }
}