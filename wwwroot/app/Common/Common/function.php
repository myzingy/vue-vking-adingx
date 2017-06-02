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
                "Reach" => number_format($r['reach'],0,'.',','),
                "Frequency" => $r['frequency'],
                "CostperResult" => 'XXX',
                "AmountSpent" => '$'.number_format($r['spend'],2),
                "LinkClicks" => number_format($r['inline_link_clicks'],0,'.',','),
                "WebsitePurchases" => 'XXX',
                "ClicksAll" => number_format($r['clicks'],0,'.',','),
                "CTRAll" => number_format($r['ctr'],2).'%',
                "CPCAll" => number_format($r['cpc'],2).'%',
                "Impressions" => number_format($r['impressions'],0,'.',','),
                "CPM1000" => '$'.number_format($r['cpm'],2),
                "CPC" => '$'.number_format($r['ost_per_inline_link_click'],2),
                "CTR" => number_format($r['inline_link_click_ctr'],2).'%',
                'Date' => array($r['date_start']),
                'List' => array() ,
                'Budget'=>  '$'.number_format($r['daily_budget']/100,2)
            );
            if (!$formatData[$r[$campaign_id]]) {
                $formatData[$r[$campaign_id]] = $dr;
            } else {
                $formatData[$r[$campaign_id]]['Date'][1] = $r['date_stop'];

            }
            array_push($formatData[$r[$campaign_id]]['List'], $dr);
        }
    }
    $data=[];
    foreach ($formatData as $r){
        $r['Date']=array_reverse($r['Date']);
        $r['Date'][1]="...".$r['Date'][1];
        //$r['List']=array_reverse($r['List']);
        array_push($data,$r);
    }
    return $data;
}