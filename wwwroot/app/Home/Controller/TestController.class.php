<?php
namespace Home\Controller;
set_time_limit(0);
use FacebookAds\Object\AdSet;
use Think\Controller;
use Facebook\FacebookBatchRequest;
use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Cursor;
//广告系列
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\Values\CampaignObjectiveValues;
use FacebookAds\Object\Values\ArchivableCrudObjectEffectiveStatuses;
use FacebookAds\Object\Fields\AdsInsightsFields;
use FacebookAds\Object\Fields\AdSetFields;

use FacebookAds\Object\AdCreative;
use FacebookAds\Object\Fields\AdCreativeFields;

use FacebookAds\Object\Ad;

use FacebookAds\Object\ProductCatalog;
class TestController extends Controller {
	function __construct() {
		parent::__construct();
		$this->api_lib=D('App','Logic');
	}
    function index()
    {
	    //die("<h2>facebook ads server!</h2>");
        vendor("vendor.autoload");
        $fb_conf=FBC('769185746596586');
        $fba=Api::init($fb_conf['app_id'],$fb_conf['app_secret'],$fb_conf['access_tokens']);
        $api = Api::instance();

        $account_id=$fb_conf['act_id'];
        $account =new AdAccount($account_id);
        //var_dump($account->read());

        ##广告系列-获取

        $campaigns_data=array();
        $after='';
        //$campaign = new Campaign('23842610790400125');
        //$campaign = new AdSet('6034369558164');
        //$campaign = new Ad('6034369571964');
$str=<<<END
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

        preg_match_all("/\[\"(.*)\"\]/",$str,$match);
        $fields=$match[1];
        //$fields=false;
        $sql="CREATE TABLE `account_insights` ( ";
        $sql.=" `id` varchar(32) NOT NULL COMMENT 'ad_id + date' ";
        foreach ($fields as $key){
            $sql.=" ,`{$key}` varchar(50) DEFAULT NULL ";
        }
        $sql.=" ,PRIMARY KEY (`id`),KEY `account_id` (`account_id`)) ENGINE=INNODB DEFAULT CHARSET=utf8mb4";
        //echo $sql."<br>";
        /*
        $insights = $account->getAdSets(
            $fields,
            array(
                //'end_time' => (new \DateTime('now'))->getTimestamp(),
                //'time_range'=>array('since'=>'2017-06-04','until'=>'2017-06-04'),
                'action_attribution_windows'=>['1d_click','1d_view'],
                'effective_status' =>array('ACTIVE'),
            )
        );
        dump($insights);
        */
        //https://graph.facebook.com/v2.5/act_nnnnnnnnnnnnnn/insights
        //?level=adset&fields=adset_id,adset_name,campaign_id,campaign_name,impressions,inline_link_clicks,spend
        //&time_increment=1&time_range[since]=2016-02-09&time_range[until]=2016-02-11
        //&breakdowns=impression_device,placement&limit=25
        //$campaign = new Campaign('23842610790400125');
        for($iiii=0;$iiii<1;$iiii++) {
            $insights = $account->getInsights(
                $fields,
                array(
                    //'end_time' => (new \DateTime('now'))->getTimestamp(),
                    //'time_range'=>array('since'=>'2017-06-14','until'=>'2017-06-14'),
                    'action_attribution_windows' => ['1d_click', '1d_view'],
                    //'effective_status' =>array('ACTIVE'),
                    //'action_breakdowns'=>['action_type'],
                    'breakdowns'=> 'device_platform', //device_platform
                    'date_preset'=>'yesterday',
                    'time_increment'=>1
                )
            );
            //echo dump($insights);
            //exit;
            $fields=array('device_platform','spend','date_start','date_stop');
            $campaigns_all=[];
            $campaigns_data = [];
            while ($insights->valid()) {
                $campaigns_data['campaigns_insights_action_types'] = array();
                $_d = $insights->current()->getData();
                //$_d = $insights->getData();

                foreach ($fields as $i => $fk) {
                    if (is_array($_d[$fk])) {
                        //continue;
                        foreach ($_d[$fk] as $v) {
                            $v['insight_key'] = $fk;
                            $campaigns_data['campaigns_insights_action_types'][] = $v;
                        }
                    } else {
                        $campaigns_data[$fk] = $_d[$fk];
                    }
                }
                $campaigns_data['id'] = md5($campaigns_data['campaign_id'] . $campaigns_data['date_start']);
                $campaigns_data['type'] = 1;
                $campaigns_all[]=$campaigns_data;
                $insights->next();
            }
            dump($campaigns_all);
        }
        //M('account')->add($campaigns_data);
        ##广告系列-创建
//        $campaign = new Campaign(null, $account_id);
//        $campaign->setData(array(
//            CampaignFields::NAME => 'My campaign'.rand(1,99),
//            CampaignFields::OBJECTIVE => CampaignObjectiveValues::LINK_CLICKS,
//        ));
//        $campaign->create(array(
//            Campaign::STATUS_PARAM_NAME => Campaign::STATUS_PAUSED,
//        ));
//        var_dump($campaign);

        ##




        /*
        $creative = new AdCreative(null, $account_id);

        $creative->setData(array(
                AdCreativeFields::NAME => 'Sample Promoted Post',
                AdCreativeFields::OBJECT_STORY_ID => null,
        ));

        $creative->create();
        */
    }
}
