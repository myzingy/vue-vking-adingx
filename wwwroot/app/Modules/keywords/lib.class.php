<?php
/**
 * author vking
 * 文章
 */
namespace Modules\keywords;
use FacebookAds\Api;
use FacebookAds\Exception\Exception;
use FacebookAds\Object\Ad;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\AdVideo;

class lib{
    function __construct($id="") {
    	$this->model=new model();
    }
    function flushKeywordsInsight(){
        $ad_id=I('request.ad_id','');
        $date=I('request.date',date('Y-m-d',NOW_TIME));
        if(!$ad_id)   return;

        $ac_id=I('request.ac_id');
        if(!$ac_id) return;
        $ac=FBC($ac_id);
        vendor("vendor.autoload");
        $fba=Api::init($ac['app_id'],$ac['app_secret'],$ac['access_tokens']);
        $api = Api::instance();

        $campaigns_data=array();
        $fields_str=<<<END
        ["clicks"] => string(12) "unsigned int"
        ["cost_per_total_action"] => string(5) "float"
        ["cost_per_unique_click"] => string(5) "float"
        ["cpc"] => string(5) "float"
        ["cpm"] => string(5) "float"
        ["cpp"] => string(5) "float"
        ["ctr"] => string(5) "float"
        ["frequency"] => string(5) "float"
        ["id"] => string(6) "string"
        ["impressions"] => string(12) "unsigned int"
        ["name"] => string(6) "string"
        ["reach"] => string(12) "unsigned int"
        ["spend"] => string(5) "float"
        ["total_actions"] => string(12) "unsigned int"
        ["total_unique_actions"] => string(12) "unsigned int"
        ["unique_clicks"] => string(12) "unsigned int"
        ["unique_ctr"] => string(5) "float"
        ["unique_impressions"] => string(12) "unsigned int"
END;
        preg_match_all("/\[\"(.*)\"\]/",$fields_str,$match);
        $fields=$match[1];
        $campaign = new Ad($ad_id);

        $adsets = $campaign->getKeywordStats($fields);
        if(!$adsets->valid()) return;
        $data=$adsets->current()->getData();
        foreach ($data as $r){
            if(is_array($r)){
                $r['account_id']=$ac_id;
                $r['ad_id']=$ad_id;
                array_push($campaigns_data,$r);
            }
        }
        if($campaigns_data){
            $this->model->addAll($campaigns_data,null,true);
        }
        return $campaigns_data;
    }
}