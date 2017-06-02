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

use FacebookAds\Object\ProductCatalog;
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
        $campaign = new Campaign('6033795518964');
        //$campaign = new AdSet('6034369558164');
        //$campaign = new Ad('6034369571964');
$str=<<<END
        ["account_id"] => NULL
        ["adlabels"] => NULL
        ["adset_schedule"] => NULL
        ["attribution_spec"] => NULL
        ["bid_amount"] => NULL
        ["bid_info"] => NULL
        ["billing_event"] => NULL
        ["budget_remaining"] => NULL
        ["campaign"] => NULL
        ["campaign_id"] => NULL
        ["configured_status"] => NULL
        ["created_time"] => NULL
        ["creative_sequence"] => NULL
        ["daily_budget"] => NULL
        ["effective_status"] => NULL
        ["end_time"] => NULL
        ["frequency_cap"] => NULL
        ["frequency_cap_reset_period"] => NULL
        ["frequency_control_specs"] => NULL
        ["id"] => string(13) "6034369558164"
        ["is_autobid"] => NULL
        ["is_average_price_pacing"] => NULL
        ["lifetime_budget"] => NULL
        ["lifetime_frequency_cap"] => NULL
        ["lifetime_imps"] => NULL
        ["name"] => NULL
        ["optimization_goal"] => NULL
        ["pacing_type"] => NULL
        ["promoted_object"] => NULL
        ["recommendations"] => NULL
        ["recurring_budget_semantics"] => NULL
        ["rf_prediction_id"] => NULL
        ["rtb_flag"] => NULL
        ["start_time"] => NULL
        ["status"] => NULL
        ["targeting"] => NULL
        ["time_based_ad_rotation_id_blocks"] => NULL
        ["time_based_ad_rotation_intervals"] => NULL
        ["updated_time"] => NULL
        ["use_new_app_click"] => NULL
        ["campaign_spec"] => NULL
        ["execution_options"] => NULL
        ["redownload"] => NULL
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

        $insights = $campaign->getAdSets(
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
