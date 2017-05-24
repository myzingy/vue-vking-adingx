<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	function __construct() {
		parent::__construct();
		$this->api_lib=D('App','Logic');
	}
    function index() {
        die("<h2>facebook ads server!</h2>");
    }
}
