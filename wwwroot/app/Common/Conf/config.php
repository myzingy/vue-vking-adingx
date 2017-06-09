<?php
//__APP__POS CC__DEV,CC__TEST,CC__LINE
define('__APP__POS',$_SERVER['__APP__POS']?$_SERVER['__APP__POS']:'CC__TEST');
$conf['db']=include(CONF_PATH.'db.'.strtolower(__APP__POS).'.php');
$conf['fb']=include(CONF_PATH.'facebookads.php');
//'配置项'=>'配置值'
$conf['think']=array(
	"URL_MODEL"=>2,
	//"SESSION_AUTO_START"=>true,
    'DEFAULT_TIMEZONE'=>'Asia/Shanghai'
);
$_conf=array();
foreach ($conf as $_c) {
	$_conf=array_merge($_conf,$_c);
}
//if(__APP__POS=='CC__LINE'){
	$_conf['TMPL_EXCEPTION_FILE']= APP_PATH.'/Public/exception.php';
//}
return $_conf;