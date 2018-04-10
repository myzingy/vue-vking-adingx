<?php
//__APP__POS CC__DEV,CC__TEST,CC__LINE
define('__APP__POS',$_SERVER['__APP__POS']?$_SERVER['__APP__POS']:'CC__TEST');
$conf['db']=include(CONF_PATH.'db.'.strtolower(__APP__POS).'.php');
$conf['fb']=include(CONF_PATH.'facebookads.php');
$conf['fbapp']=include(CONF_PATH.'facebookapp.php');
$conf['group_rules']=include(CONF_PATH.'group_rules.php');
//'配置项'=>'配置值'
$conf['think']=array(
	"URL_MODEL"=>2,
	//"SESSION_AUTO_START"=>true,
    'DEFAULT_TIMEZONE'=>__APP__POS=='CC__DEV'?'PRC':'PRC',
);
$conf['erp_host']=array('erp_host'=>array(
        'http://54.199.246.177/',//测试环境
        'http://52.199.219.172/',//vkingx 正式
        'http://54.64.69.216/',//gnoce  正式
));
$conf['brand']=include(CONF_PATH.'brand_accounts.php');
$conf['debug_rule_adsets']=include(CONF_PATH.'debug_rule_adsets.php');
$conf['patch']=include(CONF_PATH.'operator.patch.php');
$_conf=array();
foreach ($conf as $_c) {
	$_conf=array_merge($_conf,$_c);
}
//if(__APP__POS=='CC__LINE'){
	$_conf['TMPL_EXCEPTION_FILE']= APP_PATH.'/Public/exception.php';
//}
return $_conf;
