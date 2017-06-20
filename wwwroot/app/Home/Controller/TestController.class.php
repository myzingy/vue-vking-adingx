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
  ["account_id"] => string(15) "769185746596586"
    ["account_status"] => NULL
    ["age"] => NULL
    ["agency_client_declaration"] => NULL
    ["amount_spent"] => NULL
    ["balance"] => NULL
    ["business"] => NULL
    ["business_city"] => NULL
    ["business_country_code"] => NULL
    ["business_name"] => NULL
    ["business_state"] => NULL
    ["business_street"] => NULL
    ["business_street2"] => NULL
    ["business_zip"] => NULL
    ["capabilities"] => NULL
    ["created_time"] => NULL
    ["currency"] => NULL
    ["disable_reason"] => NULL
    ["end_advertiser"] => NULL
    ["end_advertiser_name"] => NULL
    ["failed_delivery_checks"] => NULL
    ["funding_source"] => NULL
    ["funding_source_details"] => NULL
    ["has_migrated_permissions"] => NULL
    ["id"] => string(19) "act_769185746596586"
    ["io_number"] => NULL
    ["is_notifications_enabled"] => NULL
    ["is_personal"] => NULL
    ["is_prepay_account"] => NULL
    ["is_tax_id_required"] => NULL
    ["line_numbers"] => NULL
    ["media_agency"] => NULL
    ["min_campaign_group_spend_cap"] => NULL
    ["min_daily_budget"] => NULL
    ["name"] => NULL
    ["offsite_pixels_tos_accepted"] => NULL
    ["owner"] => NULL
    ["partner"] => NULL
    ["rf_spec"] => NULL
    ["salesforce_invoice_group_id"] => NULL
    ["spend_cap"] => NULL
    ["tax_id"] => NULL
    ["tax_id_status"] => NULL
    ["tax_id_type"] => NULL
    ["timezone_id"] => NULL
    ["timezone_name"] => NULL
    ["timezone_offset_hours_utc"] => NULL
    ["tos_accepted"] => NULL
    ["user_role"] => NULL
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
            $insights = $account->getSelf(
                $fields,
                array(
                    //'end_time' => (new \DateTime('now'))->getTimestamp(),
                    //'time_range'=>array('since'=>'2017-06-14','until'=>'2017-06-14'),
                    'action_attribution_windows' => ['1d_click', '1d_view'],
                    //'effective_status' =>array('ACTIVE'),
                    //'breakdowns'=> 'impression_device,placement',
                    //'action_breakdowns'=>['action_device'],
                    //'date_preset'=>'yesterday',
                    //'time_increment'=>1
                )
            );
            //dump($insights);
            //exit;
            $campaigns_data = [];
            //while ($insights->valid()) {
            //$campaigns_data['campaigns_insights_action_types'] = array();
            //$_d = $insights->current()->getData();
            $_d = $insights->getData();
            foreach ($fields as $i => $fk) {
                if (is_array($_d[$fk])) {
                    continue;
                    foreach ($_d[$fk] as $v) {
                        $v['insight_key'] = $fk;
                        $campaigns_data['campaigns_insights_action_types'][] = $v;
                    }
                } else {
                    $campaigns_data[$fk] = $_d[$fk];
                }
            }

            //$campaigns_data['id'] = md5($campaigns_data['campaign_id'] . $campaigns_data['date_start']);
            //$campaigns_data['type'] = 1;
            //$insights->next();
            //}
            dump(array($iiii,$campaigns_data['id']));
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
