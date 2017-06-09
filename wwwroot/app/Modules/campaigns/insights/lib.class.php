<?php
/**
 * author vking
 * 文章
 */
namespace Modules\campaigns\insights;
use Facebook\FacebookBatchRequest;
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
        $time_range=array(date('Y-m-d' ,NOW_TIME),date('Y-m-d' , strtotime('-1 day'))
        ,date('Y-m-d' , strtotime('-7 day')),date('Y-m-d' , strtotime('-14 day')));
        list($today,$yestoday,$last_7day,$last_14day)=$time_range;
//        $adsets = $campaign->getInsights(
//            $fields,
//            array(
//                'time_range'=>array('since'=>$yestoday,'until'=>$yestoday),
//                'action_attribution_windows'=>['1d_click','1d_view'],
//            )
//        );
//        $asyn_param['after']=$adsets->getAfter();
        $adsets_branch=new FacebookBatchRequest([
            $campaign->getInsights(
                $fields,
                array(
                    'time_range'=>array('since'=>$today,'until'=>$today),
                    'action_attribution_windows'=>['1d_click','1d_view'],
                )
            ),
            $campaign->getInsights(
                $fields,
                array(
                    'time_range'=>array('since'=>$yestoday,'until'=>$yestoday),
                    'action_attribution_windows'=>['1d_click','1d_view'],
                )
            ),
            $campaign->getInsights(
                $fields,
                array(
                    'time_range'=>array('since'=>$last_7day,'until'=>$yestoday),
                    'action_attribution_windows'=>['1d_click','1d_view'],
                )
            ),
            $campaign->getInsights(
                $fields,
                array(
                    'time_range'=>array('since'=>$last_14day,'until'=>$yestoday),
                    'action_attribution_windows'=>['1d_click','1d_view'],
                )
            ),
        ]);
        $campaigns_data_branch=[];
        foreach ($adsets_branch->getApp() as $adsets) {
            while ($adsets->valid()) {
                $campaigns_data['campaigns_insights_action_types'] = array();
                $_d = $adsets->current()->getData();
                foreach ($fields as $i => $fk) {
                    if (is_array($_d[$fk])) {
                        foreach ($_d[$fk] as $v) {
                            $v['insight_key'] = $fk;
                            $campaigns_data['campaigns_insights_action_types'][] = $v;
                        }
                    } else {
                        $campaigns_data[$fk] = $_d[$fk];
                    }
                }
                switch($campaigns_data['date_start']){
                    case $today:
                        $campaigns_data['id']=$campaigns_data['campaign_id'].'.today';
                        $campaigns_data['type']=model::INSIGHT_TYPE_TODAY;
                        break;
                    case $last_7day:
                        $campaigns_data['id']=$campaigns_data['campaign_id'].'.last_7day';
                        $campaigns_data['type']=model::INSIGHT_TYPE_LAST_7DAY;
                        break;
                    case $last_14day:
                        $campaigns_data['id']=$campaigns_data['campaign_id'].'.last_14day';
                        $campaigns_data['type']=model::INSIGHT_TYPE_LAST_14DAY;
                        break;
                    default:
                        $campaigns_data['id']=md5($campaigns_data['campaign_id'].$campaigns_data['date_start']);
                        $campaigns_data['type']=model::INSIGHT_TYPE_YESTODAY;
                }
                M('campaigns_insights')->where("id='{$campaigns_data['id']}'")->delete();
                M('campaigns_insights_action_types')->where("campaigns_insights_id='{$campaigns_data['id']}'")->delete();
                array_push($campaigns_data_branch,$campaigns_data);
                $adsets->next();
            }
        }
        //return $campaigns_data_branch;
        foreach ($campaigns_data_branch as $campaigns_data){
            $this->model->relation(true)->add($campaigns_data);
        }
        return $campaigns_data_branch;
    }

    function getCampaignsInsightsData(){
        $where=" date_stop>='".date('Y-m-d',strtotime('-1 day'))."' ";
        $data=$this->model->relation(array('campaigns_insights_action_types','campaigns'))
            ->where($where)
            ->order('campaign_id asc,date_stop desc')
            ->select();
        $formatData=formatInsightsData($data);
        return array('data'=>$formatData);
    }
}