<?php
/**
 * author vking
 * 文章
 */
namespace Modules\adsets\insights;
use Facebook\FacebookBatchRequest;
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
        $adset_timespace=I('request.adset_timespace','today');
        if(!$adset_id)   return;
        
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
        $time_range=array(date('Y-m-d' ,NOW_TIME),date('Y-m-d' , strtotime('-1 day'))
        ,date('Y-m-d' , strtotime('-7 day')),date('Y-m-d' , strtotime('-14 day')));
        list($today,$yestoday,$last_7day,$last_14day)=$time_range;
        if($adset_timespace=='today'){
            $adsets = $campaign->getInsights(
                $fields,
                array(
                    'time_range'=>array('since'=>$today,'until'=>$today),
                    'action_attribution_windows'=>['1d_click','1d_view'],
                )
            );
        }else{
            $adsets = $campaign->getInsights(
                $fields,
                array(
                    'time_range'=>array('since'=>$$adset_timespace,'until'=>$yestoday),
                    'action_attribution_windows'=>['1d_click','1d_view'],
                )
            );
        }

        while ($adsets->valid()) {
            $campaigns_data['adsets_insights_action_types'] = array();
            $_d = $adsets->current()->getData();
            foreach ($fields as $i => $fk) {
                if (is_array($_d[$fk])) {
                    foreach ($_d[$fk] as $v) {
                        if (!$v['action_type']) continue;
                        $v['insight_key'] = $fk;
                        $campaigns_data['adsets_insights_action_types'][] = $v;
                    }
                } else {
                    $campaigns_data[$fk] = $_d[$fk];
                }
            }
            switch($campaigns_data['date_start']){
                case $today:
                    $campaigns_data['id']=$campaigns_data['adset_id'].'.today';
                    $campaigns_data['type']=model::INSIGHT_TYPE_TODAY;
                    break;
                case $last_7day:
                    $campaigns_data['id']=$campaigns_data['adset_id'].'.last_7day';
                    $campaigns_data['type']=model::INSIGHT_TYPE_LAST_7DAY;
                    break;
                case $last_14day:
                    $campaigns_data['id']=$campaigns_data['adset_id'].'.last_14day';
                    $campaigns_data['type']=model::INSIGHT_TYPE_LAST_14DAY;
                    break;
                case $yestoday:
                    $campaigns_data['id']=$campaigns_data['adset_id'].'.yestoday';
                    $campaigns_data['type']=model::INSIGHT_TYPE_YESTODAY;
                    break;
                default:
                    $campaigns_data['id']=md5($campaigns_data['adset_id'].$campaigns_data['date_start']);
                    $campaigns_data['type']=model::INSIGHT_TYPE_YESTODAY;
            }
            M('adsets_insights')->where("id='{$campaigns_data['id']}'")->delete();
            M('adsets_insights_action_types')->where("adsets_insights_id='{$campaigns_data['id']}'")->delete();
            //$campaigns_data['id'] = md5($campaigns_data['adset_id'] . $campaigns_data['date_start']);
            $adsets->next();
        }

        //return $campaigns_data;
        if ($campaigns_data) {
            $this->model->relation(true)->add($campaigns_data);
        }
        if($adset_timespace=='today') {
            //执行规则
            $rule=M('rules_link')->field('exec_hour_minute')->where("target_id='{$campaigns_data['campaign_id']}'")->find();
            if($rule['exec_hour_minute']){
                asyn('apido/asyn.runRules', array('id' => $adset_id, 'type' => 'adset'),null,getDayTime($rule['exec_hour_minute'].':00',0));
            }
            //其它Insights
            asyn('apido/asyn.flushAdsetsInsights',array('adset_id' => $adset_id,'adset_timespace'=>'yestoday','ac_id'=>$ac_id),null,
                getDayTime("00:01:00"),0);
            asyn('apido/asyn.flushAdsetsInsights',array('adset_id' => $adset_id,'adset_timespace'=>'last_7day','ac_id'=>$ac_id),null,
                getDayTime("00:01:00"),-1);
            asyn('apido/asyn.flushAdsetsInsights',array('adset_id' => $adset_id,'adset_timespace'=>'last_14day','ac_id'=>$ac_id),null,
                getDayTime("00:01:00"),-2);
        }
        return $campaigns_data;
    }

    function getAdsetsInsightsData($adset_id=""){
        $where=" date_stop>='".date('Y-m-d',strtotime('-1 day'))."' ";
	    if($adset_id){
            $where.=" and adset_id='$adset_id' ";
        }
        $keyword_type=I('request.keyword_type');
	    if($keyword_type=='campaign' || $keyword_type=='adset'){
            $keyword=trim(I('request.keyword'));
            if($keyword){
                $where.=" and `{$keyword_type}_name` like '%{$keyword}%' ";
            }
        }
        if($checked_campaigns=I('request.checked_campaigns')){
	        $campaigns=array();
	        foreach ($checked_campaigns as $r){
                $campaigns[]="'{$r['id']}'";
            }
            $where.=" and campaign_id in (".implode(',',$campaigns).")";
        }
        $data=$this->model->relation(array('adsets_insights_action_types','adsets','rules_run'))
            ->where($where)
            ->order('adset_id asc,date_stop desc')
            ->select();
        $formatData=formatInsightsData($data,'adset');
        return array('data'=>$formatData);
    }
}