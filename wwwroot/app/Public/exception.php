<?php 
$errstr=json_encode(array(
	'code'=>500,
	"message"=>'::message::',
	'errorfile'=>preg_replace("/.*wwwroot/", '', $e['file'])."(line#".$e['line'].")"
));
echo str_replace("::message::",$e['message'],$errstr);
//debug('exception',I('request.'),$e['message'],$errstr);
cronResult(false,'Exception:'.$e['message'].$errstr);
exit;
?>