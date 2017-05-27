<?php
namespace Home\Controller;
use Think\Controller;
class ApidoController extends Controller {
	function __construct() {
		parent::__construct();
		$this->api_lib=D('App','Logic');
	}
	public function _empty($name,$p){
		$this->_act($name);
	}
	private function _act($action,$p){
		$prarm=func_get_args();
		$info=array(
			'request'=>I('request.',null,'trim')
		);
		$this->api_lib->status($info,10000);
		//$action=preg_replace("#.*/#", "", array_shift(array_keys(I('get.'))));
		/*
		 * 接口名称前带  xxx__ 的接口需要授权后才能访问
		 */
		list($check)=explode(".", $action);
		
		//身份类别判断及身份检查,当前用户数据保存
		if(in_array($check,array('user','msg','get'))){
			$code=$this->api_lib->check($check);
			if($code<0){       //大于1的话就验证状态错误
				$this->api_lib->status($info,$code,'你的登录已失效，请重新登录');
				$this->ajaxReturn($info,I('request.callback','')?'jsonp':'');
			}
		}
        if('asyn'==$check){
            set_time_limit(0);
            ignore_user_abort(true);
        }
		$run_action=str_replace('.', '__', $action);
		if(method_exists($this->api_lib,$run_action)){
			$info=call_user_func_array(array(&$this->api_lib, $run_action), $prarm);
			if(is_string($info)){
				$info=array(
					'message'=>$info
				);
			}
			$info['message']=$info['message']?$info['message']:'';
			$info['code']=$info['message']?($info['code']?$info['code']:10000):200;
			$info['act']=$action;
			$info['provider_flag']=$info['provider_flag']?$info['provider_flag']:($this->api_lib->user->provider_flag."");

			$this->api_lib->status($info);
		}else{
			//方法未找到
			$this->api_lib->status($info,9999,$action.' 没有定义');
		}
		
		//如果非正常返回就,附带错误信息
		if($info['code']!=0 && !$info['message'])$this->api_lib->status($info,$info['code']);
		//cron sync
        if('asyn'==$check){
            cronResult();
        }
		$this->ajaxReturn($info,I('request.callback','')?'jsonp':'');
	}
}
