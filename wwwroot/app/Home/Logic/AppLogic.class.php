<?php

namespace Home\Logic;

class AppLogic {

	var $user = null;
	//当前用户数据
	var $statusCode = null;

	function __construct() {
	}

	public function status(&$arr, $code, $str = '') {
		$error = $this -> statusCode[$code];
		if ($error) {
			$arr['code'] = $code;
			$arr['message'] = $str ? $str : $error;
		} else {
			$arr['code'] = $code ? $code : $arr['code'];
			$arr['message'] = $str ? $str : $arr['message'];
		}
		$arr['code'] = ($arr['code'] == "" || $arr['code'] == 0) ? 200 : $arr['code'];
		$this -> res_data = $arr;
		return $arr;
	}
  
	public function check($acl) {
		$token = I('request.token');
		$user_lib = new \Modules\user\lib();
		$this -> user = $user_lib -> getUserForToken($token);
		if ($acl != 'user')
			return 0;
		if (!$this -> user -> id)
			return -1;
		return 0;
	}
	
	public function getApiDoc() {
		$common = new \Modules\common\common_lib();
		$reflection = new \ReflectionClass(__CLASS__);

		return $common -> getApiDoc($reflection);
	}

	############################################################
	# public api 公开，无任何权限认证开始
	############################################################
    function login(){
        $lib = new \Modules\user\lib();
        return $lib->login();
    }
    ############################################################
    # public api 公开，无任何权限认证结束
    ############################################################
    function user__getCampaignsInsightsData(){
        $lib = new \Modules\campaigns\lib();
        //return $lib -> getCampaignsInsightsData();
        return $lib -> getCampaignsData();
    }
    function user__getAdsetsInsightsData(){
        $lib = new \Modules\adsets\lib();
        //return $lib -> getAdsetsInsightsData();
        return $lib -> getAdsetsData();
    }
    function user__getAdsInsightsData(){
        $lib = new \Modules\ads\lib();
        //return $lib -> getAdsInsightsData();
        return $lib -> getAdsData();
    }
    function user__updateRulesData(){
        $lib = new \Modules\rules\lib();
        return $lib -> updateRulesData($this->user);
    }
    function user__getRulesData(){
        $lib = new \Modules\rules\lib();
        return $lib -> getRulesData($this->user);
    }
    function user__getRulesLog(){
        $lib = new \Modules\rules\lib();
        return $lib -> getRulesLog();
    }
    function user__getRulesForAd(){
        $lib = new \Modules\rules\lib();
        return $lib -> getRulesForAd();
    }
    function user__saveRulesForAd(){
        $lib = new \Modules\rules\lib();
        return $lib -> saveRulesForAd($this->user);
    }
    function user__getAcsList(){
        $lib = new \Modules\accounts\lib();
        return $lib->getAccounts($this->user);
    }
    function user__addAccounts(){
        $lib = new \Modules\accounts\lib();
        return $lib->addAccounts($this->user);
    }
    function user__delAccounts(){
        $lib = new \Modules\accounts\lib();
        return $lib->delAccounts($this->user);
    }
    function user__getNavList(){
        $lib = new \Modules\group\lib();
        return $lib->getGroupRules($this->user);
    }
    function user__getFBAccounts(){
        $lib = new \Modules\accounts\lib();
        return $lib->getFBAccounts($this->user);
    }
    function user__addUsers(){
        $lib = new \Modules\user\lib();
        return $lib->addUsers($this->user);
    }
    function user__delUsers(){
        $lib = new \Modules\user\lib();
        return $lib->delUsers($this->user);
    }
    function user__getUsers(){
        $lib = new \Modules\user\lib();
        return $lib->getUsers($this->user);
    }
    function user__updateUsers(){
        $lib = new \Modules\user\lib();
        return $lib->updateUsers($this->user);
    }
    function user__getAccountsForEmail(){
        $lib = new \Modules\accounts\lib();
        return $lib->getAccountsForEmail();
    }
    function user__setAccountsForEmail(){
        $lib = new \Modules\accounts\lib();
        return $lib->setAccountsForEmail();
    }
	############################################################
	# 一些异步接口开始
	############################################################
    /**
     * <ok class="异步"/> demon
     */
    function cron__demon(){
        $lib = new \Modules\cron\lib();
        return $lib -> demon();
    }
    function cron__demon_test(){
        $this->cron__demon();
        die('<meta http-equiv="refresh" content="15;url='.url('apido/cron.demon_test?t=').rand(1,22222222).'"> ');
    }
    /**
     * <ok class="异步"/> 刷新广告系列
     * 参数
     *      after:分页
     *      active:只获取 active 状态
     */
    function asyn__flushCampaignsInit(){
        $lib = new \Modules\campaigns\lib();
        return $lib -> flushCampaignsInit();
    }
    /**
	 * <ok class="异步"/> 刷新广告系列
	 * 参数
     *      after:分页
     *      active:只获取 active 状态
	 */
	function asyn__flushCampaigns(){
		$lib = new \Modules\campaigns\lib();
		return $lib -> flushCampaigns();
	}
    /**
     * <ok class="异步"/> 刷新广告系列的insight
     * 参数
     *      campaign_id:campaign_id
     *      active:只获取 active 状态
     */
    function asyn__flushCampaignsInsights(){
        $lib = new \Modules\campaigns\insights\lib();
        return $lib -> flushCampaignsInsights();
    }
    /**
     * <ok class="异步"/> 根据广告系列刷新广告组
     * 参数
     *      campaign_id:campaign_id
     *      after:分页
     *      active:只获取 active 状态
     */
    function asyn__flushAdsets(){
        $lib = new \Modules\adsets\lib();
        return $lib -> flushAdsets();
    }
    /**
     * <ok class="异步"/> 刷新广告组的insight
     * 参数
     *      adset_id:adset_id
     *      active:只获取 active 状态
     */
    function asyn__flushAdsetsInsights(){
        $lib = new \Modules\adsets\insights\lib();
        return $lib -> flushAdsetsInsights();
    }
    /**
     * <ok class="异步"/> 根据广告组刷新广告
     * 参数
     *      adset_id:adset_id
     *      after:分页
     *      active:只获取 active 状态
     */
    function asyn__flushAds(){
        $lib = new \Modules\ads\lib();
        return $lib -> flushAds();
    }
    /**
     * <ok class="异步"/> 刷新广告的insight
     * 参数
     *      ad_id:ad_id
     *      active:只获取 active 状态
     */
    function asyn__flushAdsInsights(){
        $lib = new \Modules\ads\insights\lib();
        return $lib -> flushAdsInsights();
    }
    /**
     * <ok class="异步"/>  执行规则
     * 参数
     *      id:insight_id
     *      type:ad/adset
     */
    function asyn__runRules(){
        if(I('request.type')=='adset'){
            $lib = new \Modules\adsets\lib();
        }else{
            $lib = new \Modules\ads\lib();
        }
        return $lib -> runRules();
    }
    /**
     * <ok class="异步"/>  调整fb预算
     * 参数
     *      adset_id:adset_id
     *      budget:budget 
     */
    function asyn__setBudget(){
        $lib = new \Modules\adsets\lib();
        return $lib -> setBudget();
    }
    /**
     * <ok class="异步"/> 刷新广告账户
     * 参数
     *      after:分页
     *      active:只获取 active 状态
     */
    function asyn__flushAccounts(){
        $lib = new \Modules\accounts\lib();
        return $lib -> flushAccounts();
    }
    /**
     * <ok class="异步"/> 刷新广告账户的insight
     * 参数
     *      campaign_id:campaign_id
     *      active:只获取 active 状态
     */
    function asyn__flushAccountsInsights(){
        $lib = new \Modules\accounts\insights\lib();
        return $lib -> flushAccountsInsights();
    }
    /**
     * fb token => fb long live token
     *
     */
    function asyn__getLongToken(){
        $lib = new \Modules\user\lib();
        return $lib -> getLongToken();
    }

    /**
     * @return mixed
     * 提交数据给erp
     */
    function asyn__postErpCampaign(){
        $lib = new \Modules\campaigns\lib();
        return $lib -> postErpCampaign();
    }
	############################################################
	# 一些异步接口结束
	############################################################





}
