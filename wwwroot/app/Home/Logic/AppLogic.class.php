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
    function user__getRule(){
        $lib = new \Modules\rules\lib();
        return $lib -> getRule($this->user);
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
    function assets__getData(){
        $lib = new \Modules\assets\lib();
        return $lib->getData();
    }
    function assets__setAuthor(){
        $lib = new \Modules\assets\lib();
        return $lib->setAuthor();
    }
    function assets__setSkus(){
        $lib = new \Modules\assets\lib();
        return $lib->setSkus();
    }
    function erp__getAcconutByPlatform(){
        $lib = new \Modules\accounts\lib();
        return $lib->getAcconutByPlatform();
    }
    function erp__getAcconutByCountry(){
        $lib = new \Modules\accounts\lib();
        return $lib->getAcconutByCountry();
    }
    function erp__getAcconutByOperator(){
        $lib = new \Modules\campaigns\lib();
        return $lib->getAcconutByOperator();
    }
    function user__getKeywords(){
        $lib = new \Modules\keywords\lib();
        return $lib->getData();
    }
    function user__getFeeds(){
        $lib = new \Modules\feeds\lib();
        return $lib->getFeeds();
    }
    function user__setFeeds(){
        $lib = new \Modules\feeds\lib();
        return $lib->setFeeds();
    }
    function user__getFeedsImageInfo(){
        $lib = new \Modules\feeds\lib();
        return $lib->getFeedsImageInfo();
    }
    function getFeedsImage(){
        $lib = new \Modules\feeds\lib();
        return $lib->getFeedsImage();
    }
    function user__getFeedsMark(){
        $lib = new \Modules\feeds\lib();
        return $lib->getFeedsMark();
    }
    function user__setFeedsMark(){
        $lib = new \Modules\feeds\lib();
        return $lib->setFeedsMark();
    }
    function user__getProducts(){
        $lib = new \Modules\products\lib();
        return $lib->getProducts();
    }
    function user__bindProductVideo(){
        $lib = new \Modules\products\lib();
        return $lib->bindProductVideo();
    }
    function user__unBindProductVideo(){
        $lib = new \Modules\products\lib();
        return $lib->unBindProductVideo();
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
    function cron__demonAdsInit(){
        $acs=FBC();
        if($acs){
            foreach ($acs as $ac){
                asyn('apido/asyn.flushAds',array(
                    'ac_id'=>$ac['account_id'],
                    //'active'=>'active'
                ),null,null,0);
            }
        }
    }
    function cron__demonAcconutsPlatformInit(){
        $acs=FBC();
        $day=I('request.day',40);
        $ac_id=I('request.ac_id');
        if($acs){
            for($i=0;$i<$day;$i++) {
                $date=date("Y-m-d",NOW_TIME-86400*$i);
                if($ac_id){
                    asyn('apido/asyn.flushAccountsInsights', array(
                        'ac_id' => $ac_id,
                        'active' => 'active',
                        'breakdowns' => 'device_platform',
                        'date' => $date,
                    ), null, null, 0);
                    continue;
                }
                foreach ($acs as $ac) {
                    asyn('apido/asyn.flushAccountsInsights', array(
                        'ac_id' => $ac['account_id'],
                        'active' => 'active',
                        'breakdowns' => 'device_platform',
                        'date' => $date,
                    ), null, null, 0);
                }
            }
        }
    }
    function cron__demonAcconutsCountryInit(){
        $acs=FBC();
        if($acs){
            $date=I('request.date');
            if($date){
                foreach ($acs as $ac) {
                    asyn('apido/asyn.flushAccountsInsights', array(
                        'ac_id' => $ac['account_id'],
                        'active' => 'active',
                        'breakdowns' => 'country',
                        'date' => $date,
                        'fouce'=>I('request.fouce'),
                    ), null, null, 0);
                }
                return;
            }
            for($i=1;$i<40;$i++) {
                $date=date("Y-m-d",NOW_TIME-86400*$i);

                foreach ($acs as $ac) {
                    asyn('apido/asyn.flushAccountsInsights', array(
                        'ac_id' => $ac['account_id'],
                        'active' => 'active',
                        'breakdowns' => 'country',
                        'date' => $date,
                        'fouce'=>I('request.fouce'),
                    ), null, null, 0);
                }
            }
        }
    }
    function cron__removeAlikeCron(){
        $lib = new \Modules\cron\lib();
        return $lib -> removeAlikeCron();
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
    function asyn__flushCampaignsInsightsForDate(){
        $lib = new \Modules\campaigns\insights\lib();
        return $lib -> flushCampaignsInsightsForDate();
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
    function asyn__setBusinessId(){
        $lib = new \Modules\user\lib();
        return $lib -> setBusinessId();
    }
    /**
     * @return mixed
     * 提交数据给erp
     */
//    function asyn__postErpCampaign(){
//        $lib = new \Modules\campaigns\lib();
//        return $lib -> postErpCampaign();
//    }
    function asyn__getAssetForAd(){
        $lib = new \Modules\assets\lib();
        return $lib -> getAssetForAd();
    }
    function asyn__setAssetsImageInfo(){
        $lib = new \Modules\assets\lib();
        return $lib -> setAssetsImageInfo();
    }
    function asyn__setAssetsVideoInfo(){
        $lib = new \Modules\assets\lib();
        return $lib -> setAssetsVideoInfo();
    }
    function asyn__getAssetsImageInfo(){
        $lib = new \Modules\assets\lib();
        return $lib -> getAssetsImageInfo();
    }
    function asyn__getAssetsVideoInfo(){
        $lib = new \Modules\assets\lib();
        return $lib -> getAssetsVideoInfo();
    }

    function asyn__setAssetsFileHash(){
        $lib = new \Modules\assets\lib();
        return $lib -> setAssetsFileHash();
    }
    function asyn__pauseAdset(){
        $lib = new \Modules\adsets\lib();
        return $lib -> pause();
    }
    function asyn__pauseAd(){
        $lib = new \Modules\ads\lib();
        return $lib -> pause();
    }
    function asyn__flushAssetVideoFile(){
        $lib = new \Modules\assets\lib();
        return $lib -> flushAssetVideoFile();
    }
    function asyn__flushAssetImageFile(){
        $lib = new \Modules\assets\lib();
        return $lib -> flushAssetImageFile();
    }
    function asyn__flushAssetsAdsInsight(){
        $lib = new \Modules\assets\lib();
        return $lib -> flushAssetsAdsInsight();
    }
    function asyn__flushKeywordsInsight(){
        $lib = new \Modules\keywords\lib();
        return $lib -> flushKeywordsInsight();
    }
    function asyn__flushKeywordsInsightAll(){
        $lib = new \Modules\keywords\lib();
        return $lib -> flushKeywordsInsightAll();
    }
    function asyn__flushFeed(){
        $lib = new \Modules\feeds\lib();
        return $lib -> flushFeed();
    }
    function asyn__flushFeedParseXML(){
        $lib = new \Modules\feeds\lib();
        return $lib -> flushFeedParseXML();
    }
    function asyn__downFeedImage(){
        $lib = new \Modules\feeds\lib();
        return $lib -> downFeedImage();
    }
    //products
    function asyn__flushProducts(){
        $lib = new \Modules\products\lib();
        return $lib -> flushProducts();
    }
    function asyn__flushProductsItem(){
        $lib = new \Modules\products\lib();
        return $lib -> flushProductsItem();
    }
	############################################################
	# 一些异步接口结束
	############################################################





}
