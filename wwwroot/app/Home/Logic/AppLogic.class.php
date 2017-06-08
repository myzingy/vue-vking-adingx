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
		//if(!in_array($acl,array('seller','member','distr')))
		
		/*$openid = I('request.openid');
		$user_lib = new \Modules\member\member_lib();
		$this -> user = $user_lib -> getOpenid2User($openid);*/
		return 0;
		
		$token = I('request.token');
		$user_lib = new \Modules\member\member_lib();
		$this -> user = $user_lib -> getToken2User($token);
		if ($acl != 'user')
			return 0;
		if (!$this -> user -> id)
			$this -> user = $user_lib -> getOpenid2User();
		if (!$this -> user -> id)
			return -1;
		else if ($this -> user -> status == 0)
			return -2;
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

    ############################################################
    # public api 公开，无任何权限认证结束
    ############################################################
    function user__getCampaignsInsightsData(){
        $lib = new \Modules\campaigns\insights\lib();
        return $lib -> getCampaignsInsightsData();
    }
    function user__getAdsetsInsightsData(){
        $lib = new \Modules\adsets\insights\lib();
        return $lib -> getAdsetsInsightsData();
    }
    function user__getAdsInsightsData(){
        $lib = new \Modules\ads\insights\lib();
        return $lib -> getAdsInsightsData();
    }
    function user__updateRulesData(){
        $lib = new \Modules\rules\lib();
        return $lib -> updateRulesData();
    }
    function user__getRulesData(){
        $lib = new \Modules\rules\lib();
        return $lib -> getRulesData();
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
        return $lib -> saveRulesForAd();
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
        die('<meta http-equiv="refresh" content="5;url='.url('apido/cron.demon_test?t=').rand(1,22222222).'"> ');
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
	############################################################
	# 一些异步接口结束
	############################################################





}
