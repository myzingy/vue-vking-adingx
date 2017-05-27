<?php
/**
 * author vking
 * 文章
 */
namespace Modules\adsets\insights;
use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Cursor;
//广告系列
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Fields\AdsInsightsFields;
use FacebookAds\Object\Values\CampaignObjectiveValues;
use FacebookAds\Object\Values\ArchivableCrudObjectEffectiveStatuses;
class lib{
    function __construct($id="") {
    	$this->model=new model();
    }
	function flushAdsetsInsights(){
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
        $fields_str=<<<END
        ["account_id"] => string(16) "1593565507558990"
        ["account_name"] => NULL
        ["action_values"] => NULL
        ["actions"] => NULL
        ["ad_id"] => NULL
        ["ad_name"] => NULL
        ["adset_id"] => string(13) "6034369558164"
        ["adset_name"] => NULL
        ["app_store_clicks"] => NULL
        ["buying_type"] => NULL
        ["call_to_action_clicks"] => NULL
        ["campaign_id"] => string(13) "6033795518964"
        ["campaign_name"] => NULL
        ["canvas_avg_view_percent"] => NULL
        ["canvas_avg_view_time"] => NULL
        ["canvas_component_avg_pct_view"] => NULL
        ["clicks"] => NULL
        ["cost_per_10_sec_video_view"] => NULL
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
        ["date_start"] => string(10) "2017-05-26"
        ["date_stop"] => string(10) "2017-05-26"
        ["deeplink_clicks"] => NULL
        ["estimated_ad_recall_rate"] => NULL
        ["estimated_ad_recallers"] => NULL
        ["frequency"] => NULL
        ["impressions"] => string(5) "29314"
        [`"impressions_dummy"] => NULL
        ["inline_link_click_ctr"] => NULL
        ["inline_link_clicks"] => NULL
        ["inline_post_engagement"] => NULL
        ["objective"] => NULL
        ["place_page_name"] => NULL
        ["reach"] => NULL
        ["relevance_score"] => NULL
        ["social_clicks"] => NULL
        ["social_impressions"] => NULL
        ["social_reach"] => NULL
        ["social_spend"] => NULL
        ["spend"] => string(3) "750"
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
        $campaign = new Adset($adset_id);
        $yestoday=date('Y-m-d' , strtotime('-1 day'));
        $adsets = $campaign->getInsights(
            $fields,
            array(
                'time_range'=>array('since'=>$yestoday,'until'=>$yestoday)
            )
        );

        $asyn_param['after']=$adsets->getAfter();
        while ($adsets->valid()) {
            $campaigns_data['adsets_insights_action_types']=array();
            $_d=$adsets->current()->getData();
            foreach ($fields as $i=>$fk){
                if(is_array($_d[$fk])){
                    foreach ($_d[$fk] as $v){
                        $v['insight_key']=$fk;
                        $campaigns_data['adsets_insights_action_types'][]=$v;
                    }
                }else{
                    $campaigns_data[$fk]=$_d[$fk];
                }
            }
            $campaigns_data['id']=md5($campaigns_data['adset_id'].$campaigns_data['date_start']);
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