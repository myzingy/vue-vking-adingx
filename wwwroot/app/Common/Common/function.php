<?php
/**
 * $data array(
  	'target'=>'app_user|provider',
  	'target_id'=>$target_id,
  	'money'=>$money,
  	'order_id'=>$order_id,
  	'info'=>'',
   )
 * $do +/-
 * $ext_table
 */
function money_log($data,$do='#',$ext_table=""){
	$model=new \Modules\moneylog\model($ext_table);
	$model->addlog($data,$do);
}
function console(){
	if(__APP__POS=='CC__LINE') return;
	return \Modules\common\common_lib::console(func_get_args());
}
function debug(){
	//if(__APP__POS=='CC__LINE') return;
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
 *	//set_time_limit(0);
 */
function asyn($path,$params=array(
		'file_type'=>'',
		'MediaId'=>'',
		'ThumbMediaId'=>'',
		'fp_id'=>'',
),$method="GET"){
	return \Modules\common\common_lib::http_asyn($path, $params, $method);
}
function fixtime($addtime,$nowtime=NOW_TIME){
	$today=strtotime("today");
	$yesterday=strtotime("yesterday");//-1 day
	$runtime=$nowtime-$addtime;
	if($runtime<=60){
		$addtime_label="刚刚";
	}elseif($runtime<=3600){
		$addtime_label=(int)($runtime/60)."分钟前";
	}elseif($runtime<=86400*3){
		$runhour=(int)($runtime/3600);
		if($runhour<2){
			$addtime_label=$runhour."小时前";
		}else{
			if($addtime>$today){
				$addtime_label="今天 ".date("H:i",$addtime);
			}elseif($addtime>$yesterday){
				$addtime_label="昨天 ".date("H:i",$addtime);
			}else{
				$runday=ceil($runtime/86400);
				$addtime_label=$runday."天前";
			}
		}

	}else{
		$addtime_label=date('n月j日',$addtime);
	}
	return $addtime_label;
}
function put2qiniu($filePath){
	$qn_conf=C('qiniu');
	vendor('qiniu.autoload');
	//require 'framework/ThinkPHP/Library/Vendor/qiniu/autoload.php';

	// 构建鉴权对象
	$auth = new \Qiniu\Auth($qn_conf['AK'], $qn_conf['SK']);
	// 生成上传 Token
	$token = $auth->uploadToken($qn_conf['bucket']);

	// 初始化 UploadManager 对象并进行文件的上传
	$uploadMgr = new \Qiniu\Storage\UploadManager();

	// 调用 UploadManager 的 putFile 方法进行文件的上传
	list($ret, $err) = $uploadMgr->putFile($token, $filePath, $filePath);
	if ($err !== null) {
		var_dump($err);
	} else {
		var_dump($ret);
	}

	var_dump($qn_conf);
	exit;
}
function share_url($path=""){
	$web_url="http://m.colorcun.com/";
	if(__APP__POS!='CC__LINE'){
		$web_url="http://m1.colorcun.com/";
	}
	return $web_url.$path;
}
function xcx_url($path=""){
	$web_url="http://www.colorcun.com/";
	if(__APP__POS!='CC__LINE'){
		$web_url="http://www1.colorcun.com/";
	}
	return $web_url.$path;
}
function share_pic($img_id="",$uid=null,$pid=null){
	if($img_id){
		return url('apido/download/id/'.$img_id.'/box/60');
	}
	return url('assets/img/logo_60.png');
}
function share_pic_weibo($img_id="",$uid=null,$pid=null){
	if($img_id){
		return url('apido/download/id/'.$img_id);
	}
	return url('assets/img/logo_60.png');
}
//返回商家名
function getPName($pid){
	$provider_name=M('provider')->where("id=$pid")->getField('name');
	return $provider_name;
}
//返回游客名
function getUName($uid){
	$user=M('app_user')->field('nick,phone')->where("id=$uid")->find();
	if(!$user) return "空空";
	if($user['nick']) return $user['nick'];
	return preg_replace("/^[0-9]{7}/","土豆",$user['phone']);
}
//返回商家名或昵称 $is_provider 0/1  游客/商家
function getName($uid,&$is_provider=0,&$pid=0){

	$provider=M('provider')->field('id,name')->where("uid=$uid")->find();
	$provider_name=$provider['name'];
	if($provider['name']){
		$is_provider=1;
		$pid=$provider['id'];
		return $provider_name;
	}
	return getUName($uid);
}
//request app/web
function requestIsApp(&$client){
	$client="web";
	$agent=preg_replace("/\/.*/","/",$_SERVER['HTTP_USER_AGENT']);
	$agent_android=array('okhttp/','Dalvik/');
	$agent_ios_seller=array('Seller/');
	$agent_ios_buyer=array('Buyer/');
	if(in_array($agent,$agent_android)){$client='android';return true;}
	if(in_array($agent,$agent_ios_seller)){$client='ios_seller';return true;}
	if(in_array($agent,$agent_ios_buyer)){$client='ios_buyer';return true;}
	return false;
}
//是否为马甲号码（不发验证码，使用特殊码登陆）
function isVest($phone){
	$online=C('online');
	$vests=$online['phone'];
	$check_code=$vests[$phone];
	if($check_code){
		return $check_code;
	}

	if(preg_match("/^17[04]{1}[0-9]{8}/",$phone)){
		foreach($vests as $key_phone=>$check_code){
			if(strpos($key_phone,"/")>-1){
				//var_dump($key_phone,$phone,preg_match($key_phone,$phone));
				if(preg_match($key_phone,$phone)){
					return $check_code;
				}
			}
		}
	}
	return false;
}
//返回当前订单的状态
function orderstatus($model,$status,$is_user){
	$data=[];
	if($is_user>0){
		switch($status){
			case 0:
				switch($model){
					case 0:
						$data['info']="待支付";
						$data['operate']="取消订单,继续支付";
						$data['opstatus']="0,1";
						break;
					case 1:
						$data['info']="待支付";
						$data['operate']="继续支付";
						$data['opstatus']="1";
						break;
					case 2:
						$data['info']="待支付";
						$data['operate']="取消订单,继续支付";
						$data['opstatus']="0,1";
						break;
					case 3:
						$data['info']="下单成功";
						$data['operate']="继续支付";
						$data['opstatus']="1";
						break;
				}
			break;
			case 2:
				switch($model){
					case 0:
						$data['info']="待商家确认";
						$data['operate']="取消订单";
						$data['opstatus']="0";
						break;
					case 1:
						$data['info']="已付款";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 2:
						$data['info']="已付款";
						$data['operate']="待发货";
						$data['opstatus']="3";
						break;
					case 3:
						$data['info']="已买单";
						$data['operate']="";
						$data['opstatus']="";
						break;
				}
			break;
			case 1:
				switch($model){
					case 0:
						$data['info']="预定成功";
						$data['operate']="取消订单";
						$data['opstatus']="0";
						break;
					case 1:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 2:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 3:
						$data['info']="下单成功";
						$data['operate']="继续支付";
						$data['opstatus']="1";
						break;
				}
			break;
			case 3:
				switch($model){
					case 0:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 1:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 2:
						$data['info']="已发货";
						$data['operate']="确认收货";
						$data['opstatus']="4";
						break;
					case 3:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
				}
			break;
			case 4:
				switch($model){
					case 0:
						$data['info']="已取消";
						$data['operate']="再次预订";
						$data['opstatus']="2";
						break;
					case 1:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 2:
						$data['info']="已取消";
						$data['operate']="删除";
						$data['opstatus']="8";
						break;
					case 3:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
				}
			break;
			case 5:
				switch($model){
					case 0:
						$data['info']="商家拒绝";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 1:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 2:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 3:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
				}
			break;
			case 6:
				switch($model){
					case 0:
						$data['info']="支付超时";
						$data['operate']="再次预订";
						$data['opstatus']="2";
						break;
					case 1:
						$data['info']="该订单已失效";
						$data['operate']="删除";
						$data['opstatus']="8";
						break;
					case 2:
						$data['info']="该订单已失效";
						$data['operate']="删除";
						$data['opstatus']="8";
						break;
					case 3:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
				}
			break;
			case 7:
				switch($model){
					case 0:
						$data['info']="预定成功";
						$data['operate']="再次预订";
						$data['opstatus']="2";
						break;
					case 1:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 2:
						$data['info']="已收货";
						$data['operate']="去评价";
						$data['opstatus']="5";
						break;
					case 3:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
				}
			break;

		}
	}
	else{
		switch($status){
			case 0:
				switch($model){
					case 0:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 1:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 2:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 3:
						$data['info']="待服务员确认";
						$data['operate']="";
						$data['opstatus']="";
						break;
				}
				break;
			case 2:
				switch($model){
					case 0:
						$data['info']="待确认";
						$data['operate']="残忍拒绝,确认预订";
						$data['opstatus']="6,7";
						break;
					case 1:
						$data['info']="已付款";
						$data['operate']="删除";
						$data['opstatus']="8";
						break;
					case 2:
						$data['info']="待发货";
						$data['operate']="发货";
						$data['opstatus']="9";
						break;
					case 3:
						$data['info']="已买单";
						$data['operate']="";
						$data['opstatus']="";
						break;
				}
				break;
			case 1:
				switch($model){
					case 0:
						$data['info']="已确认";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 1:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 2:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 3:
						$data['info']="服务员已确认";
						$data['operate']="";
						$data['opstatus']="";
						break;
				}
				break;
			case 3:
				switch($model){
					case 0:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 1:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 2:
						$data['info']="已发货";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 3:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
				}
				break;
			case 4:
				switch($model){
					case 0:
						$data['info']="已取消";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 1:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 2:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 3:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
				}
				break;
			case 5:
				switch($model){
					case 0:
						$data['info']="已拒绝";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 1:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 2:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 3:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
				}
				break;
			case 6:
				switch($model){
					case 0:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 1:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 2:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 3:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
				}
				break;
			case 7:
				switch($model){
					case 0:
						$data['info']="已完成";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 1:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 2:
						$data['info']="已收货";
						$data['operate']="";
						$data['opstatus']="";
						break;
					case 3:
						$data['info']="";
						$data['operate']="";
						$data['opstatus']="";
						break;
				}
				break;

		}
	}


	$data['operate'] = explode(",", $data['operate']);
	$data['opstatus'] = explode(",", $data['opstatus']);

	return $data;
}
