<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	function __construct() {
		parent::__construct();
	}
	function index(){
	    die('<meta http-equiv="refresh" content="0;url='.url('../wwwrootdist/').'"> ');
    }
}
