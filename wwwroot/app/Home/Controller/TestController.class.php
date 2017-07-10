<?php
namespace Home\Controller;
set_time_limit(0);
use FacebookAds\Object\AdSet;
use Facebook\Facebook;
use FacebookAds\Object\AdsPixel;
use FacebookAds\Object\Business;
use FacebookAds\Object\CustomAudience;
use FacebookAds\Object\Fields\AdAccountFields;
use FacebookAds\Object\Values\AdPreviewAdFormatValues;
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
        vendor("vendor.autoload");
        $app=C('fbapp');
        dump($app);
        $fba=Api::init($app['app_id'],$app['app_secret'],'EAABeuMl0aOwBAEf26Qx1SVwRSw9HZC1FUwHGuckdmNUm9hKw3mVAC0T9R0ESudBfG6i80ZAS3PZBqZAYS1KkqHDNZBRB76UmxSPGr6aDPqSmZB3wxNpmaDpLSXyemzUUEhQcN43aQrXBAt6jcpGOTqYHD1JP4T5UqNr3o9h0naYQZDZD');
        $api = Api::instance();

        //dump($b);
        $str=<<<END
["account_id"] => NULL
    ["ad_review_feedback"] => NULL
    ["adlabels"] => NULL
    ["adset"] => NULL
    ["adset_id"] => NULL
    ["bid_amount"] => NULL
    ["bid_info"] => NULL
    ["bid_type"] => NULL
    ["campaign"] => NULL
    ["campaign_id"] => NULL
    ["configured_status"] => NULL
    ["conversion_specs"] => NULL
    ["created_time"] => NULL
    ["creative"] => NULL
    ["effective_status"] => NULL
    ["id"] => string(17) "23842527425690228"
    ["last_updated_by_app_id"] => NULL
    ["name"] => NULL
    ["recommendations"] => NULL
    ["status"] => NULL
    ["tracking_specs"] => NULL
    ["updated_time"] => NULL
    [`"adset_spec"] => NULL
    [`"date_format"] => NULL
    ["display_sequence"] => NULL
    [`"execution_options"] => NULL
    [`"redownload"] => NULL
    [`"filename"] => NULL
END;
        $str=<<<END
["account_id"] => string(6) "string"
          ["created_time"] => string(8) "datetime"
          [~"creatives"] => string(12) "list<string>"
          ["hash"] => string(6) "string"
          ["height"] => string(12) "unsigned int"
          ["id"] => string(6) "string"
          [~"is_associated_creatives_in_adgroups"] => string(4) "bool"
          ["name"] => string(6) "string"
          ["original_height"] => string(12) "unsigned int"
          ["original_width"] => string(12) "unsigned int"
          ["permalink_url"] => string(6) "string"
          ["status"] => string(6) "Status"
          ["updated_time"] => string(8) "datetime"
          ["url"] => string(6) "string"
          ["url_128"] => string(6) "string"
          ["width"] => string(12) "unsigned int"
          ["bytes"] => string(6) "string"
          ["copy_from"] => string(6) "Object"
          ["zipbytes"] => string(6) "string"
          ["filename"] => string(4) "file"
END;
        $str=<<<END
["account_id"] => string(6) "string"
          ["actor_id"] => string(6) "string"
          ["adlabels"] => string(13) "list<AdLabel>"
          ["applink_treatment"] => string(16) "ApplinkTreatment"
          ["body"] => string(6) "string"
          ["call_to_action_type"] => string(16) "CallToActionType"
          ["effective_instagram_story_id"] => string(6) "string"
          ["effective_object_story_id"] => string(6) "string"
          ["id"] => string(6) "string"
          ["image_crops"] => string(13) "AdsImageCrops"
          ["image_hash"] => string(6) "string"
          ["image_url"] => string(6) "string"
          ["instagram_actor_id"] => string(6) "string"
          ["instagram_permalink_url"] => string(6) "string"
          ["instagram_story_id"] => string(6) "string"
          ["link_og_id"] => string(6) "string"
          ["link_url"] => string(6) "string"
          ["name"] => string(6) "string"
          ["object_id"] => string(6) "string"
          ["object_story_id"] => string(6) "string"
          ["object_story_spec"] => string(25) "AdCreativeObjectStorySpec"
          ["object_type"] => string(10) "ObjectType"
          ["object_url"] => string(6) "string"
          ["platform_customizations"] => string(6) "Object"
          ["product_set_id"] => string(6) "string"
          ["status"] => string(6) "Status"
          ["template_url"] => string(6) "string"
          ["template_url_spec"] => string(6) "Object"
          ["thumbnail_url"] => string(6) "string"
          ["title"] => string(6) "string"
          ["url_tags"] => string(6) "string"
          ["use_page_actor_override"] => string(4) "bool"
          ["video_id"] => string(6) "string"
          ["call_to_action"] => string(6) "Object"
          ["dynamic_ad_voice"] => string(14) "DynamicAdVoice"
          ["image_file"] => string(6) "string"
END;
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
        $str=<<<END
["account_id"] => string(6) "string"
      ["approximate_count"] => string(3) "int"
      ["data_source"] => string(24) "CustomAudienceDataSource"
      ["delivery_status"] => string(20) "CustomAudienceStatus"
      ["description"] => string(6) "string"
      ["external_event_source"] => string(8) "AdsPixel"
      ["id"] => string(6) "string"
      ["is_value_based"] => string(4) "bool"
      ["lookalike_audience_ids"] => string(12) "list<string>"
      ["lookalike_spec"] => string(13) "LookalikeSpec"
      ["name"] => string(6) "string"
      ["operation_status"] => string(20) "CustomAudienceStatus"
      ["opt_out_link"] => string(6) "string"
      ["permission_for_actions"] => string(24) "CustomAudiencePermission"
      ["pixel_id"] => string(6) "string"
END;


        preg_match_all("/\[\"(.*)\"\]/",$str,$match);
        $fields=$match[1];
        #$b=new Business('1594081490842802');
        //$ac=new AdAccount('act_1593565507558990');
//        $res=$ac->getAdImages($fields);
//        //$res=$ac->getAdVideos($fields);
//        dump($res);
//        exit;
        //$ac=new AdAccount('act_1593565507558990');
        //$ad=new Campaign('6084032058764');
        //$ad=new AdSet('6084652424764');
        //$ad=new Ad('6084839602764');
        //$ad=new CustomAudience('6085459889364');
        $ad=new AdsPixel('1593577174232007');
        //$res=$ad->getSelf($fields);
        //dump($ad->getAdCreatives($fields));
        //dump($ad->getTargetingSentenceLines());
//        dump($ac->getAdsPixels(['id','code','creation_time'
//            ,'is_created_by_business','last_fired_time','name'
//        ,'owner_ad_account','owner_business']));
        dump($ad->getStats(array(), array(
            'aggregation' => 'custom_data_field',
            'event' => 'product_info',
        )));
        return;
        dump($ad->getInsights($fields,array(
            'time_range'=>array('since'=>'2017-07-05','until'=>'2017-07-06'),
            'action_attribution_windows'=>['1d_click','1d_view'],
            'breakdowns'=> 'hourly_stats_aggregated_by_audience_time_zone',
            //creative_fingerprint,household_composition,
            //household_income,platform_position
        )));

        return;
        $lib=new \Modules\adsets\lib();
        $data=$lib->getAdsetsData('23842626413470368');
        dump($data);
        return;
        $fb=new Facebook(array(
            'app_id'=>$app['app_id'],
            'app_secret'=>$app['app_secret']
        ));
        $user=(object)[];
        $user->id='120876045170245';
        $user->token='EAABeuMl0aOwBANAzeIXNjxhp6oZBS8Al6aE71Pdr8A9w6vNCvdVBl4pk8LwFTEOnPVajYZC0lo6XObVQMwOvNZBqcp84GFovrzS3EoZAquDo7oMN5eojlifWq0BQZCtunEh5e6r5YQTnBxJpALQRzwfPyf0iSJkfs8gJenKUvuznefMgrkYxIh1ukETddg3EZD';
        //$res=$fb->get('/me?fields=id,name',$user->token);
        $res=$fb->get("oauth/access_token?client_id={$app['app_id']}&client_secret={$app['app_secret']}&grant_type=fb_exchange_token&fb_exchange_token={$user->token}",$user->token);
        $res=$res->getDecodedBody();
        dump($res);
        return;
        $lib=new \Modules\accounts\lib();
        $data=$lib->FBC(I('request.ac_id'));
        dump($data);
        return;
        $user=(object)[];
        $user->id='1906750516239673';
        $user->token='EAABeuMl0aOwBAEf0xSi6TkO89hHsD0IMTZC0xl2ZApStoZBkSh2pYsIU5p5mUTocn8gEE6nGVOzJcrxt8L316ZAFZBqiG6rW8AO0ZASxm8OHsZCjxLQgl10NWT9HGqTZA62qWLyN1ZCGpIzZB8zEkPGkQZAHZBWcQR1fooSJgNoB6DxFKPS6hOrUkIog';
        $ac=FBC();
        $ac=FBC($ac[0]['account_id']);
        vendor("vendor.autoload");
//        $fba=Api::init($ac['app_id'],$ac['app_secret'],$user->token);
//        $api = Api::instance();

        $fb=new Facebook(array(
            'app_id'=>$ac['app_id'],
            'app_secret'=>$ac['app_secret']
        ));
        $res=$fb->get($user->id.'/adaccounts?fields=account_id,name',$user->token);
        $data=$res->getDecodedBody();
        dump($res);

        return;
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
["account_id"] => string(6) "string"
          ["account_status"] => string(12) "unsigned int"
          ["age"] => string(5) "float"
          ["agency_client_declaration"] => string(23) "AgencyClientDeclaration"
          ["amount_spent"] => string(6) "string"
          ["balance"] => string(6) "string"
          ["business"] => string(8) "Business"
          ["business_city"] => string(6) "string"
          ["business_country_code"] => string(6) "string"
          ["business_name"] => string(6) "string"
          ["business_state"] => string(6) "string"
          ["business_street"] => string(6) "string"
          ["business_street2"] => string(6) "string"
          ["business_zip"] => string(6) "string"
          ["capabilities"] => string(12) "list<string>"
          ["created_time"] => string(8) "datetime"
          ["currency"] => string(6) "string"
          ["disable_reason"] => string(12) "unsigned int"
          ["end_advertiser"] => string(6) "string"
          ["end_advertiser_name"] => string(6) "string"
          ["failed_delivery_checks"] => string(19) "list<DeliveryCheck>"
          ["funding_source"] => string(6) "string"
          ["funding_source_details"] => string(20) "FundingSourceDetails"
          ["has_migrated_permissions"] => string(4) "bool"
          ["id"] => string(6) "string"
          ["io_number"] => string(6) "string"
          ["is_notifications_enabled"] => string(4) "bool"
          ["is_personal"] => string(12) "unsigned int"
          ["is_prepay_account"] => string(4) "bool"
          ["is_tax_id_required"] => string(4) "bool"
          ["line_numbers"] => string(9) "list<int>"
          ["media_agency"] => string(6) "string"
          ["min_campaign_group_spend_cap"] => string(6) "string"
          ["min_daily_budget"] => string(12) "unsigned int"
          ["name"] => string(6) "string"
          ["offsite_pixels_tos_accepted"] => string(4) "bool"
          ["owner"] => string(6) "string"
          ["partner"] => string(6) "string"
          ["rf_spec"] => string(18) "ReachFrequencySpec"
          ["salesforce_invoice_group_id"] => string(6) "string"
          ["show_checkout_experience"] => string(4) "bool"
          ["spend_cap"] => string(6) "string"
          ["tax_id"] => string(6) "string"
          ["tax_id_status"] => string(12) "unsigned int"
          ["tax_id_type"] => string(6) "string"
          ["timezone_id"] => string(12) "unsigned int"
          ["timezone_name"] => string(6) "string"
          ["timezone_offset_hours_utc"] => string(5) "float"
          ["tos_accepted"] => string(16) "map<string, int>"
          ["user_role"] => string(6) "string"
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
