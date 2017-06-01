<?php
function console(){
	if(__APP__POS=='CC__LINE') return;
	return \Modules\common\common_lib::debug(func_get_args());
}
function debug(){
	$param=func_get_args();
	return \Modules\common\common_lib::debug($param[0],$param);
}
function url($path=""){
	return \Modules\common\common_lib::url($path);
}
function post($url, $param = "", $header = array(), $isGET = false){
	return \Modules\common\common_lib::http_post($url, $param, $header, $isGET);
}
function http($url,$params='',$timeout=10,$type='GET'){
	return \Modules\common\common_lib::http($url,$params,$timeout,$type);
}
/*
 * 异步请求
 * 客服端需要以下代码：
 * #如果客户端断开连接，不会引起脚本abort
 *	ignore_user_abort(true);
 *	#取消脚本执行延时上限
 *	//set_time_limit(0);+
 */
function asyn_implement($path,$params=array(
		'__t'=>'',
),$method="GET"){
	return \Modules\common\common_lib::http_asyn($path, $params, $method);
}
function asyn($path,$params=array(
    '__t'=>'',
),$method="GET"){
    \Modules\cron\lib::create($path, $params, $method);
}
function cronResult($result=true,$message=''){
    \Modules\cron\lib::result($result,$message);
}

function formatInsightsData($data,$type='campaign'){
    $formatData=[];
    $campaign_id= $type."_id";
    if(count($data)>0){
        foreach ($data as $r) {
            $dr = array(
                "CampaignId" => $r[$campaign_id],
                "CampaignName" => $r[$type . '_name'],
                "Delivery" => $r['effective_status'],
                "Results" => 'XXX',
                "Reach" => $r['reach'],
                "Frequency" => $r['frequency'],
                "CostperResult" => $r['campaign_id'],
                "AmountSpent" => $r['spend'],
                "LinkClicks" => $r['inline_link_clicks'],
                "WebsitePurchases" => 'XXX',
                "ClicksAll" => $r['clicks'],
                "CTRAll" => $r['ctr'],
                "CPCAll" => $r['cpc'],
                "Impressions" => $r['impressions'],
                "CPM1000" => $r['cpm'],
                "CPC" => $r['ost_per_inline_link_click'],
                "CTR" => $r['inline_link_click_ctr'],
                'Date' => array($r['date_start']),
                'List' => array()
            );
            if (!$formatData[$r[$campaign_id]]) {
                $formatData[$r[$campaign_id]] = $dr;
            } else {
                $formatData[$r[$campaign_id]]['Date'][1] = "..." . $r['date_stop'];

            }
            array_push($formatData[$r[$campaign_id]]['List'], $dr);
        }
    }
    return $formatData;
}