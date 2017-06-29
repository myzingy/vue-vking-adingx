<?php
/**
 * author vking
 * 文章
 */
namespace Modules\accounts\insights;
use Facebook\FacebookBatchRequest;
use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Cursor;
//广告系列
use FacebookAds\Object\Ad;
use FacebookAds\Object\Values\ArchivableCrudObjectEffectiveStatuses;
class lib{
    function __construct($id="") {
    	$this->model=new model();
    }
	function flushAccountsInsights(){
        $ad_timespace=I('request.ad_timespace','today');
        $breakdowns=I('request.breakdowns','');
        $ac_id=I('request.ac_id');
        if(!$ac_id) return;
        $ac=FBC($ac_id);
        vendor("vendor.autoload");
        $fba=Api::init($ac['app_id'],$ac['app_secret'],$ac['access_tokens']);
        $api = Api::instance();
        
        
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
        $fields_str=<<<END
        ["account_id"] => string(16) "1593565507558990"
        ["account_name"] => NULL
        ["action_values"] => NULL
        ["actions"] => NULL
        ["app_store_clicks"] => NULL
        ["buying_type"] => NULL
        ["call_to_action_clicks"] => NULL
        ["campaign_id"] => string(13) "6033795518964"
        ["campaign_name"] => NULL
        ["clicks"] => NULL
        [`"cost_per_10_sec_video_view"] => NULL
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
        ["frequency"] => NULL
        ["impressions"] => string(5) "22150"
        [`"impressions_dummy"] => NULL
        ["inline_link_click_ctr"] => NULL
        ["inline_link_clicks"] => NULL
        ["inline_post_engagement"] => NULL
        ["objective"] => NULL
        ["reach"] => NULL
        ["social_clicks"] => NULL
        ["social_impressions"] => NULL
        ["social_reach"] => NULL
        ["social_spend"] => NULL
        ["spend"] => string(6) "600.55"
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
        [`"video_10_sec_watched_actions"] => NULL
        [`"video_15_sec_watched_actions"] => NULL
        [`"video_30_sec_watched_actions"] => NULL
        [`"video_avg_percent_watched_actions"] => NULL
        [`"video_avg_time_watched_actions"] => NULL
        [`"video_p100_watched_actions"] => NULL
        [`"video_p25_watched_actions"] => NULL
        [`"video_p50_watched_actions"] => NULL
        [`"video_p75_watched_actions"] => NULL
        [`"video_p95_watched_actions"] => NULL
        ["website_clicks"] => NULL
        ["website_ctr"] => NULL
END;
        preg_match_all("/\[\"(.*)\"\]/",$fields_str,$match);
        $fields=$match[1];
        //$campaign = new Ad($ad_id);
        $campaign =new AdAccount($ac['act_id']);
        
        $time_range=array(date('Y-m-d' ,NOW_TIME),date('Y-m-d' , strtotime('-1 day'))
            ,date('Y-m-d' , strtotime('-7 day')),date('Y-m-d' , strtotime('-14 day')));
        list($today,$yestoday,$last_7day,$last_14day)=$time_range;
        if($ad_timespace=='today'){
            $adsets = $campaign->getInsights(
                $fields,
                array(
                    'time_range'=>array('since'=>$today,'until'=>$today),
                    'action_attribution_windows'=>['1d_click','1d_view'],
                    'breakdowns'=> $breakdowns,
                )
            );
        }else{
            $adsets = $campaign->getInsights(
                $fields,
                array(
                    'time_range'=>array('since'=>$$ad_timespace,'until'=>$yestoday),
                    'action_attribution_windows'=>['1d_click','1d_view'],
                    'breakdowns'=> $breakdowns,
                )
            );
        }
        $breakdowns_data=[];
        if($breakdowns){
            array_push($fields,'device_platform');
        }
        while ($adsets->valid()) {
            $campaigns_data['accounts_insights_action_types']=array();
            $_d=$adsets->current()->getData();
            foreach ($fields as $i=>$fk){
                if(is_array($_d[$fk])){
                    foreach ($_d[$fk] as $v){
                        if(!$v['action_type']) continue;
                        $v['insight_key']=$fk;
                        $campaigns_data['accounts_insights_action_types'][]=$v;
                    }
                }else{
                    $campaigns_data[$fk]=$_d[$fk];
                }
            }
            switch($campaigns_data['date_start']){
                case $today:
                    $campaigns_data['id']=$campaigns_data['account_id'].'.today';
                    $campaigns_data['type']=model::INSIGHT_TYPE_TODAY;
                    break;
                case $last_7day:
                    $campaigns_data['id']=$campaigns_data['account_id'].'.last_7day';
                    $campaigns_data['type']=model::INSIGHT_TYPE_LAST_7DAY;
                    break;
                case $last_14day:
                    $campaigns_data['id']=$campaigns_data['account_id'].'.last_14day';
                    $campaigns_data['type']=model::INSIGHT_TYPE_LAST_14DAY;
                    break;
//                case $yestoday:
//                    $campaigns_data['id']=$campaigns_data['account_id'].'.yestoday';
//                    $campaigns_data['type']=model::INSIGHT_TYPE_YESTODAY;
//                    break;
                default:
                    $campaigns_data['id']=md5($campaigns_data['account_id'].$campaigns_data['date_start']);
                    $campaigns_data['type']=model::INSIGHT_TYPE_YESTODAY;
            }
            $adsets->next();
            if($breakdowns){
                array_push($breakdowns_data,array(
                    'accounts_insights_id'=>$campaigns_data['id'],
                    'insight_key'=>'breakdowns.'.$breakdowns,
                    'action_type'=>$campaigns_data['device_platform'].'.spend',
                    'value'=>  $campaigns_data['spend'],
                    '1d_click'=>  $campaigns_data['spend'],
                    '1d_view'=>  $campaigns_data['spend'],
                ));
                if($campaigns_data['device_platform']=='desktop'){
                    $pc_fee=$campaigns_data['spend']*100;
                }
                if($campaigns_data['device_platform']=='mobile'){
                    $mb_fee=$campaigns_data['spend']*100;
                }
            }
        }
        //return $campaigns_data;
        if($breakdowns){
            M('accounts_insights_action_types')->addAll($breakdowns_data);
            $erpData=array(
                'date'=> $campaigns_data['date_stop'],
                'account_id'=>$campaigns_data['account_id'],
                'account_name'=>$campaigns_data['account_name'],
                'pc_fee'=>$pc_fee+0,
                'mb_fee'=>$mb_fee+0,
                'fee'=>$pc_fee+$mb_fee,
            );
            postERP('api/api/facebook-fee',$erpData);
        }else{
            if ($campaigns_data) {
                M('accounts_insights')->where("id='{$campaigns_data['id']}'")->delete();
                M('accounts_insights_action_types')->where("accounts_insights_id='{$campaigns_data['id']}'")->delete();
                $this->model->relation(true)->add($campaigns_data);
            }
            asyn('apido/asyn.flushAccountsInsights',array('ad_timespace'=>$ad_timespace,'ac_id'=>$ac_id,'breakdowns'=>'device_platform'));
        }
        if($ad_timespace=='today' && !$breakdowns) {
            //其它Insights
            asyn('apido/asyn.flushAccountsInsights',array('ad_timespace'=>'yestoday','ac_id'=>$ac_id),null,
                getDayTime("00:01:00"),0);
        }
        return $breakdowns_data?$breakdowns_data:$campaigns_data;
    }
}