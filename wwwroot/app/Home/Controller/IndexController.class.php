<?php
namespace Home\Controller;
use FacebookAds\Object\AdSet;
use Think\Controller;
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
class IndexController extends Controller {
	function __construct() {
		parent::__construct();
		$this->api_lib=D('App','Logic');
	}
    function index()
    {
	    //die("<h2>facebook ads server!</h2>");
        vendor("vendor.autoload");
        $fb_conf=C('fb');
        $token=<<<END
EAABeuMl0aOwBAPpI4rsqiYGXIslPKCIMQ5fUWaZBQElfXmT850XzFWgCphpZCP5WvJZCTUq4yaa3nLROYgYq0cHqSuvRkjkDrUW18tivOQl6jRv22jLY9ClBoQtbCkv6kMGpNJBfBTx8GLsmpx3lBKSK0XyfqmMLUOy1ZAA8BHpkDWJb2OeREDq4lmd7ZCWI5cjgM3XYbUJ4ZBZCkZCZC7CtL
END;
        $token=<<<END2
EAABeuMl0aOwBABsrRnMU82d8BKjERXJ8Y4cqajP1elZB24FETbrjN6T75LsY0gKNZA3nhO4k3yZC5UFCcXqIGsQJH3c5yRwBgu2cV1hr8hasZBnJbXfcDlGJ2MmXIHc0xB678Tott7i8V2lKTTBrhWK3k0Dx2Vo7SA5kCz7cfVY0apWR9tob
END2;
        $fba=Api::init($fb_conf['app_id'],$fb_conf['app_secret'],$token);
        $api = Api::instance();

        //$account_id='act_103859203538756';
        //$account_id='act_101623620428821';
        $account_id='act_1593565507558990';
        $account =new AdAccount($account_id);
        //var_dump($account->read());

        ##广告系列-获取
        $campaigns_data=array();
        $after='';
        //$campaign = new Campaign('6033795518964');
        //$campaign = new AdSet('6034369558164');
        $campaign = new Ad('6034369571964');
$str=<<<END
        ["account_id"] => string(16) "1593565507558990"
        ["account_name"] => NULL
        ["action_values"] => NULL
        ["actions"] => NULL
        ["ad_id"] => string(13) "6034369571964"
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
        ["impressions"] => string(5) "22150"
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
        ["video_10_sec_watched_actions"] => NULL
        ["video_15_sec_watched_actions"] => NULL
        ["video_30_sec_watched_actions"] => NULL
        ["video_avg_percent_watched_actions"] => NULL
        ["video_avg_time_watched_actions"] => NULL
        ["video_p100_watched_actions"] => NULL
        ["video_p25_watched_actions"] => NULL
        ["video_p50_watched_actions"] => NULL
        ["video_p75_watched_actions"] => NULL
        ["video_p95_watched_actions"] => NULL
        ["website_clicks"] => NULL
        ["website_ctr"] => NULL
END;

        preg_match_all("/\[\"(.*)\"\]/",$str,$match);
        $fields=$match[1];
        //$fields=false;
        $sql="CREATE TABLE `ads_insights` ( ";
        $sql.=" `id` varchar(32) NOT NULL COMMENT 'ad_id + date' ";
        foreach ($fields as $key){
            $sql.=" ,`{$key}` varchar(50) DEFAULT NULL ";
        }
        $sql.=" ,PRIMARY KEY (`id`),KEY `ad_id` (`ad_id`)) ENGINE=INNODB DEFAULT CHARSET=utf8mb4";
        echo $sql."<br>";
        $insights = $campaign->getInsights(
            $fields,
            array(
                //'end_time' => (new \DateTime('now'))->getTimestamp(),
                'time_range'=>array('since'=>'2017-05-26','until'=>'2017-05-26')
            )
        );
        dump($insights);

        /*
         *
        $campaign = new Campaign('6032660080764');

        if(count($campaigns_data)>0){
            $model=new \Modules\campaigns\model();
            //$model->truncate();
            $model->addAll($campaigns_data,null,true);
        }
        */
        //var_dump($campaigns->response->request->client->content['data']);

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
