<?php 
$errstr=json_encode(array(
	'code'=>500,
	"message"=>'::message::',
	'errorfile'=>preg_replace("/.*wwwroot/", '', $e['file'])."(line#".$e['line'].")"
));
die(str_replace("::message::",$e['message'],$errstr));
?>