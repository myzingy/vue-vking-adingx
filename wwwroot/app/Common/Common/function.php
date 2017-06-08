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
),$method="GET",$crontime=0,$priority=0){
    \Modules\cron\lib::create($path, $params, $method,$crontime,$priority);
}
function cronResult($result=true,$message=''){
    \Modules\cron\lib::result($result,$message);
}
function getDayClick($data){
    $data_key=array(
        'actions::offsite_conversion.fb_pixel_add_to_cart'=>'WebsiteAddstoCart',
        'actions::offsite_conversion.fb_pixel_purchase'=>'WebsitePurchases',
        'cost_per_action_type::offsite_conversion.fb_pixel_add_to_cart'=>'CostperWebsiteAddtoCart',
        'action_values::offsite_conversion.fb_pixel_purchase'=>'WebsitePurchasesConversionValue',
        'cost_per_action_type::link_click'=>'CPC',
    );
    //$data=M($type.'s_insights_action_types')->where("{$type}s_insights_id='$id'")->select();
    $dayData=array();
    if($data){
        foreach ($data as  $r){
              if($key=$data_key[$r['insight_key'].'::'.$r['action_type']]){
                    $dayData[$key]=$r['1d_click'];
              }
        }
    }
    return $dayData;
}
function formatInsightsData($data,$type='campaign'){
    $formatData=[];
    $campaign_id= $type."_id";
    if(count($data)>0){
        foreach ($data as $r) {
            $day_click=getDayClick($r[$type.'s_insights_action_types']);
            $dr = array(
                "Id" => $r[$campaign_id],
                "Type" =>  $r['type'],
                "Name" => $r[$type . '_name'],
                "CampaignId"=>$r['campaign_id'],
                "CampaignName"=>$r['campaign_name'],
                "AdsetId"=>$r['adset_id'],
                "AdsetName"=>$r['adset_name'],
                "AdId"=>$r['ad_id'],
                "AdName"=>$r['ad_name'],

                "Delivery" => $r['effective_status'],
                'WebsiteAddstoCart'=>(int)$day_click['WebsiteAddstoCart'],
                'CostperWebsiteAddtoCart'=> '$'.number_format($day_click['CostperWebsiteAddtoCart'],2),
                "AmountSpent" => $r['spend']+0,
                "WebsitePurchases" => (int)$day_click['WebsitePurchases'],
                'WebsitePurchasesConversionValue'=> '$'.number_format($day_click['WebsitePurchasesConversionValue'],2),
                "LinkClicks" => number_format($r['inline_link_clicks'],0,'.',','),
                "CPC" => '$'.number_format($day_click['CPC'],2),
                "CTR" => number_format($r['inline_link_click_ctr'],2).'%',
                "CPM1000" => '$'.number_format($r['cpm'],2),
                "Reach" => number_format($r['reach'],0,'.',','),
                "Results" => (int)$day_click['WebsitePurchases'],
                "CostperResult" => '$'.number_format($r['spend']/($day_click['WebsitePurchases']),2),
                'Budget'=>  '$'.number_format($r['daily_budget']/100,2),
                //"Frequency" => $r['frequency'],
                //"ClicksAll" => number_format($r['clicks'],0,'.',','),
                //"CTRAll" => number_format($r['ctr'],2).'%',
                //"CPCAll" => number_format($r['cpc'],2).'%',
                //"Impressions" => number_format($r['impressions'],0,'.',','),
                'Date' => array($r['date_start']),
                'List' => array() ,
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
        if($r['Date'][1]){
            $r['Date']=array_reverse($r['Date']);
            $r['Date'][1]="...".$r['Date'][1];
        }
        //$r['List']=array_reverse($r['List']);
        array_push($data,$r);
    }
    return $data;
}