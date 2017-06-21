<?php
/**
 * author vking
 * 文章
 */
namespace Modules\accounts;
use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Cursor;
use FacebookAds\Object\Values\ArchivableCrudObjectEffectiveStatuses;
//广告组
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Fields\AdFields;
class lib{
    function __construct() {
    	$this->model=new model();
    }
    function flushAccounts(){
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
        preg_match_all("/\[\"(.*)\"\]/",$fields_str,$match);
        $fields=$match[1];

        $campaign =new AdAccount($ac['act_id']);
        $insights = $campaign->getSelf(
            $fields,
            array(
                'action_attribution_windows'=>['1d_click','1d_view'],
            )
        );
        $_d = $insights->getData();
        if(!$_d) return;
        $campaigns_data=[];
        foreach ($fields as $i => $fk) {
            if (is_array($_d[$fk])) {
                continue;
            }
            $campaigns_data[$fk] = $_d[$fk];
        }
        $this->model->add($campaigns_data,null,true);
        asyn('apido/asyn.flushAccountsInsights',array('ac_id'=>$ac_id));
        return $campaigns_data;
    }
    function getAccountData($account_id=""){
        $where=" AI.date_stop='".date('Y-m-d',NOW_TIME)."' ";
        $ac_id=I('request.ac_id');
        if($ac_id){
            $where.=" AND account.account_id='$ac_id' ";
        }
        if($account_id){
            $where.=" and AI.account_id='$account_id' ";
        }
        $data=$this->model
            ->field('account.id,account.name')
            ->relation(array('insights'))
            ->join('accounts_insights AI ON AI.account_id=account.account_id')
            ->where($where)
            ->order('account.id desc')
            ->select();
        $formatData=formatInsightsData($data,'campaign');
        return array('data'=>$formatData);
    }
}