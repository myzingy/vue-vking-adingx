<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	function __construct() {
		parent::__construct();
	}
	function index(){
	    dump([NOW_TIME,date("Y-m-d H:i:s",NOW_TIME)]);
	    $time=getDayTime("03:00:00",0);
        dump([$time,date("Y-m-d H:i:s",$time)]);
        $time=getDayTime("04:00:00",-1);
        dump([$time,date("Y-m-d H:i:s",$time)]);
        $time=getDayTime("05:00:00",1);
        dump([$time,date("Y-m-d H:i:s",$time)]);
        //die('<meta http-equiv="refresh" content="0;url='.url('../wwwrootdist/').'"> ');
    }
}
