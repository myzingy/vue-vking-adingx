<?php
function console(){
	if(__APP__POS=='CC__LINE') return;
	return \Modules\common\common_lib::debug(func_get_args());
}
function debug(){
	$param=func_get_args();
    $title=array_shift($param);
	return \Modules\common\common_lib::debug($title,$param);
}
function url($path=""){
	return \Modules\common\common_lib::url($path);
}
function post($url, $param = "", $header = array(), $isGET = false){
	return \Modules\common\common_lib::http_post($url, $param, $header, $isGET);
}
function http($url,$params='',$timeout=10,$type='GET',$retry=1){
	return \Modules\common\common_lib::http($url,$params,$timeout,$type,$retry);
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
function asyn($path,$params=array('__t'=>''),$method="GET",$crontime=0,$priority=1){
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
        'breakdowns.device_platform::desktop.spend'=>'DesktopSpend',
        'breakdowns.device_platform::mobile.spend'=>'MobileSpend',
        'action_values::offsite_conversion.fb_pixel_add_to_cart'=>'WebsiteAddstoCartConversionValue',
    );
    $dayData=array();
    if($data){
        foreach ($data_key as $key){
            $dayData[$key]=$data[strtolower('CLICK1D_'.$key)];
        }
    }
    return $dayData;
}
function setDayClick($action_data,&$data){
    $data_key=array(
        'actions::offsite_conversion.fb_pixel_add_to_cart'=>'WebsiteAddstoCart',
        'actions::offsite_conversion.fb_pixel_purchase'=>'WebsitePurchases',
        'cost_per_action_type::offsite_conversion.fb_pixel_add_to_cart'=>'CostperWebsiteAddtoCart',
        'action_values::offsite_conversion.fb_pixel_purchase'=>'WebsitePurchasesConversionValue',
        'cost_per_action_type::link_click'=>'CPC',
        'breakdowns.device_platform::desktop.spend'=>'DesktopSpend',
        'breakdowns.device_platform::mobile.spend'=>'MobileSpend',
        'action_values::offsite_conversion.fb_pixel_add_to_cart'=>'WebsiteAddstoCartConversionValue',
    );
    foreach ($data_key as $key){
        $data['CLICK1D_'.$key]=0;    
    }
    foreach ($action_data as  $r){
        if($key=$data_key[$r['insight_key'].'::'.$r['action_type']]){
            $data['CLICK1D_'.$key]=$r['1d_click'];
        }
    }
}
function formatInsightsData($data,$type='campaign'){
    $formatData=[];
    $campaign_id= $type."_id";
    if(count($data)>0){
        foreach ($data as &$that){
            foreach ($that['insights'] as $i=>$r){
                $day_click=getDayClick($r);
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
                    "AccountId"=>$r['account_id'],
                    "AccountName"=>$r['account_name'],

                    #"Delivery" => $r['effective_status'],
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
                    #'Budget'=>  '$'.number_format($r['daily_budget']/100,2),
                    'Date' => array($r['date_start']),
                    'List' => array() ,
                    #'RuleRuntime'=>$r['rule_runtime'],
                    'DesktopSpend' => $day_click['DesktopSpend']+0,
                    'MobileSpend' => $day_click['MobileSpend']+0,
                );
                if($i==0){
                    $that=array_merge($that,$dr,array(
                        "Delivery" => $that['effective_status'],
                        'Budget'=>  '$'.number_format($that['daily_budget']/100,2),
                        'RuleRuntime'=>$that['rule_runtime']
                    ));
                }
                $that['List'][]=$dr;
            }
            unset($that['effective_status'],$that['rule_runtime'],$that['daily_budget']
                ,$that['id'],$that['name'],$that['insights']);
        }

    }
    return $data;
}
function getDayTime($his,$day_num=1){
    $format="Y-m-d $his";
    return strtotime("$day_num day", strtotime(date($format,NOW_TIME)));
}
function getSpaceTime($m=1){
    list($h,$i)=explode(':',date("H:i",NOW_TIME));
    $i+=($i%$m);
    if($i>=60){
        $h+=1;
        $i=$i%60;
    }
    if($h>=24) getDayTime("00:00:00",1);
    return getDayTime("$h:$i:00",0);
}
function FBC($ac_id=""){
    $lib=new \Modules\accounts\lib();
    $data=$lib->FBC($ac_id);
    return $data;
}
function postERP($uri,$data){
    if(__APP__POS=='CC__DEV') return;
    $erp_host=C('erp_host');
    foreach ($erp_host as $host){
        asyn_implement($host.$uri,$data,'POST');
    }
}
function mk($path){
    $dir="";
    foreach (explode('/',$path) as $p){
        if(!$p) continue;
        $dir.=$p.'/';
        if(file_exists($dir)){
            continue;
        }
        @mkdir($dir);
    }
}