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
	function flushCampaignsInsights()
    {
        $campaign_id = I('request.campaign_id', '');
        $campaign_timespace = I('request.campaign_timespace', 'today');
        if (!$campaign_id) return;

        $ac_id=I('request.ac_id');
        if(!$ac_id) return;
        $date=I('request.date','');
        $ac=FBC($ac_id);
        vendor("vendor.autoload");
        $fba=Api::init($ac['app_id'],$ac['app_secret'],$ac['access_tokens']);
        $api = Api::instance();

        $campaigns_data = array();
        $after = I('request.after', '');
        $active = I('request.active', '');
        $EFFECTIVE_STATUS = array(
            ArchivableCrudObjectEffectiveStatuses::ACTIVE,
        );
        $asyn_param = array('campaign_id' => $campaign_id, 'after' => '');
        if (!$active) {
            array_push($EFFECTIVE_STATUS, ArchivableCrudObjectEffectiveStatuses::PAUSED);
        } else {
            $asyn_param['active'] = $active;
        }
        $fields_str = <<<END
        ["account_id"] => string(16) "1593565507558990"
        ["account_name"] => NULL
        ["action_values"] => NULL
        ["actions"] => NULL
        [`"app_store_clicks"] => NULL
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
        [`"deeplink_clicks"] => NULL
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
        [`"website_clicks"] => NULL
        ["website_ctr"] => NULL
END;
        preg_match_all("/\[\"(.*)\"\]/", $fields_str, $match);
        $fields = $match[1];
        $campaign = new Campaign($campaign_id);
        $time_range = array(date('Y-m-d', NOW_TIME), date('Y-m-d', strtotime('-1 day'))
        , date('Y-m-d', strtotime('-7 day')), date('Y-m-d', strtotime('-14 day')));
        list($today, $yestoday, $last_7day, $last_14day) = $time_range;
        if($date){
            $adsets = $campaign->getInsights(
                $fields,
                array(
                    'time_range'=>array('since'=>$date,'until'=>$date),
                    'action_attribution_windows'=>['1d_click','1d_view'],
                    'action_breakdowns'=>['action_link_click_destination'],
                )
            );
        }else if ($campaign_timespace == 'today') {
            $adsets = $campaign->getInsights(
                $fields,
                array(
                    'time_range' => array('since' => $today, 'until' => $today),
                    'action_attribution_windows' => ['1d_click', '1d_view'],
                    'action_breakdowns'=>['action_link_click_destination'],
                )
            );
        } else {
            $adsets = $campaign->getInsights(
                $fields,
                array(
                    'time_range' => array('since' => $$campaign_timespace, 'until' => $yestoday),
                    'action_attribution_windows' => ['1d_click', '1d_view'],
                    'action_breakdowns'=>['action_link_click_destination'],
                )
            );
        }
        $upkeys=[
            'actions'=>[
                'fields'=>[
                    'click_to_app_store'=>'app_store_clicks',
                    'click_to_app_deeplink'=>'deeplink_clicks',
                    'click_to_website'=>'website_clicks',
                ],
                'vs'=>'action_link_click_destination'
            ],
        ];
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
            foreach ($upkeys as $k=>$ck){
                if($_d[$k]){
                    foreach ($_d[$k] as $item){
                        if($item['action_type'])
                            $_upnkey=$item[$ck['vs']];
                        //dump([$ck['vs'],$_upnkey]);
                        if($oldk=$ck['fields'][$_upnkey]){
                            $campaigns_data[$oldk]=$item['value'];
                        }
                    }
                }
            }
            if(!$date){
                switch ($campaigns_data['date_start']) {
                    case $today:
                        $campaigns_data['id'] = $campaigns_data['campaign_id'] . '.today';
                        $campaigns_data['type'] = model::INSIGHT_TYPE_TODAY;
                        break;
                    case $last_7day:
                        $campaigns_data['id'] = $campaigns_data['campaign_id'] . '.last_7day';
                        $campaigns_data['type'] = model::INSIGHT_TYPE_LAST_7DAY;
                        break;
                    case $last_14day:
                        $campaigns_data['id'] = $campaigns_data['campaign_id'] . '.last_14day';
                        $campaigns_data['type'] = model::INSIGHT_TYPE_LAST_14DAY;
                        break;
//                case $yestoday:
//                    $campaigns_data['id'] = $campaigns_data['campaign_id'] . '.yestoday';
//                    $campaigns_data['type'] = model::INSIGHT_TYPE_YESTODAY;
//                    break;
                    default:
                        $campaigns_data['id'] = md5($campaigns_data['campaign_id'] . $campaigns_data['date_start']);
                        $campaigns_data['type'] = model::INSIGHT_TYPE_YESTODAY;
                }
            }else{
                $campaigns_data['id'] = md5($campaigns_data['campaign_id'] . $campaigns_data['date_start']);
                $campaigns_data['type'] = model::INSIGHT_TYPE_YESTODAY;
            }

            M('campaigns_insights')->where("id='{$campaigns_data['id']}'")->delete();
            //M('campaigns_insights_action_types')->where("campaigns_insights_id='{$campaigns_data['id']}'")->delete();
            $adsets->next();
        }

        //return $campaigns_data;
        if ($campaigns_data) {
            setDayClick($campaigns_data['campaigns_insights_action_types'],$campaigns_data);
            unset($campaigns_data['campaigns_insights_action_types']);
            $this->model->add($campaigns_data);
        }
        //只获取 1593565507558990 的数据
        if($ac_id!='1593565507558990') return $campaigns_data;
        if ($campaign_timespace == 'today' && !$date) {
            //其它Insights
            asyn('apido/asyn.flushCampaignsInsights', array('date'=>date("Y-m-d",NOW_TIME),'ac_id'=>$ac_id,'campaign_id' => $campaign_id),null,
                getDayTime("07:30:00",1),0);
//            asyn('apido/asyn.flushCampaignsInsights', array('campaign_id' => $campaign_id, 'campaign_timespace' => 'yestoday','ac_id'=>$ac_id),null,
//                getDayTime("08:30:00"),0);
//            asyn('apido/asyn.flushCampaignsInsights', array('campaign_id' => $campaign_id, 'campaign_timespace' => 'last_7day','ac_id'=>$ac_id),null,
//                getDayTime("03:00:00"));
//            asyn('apido/asyn.flushCampaignsInsights', array('campaign_id' => $campaign_id, 'campaign_timespace' => 'last_14day','ac_id'=>$ac_id),null,
//                getDayTime("03:00:00"));
            //获取前天数据
//            $before_yesterday=date("Y-m-d",getDayTime("16:30:00",-2));
//            asyn('apido/asyn.flushCampaignsInsights',array('date'=>$before_yesterday,'ac_id'=>$ac_id,'campaign_id' => $campaign_id,),null,
//                getDayTime("16:30:00",0),0);
            //明天刷新今天的数据
            asyn('apido/asyn.flushCampaignsInsights',array('date'=>date("Y-m-d",NOW_TIME),'ac_id'=>$ac_id,'campaign_id' => $campaign_id),null,
                getDayTime("20:00:00",1),0);

        }
        return $campaigns_data;
    }

    function getCampaignsInsightsData(){
        $where=" date_stop>='".date('Y-m-d',strtotime('-1 day'))."' ";
        $keyword_type=I('request.keyword_type');
        if($keyword_type=='campaign'){
            $keyword=trim(I('request.keyword'));
            if($keyword){
                $where.=" and `{$keyword_type}_name` like '%{$keyword}%' ";
            }
        }
        $data=$this->model->relation(array('campaigns_insights_action_types','campaigns'))
            ->where($where)
            ->order('campaign_id asc,date_stop desc')
            ->select();
        $formatData=formatInsightsData($data);
        return array('data'=>$formatData);
    }
    function flushCampaignsInsightsForDate(){
        $date=I('request.date');
        if(!$date) return 'params is null';
        $data=$this->model->field('account_id,campaign_id')->where(
            array(
                'date_start'=>$date,
                'date_stop'=>$date,
            )
        )->group('campaign_id')->select();
        if($data){
            foreach ($data as $r){
                asyn('apido/asyn.flushCampaignsInsights',array('date'=>$date,'ac_id'=>$r['account_id'],'campaign_id' => $r['campaign_id']));
            }
            return $data;
        }
        return "Time machine!!!";

    }
}