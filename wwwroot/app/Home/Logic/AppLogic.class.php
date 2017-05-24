<?php

namespace Home\Logic;

class AppLogic {

	var $user = null;
	//当前用户数据
	var $statusCode = null;

	function __construct() {
	}

	public function status(&$arr, $code, $str = '') {
		$error = $this -> statusCode[$code];
		if ($error) {
			$arr['code'] = $code;
			$arr['message'] = $str ? $str : $error;
		} else {
			$arr['code'] = $code ? $code : $arr['code'];
			$arr['message'] = $str ? $str : $arr['message'];
		}
		$arr['code'] = ($arr['code'] == "" || $arr['code'] == 0) ? 200 : $arr['code'];
		$this -> res_data = $arr;
		return $arr;
	}
  
  /*public function api4(){
	  setcookie("pkid", "1000", time()+3600);
  }*/
  
	public function check($acl) {
		//if(!in_array($acl,array('seller','member','distr')))
		
		/*$openid = I('request.openid');
		$user_lib = new \Modules\member\member_lib();
		$this -> user = $user_lib -> getOpenid2User($openid);*/
		
		
		$token = I('request.token');
		$user_lib = new \Modules\member\member_lib();
		$this -> user = $user_lib -> getToken2User($token);
		if ($acl != 'user')
			return 0;
		if (!$this -> user -> id)
			$this -> user = $user_lib -> getOpenid2User();
		if (!$this -> user -> id)
			return -1;
		else if ($this -> user -> status == 0)
			return -2;
		return 0;
		
		//var_dump($this -> user);
	}
	function checkSellerInfo($acl){
		$act_arr=array('user.addNewRoom','user.addNewFood','user.addNewActivity','user.addNewProduct','user.discountadd',
				'user.operation','user.discountsdel','user.dynamicUpdate','user.dynamicDelete',
				'user.dynamicUpdateLong','user.dynamicInfo',);
		if(in_array($acl,$act_arr)){
			$provider=$this->user->provider;
			if(trim($provider['name'])){
				return true;
			}
			$user=$this->user;
			$pm=M('provider');
			$pm->find($user->provider_id);
			$provider=$this->user->provider;
			if(trim($pm->name)){
				$user->provider=array(
						'name'=>$pm->name,
						'address'=>$pm->address,
						'lat'=>$pm->lat,
						'lng'=>$pm->lng,
				);
				S(I('request.token'),$user);
				return true;
			}
			return false;
		}
		return true;
	}
	public function getApiDoc() {
		$common = new \Modules\common\common_lib();
		$reflection = new \ReflectionClass(__CLASS__);

		return $common -> getApiDoc($reflection);
	}

	############################################################
	# public api 公开，无任何权限认证
	############################################################
	/**
	 * <ok/>获取短信验证码
	 *  参数：
	 *      phone: int 手机号
	 *      type: int 获取验证码类型(1 登录)(2 绑定银行卡)
	 * 		size：4 默认4位
	 *  返回
	 *      {
	 *          "code":200, // 200：成功
	 *      }
	 */
	function pincode() {
		return \Modules\common\pincode::send();
	}

	/**
	 * <ok/> 登录接口
	 *  参数
	 *     phone：int 手机号
	 *     code: int 验证码
	 *     pkid: int 邀请码
	 *     openid: int 微信用户标示
	 * 返回
	 *         {
	 *             “code”:200, // 200：成功
	 *             “data”:{
	 *                 “token”:”用户身份”, //后期用户和服务器交互的令牌
	 *                 “uid”:”1003730686”, //服务器后台给用户分配的唯一ID
	 * 					"provider_id":int //农家乐ID
	 * 					"nick": string 用户昵称
						"gender":int 性别 0-女 1-男
						"avatar": string 头像图片地址
						"birthday": string 生日
						"phone": string 手机号
	 *             }
	 *         }
	 */

	function login() {
		$member = new \Modules\member\member_lib();
		return $member -> login();
	}

	/**
	 * <ok/> <font color='red'>切换身份登录接口</font>
	 *  参数
	 *     token：
	 *     phone：int 手机号
	 *     code: int 验证码

	 * 返回
	 *         {
	 *             “code”:200, // 200：成功
	 *             “data”:{
	 *                 “token”:”用户身份”, //后期用户和服务器交互的令牌
	 *                 “uid”:”1003730686”, //服务器后台给用户分配的唯一ID
	 *                 “provider_id”:int //农家乐ID
	 *                 “is_clerk”: //是否为店员 0-非  1-是
	 *             }
	 *         }
	 */

	function user__changelogin() {
		$member = new \Modules\member\member_lib();
		return $member -> changelogin($this -> user);
	}

	/**
	 * <ok/> 生成短链接接口
	 *  参数
	 *     url：int 链接地址
	 * 返回
	 *         {
	 *             “code”:200, // 200：成功
	 *             “data”:{
	 *                sort接口地址
	 *             }
	 *         }
	 */

	function sorturl() {
		$member = new \Modules\common\sort_lib();
		return $member -> sorturl();
	}

	/**
	 * <ok/> 通过接口查看链接地址
	 *  参数
	 *     url：int 链接地址
	 * 返回
	 *         {
	 *             “code”:200, // 200：成功
	 *             “data”:{
	 *                sort接口地址
	 *             }
	 *         }
	 */

	function sort() {
		$member = new \Modules\common\sort_lib();
		return $member -> sort();
	}


	/**
	 * <ok/> 下载文件接口
	 *  参数
	 * 	id: 图片/视频的id-字符串。
	 * 	width:图片宽度，用于生成缩略图，不传将输出原图
	 *  返回
	 *      图片文件
	 */
	function download() {
		$lib = new \Modules\attachment\lib();
		return $lib -> download();
	}
	/**
	 * <ok/> 头像/缩略图 相关
	 * GET
	 * 	http://www.colorcun.com:10002/apido/avatar/参数/ID
	 * 参数：
	 * 	u		用户
	 * 	p		农户
	 * 	pc		农户首张图
	 * 	r		房间
	 * 	f		食物
	 * 	t		特产
	 * 	a		活动
	 * 	pu		广播评论人，优先商家头像
	 * 	article 文章图片
	 * 	dy		广播首图
	 * 	dys		精彩广播首图
	 * 	art_box 文章图片(分享的小图)
	 * ID 为对象ID，如获取房间ID为9  的房间图为 apido/avatar/r/9
	 */
	function avatar(){
		$lib = new \Modules\attachment\lib();
		$lib->avatar();
	}
	/**
	 * <ok/> 获得文章内容
	 * GET
	 * 	http://www.colorcun.com:10002/apido/article/id/$id
	 */
	function article(){
		$art=new \Modules\article\lib();
		$art->content();
	}
	/**
	 * <ok/> 帮助中心
	 *  参数
	 * 		
	 *  返回
	 *      {
	 * 			code:200,
	 * 			data:[{
	 * 				"name":string,帮助中心类型
	 * 				"list"该类型下的文章:
	 *         [{"id":1,"title":"窑洞","url":"..."},{"id":1,"title":"窑洞","url":"..."}...]
	 * 			}]
	 * 		}
	 */
	
	function helpcenter(){
		$art=new \Modules\article\lib();
		return $art->helpcenter();
	}
	
	/**
	 * <ok/> 检查app最新版本
	 *  参数
	 * 		appname:seller|buyer|seller_debug|buyer_debug
	 * 		apptype:android|ios
	 *  返回
	 *      {
	 * 			code:200,
	 * 			data:{
	 * 				"name":string,名称
	 * 				"version":string,版本号
	 * 				"info":string,简介
	 * 				"src":string,下载地址
	 * 				"addtime": int(10)
	 * 			}
	 * 		}
	 */
	
	function versioncheck(){
		$art=new \Modules\android\lib();
		return $art->versioncheck();
	}
	
	/**
	 * <ok/> 下载android版本
	 *  参数
	 * 		appname:seller|buyer|seller_debug|buyer_debug
	 * 		apptype:android|ios
	 *  返回
	 *      
	 */
	
	function versiondown(){
		$art=new \Modules\android\lib();
		return $art->versiondown();
	}

	/**
	 * <ok/> 生成自定义菜单
	 */
	function custommenu(){
		$lib = new \Modules\weixin\lib();
		return $lib -> custommenu();
	}
	############################################################
	# member api 需要登录才能处理
	############################################################
	/**
	 * <ok/> 上传文件接口
	 *  参数
	 * 		token: 用户登录后的 token
	 * 		file:文件
	 *  返回
	 *      {
	 * 		code:200,
	 * 		data:{
	 * 			"file_id":int,建立关系需要文件ID
	 * 			"url":"xxx.xxx.xxx"，可用于前端显示
	 * 		}
	 * 	}
	 */
	function user__upload() {
		$common_lib = new \Modules\common\common_lib();
		return $common_lib -> upload($this -> user);
	}


	############################################################
	# 一些异步接口开始
	############################################################
	/**
	 * <ok class="异步"/> 商家经纬度处理
	 * 更新商家地址信息时，将经纬度格式化为省市区
	 * 将省市区更新到commontype
	 * 将商家信息绑定commontype省市区ID
	 */
	function asyn__position(){
		$lib = new \Modules\provider\lib();
		return $lib -> latlng2position();
	}
	/**
	 * <ok class="异步"/> 广播浏览量增加
	 */
	function asyn__dynamicsViews(){
		$lib = new \Modules\dynamic\lib();
		return $lib -> addViews();
	}
	/**
	 * <ok class="异步"/> 异步移动资源到七牛
	 */
	function asyn__attr2qiniu(){
		$lib = new \Modules\attachment\lib();
		return $lib -> attr2qiniu();
	}
	/**
	 * <ok class="异步"/> 登陆异步控制openid
	 */
	function asyn__loginoperate(){
		$lib = new \Modules\member\member_lib();
		return $lib -> loginoperate();
	}
	/**
	 * <ok class="异步"/> 广播评论/赞的消息
	 * is_love=0&did=1&cid=1
	 */
	function asyn__dynamic_comment(){
		$lib = new \Modules\message\lib();
		return $lib -> asyn_dynamic_comment();
	}
	/**
	 * <ok class="异步"/> 统计广播模块(区分web端和app端)
	 */
	function asyn__dynamicCount(){
		$lib = new \Modules\dynamic\lib();
		return $lib -> dynamicCount();
	}
	/**
	 * <ok class="异步"/> 上传微信端头像图片
	 */
	function asyn__headimgUrl(){
		$lib = new \Modules\weixin\lib();
		return $lib -> headimgUrl();
	}
	/**
	 * <ok class="异步"/> 新注册农户自动推送消息（运营文章）
	 */
	function asyn__newProvider(){
		$lib = new \Modules\message\lib();
		return $lib -> newProvider();
	}
	/**
	 * <ok class="异步"/> 向微博推送消息
	 */
	function asyn__weibo(){
		$lib = new \Modules\dynamic\lib();
		return $lib -> weibo();
	}
	/**
	 * <ok class="异步"/> 发券后异步发送广播数据，一条游客，一条商户
	 * 直接异步调用 <a href="#user.dynamicUpdateLong">5.6 user.dynamicUpdateLong</a>
	 * 必填参数
	 * 	token:*
	 * 	title:发券POST DATA+ID to json
	 * 	modal:3
	 * 	show_which:0/1/2 全部/只在农户/只在游客		默认0
	 *	attr:[{"file_id":file_id}]   file_id为图片id
	 * 	tags:777777
	 */
	function asyn__发券后异步发送广播数据(){
	}
	/**
	 * 主动推送url给百度
	 */
	function asyn__pushBaiduSeo(){
		$api='http://data.zz.baidu.com/urls?site=m.colorcun.com&token=gNTeNbHrjjvGJWpq';
		return post($api,I('request.url'),array('Content-Type: text/plain'));
	}
	/**
	 * <ok class="异步"/> 商户确认使用卡券,向游客发送消息
	 */
	function asyn__voucher_confirm(){
		$lib = new \Modules\message\lib();
		return $lib -> asyn_voucher_confirm();
	}
	/**
	 * <ok class="异步"/> 商户确认游客已支付的订单
	 */
	function asyn__ptou_confirm(){
		$lib = new \Modules\message\lib();
		return $lib -> asyn_ptou_confirm();
	}
	/**
	 * <ok class="异步"/> 用户已支付订单/等待商家确认
	 */
	function asyn__utop_request(){
		$lib = new \Modules\message\lib();
		return $lib -> asyn_utop_request();
	}
	/**
	 * <ok class="异步"/> 用户取消订单(商家已确认状态)
	 */
	function asyn__utop_cancel(){
		$lib = new \Modules\message\lib();
		return $lib -> asyn_utop_cancel();
	}
	/**
	 * <ok class="异步"/> 商户拒绝游客已支付的订单
	 */
	function asyn__ptou_refuse(){
		$lib = new \Modules\message\lib();
		return $lib -> asyn_ptou_refuse();
	}
	/**
	 * <ok class="异步"/> 游客已支付,向商家发送消息(到店付)
	 */
	function asyn__utop_ddfrequest(){
		$lib = new \Modules\message\lib();
		return $lib -> asyn_utop_ddfrequest();
	}
	/**
	 * <ok class="异步"/> 将游客操作信息记录到'我的游客'
	 */
	function asyn__tourist_opration(){
		$lib = new \Modules\tourist\lib();
		return $lib -> asyn_tourist_opration();
	}
	/**
	 * <ok class="异步"/> 发布图文异步记录到产品列表(房间\美食\活动\特产)
	 */
	function asyn__cogradient_goods(){
		$lib = new \Modules\dynamic\lib();
		return $lib -> asyn_cogradient_goods();
	}
	/**
	 * <ok class="异步"/> 删除图文异步记录到产品列表(房间\美食\活动\特产)
	 */
	function asyn__cogradient_delete(){
		$lib = new \Modules\dynamic\lib();
		return $lib -> asyn_cogradient_delete();
	}
	/**
	 * <ok class="异步"/> 设置餐品状态为已过期
	 */
	function asyn__cookbook_expired(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> asyn_cookbook_expired();
	}
	/**
	 * <ok class="异步"/> 设置打印状态为已打印
	 */
	function asyn__cookbook_printed(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> asyn_cookbook_printed();
	}
	############################################################
	# 一些异步接口结束
	############################################################


	############################################################
	# 消息接口
	############################################################
	/**
	 * <ok class="消息管理"/>消息全局说明
	 * 正式环境环信ID规则：naji_user_$uid    用户登陆我们自己的服务器后返回了用户ID，用户ID加上（naji_user_）前缀就是环信用户ID
	 * 测试环境环信ID规则：naji_test_$uid    用户登陆我们自己的服务器后返回了用户ID，用户ID加上（naji_test_）前缀就是环信用户ID
	 * 用户密码：colorcun.com
	 *
	 * 服务器发送消息会附加扩展字段，客户端需要根据扩展字段进行处理，<a target="_blank" href="http://docs.easemob.com/im/200androidclientintegration/50singlechat#消息扩展">参考环信扩展消息</a>
	 * 扩展消息结构如下
	 * 	{t:int,d:object}
	 * t的定义为消息扩展类型，取值如下：
	 * 	const MSG_EXT_TYPE_SYS=10;//系统通知
		const MSG_EXT_TYPE_APP=11;//软件升级

		const MSG_EXT_TYPE_ORDER_CREATE=20;//用户下单
		const MSG_EXT_TYPE_ORDER_CONFIRM=21;//商家确认订单
		const MSG_EXT_TYPE_ORDER_CANCEL=22;//商家取消订单
		const MSG_EXT_TYPE_ORDER_PAY=23;//用户已支付订单/等待商家确认
		const MSG_EXT_TYPE_ORDER_COMPLETE=24;//订单完成，转入评价
		const MSG_EXT_TYPE_ORDER_EVALUATE=25;//商家或用户获得点评
		const MSG_EXT_TYPE_ORDER_UQX=26;//游客取消订单(商家已确认)
		const MSG_EXT_TYPE_ORDER_DDFZF=27;//到店付(游客已支付)
	 *
	 * 	const MSG_EXT_TYPE_DYNAMIC_LOVE=30;				//用户喜欢了你的广播
	 * 	const MSG_EXT_TYPE_DYNAMIC_COMMENT=31;			//用户点评了你的广播
	 * 	const MSG_EXT_TYPE_DYNAMIC_COMMENT_LOVE=32;		//用户喜欢了你的评论
	 * 	const MSG_EXT_TYPE_DYNAMIC_COMMENT_REMARK=33;	//用户点评了你的评论
	 *
	 * 	const MSG_EXT_TYPE_VOUCHERS_CONFIRM=40;	//商家确认使用卡券
	 *
	 * d的定义为消息扩展内容，根据业务定义如下：
	 *	--广播的评论或赞
	 * 	{
	 * 		"uid":"点评人ID",
	 * 		"name":"点评人商家/名称",
	 * 		"cont":"点评内容",
	 * 		"dy_id":"广播ID",
	 * 		"dy_time":"1471419778",//动态发布时间，可以用于跳转到广播列表位置
	 * 		"dy_cont":"广播内容",
	 * 		"dy_type":1,	// 1/2/3 纯文字/纯图/图文
	 * 		"dy_name":"发布人名称"
	 * 		"dy_modal":"类型"
	 * 	}
	 *	--发送系统消息
	 * {
	 * 		"view":1	//	1	使用webview展示
	 * 		"title":"标题"
	 * 		"id":1	//文章ID,可通过公用接口的 （article、avatar） 拼接【文章ID】后可以获得（文章链接、文章首图链接）
	 * }
	 *	--商家确认使用卡券
	 * {
	 * 		"avatar":	//	头像地址
	 * 		"provider_name":"农户名称"
	 * 		"full_money":	//满
	 * 		"reach_money":	//减
	 * 		"usetime":"使用时间"
	 * 		"vnumber":	//卡号
	 * 		"kphone":	//客服电话
	 * 		"content":	//内容
	 * }
	 *
	 *	--用户已支付订单/等待商家确认(向商家发送消息)
	 * {
	 * 		"content":"内容"
	 * 		"order_id":1	//订单ID
	 * 		"title":""
	 * }
	 *	--商家确认订单(向游客发送消息)
	 * {
	 * 		"content":"内容"
	 * 		"order_id":1	//订单ID
	 * 		"money":	//订单金额
	 * 		"title":""
	 * }
	 *	--游客取消了商家已确认的订单(向商家发送消息)
	 * {
	 * 		"content":"内容"
	 * 		"order_id":1	//订单ID
	 * 		"title":""
	 * }
	 *	--商家拒绝订单(向游客发送消息)
	 * {
	 * 		"content":"内容"
	 * 		"order_id":1	//订单ID
	 * 		"title":""
	 * }
	 *	--到店付,游客已支付(向商家发送消息)
	 * {
	 * 		"content":"内容"
	 * 		"order_id":1	//订单ID
	 * 		"money":	//支付金额
	 * 		"title":""
	 * }
	 *
	 * 服务端推送消息的用户，对应相应的消息分类
	 * admin_message 	评论/消息
	 * admin_notice		系统通知/商家确认使用卡券
	 */
	function message(){

	}
	/**
	 * <ok class="消息管理"/> 创建环信用户
	 * 参数
	 * 	token
	 * 返回
	 {
	 "data": {
		 "action": "get",
		 "path": "\/users",
		 "uri": "http:\/\/a1.easemob.com\/colorcun\/colorcun\/users\/127.0.0.1",
		 "entities": [{
			 "uuid": "3bbfcbea-1b14-11e6-b100-71014bbce1ce",
			 "type": "user",
			 "created": 1463368439710,
			 "modified": 1463368439710,
			 "username": "127.0.0.1",
			 "activated": true
		 }],
		 "timestamp": 1463370729071,
		 "duration": 4,
		 "count": 1
	 },
	 }
	 */
//	function msg__createUser() {
//		$lib = new \Modules\message\lib();
//		return array('data' => $lib -> createUser($this -> user));
//	}

	/**
	 * <ok class="消息管理"/> 发送消息
	 * 服务器发送消息会附加扩展字段，客户端需要根据扩展字段进行处理，<a target="_blank" href="http://docs.easemob.com/im/200androidclientintegration/50singlechat#消息扩展">参考环信扩展消息</a>
	 * 扩展消息结构如下
	 * 	{t:int,d:object}
	 * t的定义为消息扩展类型，取值如下：
	 * 	const MSG_EXT_TYPE_SYS=10;//系统通知
		const MSG_EXT_TYPE_APP=11;//软件升级
		
		const MSG_EXT_TYPE_ORDER_CREATE=20;//用户下单
		const MSG_EXT_TYPE_ORDER_CONFIRM=21;//商家确认订单
		const MSG_EXT_TYPE_ORDER_CANCEL=22;//商家取消订单
		const MSG_EXT_TYPE_ORDER_PAY=23;//用户已支付订单
		const MSG_EXT_TYPE_ORDER_COMPLETE=24;//订单完成，转入评价
		const MSG_EXT_TYPE_ORDER_EVALUATE=25;//商家或用户获得点评
	 * 
	 * 
	 * 参数
	 * 	token
	 * 	totype:int 1/2 	用户/商家
	 * 	toid:int
	 * 	content:string 消息内容
	 * 返回
	 *
	 */
//	function msg__sendText() {
//		$lib = new \Modules\message\lib();
//		return array('data' => $lib -> sendText($this -> user));
//	}
	/**
	 * <ok class="消息管理"/> 获取消息列表
	 * 参数
	 * 	token：
	 * 	addtime：0  //下拉获取新消息时传0，上拉获取历史消息时传递上次消息列表最后一条的addtime
	 * 	rows：20
	 * 返回
	 * 	data:[{
	 * 		id:1	//抵用卷ID
	 * 		fuid:"100.00" //满多少
			tuid:"10.00"	//减多少
	 * 		content:int(10) 	//开始时间
	 * 		ext:{	//扩展信息
	 * 			t:int 		//消息类型，请参考发送消息中的类型
	 * 			d:{			//具体业务可能要求格式不同，一般会包含id字段
	 * 				id:1
	 * 			}	
	 * 		}
	 * 		addtime:int(10)	//抵用券描述
	 * 		status：int 0/1 未读/已读
	 * 	}]
	 */
//	function user__messages() {
//		$lib = new \Modules\message\lib();
//		return $lib -> messages($this->user);
//	}
	/**
	 * <ok class="消息管理"/> 获得消息记录
	 * 参数
	 * 	token：
	 * 	addtime:int(10) 消息列表第一个消息的 addtime，最大addtime
	 */
//	function user__messagesACK() {
//		$lib = new \Modules\message\lib();
//		return $lib -> messagesACK($this->user);
//	}

	function msg__getChatLog() {
		$lib = new \Modules\message\lib();
		return array('data' => $lib -> getChatLog($this -> user));
	}
	/**
	 * 一些 web view 需要的地址
	 * 用户登录协议：http://www.colorcun.com:10002/apido/article/id/1
	 * 商户认证协议：http://www.colorcun.com:10002/apido/article/id/2
	 * 
	 * 宽松预定政策：http://www.colorcun.com:10002/apido/article/id/11
	 * 一般预定政策：http://www.colorcun.com:10002/apido/article/id/12
	 * 严格预定政策：http://www.colorcun.com:10002/apido/article/id/13
	 * 
	 * 后台业务 http://www.colorcun.com:10002/api/demon
	 *
	 *	 
	 * 网页版登陆微信获取基础信息：http://www1.colorcun.com/weixin/maintain_base
	 * 网页版登陆微信获取全部信息：http://www1.colorcun.com/weixin/maintain_userinfo
	 * 	  
	 */
	function webview(){
		
	}
	
	
	
	/**
	 * <ok/> 公共类型接口
	 *  参数
	 *     
	 *     
	 * 返回
	 *         {
	 *             “code”:200, 
	 *             “data”:(jsonstr){
	 *              travel(主题分类):
	 *               [
	 *                  {"id":1,"name":"垂钓","image":""},...
	 *               ],
	 *              person(人群分类):
	 *               [
	 *                  {"id":1,"name":"个人","image":""},...
	 *               ],
	 *              attraction(热门景点):
	 *               [
	 *                  {"id":1,"name":"瀑布","image":""},...
	 *               ],
	 *              basefacility(基础配套设置):
	 *               [
	 *                  {"id":1,"name":"电视","image":""},...
	 *               ],
	                commonfacility(公共配套设置):
	 *               [
	 *                  {"id":1,"name":"温泉","image":""},...
	 *               ],
	                extrafacility(额外配套设置):
	 *               [
	 *                  {"id":1,"name":"麻将机","image":""},...
	 *               ],
	 * 		devicetype(全部配套设置):
	 *               [
	 *                  {"id":1,"name":"电视","image":""},...
	 *               ]
	 *              roomtype(房间类型):
	 *               [
	 *                  {"id":1,"name":"窑洞","image":""},...
	 *               ],
	 * 				position(省市区):[...三级嵌套...],
	 * 				dynamic_tags(广播标签)：
	 * 				[
	 * 					{"id":1,"name":"窑洞","image":""},...
	 * 				]
	 *           }
	 *         }
	 */

	function commontype() {

		$member = new \Modules\commontype\lib();
		$data=$member -> commontype();
		return $data;
	}
	/**
	 * <ok/> 敏感词数组
	 * 参数
	 */
	function sensitive() {
		$member = new \Modules\commontype\lib();
		return $member -> sensitive();
	}



	/**
	 * <ok/> 微信 wechat JS 签名
	 * 参数
	 * 	url : urlencode(localtion.href)
	 *
	 * 返回
	  	data{
			jsapi_sign:{
	  			appId:"wx7b0a488c8306ee90"
				nonceStr:"e37vTr4ib8HUnYpS"
				signature:"2c569f8e9a4b14892aab7c299fe385938f771f7e"
				timestamp:1470196427
				url:"http://m.colorcun.com/"
	  		},
	  		jsapi_ticket:"kgt8ON7yVITDhtdwci0qec-PuvQJKVvypLt8M5dqwtmOU4cF62BXgzIVWc-AKrknY7RQ9TpRHuW_BZdnJHy0cA"
	  	}
	 */
	function wechatjs(){
		$wechat_conf=C('wechat');
		$wechat=new \Modules\common\wechat_lib($wechat_conf);
		$wechat->checkAuth();
		$data=array(
				'jsapi_ticket'=>$wechat->getJsTicket(),
				'jsapi_sign'=>$wechat->getJsSign($_SERVER['HTTP_REFERER']),
		);
		return array('data'=>$data);
	}

	/**
	 * <ok/> 记录分享链接
	 *  参数
	 *     url：string 分享链接地址
	 * 返回
	 *         {
	 *             “code”:200, // 200：成功
	 *             “data”:{
	 * 					sid: int 如果添加成功，返回id
	 *             }
	 *         }
	 */

	function shareurl() {
		$lib = new \Modules\share\lib();
		return $lib -> shareurl();
	}
	/**
	 * <ok/> 通过传值生成二维码
	 *  参数
	 *     token:
	 *     path:
	 * 返回
	 *         {
	 *             “code”:200, // 200：成功
	 *             “data”:{
	 * 					url
	 *             }
	 *         }
	 */

	function user__qrcodeurl() {
		$lib = new \Modules\weixin\lib();
		return $lib -> qrcodeurl($this->user);
	}
	############################################################
	# 用户端各查看接口API(1) start 
	############################################################
	
	/**
	 * <ok class="游客查看(1)"/> 1.0 首页所有农户列表
	 * 参数
	 * 	token：
	 * 	city_id：城市id
	 * 	lat：
	 * 	lng：	 	 
	 *  offset：分页开始位置，默认0
	 *  rows:从开始位置读取多少行，默认20
	 *  说明:city_id的优先级最高,会优先显示city_id下的农家列表
	 *      若无city_id,会根据传的经纬度,显示当地农家列表,
	 *      若未传值,默认显示北京市的农家列表	 
	 * 返回
	 * 	data:[{
	 * 		id:1	// int ID
	 * 		name: string //  农户名称
	 * 		sign: string //  0-普通农户,1-优选农户
	 * 		is_authenticated: string 是否认证，0未认证\1已认证
	 * 		cover_image  string	//封面图片
	 * 		desc:string	//农户简介
	 * 		city_id:string	//该农户所属城市id
	 * 		district_id:string	//该农户所属区域id
	 * 		city_name:string	//该农户所属城市名称
	 * 		district_name:string	//该农户所属区域名称
	 * 		position:string	//该农户位置
	 * 		distance:string	//当前游客与该农户的距离	 (只有当传lat,lng时,才会生效)
	 * 		room_num:int	//是否存在已发布的房间 (无--room_num=0,有--room_num>0)		 
	 * 		food_num:int	//是否存在已发布的美食 (无--food_num=0,有--food_num>0)		
	 * 		activity_num:int	//是否存在已发布的活动 (无--activity_num=0,有--activity_num>0)		
	 * 		product_num:int	//是否存在已发布的特产 (无--product_num=0,有--product_num>0)			 
	 * 		dicount_num:int	//是否存在已发布的福利 (无--dicount_num=0,有--dicount_num>0)		 
	 * 		collected_num:int	//该农户被收藏数量
	 * 		comments_num: int //该农户被评论数量
	 * 		is_collected: int  //是否被正在查阅的用户收藏  0-未收藏，1-已收藏
	 * 		share_url: string  // 分享链接  http://...
	 * 		share_xcx_url: string  // 小程序分享链接  http://...
	 * 		share_xcx_username: string  // 小程序username
	 * 	}]
	 * 	city_id: 城市id 
	 */
	function get__providerlist() {
		$lib = new \Modules\provider\lib();
		return $lib -> providerlist($this -> user);
	}
	
	/**
	 * <ok class="游客查看(1)"/> 1.1 首页顶部轮播图
	 * 参数
	 * 	token：
	 *  offset：0
	 *  rows:20
	 * 返回
	 * 	data:[{
	 * 		id:1	// int ID
	 * 		name: string //  顶部图片名称
	 * 		url: string //  跳转链接地址
	 * 		cover_image  string	//顶部图片地址
	 * 	}]
	 */
	function get__indexImage() {
		$lib = new \Modules\provider\lib();
		return $lib -> indexImage($this -> user);
	}
	
	/**
	 * <ok class="游客查看(1)"/> 1.2 福利列表
	 * 参数
	 * 	token：
	 *  offset：0
	 *  rows:20
	 * 返回
	 * 	data:[{
	 * 		id:1	// int 福利ID
	 * 		provider_id: int //  该福利品所属农户id
	 * 		path: string	//福利照片路径(默认为农户封面图片)
	 *    btime: int //福利开始时间
	 *    etime: int //福利结束时间
	 *    desc: string // 福利内容描述
	 *    is_checked: int // 是否已审核
	 *    collections_num: int // 该福利被收藏数量
	 *    comments_num: int //该福利被评论数量
	 * 	}]
	 */
	function get__indexDiscounts() {
		$lib = new \Modules\discount\lib();
		return $lib -> indexDiscounts($this -> user);
	}
	
	/**
	 * <ok class="游客查看(1)"/> 1.3 农户详情首页
	 * 参数
	 * 	token：
	 * 	id:int
	 * 返回
	 {
	 "data": {
		 "id": int 该农户id
	   "name": string 该农户（景点）名
	   "sign: string //  0-普通农户,1-优选农户
	   "owner":string  负责人身份信息
	   "lat":string  纬度
	   "lng":string  经度
	   "distance":string	//当前游客与该农户的距离	 (只有当传lat,lng时,才会生效)
	   "is_collected" int 是否被正在查阅的用户收藏  0-未收藏，1-已收藏
	   "is_authenticated" int 是否为认证农户  0-该用户未认证，1-该用户已认证
	   "meta_evaluation": int 该农户评星数（目前设计至少一星，最多五星）
	   "level": int 该农户的等级  1-铜级，2-银级，3-金级
	   "around":text 周边景点(文字介绍)
	   "route":string 行车路线
	   "comment_ptotal":int 该农户被评价总数
	   "collection_ptotal":int 该农户被收藏总数
	   "address": string 该农户地址
	   "avatar": string 该农户头像图片地址
	   "share_url":string "http://..."    //分享链接
	   "share_name":string     //分享名
	   "share_desc":string     //分享描述
	   "share_pic":string     //分享图片
	   "share_xcx_username":string     //
	   "share_xcx_url":string "http://..."    //小程序分享链接
	   "share_xcx_name":string     //小程序分享名
	   "share_xcx_desc":string     //小程序分享描述
	   "share_xcx_pic":string     //小程序分享图片
	   "share_pic_weibo":string     //微博分享图片
	   "desc": string 农家乐简介
	   "cover_image": string 主图片地址
	   "dynum":int 该农户发布的广播数量
	   "phone":string list[
		   {
		    "id": int 该电话id 
		    "phone": string 电话号码
		    "name": string 姓名
		   },
		   ......
		   ......
		  ]
	   "attraction":(list 该农户的周边景点(与该农户直线距离小于30公里))[
		   {
		    "id": int 该景点id 
		    "name": string 该景点名称
		   },
		   ......
		   ......
		  ]
		 "license": (list 该农户的资质信息) [
		   {
		    "id": int 该资质id 
		    "identity_card_no": string 身份证号
		    "business_license_no": string 营业执照
		    "hygiene_license_no":卫生许可证号
		    "other_no": string 其它证书号
		    "identity_card_photo": string 身份证照片id
		    "business_license_photo": string 营业执照照片id
		    "hygiene_license_photo":卫生许可证照片id
		    "other_photo": string 其它证书照片id
		    "other": [1,2,3]  其他证书照片id(数组形式)
		   },
		   .....
		   .....
		  ],
		  "gallery_provider": (list 该农户的风光照片) [
		   {
		    "id": int 该图片/照片id 
		    "image": string 图片/照片地址
		   },
		   ......
		   ......
		  ],
		  "gallery_discount": list 福利列表 [
		   {
		    "id": 福利id
		    "provider_id": int 所属农户id
		    "btime" : int 福利开始时间
		    "etime"   : int 福利结束时间
		    "desc"       : string 福利内容描述
		    "is_checked" : int 是否已审核 (0-未审核，1-已审核)
		    "type": string 该分类类型,默认为dicount
		   },
		   ......
		   ......
		  ],
		  "gallery_rooms": (list 该农户/景点房间的相册--同一类房间) [
		   {
		    "id": int 该图片/相片id
		    "image": string 图片地址
		    "name": string 房间名
		    "price": decimal 价格
		    "price_unit": string 价格单位
		    "desc": string 房间简介
		    "comment_rtotal":int 该房间被评价总数
		    "collection_rtotal":int 该房间被收藏总数
		    "type": string 该分类类型,默认为room
		   },
		   ......
		   ......
		  ],
		  "gallery_foods": list 菜品列表 [
		   {
		    "id": int 菜品id
		    "name": string 菜品名
		    "image": string 菜品照片 
		    "price": decimal 价格
		    "price_unit": string 价格单位
		    "desc": string 餐品简介
		    "comment_ftotal":int 该餐品被评价总数
		    "collection_ftotal":int 该餐品被收藏总数
		    "type": string 该分类类型,默认为food
		   },
		   ......
		   ......
		  ],
		  "gallery_speciaties": list 特产列表 [
		   {
		    "id": int 特产id
		    "name": string 特产名
		    "image": string 特产照片 
		    "price": decimal 价格
		    "price_unit": string 价格单位
		    "desc": string 特产简介
		    "comment_stotal":int 该特产被评价总数
		    "collection_stotal":int 该特产被收藏总数
		    "type": string 该分类类型,默认为product
		   },
		   ......
		   ......
		  ],
		  "gallery_activity": list 活动列表 [
		   {
		    "id": int 活动id
		    "name": string 活动名
		    "image": string 活动照片 
		    "price": decimal 价格
		    "price_unit": string 价格单位
		    "desc": string 活动简介
		    "comment_atotal":int 该活动被评价总数
		    "collection_atotal":int 该活动被收藏总数
		    "type": string 该分类类型,默认为activity
		   },
		   ......
		   ......
		  ],
		  "gallery_merge": list 汇总列表 [
		   {
		    "id": int id
		    "name": string 名称
		    "image": string 照片
		    "price": decimal 价格
		    "price_unit": string 价格单位
		    "desc": string 简介
		    "comment_stotal":int 被评价总数
		    "collection_stotal":int 被收藏总数
		    "type": string 该分类类型
		   },
		   ......
		   ......
		  ],
		   
	 "message": "",
	 "provider_flag": "1",
	 "code": 200
	 }
	 */
	function get__providerindex() {
		$lib = new \Modules\provider\lib();
		return $lib -> providerindex($this -> user);
	}
	
	
	/**
	 * <ok class="游客查看(1)"/> 1.4 房间详情(游客端)
	 * 参数
	   	token：
	 * 	id:int
	 * 返回
	 {
	 "data": {
		 "id": int 房间id
	   "provider_id": int 所属农户id
	   "position": int 对房间的定位 (1-整套房子,2-独立房间,3-套间)
	   "price": float 房间费用
	   "price_unit": string 价格的单位(如"一晚", "12小时"等) 
	   "room_type": string 房间类型(单选) 接口http://www.colorcun.com:10002/apido/commontype
	                                 (该接口地址:http://www.colorcun.com:10002/api.html#commontype) 
	   "is_collected" int 是否被正在查阅的用户收藏  0-未收藏，1-已收藏 
	   "name": string 房间名
	   "desc": string 亮点介绍
	   "device_type": [1,2,3]  配套设施(基础、公共、额外) 接口http://www.colorcun.com:10002/apido/commontype
	                                (该接口地址:http://www.colorcun.com:10002/api.html#commontype)
	   "device_type_basefacility":  [1,2,3]  基础配套设施 接口
	   "device_type_commonfacility": [1,2,3]  公共配套设施
	   "device_type_extrafacility": [1,2,3]  额外配套设施
	   "unsubscribe": int  退订政策(11-宽松,12-适中,13-严格)
	   "unavailable": string  不可用日期    //数组形式请查看 unavail
	   "unavail": [2016-07-23,2016-07-24,2016-07-25]  不可用日期	   
	   "capacity": int 房间适宜人数
	   "roomnum": int 房间数(默认整套房子时设置)
	   "bednum": int 床位数
	   "bedtype": int 床型(1-中式,2-榻榻米,3-欧式)
	   "bedmodel": int 床的尺寸(1-1.2*2米,2-1.5*2米,3-1.8*2米)
	   "cover_image": string 房间封面图片
	   "publish_status": string 房间状态	(0-下架,1-上架)	
	   "has_bathroom": int 卫生间数量	  
	   "share_url":string "http://..."    //分享链接			
	   "share_name":string     //分享房间名	
	   "share_desc":string     //分享描述
	   "share_pic":string     //分享图片
	   "share_xcx_username":string     //
	   "share_xcx_url":string "http://..."    //小程序分享链接
	   "share_xcx_name":string     //小程序分享名
	   "share_xcx_desc":string     //小程序分享描述
	   "share_xcx_pic":string     //小程序分享图片
	   "share_pic_weibo":string     //微博分享图片
	   "photos": (list 该房间的照片) [
		   {
		    "id": int 该图片/照片id 
		    "image": string 图片/照片地址
		   },
		   ......
		   ......
		  ],
	   
	  "reserved": (list 已预订信息) [
	   {
	    "id": int 订单号id 
	    "provider_id": int 预订农户id
	    "target_id": int 已预订房间id
	    "begin_time": string 预订开始日期
	    "end_time": string 预订结束日期
	    "time_quantum": string list [  预订时间段
	     datetime 如2016-06-18 ,
	     ....
	     ....
	     
	    ]
	   },
	   ......
	   ......
	  ],
	  "provider_room":该房间所属农户信息{
		  "id":int 该农户id,
		  "name":string 该农户（景点）名,
		  "lat":string  纬度,
		  "lng":string  经度,
		  "address":string 该农户地址,
		  "avatar":该农户头像图片地址,
		  "phone":农户电话[
		    {
		      "id":int 该电话id ,
		      "phone":string 电话号码,
		      "name":string 姓名
		    },...
		  ]
		  备注：当农户未添加电话时,会显示登陆时输入的电话；		  
	  },
	 "message": "",
	 "provider_flag": "1",
	 "code": 200
	 }
	 */
	function get__room() {
		$lib = new \Modules\room\lib();
		return $lib -> roomInfo($this -> user,'',false);
	}
	
	/**
	 * <ok class="游客查看(1)"/> 1.5 餐品详情(游客端)
	 * 参数
	   	token：
	 * 	id:int
	 * 返回
	 {
	 "data": {
		 "id": int 餐品id
		 "provider_id": int 所属农户id
		 "is_collected" int 是否被正在查阅的用户收藏  0-未收藏，1-已收藏  
		 "name": string 食品名
		 "price": float 价格 
		 "price_unit": string 价格的单位(如"斤", "盒"等)
		 "desc": string 简介
		 "cover_image": string 封面图片
		 "publish_status": string 餐品状态	(0-下架,1-上架)
		 "share_url":string "http://..."    //分享链接
		 "share_name":string     //分享餐品名
		 "share_desc":string     //分享描述
		 "share_pic":string     //分享图片
		 "share_xcx_username":string     //
		 "share_xcx_url":string "http://..."    //小程序分享链接
		 "share_xcx_name":string     //小程序分享名
		 "share_xcx_desc":string     //小程序分享描述
		 "share_xcx_pic":string     //小程序分享图片
		 "share_pic_weibo":string     //微博分享图片
		"photos": (list 该食品的照片) [
		   {
		    "id": int 该图片/照片id 
		    "image": string 图片/照片地址
		   },
		   ......
		   ......
		  ],
	  "provider_food":该餐品所属农户信息{
		  "id":int 该农户id,
		  "name":string 该农户（景点）名,
		  "lat":string  纬度,
		  "lng":string  经度,
		  "address":string 该农户地址,
		  "avatar":该农户头像图片地址,
		  "phone":农户电话[
		    {
		      "id":int 该电话id ,
		      "phone":string 电话号码,
		      "name":string 姓名
		    },...
		  ]
	  },
	 "message": "",
	 "provider_flag": "1",
	 "code": 200
	 }
	 */
	function get__food() {
		$lib = new \Modules\food\lib();
		return $lib -> food($this -> user,'',false);
	}
	
	/**
	 * <ok class="游客查看(1)"/> 1.6 活动详情(游客端)
	 * 参数
	   	token：
	 * 	id:int
	 * 返回
	 {
	 "data": {
		 "id": int 活动id 
	   "provider_id": int 所属农户id 
	   "is_collected" int 是否被正在查阅的用户收藏  0-未收藏，1-已收藏
	   "name": string 活动名
	   "price": float 价格
	   "price_unit": string 活动价格的单位 (如: "一次", "每天") 
	   "desc": string 简介
	   "cover_image": string 封面图片
	   "publish_status": string 活动状态	(0-下架,1-上架)	
	   "share_url":string "http://..."    //分享链接			
	   "share_name":string     //分享活动名	
	   "share_desc":string     //分享描述
	   "share_pic":string     //分享图片
	   "share_xcx_username":string     //
	   "share_xcx_url":string "http://..."    //小程序分享链接
	   "share_xcx_name":string     //小程序分享名
	   "share_xcx_desc":string     //小程序分享描述
	   "share_xcx_pic":string     //小程序分享图片
	   "share_pic_weibo":string     //微博分享图片
		 "photos": (list 该活动的照片) [
		   {
		    "id": int 该图片/照片id 
		    "image": string 图片/照片地址
		   },
		   ......
		   ......
		  ],
	  "provider_activity":该活动所属农户信息{
		  "id":int 该农户id,
		  "name":string 该农户（景点）名,
		  "lat":string  纬度,
		  "lng":string  经度,
		  "address":string 该农户地址,
		  "avatar":该农户头像图片地址,
		  "phone":农户电话[
		    {
		      "id":int 该电话id ,
		      "phone":string 电话号码,
		      "name":string 姓名
		    },...
		  ]
	  },
	 "message": "",
	 "provider_flag": "1",
	 "code": 200
	 }
	 */
	function get__activity() {
		$lib = new \Modules\activity\lib();
		return $lib -> activity($this -> user,'',false);
	}
	
	/**
	 * <ok class="游客查看(1)"/> 1.7 特产详情(游客端)
	 * 参数
	   	token：
	 * 	id:int
	 * 返回
	 {
	 "data": {
		 "id": int 特产id
	   "provider_id": int 所属农户id
	   "name": string 特产名 
	   "price": float 特产价格 
	   "price_unit": string 特产价格的单位 (比如: "斤" "两" "盒" 等)
	   "spec_num": 产品规格(1,2,3)
	   "spec_unit": 产品单位(kg/份)
	   "informat_one": 产品信息(北京市密云区有机大棚)
	   "informat_two": 产品信息(全国（不包括内蒙古自治区，西藏自治区，青海省，宁夏回族自治区，新疆维吾尔自治区）配送)
	   "informat_three": 产品信息(默认申通快递发货，价格由数量和配送地区决定)
	   "desc": string 简介 
	   "cover_image": string 封面图片 
	   "publish_status": string 特产状态	(0-下架,1-上架)		   
	   "is_collected" int 是否被正在查阅的用户收藏  0-未收藏，1-已收藏
	   "share_url":string "http://..."    //分享链接			
	   "share_name":string     //分享特产名	
	   "share_desc":string     //分享描述
	   "share_pic":string     //分享图片
	   "share_xcx_username":string     //
	   "share_xcx_url":string "http://..."    //小程序分享链接
	   "share_xcx_name":string     //小程序分享名
	   "share_xcx_desc":string     //小程序分享描述
	   "share_xcx_pic":string     //小程序分享图片
	   "share_pic_weibo":string     //微博分享图片
	   "photos": (list 该特产的照片) [
		   {
		    "id": int 该图片/照片id  
		    "image": string 图片/照片地址
		   },
		   ......
		   ......
		  ],
	   "product_spec": (list 该特产的规格) [
		   {
		    "id": int 该规格id
		    "spec_num": 产品规格(1,2,3)
		    "spec_unit": 产品单位(kg/份)
		   },
		   ......
		   ......
		   ],
	  "provider_product":该特产所属农户信息{
		  "id":int 该农户id,
		  "name":string 该农户（景点）名,
		  "lat":string  纬度,
		  "lng":string  经度,
		  "address":string 该农户地址,
		  "avatar":该农户头像图片地址,
		  "phone":农户电话[
		    {
		      "id":int 该电话id ,
		      "phone":string 电话号码,
		      "name":string 姓名
		    },...
		  ]
	  },
	 "message": "",
	 "provider_flag": "1",
	 "code": 200
	 }
	 */
	function get__product() {
		$lib = new \Modules\product\lib();
		return $lib -> product($this -> user,'',false);
	}
	
	/**
	 * <ok class="游客查看(1)"/> 1.8 游客查看某商户产品列表(房间,餐品,活动,特产)
	  参数
	 	  "token":
	 	  "pid":	当前农户id
	 	  "type": string 产品类型
	 	  	 	  	 	  "room"  : 房间
	 	  	 	  	 	  "food"  : 餐品
	 	  	 	  	 	  "activity"  : 活动
	 	  	 	  	 	  "product"  : 特产
	 	  *"offset": 分页开始位置，默认0
	 	  *"rows": 从开始位置读取多少行，默认10
	 	  *"ssc": 针对果蔬价格排序有效 desc-降序  asc-升序

	 	返回
	       {
	  			code:200,
	  			data:{
	  				"id": 所选物品主id
	  				"name": 物品名
	  				"desc": 描述	 
	  				"price": 价格
	  				"price_unit": 价格单位
	  				"spec_num": 产品规格(1,2,3)
	  				"spec_unit": 产品单位(kg/份)
	  				"cover_image": 物品的封面照片
	  				"comment_ptotal":int 被评价总数
	  				"collection_ptotal":int 被收藏总数
	  				"is_owner":int  0/1	已登录游客是否保存当前产品(否/是) 
	  			}
	  		}	
	 */
	 function get__pubList(){
		$lib = new \Modules\provider\lib();
		return $lib -> pubList($this -> user);
	}
	
	/**
	 * <ok class="游客查看(1)"/> 1.9 用户的浏览记录
	  参数
	 	  "token":
	 	  *"offset": 分页开始位置，默认0
	 	  *"rows": 从开始位置读取多少行，默认10
	 	  *"lat": 
	 	  *"lng": 
	 	返回
	       {
	  			code:200,
	  			 	data:[{
	  			 		id:1	// int ID
	  			 		name: string //  农户名称
	  			 		cover_image  string	//封面图片
	  			 		desc:string	//农户简介
	  			 		city_id:string	//该农户所属城市id
	  			 		district_id:string	//该农户所属区域id
	  			 		city_name:string	//该农户所属城市名称
	  			 		district_name:string	//该农户所属区域名称
	  			 		position:string	//该农户位置   
	  			 		distance:string	//当前游客与该农户的距离	  (只有当传lat,lng时,才会生效)
	  			 		room_num:int	//是否存在已发布的房间 (无--room_num=0,有--room_num>0)		 
	  			 		food_num:int	//是否存在已发布的美食 (无--food_num=0,有--food_num>0)		
	  			 		activity_num:int	//是否存在已发布的活动 (无--activity_num=0,有--activity_num>0)		
	  			 		product_num:int	//是否存在已发布的特产 (无--product_num=0,有--product_num>0)			 
	  			 		dicount_num:int	//是否存在已发布的福利 (无--dicount_num=0,有--dicount_num>0)		 
	  			 		collected_num:int	//该农户被收藏数量
	  			 		comments_num：int //该农户被评论数量
	  			 		is_collected:int //是否被正在查阅的用户收藏  0-未收藏，1-已收藏 
	  			 	}]
	  		}	
	 */
	 function user__glanceList(){
		$lib = new \Modules\glance\lib();
		return $lib -> glanceList($this -> user);
	}

	/**
	 * <ok class="游客查看(1)"/> 1.10 用户提交反馈意见
	 * 参数
	 	  "token":*
	 	  "info":string  //反馈意见  
	   返回
	           {
	               “code”:200, // 200：成功
	               “data”:如果反馈成功，提示:您的意见已提交成功,感谢您的支持;
	           }			
	 */
	 function user__feedback(){
		$lib = new \Modules\feedback\lib();
		return $lib -> feedback($this -> user);
	}

	/**
	 * <ok class="游客查看(1)"/> 1.11 新版农户首页
	 * 参数
	 * 	token:
	 * 返回
	 * 	data: {
	 * 	"homestay": (list 乡村民俗) [
	 * 	 {
	 * 	  "id": //农户id
	 * 	  "name": //农户名称
	 * 	  "cover_image": //农户图片
	 * 	 },
	 * 	 ......
	 * 	 ......
	 * 	],
	 * 	"summerlist": (list 热门活动(夏列营)) [
	 * 	 {
	 * 	  "id":
	 * 	  "title":
	 * 	  "desc":
	 * 	  "address":
	 * 	  "price":
	 * 	  "price_unit":
	 * 	  "cover_image":
	 * 	  },
	 * 	  ......
	 * 	  ......
	 * 	],
	 * 		"message": "",
	 * 		"provider_flag": "1",
	 * 		"code": 200
	 * 	}
	 */
	function get__providerhome() {
		$lib = new \Modules\provider\lib();
		return $lib -> providerhome($this -> user,'',false);
	}

	/**
	 * <ok class="游客查看(1)"/> 1.12 所有活动列表
	 * 参数
	 * 	token：
	 * 	lat：
	 * 	lng：
	 *  offset：分页开始位置，默认0
	 *  rows:从开始位置读取多少行，默认20
	 * 返回
	 * 	data:[{
	 * 		id:1	// int ID
	 * 		name: string //  名称
	 * 		price: decimal 价格
	 * 		price_unit: string 价格单位
	 * 		cover_image  string	//封面图片
	 * 		desc  string	//描述
	 * 		position:string	//当前位置
	 * 		distance:string	//当前游客与该农户的距离	 (只有当传lat,lng时,才会生效)
	 * 	}]
	 */
	function get__activitylist() {
		$lib = new \Modules\activity\lib();
		return $lib -> activityhomelist($this -> user);
	}

	/**
	 * <ok class="游客查看(1)"/> 1.13 所有房间列表
	 * 参数
	 * 	token：
	 * 	lat：
	 * 	lng：
	 *  offset：分页开始位置，默认0
	 *  rows:从开始位置读取多少行，默认20
	 * 返回
	 * 	data:[{
	 * 		id:1	// int ID
	 * 		name: string //  名称
	 * 		price: decimal 价格
	 * 		price_unit: string 价格单位
	 * 		cover_image  string	//封面图片
	 * 		desc  string	//描述
	 * 		position:string	//当前位置
	 * 		distance:string	//当前游客与该农户的距离	 (只有当传lat,lng时,才会生效)
	 * 	}]
	 */
	function get__roomlist() {
		$lib = new \Modules\room\lib();
		return $lib -> roomhomelist($this -> user);
	}

	/**
	 * <ok class="游客查看(1)"/> 1.14 所有美食列表
	 * 参数
	 * 	token：
	 * 	lat：
	 * 	lng：
	 *  offset：分页开始位置，默认0
	 *  rows:从开始位置读取多少行，默认20
	 * 返回
	 * 	data:[{
	 * 		id:1	// int ID
	 * 		name: string //  名称
	 * 		price: decimal 价格
	 * 		price_unit: string 价格单位
	 * 		cover_image  string	//封面图片
	 * 		desc  string	//描述
	 * 		position:string	//当前位置
	 * 		distance:string	//当前游客与该农户的距离	 (只有当传lat,lng时,才会生效)
	 * 	}]
	 */
	function get__foodlist() {
		$lib = new \Modules\food\lib();
		return $lib -> foodhomelist($this -> user);
	}

	############################################################
	# 用户端各查看接口API(1) over 
	############################################################

  ############################################################
	# 用户端各操作接口API(2) start 
	############################################################
	/**
	 * <ok class="游客操作(2) "/> 2.0 完善/修改用户信息
	 * 参数
	 	  "token":*，
			"nick": string 用户昵称
			"gender": int 用户性别 0-女 1-男
			"birthday": date 用户生日 (格式： "2015-01-01")
			"avatar": binary file 用户头像
	 */
	 function user__pushInfo(){
		$lib = new \Modules\member\member_lib();
		return $lib -> pushInfo($this -> user);
	}
	
	/**
	 * <ok class="游客操作(2) "/> 2.1 查看用户个人信息
	 *	参数
	 * 		token: 用户登录后的 token
	 * 	返回
	 * 		"data": {
				"id": int 该农户id
				"nick": string 用户昵称
				"gender":int 性别 0-女 1-男  
				"avatar": string 头像图片地址
				"birthday": string 生日
				"phone": string 手机号
				}
	 */
	 function user__singleInfo(){
		$lib = new \Modules\member\member_lib();
		return $lib -> singleInfo($this -> user);
	}
	
	/**
	 * <ok class="游客操作(2) "/> 2.2 用户的收藏农户列表
	 *  参数
	 * 		token: 用户登录后的 token
	 * 		offset:分页开始位置，默认0
	 * 	  rows：从开始位置读取多少行，默认10
	 * 		lat:
	 * 	  lng：
	 *  返回
	 * 	data:[{
	 * 		id:1	// int ID
	 * 		name: string //  农户名称
	 * 		sign: string //  0-普通农户,1-优选农户
	 * 		cover_image  string	//封面图片
	 * 		desc:string	//农户简介
	 * 		city_id:string	//该农户所属城市id
	 * 		district_id:string	//该农户所属区域id
	 * 		city_name:string	//该农户所属城市名称
	 * 		district_name:string	//该农户所属区域名称
	 * 		position:string	//该农户位置
	 * 		distance:string	//当前游客与该农户的距离	 (只有当传lat,lng时,才会生效)
	 * 		room_num:int	//是否存在已发布的房间 (无--room_num=0,有--room_num>0)		 
	 * 		food_num:int	//是否存在已发布的美食 (无--food_num=0,有--food_num>0)		
	 * 		activity_num:int	//是否存在已发布的活动 (无--activity_num=0,有--activity_num>0)		
	 * 		product_num:int	//是否存在已发布的特产 (无--product_num=0,有--product_num>0)			 
	 * 		dicount_num:int	//是否存在已发布的福利 (无--dicount_num=0,有--dicount_num>0)		 
	 * 		collected_num:int	//该农户被收藏数量
	 * 		comments_num：int //该农户被评论数量
	 * 		is_collected:int //是否被正在查阅的用户收藏  0-未收藏，1-已收藏 
	 * 	}]
	 */
	function user__collectionProviders(){
		$lib = new \Modules\collection\lib();
		return $lib -> collectionProviders($this -> user);
	}

	/**
	 * <ok class="游客操作(2)"/> 2.2-1 用户的收藏产品列表 (房间,餐品,活动,特产)
	  参数
	 	  "token":	  
	 	  "type": string 产品类型
	 	  	 	  	 	  "room"  : 房间
	 	  	 	  	 	  "food"  : 餐品
	 	  	 	  	 	  "activity"  : 活动
	 	  	 	  	 	  "product"  : 特产
	 	  *"offset": 分页开始位置，默认0
	 	  *"rows": 从开始位置读取多少行，默认10
	 	  *"pid":传值367可看到收藏的果蔬信息

	 	返回
	       {
	  			code:200,
	  			data:{
	  				"id": 所选物品主id
	  				"name": 物品名
	  				"desc": 描述	 
	  				"price": 价格	
	  				"price_unit": 价格单位	
	  				"cover_image": 物品的封面照片
	  				"comment_ptotal":int 被评价总数
	  				"collection_ptotal":int 被收藏总数
	  				"publish_status": 发布状态(0-下架,1-上架)	
	  				"is_collected":  是否被正在查阅的用户收藏  0-未收藏，1-已收藏 
	  			}
	  		}	
	 */
	 function user__collectionLists(){
		$lib = new \Modules\collection\lib();
		return $lib -> collectionLists($this -> user);
	}
 
	
	/**
	 * <ok class="游客操作(2) "/> 2.3 用户收藏
	 * 参数
	 	  "token":*，
			"target": varchar 收藏的类型，如provider,room,food,product,activity
			"id": int 要收藏的对象id
	 * 返回
	 *         {
	 *             “code”:200, // 200：成功
	 *             “data”:如果收藏成功，返回success;
	 *                     已存在的收藏,无法再次收藏。
	 *         }
	 */
	 function user__collectPA(){
		$lib = new \Modules\collection\lib();
		return $lib -> collectPA($this -> user);
	}
	
	/**
	 * <ok class="游客操作(2) "/> 2.4 用户取消收藏
	 * 参数
	 	  "token":*，
			"target": varchar 收藏的类型，如provider,room,food,product,activity
			"id": int 要收藏的对象id
	 * 返回
	 *         {
	 *             “code”:200, // 200：成功
	 *             “data”:如果取消成功，返回cancel;
	 *         }
	 */
	 function user__collectPAdel(){
		$lib = new \Modules\collection\lib();
		return $lib -> collectPAdel($this -> user);
	}
	
	/**
	 * <ok class="游客操作(2) "/> 2.5 查看是否已收藏
	 * 参数
	 	  "token":*，
			"target": varchar 收藏的类型，如provider,room,food,product,activity
			"id": int 要收藏的对象id
	 * 返回
	 *         {
	 *             “code”:200, // 200：成功
	 *             “data”:1-已收藏  0-未收藏;
	 *         }
	 */
	 function user__collectPAjudege(){
		$lib = new \Modules\collection\lib();
		return $lib -> collectPAjudege($this -> user);
	}
	
	/**
	 * <ok class="游客操作(2) "/> 2.6 用户添加/修改收货地址
	 * 参数
	 *	  "token":*
	 *	  "id": int 已添加的收货地址id(当修改收货地址信息或设置为默认地址需要此id)
	 *		"name": string 收货人姓名
	 *		"phone": string 收货人电话
	 *		"province": string 省
	 *		"city": string 市
	 *		"district": string 地区
	 * 		"street_office":string 街道
	 *		"address": string 具体地址
	 *		"is_default": int 是否为默认地址（0-不是,1-是）<font color="red">只设置默认地址时其它参数可以不传</font>
	 *	返回
	 *      {
	 * 			code:200,
	 * 			data:{
	 * 				"id":int 如果添加成功，返回新地址id(修改时，无需返回id)
	 * 			}
	 * 		}	
	 */
	 function user__addNewAddress(){
		$lib = new \Modules\useraddress\lib();
		return $lib -> addNewAddress($this -> user);
	}
	
	/**
	 * <ok class="游客操作(2) "/> 2.7 用户收货地址列表
	 * 参数
	 * 	token：
	 *  offset：0
	 *  rows:20
	 * 返回
	 * 	data:[{
	 * 		id:1	// int ID
	 *    uid: int // 用户id
	 * 		name: string //  收货人名称
	 * 		phone  string	// 收货人电话
	 * 		province:string	//省
	 * 		city:string	//市
	 * 		district:string	//地区
	 * 		address:string	//具体地址
	 * 		is_default:int 	//是否为默认地址(0-不是 1-是)
	 * 	}]
	 */
	function user__addresslist(){
		$lib = new \Modules\useraddress\lib();
		return $lib -> addresslist($this -> user);
	}
	
	/**
	 * <ok class="游客操作(2) "/> 2.8 用户某一收货地址详情页
	 * 参数
	 * 	token：
	 * 	id：
	 * 返回
	 * 	data:[{
	 * 		id:1	// int ID
	 *    uid: int // 用户id
	 * 		name: string //  收货人名称
	 * 		phone  string	// 收货人电话
	 * 		province:string	//省
	 * 		city:string	//市
	 * 		district:string	//地区
	 * 		address:string	//具体地址
	 * 		is_default:int 	//是否为默认地址(0-不是 1-是)
	 * 	}]
	 */
	function user__addressdetail(){
		$lib = new \Modules\useraddress\lib();
		return $lib -> addressdetail($this -> user);
	}
	
	/**
	 * <ok class="游客操作(2) "/> 2.9 删除用户的收货地址(单个)
	 * 参数
	 *	  "token":*
	 *	  "id": int 收货地址id
	 
	 */
	 function user__addressdel(){
		$lib = new \Modules\useraddress\lib();
		return $lib -> addressdel($this -> user);
	}
	
	/**
	 * <ok class="游客操作(2) "/> 2.11 通过关键字实现站内搜索
	 * 参数
	 * 	token：
	 * 	keyword：
	   	lat：
	   	lng：
	   	city_id：
	   	"offset": 分页开始位置，默认0
	   	"rows": 从开始位置读取多少行，默认10	 
	 * 返回
	 * 	data:[{
	 * 		id:1	// int ID
	 * 		name: string //  农家乐名称
	 * 		desc  string	// 农家乐简介
	 * 		address:string	//具体地址
	 * 		cover_image:string	//主图片地址
	 * 		city_id:string	//该农户所属城市id
	 * 		district_id:string	//该农户所属区域id
	 * 		city_name:string	//该农户所属城市名称
	 * 		district_name:string	//该农户所属区域名称
	 * 		position:string	//该农户位置
	 * 		distance:string	//当前游客与该农户的距离	 (只有当传lat,lng时,才会生效)
	 * 		room_num:int	//是否存在已发布的房间 (无--room_num=0,有--room_num>0)		 
	 * 		food_num:int	//是否存在已发布的美食 (无--food_num=0,有--food_num>0)		
	 * 		activity_num:int	//是否存在已发布的活动 (无--activity_num=0,有--activity_num>0)		
	 * 		product_num:int	//是否存在已发布的特产 (无--product_num=0,有--product_num>0)			 
	 * 		dicount_num:int	//是否存在已发布的福利 (无--dicount_num=0,有--dicount_num>0)		 
	 * 		collected_num:int	//该农户被收藏数量
	 * 		comments_num：int //该农户被评论数量
	 * 		is_collected //是否被正在查阅的用户收藏  0-未收藏，1-已收藏 
	 * 	}]
	 */
	function get__providerSearch(){
		$lib = new \Modules\search\lib();
		return $lib -> providerSearch($this -> user);
	}
	
	
  
  /**
	 * <ok class="游客操作(2) "/> 2.12 所有的搜索关键字
	 * 参数
	 * 	token：
	 * 返回
	 *         {
	 *             “code”:200, 
	 *             “data”:(jsonstr){
	 *              travel(主题分类):
	 *               [
	 *                  {"id":1,"name":"垂钓","image":""},...
	 *               ],
	 *              person(人群分类):
	 *               [
	 *                  {"id":1,"name":"个人","image":""},...
	 *               ],
	 *              attraction(热门景点):
	 *               [
	 *                  {"id":1,"name":"瀑布","image":""},...
	 *               ],
	 *           }
	 *         }
	 * 备注:可直接使用commontype接口;获取下列分类
	 *     type为travel;即为人群分类
	 *     type为person;即为人群分类
	 *     type为attraction;即为热门景点分类
	 */
	function get__searchAll(){
		$lib = new \Modules\search\lib();
		return $lib -> searchAll($this -> user);
	}

	############################################################
	# 点评评论
	############################################################
	/**
	 * <ok class="游客操作(2) "/> 2.13 评论管理--增加评论
	 * 参数
	 * 	token：
	 * 	target:string room/food/product/provider/discount/activity
	 * 	target_id:int
	 * 	order_id:int 	如果是订单商品需要传递订单ID
	 * 	content:string
	 * 	is_praise:int 0/1/2/3/4/5 	评论星星数
	 * 	image: string 多图用数字分割file_id(如23，45),file_id通过上传接口获得
	 */
	function user__comment() {
		$lib = new \Modules\comment\lib();
		return $lib -> addComment($this->user);
	}
	/**
	 * <ok class="游客操作(2)"/> 2.13 评论管理--获取评论列表
	 * 参数
	 * 	target:string room/food/product/provider/discount/activity
	 * 	target_id:int
	 * 	offset:0
	 * 	rows:20
	 * 返回
	 * 	data:[{
	 * 	 "id": "16",
	 * 	 "app_user_id": "21",
	 * 	 "user_name": //评论人昵称,
	 * 	 "user_headimg": //评论人头像,
	 * 	 "order_id": "0",
	 * 	 "target": "order",
	 * 	 "target_id": "1",
	 * 	 "content": "sdafsddsf",
	 * 	 "addtime": "1463971169",
	 * 	 "status": "1",
	 * 	 "is_praise": "0",
	 * 	 "cover_image": [{
	 * 	 	"id": "0",
	 * 	 	"path": "1",
	 * 	 }],
	 * 	}]
	 * //以下只有当taraget为provider时有值
	 * 	provider_name:	//农户昵称
	 * 	provider_avatar: //农户头像
	 * 	provider_praise: //农户星星数
	 * 	provider_count: //该农户评论总数
	 */
	function get__comments() {
		$lib = new \Modules\comment\lib();
		return $lib -> getComments($this->user);
	}
	############################################################
	# 点评评论 over
	############################################################

	############################################################
	# 用户端各操作接口API(2) over 
	############################################################


	############################################################
	# 农户个人信息接口API (3) start
	############################################################
	/**
	 * <ok class="农户个人信息(3) "/> 3.0 农户基本信息添加
	 * 参数
		"token":*，
		"cover_image": file_id,file_id,file_id,
		"avatar": file_id,
		"name": string 店铺名称，
		"owner": string 负责人,
		<s>"phone_1": string 联系方式1，</s>
		<s>"phone_2": string 联系方式2，</s>
		<s>"phone_3": string 联系方式3，</s>
		"link_1": json string {"name":"xxxx","phone":"15800000000"}，联系方式1
		"link_2": json string {"name":"xxxx","phone":"15800000000"}，联系方式2，
		"link_3": json string {"name":"xxxx","phone":"15800000000"}，联系方式3，
	 */
	function user__offerBasicAdd(){
		$lib = new \Modules\provider\lib();
		return $lib -> offerBasicAdd($this -> user);
	}
	/**
	 * <ok class="农户个人信息(3) "/> 3.1 农家乐简介添加
	 * 参数
		"token":*，
		"brief"       : string  农家乐简介
		"around"      : string  周边风光
		"route"       : string  行车路线
		"lat"         : string  纬度
		"lng"         : string  经度
		"address"     : string 该农户地址
	 */
	function user__offerBriefAdd(){
		$lib = new \Modules\provider\lib();
		return $lib -> offerBriefAdd($this -> user);
	}
	/**
	 * <ok class="农户个人信息(3) "/> 3.2 农户资质信息添加
	 * 参数
		"token":*，
		"identity_card_no": string 经营者身份证号
		"identity_card_photo": string 经营者身份证照片id(照片通过上传接口上传，并返回id)
		"business_license_no": string 营业执照号
		"business_license_photo": string 经营者营业执照照片id(照片通过上传接口上传，并返回id)
		"hygiene_license_no": string 卫生许可证号
		"hygiene_license_photo": string 经营者卫生许可证照片id(照片通过上传接口上传，并返回id)
		"other_no": string 其它证书号
		"other_photo": string 其它证书照片id(照片通过上传接口上传，并返回id)
	 */
	function user__offerLicenseAdd(){
		$lib = new \Modules\provider\lib();
		return $lib -> offerLicenseAdd($this -> user);
	}
	/**
	 * <ok class="农户个人信息(3) "/> 3.4 农户商铺资料旅游类型添加
	 * 参数
		"token":*，
		"type_id": api commontype->travel 传值以逗号分开
			参考(1-垂钓,2-棋牌,3-烧烤,4-打渔,5-真人cs,6-骑马,7-会议,
			8-漂流,9-采摘,10-种植,11-住宿,12-拓展)
	 */
	function user__offerTraveledTypeAdd(){
		$lib = new \Modules\provider\lib();
		return $lib -> offerTraveledTypeAdd($this -> user);
	}
	/**
	 * <ok class="农户个人信息(3) "/> 3.5 农户商铺资料旅游人群添加
	 * 参数
		"token":*，
		"type_id":  api commontype->person 传值以逗号分开
			参考 (1-个人,2-同学,3-情侣,4-亲子,5-家庭,6-公司)
	 */
	function user__offerPersonedTypeAdd(){
		$lib = new \Modules\provider\lib();
		return $lib -> offerPersonedTypeAdd($this -> user);
	}
	/**
	 * <ok class="农户个人信息(3) "/> 3.6 附近景点设置
	 * 参数
		"token":*，
		 *"attraction_ids":string 景点ID逗号分隔
		WEB 可以使用以下参数方式
		 *"attraction_id[]":int 景点ID
		"attraction_id[]":int 景点ID
		"attraction_id[]":int 景点ID
	 */
	function user__offerSetAttraction(){
		$lib = new \Modules\provider\lib();
		return $lib -> offerSetAttraction($this -> user);
	}
	/**
	 * <ok class="农户个人信息(3) "/> 3.6-1 (3.13) 附近景点查看
	 * 	参数
			"token":*，
			"lat":float 纬度 0.000000
			"lng":float 经度 0.000000
		返回
			"data": [
				{"id":1,"name":"瀑布","status":1(已选中)},
				{"id":2,"name":"大江","status":0(未选中)},
			]
	 */
	function user__offerGetAttraction(){
		$lib = new \Modules\attraction\lib();
		return $lib -> offerGetAttraction($this -> user);
	}
	/**
	 * <ok class="农户个人信息(3) "/> 3.6-2 自定义添加附近景点
	 *	参数
	 * 		token: 用户登录后的 token
	 * 		name:景点名称
	 *  返回
	 *      {
	 * 			code:200,
	 * 			data:{
	 * 				"id":int,景点ID建立关系需要此ID
	 * 			}
	 * 		}
	 */
	function user__offerDiyAttraction(){
		$lib = new \Modules\attraction\lib();
		$id = $lib -> addDiyAttraction($this -> user);
		return array('data' => array('id' => $id));
	}
	/**
	 * <ok class="农户个人信息(3) "/> 3.7 农户必填资料添加
	 *	参数
	 * 		token: 用户登录后的 token
	 * 		"name"       : string  农家乐名称
			"lat"         : string  纬度
			"lng"         : string  经度
			"address"     : string 该农户地址
	 */
	function user__offerRequiredAdd(){
		$lib = new \Modules\provider\lib();
		return $lib -> offerRequiredAdd($this -> user);
	}
	/**
	 * <ok class="农户个人信息(3) "/> 3.8 农户基本信息详情
	 *	参数
	 * 		token: 用户登录后的 token
	 * 	返回
	 * 		"data": {
				"id": int 该农户id
				"name": string 该农户（景点）名
				"sign": string //  0-普通农户,1-优选农户
				"owner":string  负责人身份信息
				"avatar": string 该农户头像图片地址
				"cover_image": string 封面图片地址
				"brief": string 农家乐简介
				"lat":string  纬度
				"lng":string  经度
				"address": string 该农户地址
				"around":text 周边风光
				"route":string 行车路线
				"share_url":string "http://..."    //分享链接			
				"share_name":string     //分享农户名	
				"share_desc":string     //分享描述
				"share_pic":string     //分享图片
				"share_xcx_username":string     //
				"share_xcx_url":string "http://..."    //小程序分享链接
				"share_xcx_name":string     //小程序分享名
				"share_xcx_desc":string     //小程序分享描述
				"share_xcx_pic":string     //小程序分享图片
	 			<font color="red">"share_nickname":"分享名，优先使用用户昵称,无则店铺名称"</font>
				"share_pic_weibo":string     //微博分享图片
	 			<font color="red">"invite_share_title":"邀请好友时分享标题"</font>
	 			<font color="red">"invite_share_desc":"邀请好友时分享描述"</font>
	 			<font color="red">"invite_share_url":"邀请好友时分享链接"</font>
	 			<font color="red">"invite_share_pic":"邀请好友时分享图片"</font>
	 			<font color="red">"invite_share_weibo_pic":"邀请好友时微博分享图片"</font>
				"phones": string list [
					{"name":"vvv","phone":"1580000000"}
					{"name":"vvv","phone":"1580000000"}
					{"name":"vvv","phone":"1580000000"}
				],
				"gallery_provider":[
					{
						"id": int 该图片/照片id
						"image": string 图片/照片地址
					}
				]
	 * 		}
	 */
	function user__offerBaseOne(){
		$lib = new \Modules\provider\lib();
		return $lib -> offerBaseOne($this -> user);
	}

	/**
	 * <ok class="农户个人信息(3) "/> 3.8-1 页面头部统计数据信息
	 *	参数
	 * 		token: 用户登录后的 token
	 * 	返回
	 * 		"data": {
				"id": int 该农户id
				"name": string 该农户（景点）名
				"avatar": string 该农户头像图片地址
	 * 			"is_authenticated": string 是否认证，0未认证\1已认证
	 * 			"count_collection":int 用户收藏
	 * 			"count_collection_goods":int 商品关注（收藏）
	 * 			"count_display":int 浏览次数
	 * 		}
	 */
	function user__offerBase(){
		$lib = new \Modules\provider\lib();
		return $lib -> offerBase($this -> user);
	}
	/**
	 * <ok class="农户个人信息(3) "/> 3.9 农户资质信息详情
	 *	参数
	 * 		token: 用户登录后的 token
	 * 	返回
	 * 		"data": {
				"provider_id": int 所属农户id
				"identity_card_photo": string 经营者身份证照片id
				"business_license_photo": string 经营者营业执照照片id
				"hygiene_license_photo": string 经营者卫生许可证照片id
				"other_photo": string 其它证书照片id
	 * 			"identity_card_photo_src": string 经营者身份证照片路径
				"business_license_photo_src": string 经营者营业执照照片路径
				"hygiene_license_photo_src": string 经营者卫生许可证照片路径
				"other_photo_src": [  //其它证书照片路径
	 * 				"http://......download....","http://......download...."
	 * 			]
			}
	 */
	function user__offerLicenseOne(){
		$lib = new \Modules\provider\lib();
		return $lib -> offerLicenseOne($this -> user);
	}
	/**
	 * <nok class="农户个人信息(3) "/> 3.10 农户账户信息详情
	 * <font color="red">废弃，请使用8.4</font>
	 *	参数
	 * 		token: 用户登录后的 token
	 * 	返回
	 * 		"data": {
				"provider_id": int 所属农户id
				"account_id"     :string  账号
				"account_name"   :string  户名
				"payment"        :sting  收款方式
			}
	 */
	function user__offerAccountOne(){
		$lib = new \Modules\provider\lib();
		return $lib -> offerAccountOne($this -> user);
	}
	/**
	 * <ok class="农户个人信息(3) "/> 3.11 农户商铺资料旅游类型详情
	 *	参数
	 * 		token: 用户登录后的 token
	 * 	返回
	 * 		"data": [type_id,type_id,type_id,]  //参考commontype.travel
	 * 		//(1-垂钓,2-棋牌,3-烧烤,4-打渔,5-真人cs,6-骑马,7-会议,8-漂流,9-采摘,10-种植,11-住宿,12-拓展）
	 */
	function user__offerTraveledTypeOne(){
		$lib = new \Modules\provider\lib();
		return $lib -> offerTraveledTypeOne($this -> user);
	}
	/**
	 * <ok class="农户个人信息(3) "/>3.12 农户商铺资料旅游人群详情
	 *	参数
	 * 		token: 用户登录后的 token
	 * 	返回
	 * 		"data": [type_id,type_id,type_id,]  //参考commontype.travel
	 * 		//(1-个人,2-同学,3-情侣,4-亲子,5-家庭,6-公司)
	 */
	function user__offerPersonedTypeOne(){
		$lib = new \Modules\provider\lib();
		return $lib -> offerPersonedTypeOne($this -> user);
	}
	
	/**
	 * <ok class="农户个人信息(3) "/> 3.13 农户公共设施添加
	 * 参数
		"token":*，
		"device_id":  api commontype->commonfacility  传值以逗号分开
	 */
	function user__offerFacilityTypeAdd(){
		$lib = new \Modules\provider\lib();
		return $lib -> offerFacilityTypeAdd($this -> user);
	}
	/**
	 * <ok class="农户个人信息(3) "/> 3.14 农户公共设施详情
	 *	参数
	 * 		token: 用户登录后的 token
	 * 	返回
	 * 		"data": [device_id,device_id,device_id,]  //参考commontype.commonfacility	
	 */
	function user__offerFacilityTypeOne(){
		$lib = new \Modules\provider\lib();
		return $lib -> offerFacilityTypeOne($this -> user);
	}


	############################################################
	# 农家接口
	############################################################
	/**
	 * <ok class="农户个人信息(3)"/> 3.15农家乐基本信息--自定义添加周边景点
	 *  参数
	 * 		token: 用户登录后的 token
	 * 		name:景点名称
	 *  返回
	 *      {
	 * 			code:200,
	 * 			data:{
	 * 				"id":int,景点ID建立关系需要此ID
	 * 			}
	 * 		}
	 */
	function user__diyAttraction() {
		$lib = new \Modules\attraction\lib();
		$id = $lib -> addDiyAttraction($this -> user);
		return array('data' => array('id' => $id));
	}

	/**
	 * <nok class="农户个人信息(3)"/> 3.16农家乐基本信息--添加提现账户
	 * <font color="red">废弃，请使用8.3</font>
	 *  参数
	 * 		token: 用户登录后的 token
	 * 		account_id     :string  账号
	 * 		account_name  :string  户名
	 * 		payment        ：int  10/11/20/21/22/23      支付宝/微信/工商/农行/建行/邮政
	 *  返回
	 *      {
	 * 			code:200,
	 * 			data:""
	 * 		}
	 */
	function user__providerAccount() {
		$lib = new \Modules\provider\lib();
		return array('data' => $lib -> providerAccount($this -> user));
	}
	/**
	 * <ok class="农户个人信息(3)"/> 3.17农家乐基本信息--提交商家认证
	 *  参数
	 * 		token: 用户登录后的 token
	 * 		type     :int  1/2         从收益中扣除/在线付款
	 *  返回
	 *      {
	 * 			code:200,
	 * 			data:
	 * 				1，从收益中扣除返回值可忽略
	 * 				2，如果是在线付款，返回数据格式请参考获取订单详细接口，之后需要调用支付进行订单支付
	 * 		}
	 */
	function user__providerCertified(){
		$lib = new \Modules\certified\lib();
		return array('data' => $lib -> submitCertified($this -> user));
	}
	/**
	 * <ok class="农户个人信息(3)"/> 3.18农家乐基本信息--该农户所有产品列表
	 *  参数
	 * 		token: 用户登录后的 token
	 * 		offset:分页开始位置，默认0
	 * 	  rows：从开始位置读取多少行，默认10
	 *  返回
	 *      {
	 * 			code:200,
	 * 			data:
	 *	    {
	 *	      "id":"",
	 *	      "name":"",
	 *	      room:[
	 * 		      {"id":"260","name":"","position":"1","desc":"","price":"2323.00","price_unit":"yuan",
	 * 		      "cover_image":"http:\/\/101.201.37.61:10002\/apido\/download\/id\/56","publish_status":"1",},
	 * 		      ......
	 *		      ],
	 *	      food:[
	 * 		      {"id":"260","name":"","desc":"","price":"2323.00","price_unit":"yuan",
	 * 		      "cover_image":"http:\/\/101.201.37.61:10002\/apido\/download\/id\/56","publish_status":"1",},
	 * 		      ......
	 *	      ],
	 *	      activity:[
	 * 		      {"id":"260","name":"","desc":"","price":"2323.00","price_unit":"yuan",
	 * 		      "cover_image":"http:\/\/101.201.37.61:10002\/apido\/download\/id\/56","publish_status":"1",},
	 * 		      ......
	 *	      ],
	 *	      product:[
	 * 		      {"id":"260","name":"","desc":"","price":"2323.00","price_unit":"yuan",
	 * 		      "cover_image":"http:\/\/101.201.37.61:10002\/apido\/download\/id\/56","publish_status":"1",},
	 * 		      ......
	 *	      ],
	 *       }
	 *
	 *
	 * 		}
	 */
	function user__goodsList(){
		$lib = new \Modules\provider\lib();
		//return array('data' => $lib -> goodsList($this -> user));
		return $lib -> goodsList($this -> user);
	}
	############################################################
	# 农家接口over
	############################################################

	############################################################
	# 农户个人信息接口API (3) over
	############################################################
	
	############################################################
	# 农户发布信息接口API (4) start
	############################################################
	
	/**
	 * <ok class="农户发布信息(4)  "/> 4.0 商户添加/修改新房间
	  参数
	 	  *"token":
	 	  "id": int 已添加的房间id(当修改房间信息时，需调用该房间的id;修改操作的必填项为'token','id')
	 	  *"position": int 对房间的定位 (1-整套房子,2-独立房间,3-套间)
	 	  *"price": string <font color="red">价格串,多个价格使用逗号分隔；依次为 闲时价格,忙时价格,节假日价格；注意顺序</font>
	 	  *"price_unit": string 价格的单位(如"一晚"，"12小时"等)
	 	  *"room_type": string 房间类型(单选) 接口http://www.colorcun.com:10002/apido/commontype
	 	  	 	  	 	   (该接口地址:http://www.colorcun.com:10002/api.html#commontype)
	 	  "name": string 具体地址
	 	  "desc": string 亮点介绍
	 	  <s color="red">"device_type": string 配套设施(基础、公共、额外) 接口http://www.colorcun.com:10002/apido/commontype</s>
	 	  	 	  	 	    <s>(该接口地址:http://www.colorcun.com:10002/api.html#commontype)</s>
	 	  <font color="red">"device_type": string 配套设施 通过commontype.basefacility获取;</font>
	 	  "unsubscribe": int 退订政策(11-宽松，12-适中，13-严格)
	 	  "unavailable": string 不可用日期
	 	  "capacity": int 房间适宜人数
	 	  "roomnum": int 房间数(默认整套房子时设置)
	 	  "bednum": int 床位数
	 	  "bedtype": int 床型(1-中式,2-榻榻米,3-欧式)
	 	  "bedmodel": int 床的尺寸(1-1.2*2米,2-1.5*2米,3-1.8*2米) 
	 	  "has_bathroom": int 卫生间数量	
	 	  *"image": string 多图用数字分割file_id(如23，45),file_id通过上传接口获得
	 	  //说明:"cover_image": string 封面图片id(通过image上传，默认第一张为cover_image)
	 	返回
	       {
	  			code:200,
	  			data:{
	  				"id":int 如果添加成功，返回新房间id(修改时，无需返回id)
	  			}
	  		}	
	 */
	 function user__addNewRoom(){
		$lib = new \Modules\room\lib();
		return $lib -> addNewRoom($this -> user);
	}
	
	/**
	 * <ok class="农户发布信息(4)  "/> 4.1 商户添加/修改新餐品
	  参数
	 	  *"token":
	 	  "id": int 已添加的餐品id(当修改餐品信息时，需调用该餐品的id;修改操作的必填项为'token','id')
	 	  *"price": float 价格
	 	  *"price_unit": string 价格的单位(如"一晚"，"12小时"等)
	 	  "name": string 具体地址
	 	  "desc": string 亮点介绍
	 	  *"image": string 多图用数字分割file_id(如23，45),file_id通过上传接口获得
	 	  //说明:"cover_image": string 封面图片id(通过image上传，默认第一张为cover_image)
	 	返回
	       {
	  			code:200,
	  			data:{
	  				"id":int 如果添加成功，返回新餐品id(修改时，无需返回id)
	  			}
	  		}	
	 */
	 function user__addNewFood(){
		$lib = new \Modules\food\lib();
		return $lib -> addNewFood($this -> user);
	}
		
	/**
	 * <ok class="农户发布信息(4)  "/> 4.2 商户添加/修改新活动
	  参数
	 	  *"token":
	 	  "id": int 已添加的活动id(当修改活动信息时，需调用该活动的id;修改操作的必填项为'token','id')
	 	  *"price": float 价格
	 	  *"price_unit": string 价格的单位(如"一晚"，"12小时"等)
	 	  "name": string 具体地址
	 	  "desc": string 亮点介绍
	 	  *"image": string 多图用数字分割file_id(如23，45),file_id通过上传接口获得
	 	  //说明:"cover_image": string 封面图片id(通过image上传，默认第一张为cover_image)
	 	返回
	       {
	  			code:200,
	  			data:{
	  				"id":int 如果添加成功，返回新活动id(修改时，无需返回id)
	  			}
	  		}	
	 */
	 function user__addNewActivity(){
		$lib = new \Modules\activity\lib();
		return $lib -> addNewActivity($this -> user);
	}
		
	/**
	 * <ok class="农户发布信息(4)  "/> 4.3 商户添加/修改新特产
	  参数
	 	  *"token":
	 	  "id": int 已添加的特产id(当修改特产信息时，需调用该特产的id;修改操作的必填项为'token','id')
	 	  *"price": float 价格
	 	  *"price_unit": string 价格的单位(如"一晚"，"12小时"等)
	 	  "name": string 具体地址
	 	  "desc": string 亮点介绍
	 	  *"image": string 多图用数字分割file_id(如23，45),file_id通过上传接口获得
	 	  //说明:"cover_image": string 封面图片id(通过image上传，默认第一张为cover_image)
	 	返回
	       {
	  			code:200,
	  			data:{
	  				"id":int 如果添加成功，返回新特产id(修改时，无需返回id)
	  			}
	  		}	
	 */
	 function user__addNewProduct(){
		$lib = new \Modules\product\lib();
		return $lib -> addNewProduct($this -> user);
	}

	/**
	 * <ok class="农户发布信息(4)  "/> 4.4 商户添加/修改福利
	  参数
	 	  *"token":
	 	  "id": int 已添加的福利id(当修改福利信息时，需调用该福利的id;修改操作的必填项为'token','id')
	 	  "begin_time": YYYY-MM-DD
	 	  "end_time": YYYY-MM-DD
	 	  "desc": string 福利内容描述
	 	返回
	       {
	  			code:200,
	  			data:{
	  				"id":int 如果添加成功，返回新福利id(修改时，无需返回id)
	  			}
	  		}	
	 */
	 function user__discountadd(){
		$lib = new \Modules\discount\lib();
		return $lib -> discountadd($this -> user);
	}	
	
	/**
	 * <ok class="农户发布信息(4)  "/> 4.5 商户发布列表(房间,餐品,活动,特产)
	  参数
	 	  *"token":
	 	  "type": string 发布的物品类型
	 	  	 	  	 	  "room"  : 房间
	 	  	 	  	 	  "food"  : 餐品
	 	  	 	  	 	  "activity"  : 活动
	 	  	 	  	 	  "product"  : 特产
	 	  *"offset": 分页开始位置，默认0
	 	  *"rows": 从开始位置读取多少行，默认10

	 	返回
	       {
	  			code:200,
	  			data:{
	  				"id": 所选物品主id
	  				"name": 物品名
	  				"position": 对房间的定位(1-整套房子,2-独立房间,3-套间)
	  				"desc": 描述	 
	  				"price": 价格	
	  				"price_unit": 价格单位	
	  				"cover_image": 物品的封面照片
	  				"publish_status": 发布状态(0-下架,1-上架)	
	  			}
	  		}	
	 */
	 function user__publishList(){
		$lib = new \Modules\provider\lib();
		return $lib -> publishList($this -> user);
	}
	
	/**
	 * <ok class="农户发布信息(4)  "/> 4.6 农户福利列表
	  参数
	 	  *"token":
	 	返回
	       {
	  			code:200,
	  			data:{
	  				"id": 所选物品主id
	  				"provider_id": 所属农户id
	  				"path": 福利照片路径(默认为农户封面图片)
	  				"begin_time": 福利开始时间	 
	  				"end_time": 福利结束时间	
	  				"btime": 福利开始时间	YYYY-MM-DD
	  				"etime": 福利结束时间	YYYY-MM-DD
	  				"desc": 福利内容描述	
	  				"is_checked": 是否已经审核(0-未审核,1-已审核)
	 * 				"run_status": -1/0/1   未开始／进行中／已完成
	  			}
	  		}	
	 */
	 function user__discountlist(){
		$lib = new \Modules\discount\lib();
		return $lib -> discountlist($this -> user);
	}
	
	/**
	 * <ok class="农户发布信息(4)  "/> 4.7 农户某一福利详情
	  参数
	 	  *"token":
	 	  *"id": 福利id	 	   
	 	返回
	       {
	  			code:200,
	  			data:{
	  				"id": 所选物品主id
	  				"provider_id": 所属农户id
	  				"path": 福利照片路径(默认为农户封面图片)
	  				"begin_time": 福利开始时间	 
	  				"end_time": 福利结束时间	
	  				"btime": 福利开始时间	YYYY-MM-DD
	  				"etime": 福利结束时间	YYYY-MM-DD
	  				"desc": 福利内容描述	
	  				"is_checked": 是否已经审核(0-未审核,1-已审核)		
	  			}
	  		}	
	 */
	 function user__discountone(){
		$lib = new \Modules\discount\lib();
		return $lib -> discountone($this -> user);
	}
	
	/**
	 * <ok class="农户发布信息(4)  "/> 4.8 商户对某一商品(房间,餐品,活动,特产)的操作
	  参数
	 	  *"token":
	 	  "operate": string 对商品的操作
	 	  	 	  	 	  "undercarriage"  : 下架 
	 	  	 	  	 	  "oncarriage"  : 上架 
	 	  	 	  	 	  "delete"  : 删除
	 	  备注: publish_status的值会根据不同的操作而变化: 0--下架,1--上架,-1--删除;	 	  	 	  	 	   
	 	  "type": string 发布的物品类型
	 	  	 	  	 	  "room"  : 房间
	 	  	 	  	 	  "food"  : 餐品
	 	  	 	  	 	  "activity"  : 活动
	 	  	 	  	 	  "product"  : 特产
	 	  "id": string 要操作的商品id	 	  	
	 */
	 function user__operation(){
		$lib = new \Modules\provider\lib();
		return $lib -> operation($this -> user);
	}
	
	/**
	 * <ok class="农户发布信息(4)  "/> 4.9 删除福利
	  参数
	 	  *"token":
	 	  *"id": 福利id	 	   
	 */
	 function user__discountsdel(){
		$lib = new \Modules\discount\lib();
		return $lib -> discountsdel($this -> user);
	}
	
	
	/**
	 * <ok class="农户发布信息(4)"/> 4.10 房间详情(农户端)
	 * 参数
	 * 	token：
	 * 	id:int
	 * 返回
	 {
	 "data": {
		 "id": int 房间id
	   "provider_id": int 所属农户id
	   "position": int 对房间的定位 (1-整套房子,2-独立房间,3-套间)
	   "price": float 房间费用,闲时
	   "price_unit": string 价格的单位(如"一晚", "12小时"等)
	 	<font color="red">
	 * "price_busy": float 房间费用,忙时
	 * "price_holiday": float 房间费用,节假日
	 	</font>
	   "room_type": string 房间类型(单选) 接口http://www.colorcun.com:10002/apido/commontype
	                                 (该接口地址:http://www.colorcun.com:10002/api.html#commontype) 
	   "is_collected" int 是否被正在查阅的用户收藏  0-未收藏，1-已收藏 
	   "name": string 房间名
	   "desc": string 亮点介绍
	   "device_type": [1,2,3]  配套设施(基础、公共、额外) 接口http://www.colorcun.com:10002/apido/commontype
	                                (该接口地址:http://www.colorcun.com:10002/api.html#commontype)
	   "device_type_basefacility":  [1,2,3]  基础配套设施 接口
	   "device_type_commonfacility": [1,2,3]  公共配套设施
	   "device_type_extrafacility": [1,2,3]  额外配套设施
	   "unsubscribe": int  退订政策(11-宽松,12-适中,13-严格)
	   "unavailable": string  不可用日期    //数组形式请查看 unavail
	   "unavail": [2016-07-23,2016-07-24,2016-07-25]  不可用日期	   
	   "capacity": int 房间适宜人数
	   "roomnum": int 房间数(默认整套房子时设置)
	   "bednum": int 床位数
	   "bedtype": int 床型(1-中式,2-榻榻米,3-欧式)
	   "bedmodel": int 床的尺寸(1-1.2*2米,2-1.5*2米,3-1.8*2米)
	   "cover_image": string 房间封面图片
	   "publish_status": string 房间状态	(0-下架,1-上架)	
	   "has_bathroom": int 卫生间数量	 
	   "share_url":string "http://..."    //分享链接			
	   "share_name":string     //分享房间名	
	   "share_desc":string     //分享描述
	   "share_pic":string     //分享图片
	   "share_pic_weibo":string     //微博分享图片
	   "photos": (list 该房间的照片) [
		   {
		    "id": int 该图片/照片id 
		    "image": string 图片/照片地址
		   },
		   ......
		   ......
		  ],
	   
	  "reserved": (list 已预订信息) [
	   {
	    "id": int 订单号id 
	    "provider_id": int 预订农户id
	    "target_id": int 已预订房间id
	    "begin_time": string 预订开始日期
	    "end_time": string 预订结束日期
	    "time_quantum": string list [  预订时间段
	     datetime 如2016-06-18 ,
	     ....
	     ....
	     
	    ]
	   },
	   ......
	   ......
	  ],
	  "provider_room":该房间所属农户信息{
		  "id":int 该农户id,
		  "name":string 该农户（景点）名,
		  "lat":string  纬度,
		  "lng":string  经度,
		  "address":string 该农户地址,
		  "avatar":该农户头像图片地址,
		  "phone":农户电话[
		    {
		      "id":int 该电话id ,
		      "phone":string 电话号码,
		      "name":string 姓名
		    },...
		  ]
	  },
	 "message": "",
	 "provider_flag": "1",
	 "code": 200
	 }
	 */
	function user__proom() {
		$lib = new \Modules\room\lib();
		return $lib -> roomInfo($this -> user,'',true);
	}
	
	/**
	 * <ok class="农户发布信息(4)"/> 4.11 餐品详情(农户端)
	 * 参数
	 * 	token：
	 * 	id:int
	 * 返回
	 {
	 "data": {
		 "id": int 餐品id
		 "provider_id": int 所属农户id
		 "is_collected" int 是否被正在查阅的用户收藏  0-未收藏，1-已收藏  
		 "name": string 食品名
		 "price": float 价格 
		 "price_unit": string 价格的单位(如"斤", "盒"等)
		 "desc": string 简介
		 "cover_image": string 封面图片
		 "publish_status": string 餐品状态	(0-下架,1-上架)
		 "share_url":string "http://..."    //分享链接
		 "share_name":string     //分享餐品名
		 "share_desc":string     //分享描述
		 "share_pic":string     //分享图片
		 "share_xcx_username":string     //
		 "share_xcx_url":string "http://..."    //小程序分享链接
		 "share_xcx_name":string     //小程序分享名
		 "share_xcx_desc":string     //小程序分享描述
		 "share_xcx_pic":string     //小程序分享图片
		 "share_pic_weibo":string     //微博分享图片
		 "photos": (list 该食品的照片) [
		   {
		    "id": int 该图片/照片id 
		    "image": string 图片/照片地址
		   },
		   ......
		   ......
		  ],
	  "provider_food":该餐品所属农户信息{
		  "id":int 该农户id,
		  "name":string 该农户（景点）名,
		  "lat":string  纬度,
		  "lng":string  经度,
		  "address":string 该农户地址,
		  "avatar":该农户头像图片地址,
		  "phone":农户电话[
		    {
		      "id":int 该电话id ,
		      "phone":string 电话号码,
		      "name":string 姓名
		    },...
		  ]
	  },
	 "message": "",
	 "provider_flag": "1",
	 "code": 200
	 }
	 */
	function user__pfood() {
		$lib = new \Modules\food\lib();
		return $lib -> food($this -> user,'',true);
	}
	
	/**
	 * <ok class="农户发布信息(4)"/> 4.12 活动详情(农户端)
	 * 参数
	 * 	token：
	 * 	id:int
	 * 返回
	 {
	 "data": {
		 "id": int 活动id 
	   "provider_id": int 所属农户id 
	   "is_collected" int 是否被正在查阅的用户收藏  0-未收藏，1-已收藏
	   "name": string 活动名
	   "price": float 价格
	   "price_unit": string 活动价格的单位 (如: "一次", "每天") 
	   "desc": string 简介
	   "cover_image": string 封面图片
	   "publish_status": string 活动状态	(0-下架,1-上架)	
	   "share_url":string "http://..."    //分享链接			
	   "share_name":string     //分享活动名	
	   "share_desc":string     //分享描述
	   "share_pic":string     //分享图片
	   "share_xcx_username":string     //
	   "share_xcx_url":string "http://..."    //小程序分享链接
	   "share_xcx_name":string     //小程序分享名
	   "share_xcx_desc":string     //小程序分享描述
	   "share_xcx_pic":string     //小程序分享图片
	   "share_pic_weibo":string     //微博分享图片
		 "photos": (list 该活动的照片) [
		   {
		    "id": int 该图片/照片id 
		    "image": string 图片/照片地址
		   },
		   ......
		   ......
		  ],
	  "provider_activity":该活动所属农户信息{
		  "id":int 该农户id,
		  "name":string 该农户（景点）名,
		  "lat":string  纬度,
		  "lng":string  经度,
		  "address":string 该农户地址,
		  "avatar":该农户头像图片地址,
		  "phone":农户电话[
		    {
		      "id":int 该电话id ,
		      "phone":string 电话号码,
		      "name":string 姓名
		    },...
		  ]
	  },
	 "message": "",
	 "provider_flag": "1",
	 "code": 200
	 }
	 */
	function user__pactivity() {
		$lib = new \Modules\activity\lib();
		return $lib -> activity($this -> user,'',true);
	}
	
	/**
	 * <ok class="农户发布信息(4)"/> 4.13 特产详情(农户端)
	 * 参数
	 * 	token：
	 * 	id:int
	 * 返回
	 {
	 "data": {
		 "id": int 特产id
	   "provider_id": int 所属农户id
	   "name": string 特产名 
	   "price": float 特产价格 
	   "price_unit": string 特产价格的单位 (比如: "斤" "两" "盒" 等)
	   "desc": string 简介 
	   "cover_image": string 封面图片 
	   "publish_status": string 特产状态	(0-下架,1-上架)		   
	   "is_collected" int 是否被正在查阅的用户收藏  0-未收藏，1-已收藏
	   "share_url":string "http://..."    //分享链接			
	   "share_name":string     //分享特产名	
	   "share_desc":string     //分享描述
	   "share_pic":string     //分享图片
	   "share_xcx_username":string     //
	   "share_xcx_url":string "http://..."    //小程序分享链接
	   "share_xcx_name":string     //小程序分享名
	   "share_xcx_desc":string     //小程序分享描述
	   "share_xcx_pic":string     //小程序分享图片
	   "share_pic_weibo":string     //微博分享图片
	   "photos": (list 该特产的照片) [
		   {
		    "id": int 该图片/照片id  
		    "image": string 图片/照片地址
		   },
		   ......
		   ......
		  ], 
	  "provider_product":该特产所属农户信息{
		  "id":int 该农户id,
		  "name":string 该农户（景点）名,
		  "lat":string  纬度,
		  "lng":string  经度,
		  "address":string 该农户地址,
		  "avatar":该农户头像图片地址,
		  "phone":农户电话[
		    {
		      "id":int 该电话id ,
		      "phone":string 电话号码,
		      "name":string 姓名
		    },...
		  ]
	  },
	 "message": "",
	 "provider_flag": "1",
	 "code": 200
	 }
	 */
	function user__pproduct() {
		$lib = new \Modules\product\lib();
		return $lib -> product($this -> user,'',true);
	}

	/**
	 * <ok class="农户发布信息(4)  "/> 4.14 快速建店list
	 * 参数
	 * 	token:
	 * 	offset:
	 * 	rows:20
	 * 返回
	 * 	"data":{[
	 * 		id:	//农户id
	 * 		avatar:	//农户头像
	 * 		name:	//农户名称
	 * 		operator:	//创建人
	 * 		addtime:	//时间
	 * 		]......
	 * 	}
	 */
	function user__quicklist(){
		$lib = new \Modules\provider\lib();
		return $lib -> quicklist($this -> user);
	}

	/**
	 * <ok class="农户发布信息(4)  "/> 4.15 输入手机号快速建店
	 * 参数
	 * 	token:
	 * 	phone:
	 * 	pid:复制店铺时使用
	 * 返回
	 * 	"data":{
	 * 		provider_id:	//农户id
	 * 	}
	 */
	function user__quickcreate(){
		$lib = new \Modules\provider\lib();
		return $lib -> quickcreate($this -> user);
	}



	############################################################
	# 农户发布信息接口API (4) over
	############################################################

	############################################################
	# 广播接口API (5) start
	############################################################
	/**
	 * <ok class="广播 (5)"/> 5.1 发布
	 * 参数
	 * 	token：*
	 * 	type：0/1 商家/游客  默认0
	 * 	content：内容
	 * 	attr_img:1,2,3	//图片附件 file_id 拼串
	 * 	tags:1,2,3		//标签 commontype->dynamic_tags
	 * 	<font color='red'>modal：(当发布短视频时,传递该参数,该参数的值为4)</font>
	 * 	<font color='red'>cover_image：(当发布短视频时,需要通过该参数传递第一帧的图片作为封面图)</font>
	 * 返回
	 * 	data:{
	 *		"id":"1" //广播ID，
	 * 		"share_url":"http://..."	//分享链接
	 * 		"share_title":"分享标题"		//农户名
	 * 		"share_desc":"分享描述"		//内容
	 * 		"share_pic":"http://..."	//分享图标60*60
	 * 		"share_xcx_username":string     //
	 * 		"share_xcx_url":string "http://..."    //小程序分享链接
	 * 		"share_xcx_name":string     //小程序分享名
	 * 		"share_xcx_desc":string     //小程序分享描述
	 * 		"share_xcx_pic":string     //小程序分享图片
	 * 		"share_pic_weibo":"http://..."	//微博分享图标
	 * 	}
	 */
	function user__dynamicUpdate(){
		$lib = new \Modules\dynamic\lib();
		return $lib -> update($this->user);
	}
	/**
	 * <ok class="广播 (5)"/> 5.2 删除
	 * 参数
	 * 	token：*
	 * 	id：广播 ID
	 */
	function user__dynamicDelete(){
		$lib = new \Modules\dynamic\lib();
		return $lib -> delete($this->user);
	}
	/**
	 * <ok class="广播 (5)"/> 5.3 点赞
	 * 参数
	 * 	token：*
	 * 	id：广播 ID
	 * 	type:1/!1	点赞/取消点赞
	 */
	function user__dynamicLove(){
		$lib = new \Modules\dynamic\lib();
		return $lib -> love($this->user);
	}
	/**
	 * <ok class="广播 (5)"/> 5.4 游客端列表
	 * 参数
	 * 	token：*
	 * 	addtime:0 //传0取最新的广播，分页取动态时传最后一条广播的addtime
	 *	showthis:0 //默认0，显示小于addtime的动态；为1时显示小于等于addtime的动态
	 * 	rows:20
	 * 	is_spirit:1 //是否精选广播
	 * 	owner_id:213 //查看该农户的广播列表
	 * 返回
	 * 	{
	 * 		data:[...
	 * 			{
	 * 				id:1	// 广播ID
	 * 				content:"content",
	 * 				attr_img:[1,2,3],//调用download接口获取图片
	 * 				addtime:int(10),
	 * 				addtime_label:"1分钟前",//1分钟前
	 * 				provider_name:"provider_name",
	 * 				<font color="red">is_provider:		//判断当前农户是否有头像,1-有 0-无</font>
	 * 				provider_id:"provider_id",//根据商家id调用avatar接口可以获取商家头像
	 * 				<font color="red">wx_avatar:		//若无头像,显示微信头像</font>
	 * 				count_view:0	//浏览量
	 * 				count_love:0	//点赞数
	 * 				is_loved:0	// 0/1	未点赞/已点赞
	 * 				type:0		//谁发布的 0/1/2	商家/游客/运营人员（官方）；商家头像可点击，其它头像不可点击
	 * 				<font color="red">modal:0		//广播模型 0/1/2/3/4  短广播/长广播/运营广播/卡券广播/短视频</font>
	 * 				<font color="red">cover_image:		//短视频时,默认第一帧图片</font>
	 * 				tags:[1,2,3] //标签，commontype->dynamic_tags
	 * 				share_url:"share_url"	//分享链接
	 * 				share_title:"分享标题"	//分享标题
	 * 				share_desc:"分享描述"		//分享描述
	 * 				share_pic:"http://..."	//分享图标60*60
	 * 				share_xcx_username:string     //
	 * 				share_xcx_url:string "http://..."    //小程序分享链接
	 * 				share_xcx_title:string     //小程序分享名
	 * 				share_xcx_desc:string     //小程序分享描述
	 * 				share_xcx_pic:string     //小程序分享图片
	 * 				share_pic_weibo:"http://..."	//微博分享图标
	 * 				is_owner:0	// 0/1	该广播是否属于自己 不属于/属于
	 * 				dy_comment:广播评论[
	 * 				  {
	 * 				   "id":int 该评论id ,
	 * 				   "did":int 该广播id ,
	 * 				   "uid":int 对广播的评论人id ,
	 * 				   "name":string 对广播的评论人姓名 ,
	 * 				   "pkuid":int 对评论的评论人id ,
	 * 				   "pkname":string 对评论的评论人姓名
	 * 				   "content":string 评论内容
	 * 				   "addtime":string 评论时间
	 * 				   "type":int 1/2   1-表示对广播的评论;2-表示对评论的评论
	 * 				  },...
	 * 				]
	 * 				voucher:{	//优惠券信息
						id:优惠券ID
	 * 					provider_id:商家ID
	 * 					full_money:满多少
	 * 					reach_money:减多少
	 *					stime:开始时间戳
	 *					etime:结束时间戳
	 * 					count:发行数量
	 * 					content:"去村里玩是怎样一种体验？\n你想象中的“田园生活”，没雾霾，空气好，吃的放心。\n戳这里，立即体验。"
	 * 					addtime:添加时间戳 int（10）
	 * 					vnumber:卡号
	 * 					receive_number:已领取数量
	 * 					validity_str:"有效期2009.02.14-2009.02.14"
	 * 					info:"全店通用；不限品类；每单仅限使用一张卡券"
	 * 				}
	 * 			}
	 * 		...]
	 * 	}
	 */
	function get__dynamics(){
		$lib = new \Modules\dynamic\lib();
		return $lib -> data($this->user);
	}
	/**
	 * <ok class="广播 (5)"/> 5.5 商家端列表
	 * 参数
	 * 	token：*
	 * 	addtime:0 //传0取最新的广播，分页取动态时传最后一条广播的addtime
	 * 	rows:20
	 * 	owner_id:  通过传递ownerid展示当前农户发布的历史广播
	 * 	is_owner: 传值即可查看本人发布的历史广播
	 * 	<font color="red">spirit_type:1	//通知公告</font>
	 * 	<font color="red">is_twlist:1	//图文列表只展示图文信息</font>
	 * 返回
	 * 	{
	 * 		data:[...
	 * 			{
	 * 				id:1	// 广播ID
	 * 				content:"content",
	 * 				attr_img:[1,2,3],//调用download接口获取图片
	 * 				first_img:"first_img",
	 * 				addtime:int(10),
	 * 				owner_id:int(11),
	 * 				addtime_label:"1分钟前",//1分钟前
	 * 				provider_name:"provider_name",
	 * 				provider_id:"provider_id",//根据商家id调用avatar接口可以获取商家头像
	 * 				count_view:0	//浏览量
	 * 				count_love:0	//点赞数
	 * 				count_comment:0	//评论数
	 * 				is_loved:0	// 0/1	未点赞/已点赞
	 * 				type:0		//谁发布的 0/1/2	商家/游客/运营人员（官方）；商家头像可点击，其它头像不可点击
	 * 				<font color="red">modal:0		//广播模型 0/1/2/3/4  短广播/长广播/运营广播/卡券广播/短视频</font>
	 * 				<font color="red">cover_image:		//短视频时,默认第一帧图片</font>
	 * 				tags:[1,2,3] //标签，commontype->dynamic_tags
	 * 				share_url:"share_url"	//分享链接
	 * 				share_title:"分享标题"	//分享标题
	 * 				share_desc:"分享描述"		//分享描述
	 * 				share_pic:"http://..."	//分享图标60*60
	 * 				share_xcx_username:string     //
	 * 				share_xcx_url:string "http://..."    //小程序分享链接
	 * 				share_xcx_title:string     //小程序分享名
	 * 				share_xcx_desc:string     //小程序分享描述
	 * 				share_xcx_pic:string     //小程序分享图片
	 * 				share_pic_weibo:"http://..."	//微博分享图标
	 * 				is_owner:0	// 0/1	该广播是否属于自己 不属于/属于
	 * 				dy_comment:广播评论[
	 * 				  {
	 * 				   "id":int 该评论id ,
	 * 				   "did":int 该广播id ,
	 * 				   "uid":int 对广播的评论人id ,
	 * 				   "name":string 对广播的评论人姓名 ,
	 * 				   "pkuid":int 对评论的评论人id ,
	 * 				   "pkname":string 对评论的评论人姓名
	 * 				   "content":string 评论内容
	 * 				   "addtime":string 评论时间
	 * 				   "type":int 1/2   1-表示对广播的评论;2-表示对评论的评论
	 * 				  },...
	 * 				]
	 * 				voucher:{	//优惠券信息
						id:优惠券ID
	 * 					provider_id:商家ID
	 * 					full_money:满多少
	 * 					reach_money:减多少
	 *					stime:开始时间戳
	 *					etime:结束时间戳
	 * 					count:发行数量
	 * 					content:"去村里玩是怎样一种体验？\n你想象中的“田园生活”，没雾霾，空气好，吃的放心。\n戳这里，立即体验。"
	 * 					addtime:添加时间戳 int（10）
	 * 					vnumber:卡号
	 * 					receive_number:已领取数量
	 * 					validity_str:"有效期2009.02.14-2009.02.14"
	 * 					info:"全店通用；不限品类；每单仅限使用一张卡券"
	 * 				}
	 * 			}
	 * 		...]
	 * 		provider(传值owner_id时显示)
	 * 		{
	 * 				id:"农户id",//根据商家id调用avatar接口可以获取商家头像
	 * 				provider_name:"provider_name",
	 * 				phone:  list [
	 * 				 {"name":"vvv","phone":"1580000000"}
	 * 				 {"name":"vvv","phone":"1580000000"}
	 * 				 {"name":"vvv","phone":"1580000000"}
	 * 				]
	 * 				provider_address: //地址
	 * 				dy_count: //当前农户发布的广播总数
	 * 		}
	 * 	}
	 */
	function user__dynamicsForSeller(){
		$lib = new \Modules\dynamic\lib();
		return $lib -> data($this->user,'seller');
	}
	/**
	 * <ok class="广播 (5)"/> 5.6 发布长广播
	 * 参数
	 * 	token：*
	 * 	type：0/1 商家/游客  默认0
	 * 	title：""
	 * 	first_img:""
	 * 	describe：描述信息
	 * 	<s>attr_img:[{"file_id":12,"info":"xxxxxxx"}]	//file_id 图片附件</s>
	 * 	attr:[...
	 * 		{"file_id":file_id}, //file_id 图片附件ID
	 * 		{"info":"xxxxxxxxxxxxxxxx"},
	 * 		{"file_id":file_id}, //file_id 图片附件ID
	 * 		{"info":"xxxxxxxxxxxxxxxx"},
	 * 		{"info":"xxxxxxxxxxxxxxxx"},
	 * 	...]
	 * 	tags:1,2,3		//标签 commontype->dynamic_tags
	 * 	tpl:"默认"		//模板名称
	 * 	address:"当前位置"		//当前位置
	 * 	lat:"纬度"		//纬度
	 * 	lng:"经度"		//经度
	 * 	cogradient:"同步到"		//room-房间,food-美食,activity-活动,product-特产
	 * 返回
	 * 	data:{
	 *		"id":"1" //广播ID，
	 * 		"share_url":"http://..."		//分享链接
	 * 		"share_pic":"http://..."		//分享图标60*60
	 * 		"share_pic_weibo":"http://..."		//微博分享图标
	 * 		"share_longpic":"http://..."	//分享长图
	 * 		"share_title":"分享标题"			//农户名
	 * 		"share_desc":"分享描述"			//标题
	 * 		"share_xcx_username":string     //
	 * 		"share_xcx_url":string "http://..."    //小程序分享链接
	 * 		"share_xcx_name":string     //小程序分享名
	 * 		"share_xcx_desc":string     //小程序分享描述
	 * 		"share_xcx_pic":string     //小程序分享图片
	 * 	}
	 */
	function user__dynamicUpdateLong(){
		$lib = new \Modules\dynamic\lib();
		return $lib -> updateLong($this->user);
	}

	/**
	 * <ok class="广播 (5)"/> 5.6-1 发布图文
	 * 参数
	 * 	token：*
	 * 	id：*(当需要修改时传递该参数)
	 * 	type：0/1 商家/游客  默认0
	 * 	title：""
	 * 	attr_tw:[...
	 * 		{"x_image_id":x_image_id,"info":"xxxxxxx"}, //x_image_id 图片附件ID
	 * 		{"x_image_id":x_image_id,"info":"xxxxxxx"}, //x_image_id 图片附件ID
	 * 		{"x_image_id":x_image_id,"info":"xxxxxxx"}, //x_image_id 图片附件ID
	 * 	...]
	 * 	tags:1,2,3		//标签 commontype->dynamic_tags
	 * 	tpl:"默认"		//模板名称
	 * 	address:"当前位置"		//当前位置
	 * 	lat:"纬度"		//纬度
	 * 	lng:"经度"		//经度
	 * 	cogradient:"同步到"		//room-房间,food-美食,activity-活动,product-特产
	 * 返回
	 * 	data:{
	 *		"id":"1" //广播ID，
	 * 		"share_url":"http://..."		//分享链接
	 * 		"share_pic":"http://..."		//分享图标60*60
	 * 		"share_pic_weibo":"http://..."		//微博分享图标
	 * 		"share_longpic":"http://..."	//分享长图
	 * 		"share_title":"分享标题"			//农户名
	 * 		"share_desc":"分享描述"			//标题
	 * 		"share_xcx_username":string     //
	 * 		"share_xcx_url":string "http://..."    //小程序分享链接
	 * 		"share_xcx_name":string     //小程序分享名
	 * 		"share_xcx_desc":string     //小程序分享描述
	 * 		"share_xcx_pic":string     //小程序分享图片
	 * 	}
	 */
	function user__ImageTextLong(){
		$lib = new \Modules\dynamic\lib();
		return $lib -> ImageTextLong($this->user);
	}

	/**
	 * <ok class="广播 (5)"/> 5.7 广播详细数据
	 * 参数
	 * 	token：*
	 * 	id:广播 ID
	 * 返回
	 * 	{
	 * 		data:{
	 * 				id:1	// 广播ID
	 * 				title:"",
	 * 				first_img:1	//file_id 调用download接口获取图片
	 * 				describe:"描述信息",
	 * 				attr:[
	 * 					...
	 * 					{"type":"img","data":file_id} //通过file_id调用download接口获取图片
	 * 					{"type":"text","data":"文字信息"}
	 * 					...
	 * 				],
	 * 				attr_tw:[
	 * 					...
	 * 					{"aid":"img","info":文字信息,"order":排序} //通过aid调用download接口获取图片
	 * 					{"aid":"img","info":文字信息,"order":排序}
	 * 					...
	 * 				],
	 * 				cogradient:"同步到"		//room-房间,food-美食,activity-活动,product-特产,若未同步过则为空
	 * 				addtime:int(10),
	 * 				addtime_label:"1分钟前",//1分钟前
	 * 				provider_name:"provider_name",
	 * 				provider_id:"provider_id",//根据商家id调用avatar接口可以获取商家头像
	 * 				type:0		//谁发布的 0/1/2	商家/游客/运营人员（官方）；商家头像可点击，其它头像不可点击
	 * 				<font color="red">modal:0		//广播模型 0/1/2/3/4  短广播/长广播/运营广播/卡券广播/短视频</font>
	 * 				<font color="red">cover_image:		//短视频时,默认第一帧图片</font>
	 *				tags:[1,2,3] 	//选中的标签
	 * 				dynamic_long：{
	 * 					tpl_name: "默认", //模板名称
	 * 					aid: "0", 	//如果已经生成长图，aid将大于0
	 * 					retry: "0"	//生成长图可能失败，这里表示错误的次数，大于3将不会在生成长图
	 * 				}
	 * 				share_title:"丰居农家院"
	 * 				share_url:"http://...."		//长文链接
	 * 				share_desc:"长文描述"			//长文描述
	 * 				share_pic:"http://..."		//分享图标60*60
	 * 				share_xcx_username:string     //
	 * 				share_xcx_url:string "http://..."    //小程序分享链接
	 * 				share_xcx_name:string     //小程序分享名
	 * 				share_xcx_desc:string     //小程序分享描述
	 * 				share_xcx_pic:string     //小程序分享图片
	 * 				share_pic_weibo:"http://..."		//微博分享图标
	 * 				share_longpic:"http://..."	//分享长图
	 * 				webview_url:"http://..."	//webview 地址
	 * 				count_view:0	//浏览量
	 * 				count_love:0	//点赞数
	 * 				count_comment:0	//评论数
	 * 				is_loved:0	// 0/1	未点赞/已点赞
	 * 				is_owner:0	// 0/1	该广播是否属于自己 不属于/属于
	 * 				<font color="red">comment:[...{}...]	//评论列表，请参考广播评论列表data.list结构
	 * 				love:[...{ //点赞列表
						id:1				//点赞id
	 * 					did:1				//广播id
	 * 					uid:1				//用户id
	 * 					name:1				//姓名
	 * 					addtime:"m-d h:i" 	//时间
	 * 				}...]</font>
	 * 				voucher:{	//优惠券信息
						id:优惠券ID
	 * 					provider_id:商家ID
	 * 					full_money:满多少
	 * 					reach_money:减多少
	 *					stime:开始时间戳
	 *					etime:结束时间戳
	 * 					count:发行数量
	 * 					content:"去村里玩是怎样一种体验？\n你想象中的“田园生活”，没雾霾，空气好，吃的放心。\n戳这里，立即体验。"
	 * 					addtime:添加时间戳 int（10）
	 * 					vnumber:卡号
	 * 					receive_number:已领取数量
	 * 					validity_str:"有效期2009.02.14-2009.02.14"
	 * 					info:"全店通用；不限品类；每单仅限使用一张卡券"
	 * 				}
	 * 			}
	 * 	}
	 */
	function get__dynamicInfo(){
		$lib = new \Modules\dynamic\lib();
		return $lib -> dynamicContent($this->user);
	}
	/**
	 * <ok class="广播 (5)"/> 5.8 获取广播长图
	 * 参数
	 * 	token：*
	 * 	id:广播 ID
	 * 返回
	 * 	图片
	 */
	function get__dynamicPic(){
		$lib = new \Modules\dynamic\lib();
		return $lib -> dynamicPic($this->user);
	}
	/**
	 * <ok class="广播 (5)"/> 5.9 评论列表
	 * 参数
	 * 	token：*
	 * 	id：广播id
	 * 返回
	 * 	{
	 * 		data:{
	 *            list:[
	 * 			  {
	 * 				 "id":int 该评论id ,
	 * 				 "did":int 该广播id ,
	 * 				 "uid":int 评论人id ,
	 * 				 "pid":int 评论人的农户id ,
	 * 				 "name":string 评论人姓名 ,
	 * 				 "is_provider":string 评论人是否为农户 1-是,0-否 ,
	 * 				 "pkuid":int @对象的id ,
	 * 				 "pkname":string @对象的姓名 ,
	 * 				 "content":string 评论内容 ,
	 * 				 "addtime":string 评论时间 ,
	 * 				 "comment_love":string 评论点赞数量 ,
	 * 				 "type":int 1/2   1-表示对广播的评论;2-表示对评论人的评论
	 * 				 "is_love":int 0/1 当前用户是否对该评论点赞  否/是
	 * 				 "is_owner":int 0/1 该评论是否属于自己  否/是
	 * 				 "children":[...,{子评论结构},...] //子评论结构 与 父级评论结构一样
	 * 			  }
	 * 		    ...]
	 *          count_love: 广播点赞数
	 *          count_comment: 广播评论数
	 *          count_view: 广播浏览数
	 *      }
	 * 	}
	 */
	function get__dynamicsCommentList(){
		$lib = new \Modules\dynamic\lib();
		return $lib -> commentList($this->user);
	}
	/**
	 * <ok class="广播 (5)"/> 5.10 发布评论
	 * 参数
	 * 	token：*
	 * 	did：广播id
	 * 	content：内容
	 * 	pkcid：@评论的id(当对评论进行评论时使用)
	 * 	pkuid: @对象的uid(当对评论进行评论时使用)
	 * 返回
	 * 	{
	 * 		data:{
	 * 			"id":int 该评论id ,
	 * 			}
	 *
	 * 	}
	 */
	function user__dynamicsAddComment(){
		$lib = new \Modules\dynamic\lib();
		return $lib -> addComment($this->user);
	}
	/**
	 * <ok class="广播 (5)"/> 5.11 评论点赞
	 * 参数
	 * 	token：*
	 * 	cid：评论id
	 * 返回
	 * 	{
	 * 		data: 1-点赞成功, 0-取消点赞
	 * 	}
	 */
	function user__dynamicsCommentLove(){
		$lib = new \Modules\dynamic\lib();
		return $lib -> commentLove($this->user);
	}
	/**
	 * <ok class="广播 (5)"/> 5.12 删除评论
	 * 参数
	 * 	token：*
	 * 	id：评论id
	 */
	function user__dynamicsCommentDelete(){
		$lib = new \Modules\dynamic\lib();
		return $lib -> commentDelete($this->user);
	}

	function wechatMessage(){
		$lib = new \Modules\dynamic\lib();
		return $lib -> wechatMessage();
	}

	/**
	 * <ok class="广播 (5)"/> 5.13 "广播标签"汇总信息
	 * 参数
	 * 	token:
	 * 返回
	 * 	"data":{
	 *		owner(农户标签)
	 *		[{
	 *			id:	//标签
	 *			name:	//标签名称
	 *		}]
	 *		system(系统标签)
	 *		[{
	 *			id:	//标签
	 *			name:	//标签名称
	 *		}]
	 *		all(系统标签)
	 *		[{
	 *			id:	//标签
	 *			name:	//标签名称
	 *			is_system:	//0-个人标签  1-系统标签
	 *		}]
	 * 	}
	 */
	function user__dcommontag(){
		$lib = new \Modules\dynamic\lib();
		return $lib -> dcommontag($this->user);
	}

	/**
	 * <ok class="广播 (5)"/> 5.14 添加广播标签
	 * 参数
	 * 	token:
	 * 	name:标签名称
	 * 返回
	 * 	"data":{
	 * 		id:	//标签id
	 * 	}
	 */
	function user__dtagadd(){
		$lib = new \Modules\dynamic\lib();
		return $lib -> dtagadd($this->user);
	}

	############################################################
	# 内容/广播/动态 接口API (5) over
	############################################################
	############################################################
	# 抵用券(6)开始
	############################################################
	/**
	 * <ok class="抵用券管理(6)"/> 6.1增加抵用券
	 * 参数
	 * 	token：
	 * 	id:抵用卷ID，修改时需要传递此ID
	 * 	full_money:	满多钱
	 * 	reach_money:减多钱
	 * 	stime:int(10) 开始时间 10位时间戳
	 * 	etime:int(10) 结束时间 10位时间戳
	 * 	count:int 发行量
	 * 	type:int 0-通用券,1-住宿券,2-美食券,3-采摘券,4-门票券,5-其他
	 * 	content:string 券说明文字
	 * 返回
	 * 	data:{
	 *		"id":"1" //卡券ID，
	 * 		"share_url":"http://..."	//分享链接
	 * 		"share_title":"分享标题"		//标题
	 * 		"share_desc":"分享描述"		//内容
	 * 		"share_pic":"http://..."	//分享图标60*60
	 * 		"share_xcx_username":string     //
	 * 		"share_xcx_url":string "http://..."    //小程序分享链接
	 * 		"share_xcx_name":string     //小程序分享名
	 * 		"share_xcx_desc":string     //小程序分享描述
	 * 		"share_xcx_pic":string     //小程序分享图片
	 * 		"share_pic_weibo":"http://..."	//微博分享图标
	 * 	}
	 */
	function user__vouchersUpdate() {
		$lib = new \Modules\vouchers\lib();
		return $lib -> update($this->user);
	}
	/**
	 * <ok class="抵用券管理(6)"/> 6.2领取抵用券
	 * 参数
	 * 	token：
	 * 	voucher_id:	抵用券ID
	 * 返回
	 * 	data:{
	 *		"id":"1" //， 领取id
	 *		"vid":"1" //，卡券id
	 * 		"share_url":"http://..."	//分享链接
	 * 		"share_title":"分享标题"		//标题
	 * 		"share_desc":"分享描述"		//内容
	 * 		"share_pic":"http://..."	//分享图标60*60
	 * 		"share_xcx_username":string     //
	 * 		"share_xcx_url":string "http://..."    //小程序分享链接
	 * 		"share_xcx_name":string     //小程序分享名
	 * 		"share_xcx_desc":string     //小程序分享描述
	 * 		"share_xcx_pic":string     //小程序分享图片
	 * 		"share_pic_weibo":"http://..."	//微博分享图标
	 * 	}
	 */
	function user__vouchersReceive() {
		$lib = new \Modules\vouchers\lib();
		return $lib -> receive($this->user);
	}
	/**
	 * <ok class="抵用券管理(6)"/> 6.3订单支付时获取可用抵用券
	 * 参数
	 * 	token：
	 * 	order_id:	订单ID
	 * 返回
	 * 	data:{
	 * 		id:1	//抵用卷ID，支付时传递给支付接口
	 * 		full_money:"100.00" //满多少
	 * 		reach_money:"10.00"	//减多少
	 * 	}
	 */
	function user__vouchersForOrder() {
		$lib = new \Modules\vouchers\lib();
		return $lib -> getVoucher($this->user);
	}
	/**
	 * <ok class="抵用券管理(6)"/> 6.4游客获取已领取的抵用卷
	 * 参数
	 * 	token：
	 * 	provider_id：传递农户id,返回在该农户下获取的已领取的抵用券
	 * 	invalid：0-失效券
	 *  offset：分页开始位置，默认0
	 *  rows:从开始位置读取多少行，默认10
	 * 返回
	 * 	data:[{
	 * 		id:1	//用户领卷ID
	 * 		id:1	//卡券ID
	 * 		vnumber:	//卡券卡号
	 * 		code_url:	//生成二维码规则
	 * 		qrcode_url:	//生成二维码链接
	 * 		full_money:"100.00" //满多少
	 * 		reach_money:"10.00"	//减多少
	 * 		stime:int(10) 	//开始时间
	 * 		etime:int(10)	//结束时间
	 * 		content:string	//抵用券描述
	 * 		provider_name：商家名
	 * 		provider_id:商家ID
	 * 		share_url："http://..."	//分享链接
	 * 		share_title："分享标题"		//标题
	 * 		share_desc："分享描述"		//内容
	 * 		share_pic："http://..."	//分享图标60*60
	 * 		share_xcx_username:string     //
	 * 		share_xcx_url:string "http://..."    //小程序分享链接
	 * 		share_xcx_name:string     //小程序分享名
	 * 		share_xcx_desc:string     //小程序分享描述
	 * 		share_xcx_pic:string     //小程序分享图片
	 * 		share_pic_weibo："http://..."	//微博分享图标
	 * 		vstatus: 4-已过期,5-已使用(只有失效券才会体现)
	 * 	}]
	 */
	function user__vouchersHave() {
		$lib = new \Modules\vouchers\lib();
		return $lib -> vouchersHave($this->user);
	}
	/**
	 * <ok class="抵用券管理(6)"/> 6.5游客获取未领取的抵用卷
	 * 参数
	 * 	token：
	 * 返回
	 * 	data:[{
	 * 		id:1	//抵用卷ID，领取时需要传递此ID
	 * 		full_money:"100.00" //满多少
	 * 		reach_money:"10.00"	//减多少
	 * 		stime:int(10) 	//开始时间
	 * 		etime:int(10)	//结束时间
	 * 		content:string	//抵用券描述
	 * 		provider_name：商家名
	 * 		provider_id:商家ID
	 * 	}]
	 */
	function user__vouchersUnHave() {
		$lib = new \Modules\vouchers\lib();
		return $lib -> vouchersUnHave($this->user);
	}
	/**
	 * <ok class="抵用券管理(6)"/> 6.6商家已发布抵用卷列表
	 * 参数
	 * 	token：
	 * 	provider_id：当传递农户id时,返回在该农户下获取的已领取的抵用券
	 * 	invalid：0-失效券
	 *  offset：分页开始位置，默认0
	 *  rows:从开始位置读取多少行，默认10
	 * 返回
	 * 	data:[{
	 * 		id:1	//抵用卷ID
	 * 		full_money:"100.00" //满多少
	 * 		reach_money:"10.00"	//减多少
	 * 		stime:int(10) 	//开始时间
	 * 		etime:int(10)	//结束时间
	 * 		content:string	//抵用券描述
	 * 		count：int 发行数量
	 * 		receive_num:已被领取数量
	 * 		addtime：string 添加时间
	 * 		share_url："http://..."	//分享链接
	 * 		share_title："分享标题"		//标题
	 * 		share_desc："分享描述"		//内容
	 * 		share_pic："http://..."	//分享图标60*60
	 * 		share_xcx_username:string     //
	 * 		share_xcx_url:string "http://..."    //小程序分享链接
	 * 		share_xcx_name:string     //小程序分享名
	 * 		share_xcx_desc:string     //小程序分享描述
	 * 		share_xcx_pic:string     //小程序分享图片
	 * 		share_pic_weibo："http://..."	//微博分享图标
	 * 		vstatus：1-已过期   2-已停用 (只有失效券才会体现)
	 * 		provider_name：商家名
	 * 		provider_id:商家ID
	 * 	}]
	 */
	function user__vouchers() {
		$lib = new \Modules\vouchers\lib();
		return $lib -> vouchers($this->user);
	}
	/**
	 * <ok class="抵用券管理(6)"/> 6.7游客查看抵用券详情
	 * 参数
	 * 	token：
	 * 	voucher_id：抵用券的id
	 * 返回
	 * 	data:{
	 * 		id:1	//抵用卷ID
	 * 		full_money:"100.00" //满多少
	 * 		reach_money:"10.00"	//减多少
	 * 		stime:int(10) 	//开始时间
	 * 		etime:int(10)	//结束时间
	 * 		content:string	//抵用券描述
	 * 		count：int //发行数量
	 * 		receive_num: //已被领取数量
	 * 		addtime：string //添加时间
	 * 		vstatus：//游客操作卡券状态  0-立即领取 1-已领取 2-已领完 3-已停用 4-已过期
	 * 		provider_id： //农户id
	 * 		share_url："http://..."	//分享链接
	 * 		share_title："分享标题"		//标题
	 * 		share_desc："分享描述"		//内容
	 * 		share_pic："http://..."	//分享图标60*60
	 * 		share_xcx_username:string     //
	 * 		share_xcx_url:string "http://..."    //小程序分享链接
	 * 		share_xcx_name:string     //小程序分享名
	 * 		share_xcx_desc:string     //小程序分享描述
	 * 		share_xcx_pic:string     //小程序分享图片
	 * 		share_pic_weibo："http://..."	//微博分享图标
	 * 	}
	 */
	function get__voucherone() {
		$lib = new \Modules\vouchers\lib();
		return $lib -> voucherone($this->user);
	}
	/**
	 * <ok class="抵用券管理(6)"/> 6.7-1商户端扫码或输入卡号显示抵用券详情
	 * 参数
	 * 	token：
	 * 	keyword：传vnumber或者code_url获取卡券详情(卡券卡号:vnumber;二维码扫描:code_url)
	 * 返回
	 * 	data:{
	 * 		id:1	//用户领卷ID
	 * 		vid:1	//抵用券ID
	 * 		uid:1	//领券用户ID
	 * 		unick:1	//领券用户昵称
	 * 		provider_id： //发券农户id
	 * 		full_money:"100.00" //满多少
	 * 		reach_money:"10.00"	//减多少
	 * 		stime:int(10) 	//开始时间
	 * 		etime:int(10)	//结束时间
	 * 		content:string	//抵用券描述
	 * 		count：int //发行数量
	 * 		receive_num: //已被领取数量
	 * 		addtime：string //添加时间
	 * 		vstatus：//卡券状态   1-未使用  5-已使用
	 * 		vnumber： //卡券卡号
	 * 		share_url："http://..."	//分享链接
	 * 		share_title："分享标题"		//标题
	 * 		share_desc："分享描述"		//内容
	 * 		share_pic："http://..."	//分享图标60*60
	 * 		share_xcx_username:string     //
	 * 		share_xcx_url:string "http://..."    //小程序分享链接
	 * 		share_xcx_name:string     //小程序分享名
	 * 		share_xcx_desc:string     //小程序分享描述
	 * 		share_xcx_pic:string     //小程序分享图片
	 * 		share_pic_weibo："http://..."	//微博分享图标
	 * 	}
	 */
	function user__voucherones() {
		$lib = new \Modules\vouchers\lib();
		return $lib -> voucherones($this->user);
	}
	/**
	 * <ok class="抵用券管理(6)"/> 6.8获取已领取的此商家抵用券,按消费金额匹配卡券列表
	 * 参数
	 * 	token：
	 * 	pid：商家id
	 *	money:消费金额
	 * 返回
	 * 	data:[{
	 * 		id:1 //消费者获取抵用券时生成的id
	 * 		full_money:"100.00" //满多少
	 * 		reach_money:"10.00"	//减多少
	 * 		stime:int(10) 	//开始时间
	 * 		etime:int(10)	//结束时间
	 * 		content:string	//抵用券描述
	 * 		count：int 发行数量
	 * 		receive_num:已被领取数量
	 * 		addtime：string 添加时间
	 * 		provider_id：int 商家id
	 * 		provider_name：int 商家名称
	 * 	}]
	 */
	function user__pvoucher() {
		$lib = new \Modules\vouchers\lib();
		return $lib -> pvoucher($this->user);
	}

	/**
	 * <ok class="抵用券管理(6)"/> 6.9商户确认使用卡券
	 * 参数
	 * 	token：
	 * 	voucher_id：抵用券id
	 * 返回
	 * 	{
	 * 		data: 1--使用成功
	 * 	}
	 */
	function user__voucherconfirm() {
		$lib = new \Modules\vouchers\lib();
		return $lib -> voucherconfirm($this->user);
	}

	/**
	 * <ok class="抵用券管理(6)"/> 6.10商户停用卡券
	 * 参数
	 * 	token：
	 * 	voucher_id：抵用券id
	 * 返回
	 * 	{
	 * 		data: 1--停用成功
	 * 	}
	 */
	function user__voucherstop() {
		$lib = new \Modules\vouchers\lib();
		return $lib -> voucherstop($this->user);
	}

	/**
	 * <ok class="抵用券管理(6)"/> 6.11游客端查看所有卡券(有效期内)
	 * 参数
	 * 	token：
	 * 	offset：分页开始位置，默认0
	 * 	rows:从开始位置读取多少行，默认20
	 * 返回
	 * 	data:[{
	 * 		id:1	//卡券ID
	 * 		provider_id:商家ID
	 * 		provider_name：商家名
	 * 		full_money:"100.00" //满多少
	 * 		reach_money:"10.00"	//减多少
	 * 		stime:int(10) 	//开始时间
	 * 		etime:int(10)	//结束时间
	 * 		content:string	//抵用券描述
	 * 		is_receive: 0-未领取,1-已领取
	 * 	}]
	 */
	function user__vouchersForUser() {
		$lib = new \Modules\vouchers\lib();
		return $lib -> vouchersForUser($this->user);
	}

	/**
	 * <ok class="抵用券管理(6)"/> 6.12游客领取卡券详情页
	 * 参数
	 * 	token：
	 * 	pid：当前农户的id
	 * 返回
	 * 	data:{
	 * 		provider_id： //农户id
	 * 		provider_name： //农户名称
	 * 		provider_avatar： //农户头像
	 * 		provider_voucher (当前农户发布的卡券) [{
	 * 		  id:1	//抵用卷ID
	 * 		  full_money:"100.00" //满多少
	 * 		  reach_money:"10.00"	//减多少
	 * 		  stime:int(10) 	//开始时间
	 * 		  etime:int(10)	//结束时间
	 * 		  content:string	//抵用券描述
	 * 		  count：int //发行数量
	 * 		  addtime：string //添加时间
	 * 		  is_receive：//游客操作卡券状态  0-未领取 1-已领取 2-已领完 3-已停用 4-已过期
	 * 		}]
	 * 		recommend_voucher (其他农户发布的卡券) [{
	 * 		  provider_name:	//农户名称
	 * 		  id:1	//抵用卷ID
	 * 		  full_money:"100.00" //满多少
	 * 		  reach_money:"10.00"	//减多少
	 * 		  stime:int(10) 	//开始时间
	 * 		  etime:int(10)	//结束时间
	 * 		  content:string	//抵用券描述
	 * 		  count：int //发行数量
	 * 		  addtime：string //添加时间
	 * 		  is_receive：//游客操作卡券状态  0-未领取 1-已领取 2-已领完 3-已停用 4-已过期
	 * 		}]
	 * 	}
	 */
	function user__voucherhaveone() {
		$lib = new \Modules\vouchers\lib();
		return $lib -> voucherhaveone($this->user);
	}

	############################################################
	# 抵用券(6)结束
	############################################################

	############################################################
	# 订单接口(7) start
	############################################################
	/**
	 * <ok class="订单管理(7)"/> 7.0创建订单
	 * 参数
	 * 	token:
	 * 	data:jsonstr
	 * 		{
	 * 		"room":[{"id":1,"num":"1","stime":"YYYY-MM-DD","etime":"YYYY-MM-DD"}
	 * 				,{"id":2,"num":"1","stime":"YYYY-MM-DD","etime":"YYYY-MM-DD"}]
	 * 		,"food":[{"id":1,"num":"1"},{"id":2,"num":"1"}]
	 * 		,"product":[{"id":1,"num":"1","spec_id":"规格ID"},{"id":2,"num":"1","spec_id":"规格ID"}],
	 * 		}
	 * address_id:address_id	//用户地址ID
	 * 返回
	 * 		{
	 * 			code:200,
	 * 			data:{
	 * 				//返回数据格式请参考获取订单详细接口
	 * 			}
	 * 		}
	 */
	function user__orderCreate() {
		$lib = new \Modules\order\order_lib();
		return $lib -> create($this -> user);
	}

	/**
	 * <ok class="订单管理(7)"/> <font color="red">7.0-1创建订单(预定)</font>
	 * 参数
	 * 	token：
	 * 	provider_id：预定农户id
	 * 	btime:int(10) 预定时间 10位时间戳
	 * 	count:int 人数
	 * 	rename:string 预定人姓名
	 * 	resex:int  0-女  1-男
	 * 	rephone:string 预定人电话
	 * 	desc:string 备注
	 * 返回
	 * 	data:{
	 *		//返回数据格式请参考获取订单详细接口
	 * 	}
	 */
	function user__corderCreate() {
		$lib = new \Modules\order\order_lib();
		return $lib -> createone($this -> user);
	}

	/**
	 * <ok class="订单管理(7)"/> <font color="red">7.0-2创建订单(到店付)</font>
	 * 参数
	 * 	token：
	 * 	provider_id: "1",
	 * 	total_price: "220.00",
	 * 返回
	 * 	data:{
	 *		//返回数据格式请参考获取订单详细接口
	 * 	}
	 */
	function user__porderCreate() {
		$lib = new \Modules\order\order_lib();
		return $lib -> createtwo($this -> user);
	}

	/**
	 * <ok class="订单管理(7)"/> <font color="red">7.0-3创建订单(点菜)</font>
	 * 参数
	 * 	token：
	 * 	provider_id: "1",
	 * 	dno: "1",
	 * 	desc: "",
	 * 返回
	 * 	data:{
	 *		//返回数据格式请参考获取订单详细接口
	 * 	}
	 */
	function user__cookorderCreate() {
		$lib = new \Modules\order\order_lib();
		return $lib -> createthree($this -> user);
	}

	/**
	 * <ok class="订单管理(7)"/> 7.1查看订单详情
	const ORDER_STATUS_DCL=0;//'待处理';
	const ORDER_STATUS_YQR=1;//'已确认';
	const ORDER_STATUS_YZF=2;//'游客已支付/商家待确认';
	const ORDER_STATUS_KSS=3;//'开始派送';
	const ORDER_STATUS_UQX=4;//'用户取消';
	const ORDER_STATUS_SQX=5;//商家取消;
	const ORDER_STATUS_YSX=6;//订单失效;
	const ORDER_STATUS_YWC=7;//已完成（消费期结束）;
	 * 参数
	 * 	token：
	 * 	order_id:int
	 * 返回
	{
	"data":
	  {
		"id": "6",
		"no": "2016051112155070275",
		"app_user_id": "12",
		"provider_id": "1",
		"total_price": "220.00",
		"pay_price": "220.00",
		"addtime": "1462934828",
	 * 	<font color="red">"payment_status":int 1	1为已付款 </font>
	 * 	<font color="red">"payment":[{支付对象}]</font>
		<font color="red">"desc": "描述",</font>
		<font color="red">"model": "类型",(0-预定,1-到店付,2-果蔬)</font>
		<font color="red">"overtime": "倒计时"(若在十分钟以内,显示具体秒数,若在十分钟以外,显示支付超时文字)</font>
		<font color="red">"replytime": "等待商户回复时间",</font>
		<font color="red">"overstatus":{"info": 订单状态描述, "operate": "操作按钮(文字)", "opstatus": "操作按钮(数字)"}</font>
		"status": "0",
		"provider_name": "xxx",
		"provider_address": "\u5317\u4eac\u5e02\u5e73\u8c37\u533a\u9547\u7f57\u8425\u73bb\u7483\u53f0\u65b0\u6751",
		"user_phone": "15877556369",
		"user_name": "",
		"action": [{"uid": "9", "step": "0", "addtime": "1463452898", "info": "用户下单", "reamount": "退款金额","refuse": "备注/拒绝理由"}],
		"address": {"name":"name","province":"province","city":"city","district":"district","address":"address","phone":"phone","post_code":"post_code"},
		"goods": [{
			"target":"room",
			"target_id":1,
			"name": "\u5927\u5e8a\u623f",
			"avatar": "http:\/\/127.0.0.1\/colorcun\/wwwroot\/\/upload\/rc\/bolitai\/room\/kindsize.jpg",
			"price": "120.00",
			"count": "1"
			"spec_num": "120.00",
			"spec_unit": "斤"
			<font color="red">"begin_time": "预约时间"</font>
			<font color="red">"reservename": "预约人姓名"</font>
			<font color="red">"reservesex": "预约人性别  0-女 1-男",</font>
			<font color="red">"reservephone": "预约人电话"</font>
			<font color="red">"btimeone": "2017年01月11日",</font>
			<font color="red">"btimetwo": "星期三"</font>
			<font color="red">"btimethree": "12:55"</font>

			<font color="red"> "cookbook":{</font>
			<font color="red">  "provider_id":"1",</font>
			<font color="red">  "dno":"1",</font>
			<font color="red">  "cno":"1001",</font>
			<font color="red">  "addtime":"1491031991",</font>
			<font color="red">  "status":"1"</font>
			<font color="red">  "is_cost":"1"</font>
			<font color="red"> },</font>
			<font color="red"> "cooknum":"3",</font>
			<font color="red"> "cookprice":"210.00",</font>
			<font color="red"> "cookbooklist":[</font>
			<font color="red">  {</font>
			<font color="red">  "name":"西红柿",</font>
			<font color="red">  "num":"1",</font>
			<font color="red">  "price":"12.00"</font>
			<font color="red">  },</font>
			<font color="red">  {</font>
			<font color="red">  "name":"西湖牛肉羹",</font>
			<font color="red">  "num":"2",</font>
			<font color="red">  "price":"99.00"</font>
			<font color="red">  }</font>
			<font color="red"> ],</font>
			},
			{
			"target":"room",
			"target_id":2,
			"name": "\u53cc\u4eba\u95f4",
			"avatar": "http:\/\/127.0.0.1\/colorcun\/wwwroot\/\/upload\/rc\/bolitai\/room\/double.jpg",
			"price": "100.00",
			"count": "1"
		}]
	},
	"message": "",
	"provider_flag": "1",
	"code": 200
	}
	 */
	function user__orderInfo() {
		$lib = new \Modules\order\order_lib();
		return $lib -> info($this -> user);
	}

	/**
	 * <ok class="订单管理(7)"/> 7.2查看订单列表
	 * 参数
	 * 	token：
	 * 	type：1/2 查看自己/商家查看用户订单，默认1
	 * 	status：0/1/2/3，全部/待确认/待付款/已完成，默认0
	 * 	offset:分页开始位置，默认0
	 * 	rows：从开始位置读取多少行，默认10
	 * 返回
	{
	"data": [
	{
	"id": "3",
	"no": "2016051012116510995",
	"status": "0",	//具体值参考订单详情status的定义
	"addtime": "1462934828",
	<font color="red">"provider_id": "农户id"(可获取农户头像)</font>
	<font color="red">"app_user_id": "游客id"(可获取游客头像)</font>
	<font color="red">"total_price": "应付金额",</font>
	<font color="red">"pay_price": "实付金额",</font>
	<font color="red">"desc": "描述",</font>
	<font color="red">"model": "类型",(0-预定,1-到店付,2-果蔬)</font>
	<font color="red">"overstatus":{"info": 订单状态描述, "operate": "操作按钮(文字)", "opstatus": "操作按钮(数字)"}</font>
	"provider_name": "",
	"provider_address": "",
	"user_phone": "",
	"user_name": "",
	"goods": [
		{
		"target":"room",
		"target_id":1,
		"name": "\u5927\u5e8a\u623f",
		"avatar": "http:\/\/127.0.0.1\/colorcun\/wwwroot\/\/upload\/rc\/bolitai\/room\/kindsize.jpg",
		"price": "120.00",
		"count": "1"
		"spec_num": "120.00",
		"spec_unit": "斤"
		<font color="red">"begin_time": "预约时间"</font>
		<font color="red">"reservename": "预约人姓名"</font>
		<font color="red">"reservesex": "预约人性别  0-女 1-男",</font>
		<font color="red">"reservephone": "预约人电话"</font>
		<font color="red">"btimeone": "2017年01月11日",</font>
		<font color="red">"btimetwo": "星期三"</font>
		},
		{
		"target":"room",
		"target_id":2,
		"name": "\u53cc\u4eba\u95f4",
		"avatar": "http:\/\/127.0.0.1\/colorcun\/wwwroot\/\/upload\/rc\/bolitai\/room\/double.jpg",
		"price": "100.00",
		"count": "1"
		},
		{
		"target":"room",
		"target_id":3,
		"name": "\u70e4\u5168\u7f8a",
		"avatar": "http:\/\/127.0.0.1\/colorcun\/wwwroot\/\/upload\/rc\/bolitai\/food\/kqy.jpg",
		"price": "500.00",
		"count": "1"
		},
		{
		"target":"room",
		"target_id":4,
		"name": "\u73bb\u7483\u5bb4",
		"avatar": "http:\/\/127.0.0.1\/colorcun\/wwwroot\/\/upload\/rc\/bolitai\/food\/boliyan.jpg",
		"price": "1260.00",
		"count": "1"
		}]
	},

	],
	"provider_flag": "1",
	"code": 200
	}
	 *
	 */
	function user__orders() {
		//money_log(array('target'=>'app_user','target_id'=>1,'money'=>'100','order_id'=>3123),'+');
		$lib = new \Modules\order\order_lib();
		return $lib -> orders($this -> user);
	}

	/**
	 * <ok class="订单管理(7)"/> 7.3商家确认用户订单
	 * 参数
	 * 	token：
	 * 	order_id:
	 * 	<font color="red">info:确认理由</font>
	 * 返回
	 * {
	 * 	data:""
	 * }
	 */
	function user__orderConfirm() {
		$lib = new \Modules\order\order_lib();
		return $lib -> confirm($this -> user);
	}

	/**
	 * <ok class="订单管理(7)"/> 7.4取消/拒绝用户订单
	 * 参数
	 * 	token：
	 * 	order_id:
	 * 	<font color="red">info:拒绝理由</font>
	 * 返回
	 * {
	 * 	data:""
	 * }
	 */
	function user__orderCancel() {
		$lib = new \Modules\order\order_lib();
		return $lib -> cancel($this -> user);
	}
	/**
	 * <ok class="订单管理(7)"/> 7.5删除用户订单
	 * 参数
	 * 	token：
	 * 	order_id:
	 * 	type:1/2 游客删除/商户删除
	 * 返回
	 * {
	 * 	data:""
	 * }
	 */
	function user__orderDelete() {
		$lib = new \Modules\order\order_lib();
		return $lib -> delete($this -> user);
	}
	/**
	 * <ok class="订单管理(7)"/> 7.6用户进行订单支付
	 * 参数
	 * 	token：
	 * 	order_id:
	 * 	<font color="red">type:int 0/2/4  alipay/wx/upacp  默认0,服务端自动识别 app/web
	 * 		如果明确知道自己的客户端使用的渠道，请直接传渠道channel属性值（参考 https://www.pingxx.com/api#支付渠道属性值）</font>
	 * 	voucher_id:0 //抵用卷ID
	 * 返回
	 * {
	 * 	data:charge
	 * }
	 */
	function user__pay() {
		$lib = new \Modules\order\order_lib();
		return $lib -> pay($this -> user);
	}

	/**
	 * <ok class="订单管理(7)"/> 7.6-1订单支付(微信子商户支付)
	 * 参数
	 * 	token：
	 * 	order_id:
	 * 	voucher_id:0 //抵用卷ID(暂时先不考虑)
	 * 返回
	 * {
	 * 	data:charge
	 * }
	 */
	function get__wxpay() {
		$lib = new \Modules\order\order_lib();
		return $lib -> wxpay($this -> user);
	}
	function get__wxopenid() {
		$lib = new \Modules\order\order_lib();
		return $lib -> wxopenid($this -> user);
	}
	/**
	 * <ok class="订单管理(7)"/> 7.6-2
	 * 参数
	 * 	token：
	 * 	order_id:
	 * 	voucher_id:0 //抵用卷ID(暂时先不考虑)
	 * 返回
	 * {
	 * 	data:charge
	 * }
	 */
	/*function openid() {
		$lib = new \Modules\order\order_lib();
		return $lib -> openid($this -> user);
	}*/

	function get__paywx_result() {
		$lib = new \Modules\order\order_lib();
		return $lib -> paywx_result($this -> user);
	}

	/**
	 * <ok class="订单管理(7)"/> 7.6-2 支付失败
	 * 参数
	 * 	token：
	 * 	order_id:
	 * 返回
	 * {
	 * 	data:
	 * }
	 */
	function user__paydelete() {
		$lib = new \Modules\order\order_lib();
		return $lib -> paydelete($this -> user);
	}

	/**
	 * <ok class="订单管理(7)"/> 7.7用户退房（提前退房）结算
	 * 参数
	 * 	token：
	 * 	order_id:
	 * 返回
	 * {
	 * 	//返回格式参考订单详细
	 * }
	 */
	function user__checkOutAdvance() {
		$lib = new \Modules\order\order_lib();
		return $lib -> checkOutAdvance();
	}
	/**
	 * <nok class="订单管理(7)"/> 7.8支付回调接口
	 */
	function pay__result() {
		$lib = new \Modules\order\order_lib();
		return $lib -> pay_result();
	}
	/**
	 * <ok class="订单管理(7)"/> <font color="red">7.9商家确认发货(果蔬)</font>
	 * 参数
	 * 	token：
	 * 	order_id:
	 * 	logistics_company:物流公司
	 * 	logistics_no:物流单号
	 * 返回
	 * {
	 * 	data:""
	 * }
	 */
	function user__orderSend() {
		$lib = new \Modules\order\order_lib();
		return $lib -> orderSend($this -> user);
	}
	/**
	 * <ok class="订单管理(7)"/> <font color="red">7.10游客确认收货(果蔬)</font>
	 * 参数
	 * 	token：
	 * 	order_id:

	 * 返回
	 * {
	 * 	data:""
	 * }
	 */
	function user__orderReceipt() {
		$lib = new \Modules\order\order_lib();
		return $lib -> orderReceipt($this -> user);
	}
	############################################################
	# 订单接口(7) over
	############################################################

	############################################################
	# 资金相关接口
	############################################################
	/**
	 * <ok class="订单管理(7)"/> 7.11 资金--我的资金
	 * 参数
	 * 	token
	 * 返回
	 * 	{
	 * 		"data":{
				"money_usable":"可用余额"
	 * 			"money_expect":"不可用余额"
	 * 		}
	 * 	}
	 */
	function user__coffers(){
		$lib = new \Modules\settlement\lib();
		return $lib -> getOne($this->user);
	}
	/**
	 * <ok class="订单管理(7)"/> 7.12 资金--我的资金
	 * 参数
	 * 	token
	 * 	rows:20
	 * 	offset:0
	 * 返回
	 * 	"data":[...{
				"money"=>"99.99",
				"addtime"=>"1334567890",
				"info"=>"到店付1"
			}...]
	 */
	function user__coffersFlow(){
		$lib = new \Modules\settlement\lib();
		return $lib -> data($this->user);
	}
	/**
	 * <ok class="订单管理(7)"/> 7.13 资金--绑定提现方式
	 * 参数
	 * 	token
	 * 	type: 0/1/2 (def:0) 银行卡/微信/支付宝
	 * 	account_id:账户ID
	 * 	account_name:账户姓名
	 * 	account_bank:开户行
	 *	code:验证码
	 */
	function user__setAccount(){
		$lib = new \Modules\provider\lib();
		return $lib -> setAccount($this -> user);
	}
	/**
	 * <ok class="订单管理(7)"/> 7.14 资金--获取提现方式
	 * 参数
	 * 	token
	 * 	type:0/1/2 (def:0) 银行卡/微信/支付宝
	 * 返回
	 * data:{
	 * 	id:1
		type: 0/1/2 银行卡/微信/支付宝
	 * 	account_id:账户ID
	 * 	account_name:账户姓名
	 * 	account_bank:开户行
	 * }
	 */
	function user__getAccount(){
		$lib = new \Modules\provider\lib();
		return $lib -> getAccount($this -> user);
	}
	############################################################
	# 资金相关结束
	############################################################

	############################################################
	# 面对面砍价相关接口
	############################################################
	/**
	 * <ok class="面对面砍价(8)"/> 8.1 添加规则
	 * 参数
	 * 	token:
	 * 	bmoney:砍价金额(单人)
	 * 	type:兑换方式  1-现金减免,2-赠送菜品,3-赠送实物产品,4-其他(其他时去输入)
	 * 	type_desc:兑换方式描述(当type为4时输入);
	 * 	status:0-关闭,1-开启
	 * 返回
	 * 	"data":{
	 * 	 	id:int 如果添加成功，返回规则id(修改时，无需返回id)
	 * 	}
	 */
	function user__barginadd(){
		$lib = new \Modules\bargain\lib();
		return $lib -> bargainadd($this -> user);
	}

	/**
	 * <ok class="面对面砍价(8)"/> 8.2 规则详情
	 * 参数
	 * 	token:*
	 * 返回
	 * 	"data":{
	 * 	 	id:规则id
	 * 	 	bmoney:砍价金额(单人)
	 * 	 	type:兑换方式  1-现金减免,2-赠送菜品,3-赠送实物产品,4-其他(其他时去输入)
	 * 	 	type_desc:兑换方式描述(当type为4时显示内容);
	 * 	 	status:0-关闭,1-开启
	 * 	}
	 */
	function user__barginone(){
		$lib = new \Modules\bargain\lib();
		return $lib -> bargainone($this -> user);
	}

	/**
	 * <ok class="面对面砍价(8)"/> 8.3 商户查看游客砍价记录(列表页)
	 * 参数
	 * 	token:*
	 * 	offset：分页开始位置，默认0
	 * 	rows:从开始位置读取多少行，默认20
	 * 返回
	 * 	"data":[...{
	 * 	 	id:砍价房间id
	 * 	 	bargainer:
	 * 	 	total_money:
	 * 	 	addtime: 时间戳格式
	 * 	 	time: 时间格式
	 * 	 	status: 状态 0-有效期内   1-已无效
	 * 	}...]
	 */
	function user__bargainlist(){
		$lib = new \Modules\bargain\lib();
		return $lib -> bargainlist($this->user);
	}
	/**
	 * <ok class="面对面砍价(8)"/> 8.4 商户查看游客砍价记录(详情页)
	 * 参数
	 * 	token:*
	 * 	id:砍价房间id
	 * 返回
	 * 	"data":{
	 * 	 	id:
	 * 	 	total_money:砍价总金额
	 * 	 	total_person:砍价总人数
	 * 	 	status: 状态 0-有效期内   1-已无效
	 * 	 	bargainone:string list[
	 * 	 		{
	 * 	 		"id": int
	 * 	 		"nickname": string 昵称
	 * 	 		"headimgurl": string 头像
	 * 	 		"money": string 个人砍价金额
	 * 	 		"addtime":  砍价时间(时间戳模式)
	 * 	 		"time":  砍价时间(正常模式)
	 * 	 		"is_bmoney":金额最大  1-金额最大
	 * 	 		"is_bargainer":坎主  1-坎主身份
	 * 	 		},
	 * 	 		......
	 * 	 		......
	 * 	 	]

	 * 	}
	 */
	function user__bargaindetail(){
		$lib = new \Modules\bargain\lib();
		return $lib -> bargaindetail($this->user);
	}
	/**
	 * <ok class="面对面砍价(8)"/> 8.5 游客查看游客砍价记录(详情页)
	 * 参数
	 * 	id:砍价房间id
	 * 	openid:
	 * 返回
	 * 	"data":{
	 * 	 	id:
	 * 	 	type:兑换方式  1-现金减免,2-赠送菜品,3-赠送实物产品,4-其他
	 * 	 	type_desc:兑换方式描述(当type为4时生效);
	 * 		provider_codeurl:	//生成二维码规则
	 * 		provider_qrcodeurl:	//生成二维码链接
	 * 	 	total_money:砍价总金额
	 * 	 	total_person:砍价总人数
	 * 	 	status: 状态 0-有效期内   1-已无效
	 * 	 	roomnum: 房间号(坎主可见)
	 * 	 	share_url: 分享链接
	 * 	 	share_title: 分享标题
	 * 	 	share_pic: 分享图片
	 * 	 	share_desc: 分享描述
	 * 	 	bargainone:string list[
	 * 	 		{
	 * 	 		"id": int
	 * 	 		"nickname": string 昵称
	 * 	 		"headimgurl": string 头像
	 * 	 		"money": string 个人砍价金额
	 * 	 		"addtime":  砍价时间(时间戳模式)
	 * 	 		"time":  砍价时间(正常模式)
	 * 	 		"is_bmoney":金额最大  1-金额最大
	 * 	 		"is_bargainer":坎主  1-坎主身份
	 * 	 		},
	 * 	 		......
	 * 	 		......
	 * 	 	]

	 * 	}
	 */
	function bargain(){
		$lib = new \Modules\bargain\lib();
		return $lib -> bargaindetail($this->user);
	}

	/**
	 * <ok class="面对面砍价(8)"/> 8.6 农户二维码
	 * 参数
	 * 	token:*
	 * 返回
	 * 	"data":{
	 * 		provider_codeurl:	//生成二维码规则
	 * 		provider_qrcodeurl:	//生成二维码链接
	 * 		avatar:	//农户头像
	 * 		name:	//农户名称
	 * 	 	status:0-关闭,1-开启
	 * 	}
	 */
	function user__bargainurl(){
		$lib = new \Modules\bargain\lib();
		return $lib -> bargainurl($this->user);
	}

	/**
	 * <ok class="面对面砍价(8)"/> 8.7-1 获取用户信息(扫描农户二维码)
	 * 参数
	 * 	openid:  用户的微信标示
	 * 	nickname：昵称
	 * 	sex: 性别
	 * 	language: 语言
	 * 	city：国家
	 * 	province：省
	 * 	country：市
	 * 	headimgurl：头像地址
	 * 	unionid：*
	 * 	source：信息来源,3-小程序,1-app,0-web
	 * 	provider_id：当前农户id
	 *
	 * 返回
	 * 	"data":{
	 * 	 	is_part:是否已参与当前农户  0-未参与 1-已参与
	 * 	 	bargain_id: 若参与显示房间id, 未参与显示0
	 * 	}
	 */
	function user__bargainingadd(){
		$lib = new \Modules\bargain\lib();
		return $lib -> bargainingadd($this->user);
	}

	/**
	 * <ok class="面对面砍价(8)"/> 8.7 获取砍价红包/查看已砍价的红包
	 * 参数
	 * 	openid:  用户的微信标示
	 * 	nickname：昵称
	 * 	headimgurl：头像地址
	 * 	unionid：*
	 * 	provider_id：当前农户id
	 * 	room_num:砍价号码
	 * 	lat:纬度
	 * 	lng:经度
	 *
	 * 返回
	 * 	"data":{
	 * 	 	id:当前房间id
	 * 	}
	 */
	function user__bargaining(){
		$lib = new \Modules\bargain\lib();
		return $lib -> bargaining($this->user);
	}

	/**
	 * <ok class="面对面砍价(8)"/> 8.8 砍价的分享
	 * 参数
	 * 	bargain_id:房间id
	 * 	openid:*
	 * 返回
	 * 	"data":{
	 * 		nickname:	//用户名称
	 * 		name:	//农户名称
	 * 	 	total_money:  //砍价总金额
	 * 	 	is_coverimage:  //0-当前农户有首图,链接为农户首页;1-链接为广播)
	 * 	}
	 */
	function bargainshare(){
		$lib = new \Modules\bargain\lib();
		return $lib -> bargainshare($this->user);
	}

	/**
	 * <ok class="面对面砍价(8)"/> 8.9 正在进行的砍价

	 * 返回
	 * 	"data":[{
	 * 		id:	//房间名称
	 * 		headimg:	//坎主头像

	 * 	}
	 * 	......
	 * ]
	 */
	function user__bargaingo(){
		$lib = new \Modules\bargain\lib();
		return $lib -> bargaingo($this->user);
	}

	/**
	 * <ok class="面对面砍价(8)"/> 8.10 获取砍价红包/查看已砍价的红包(通过房间id获取)
	 * 参数
	 * 	openid:  用户的微信标示
	 * 	nickname：昵称
	 * 	sex: 性别
	 * 	language: 语言
	 * 	city：国家
	 * 	province：省
	 * 	country：市
	 * 	headimgurl：头像地址
	 * 	unionid：*
	 * 	source：信息来源,3-小程序,1-app,0-web
	 * 	room_id：当前砍价房间id
	 * 	lat:纬度
	 * 	lng:经度
	 *
	 * 返回
	 * 	"data":{
	 * 	 	id:当前房间id
	 * 	}
	 */
	function user__bargainings(){
		$lib = new \Modules\bargain\lib();
		return $lib -> bargainings($this->user);
	}

	/**
	 * <ok class="面对面砍价(8)"/> 8.11 二维码图片(农户/房间)
	 * 参数
	 * 	path:
	 *
	 * 返回
	 * 	"data":{
	 * 	 	二维码图片(直接生成二维码图片,后续会有id,用以下载)
	 * 	}
	 */
	function bargainimage(){
		$lib = new \Modules\bargain\lib();
		return $lib -> bargainimage($this->user);
	}

	function bargainimageing(){
		$lib = new \Modules\bargain\lib();
		return $lib -> bargainimageing($this->user);
	}



	############################################################
	# 面对面砍价相关结束
	############################################################


	############################################################
	# 我的游客
	############################################################
	/**
	 * <ok class="我的游客(9)"/> 9.1 我的游客统计
	 * 参数
	 * 	token:
	 * 返回
	 * 	"data":{
	 * 		today_num:	//今日人数
	 * 		weekday_num:	//本周人数
	 * 	 	total_num:  //总人数
	 * 	}
	 */
	function user__touristHave(){
		$lib = new \Modules\tourist\lib();
		return $lib -> touristHave($this->user);
	}

	/**
	 * <ok class="我的游客(9)"/> 9.2 我的游客事件列表
	 * 参数
	 * 	token:
	 * 	type:  cost-显示消费次数列表 (该参数不传则为默认:最近行为列表)
	 * 返回
	 * 	"data":{
	 * 		user_id:	//游客(用户)id
	 * 		name:	//游客名称
	 * 		avatar:	//游客头像
	 * 	 	person:[1,2,3]  //游客个人标签 (在tcommontag中的owner获取)
	 * 	 	action:  //行为
	 * 	 	count:  //当前用户消费次数
	 * 	}
	 */
	function user__touristaction(){
		$lib = new \Modules\tourist\lib();
		return $lib -> touristaction($this->user);
	}

	/**
	 * <ok class="我的游客(9)"/> 9.3 我的游客详情
	 * 参数
	 * 	token:
	 * 	uid:
	 * 返回
	 * 	"data":{
	 * 		name:	//游客名称
	 * 		avatar:	//头像
	 * 		desc:	//描述
	 * 		phone:	//电话
	 * 		cost_num:	//消费次数
	 * 		action_num:	//行为统计
	 * 		relation: [1,2,3]  //社会关系 (在tcommontag中的owner获取)
	 * 		consume: [1,2,3]  //消费能力 (在tcommontag中的owner获取)
	 * 		person: [1,2,3]  //游客个人标签 (在tcommontag中的owner获取)
	 * 	}
	 */
	function user__touristone(){
		$lib = new \Modules\tourist\lib();
		return $lib -> touristone($this->user);
	}

	/**
	 * <ok class="我的游客(9)"/> 9.3-1 基本信息(我的游客)
	 * 参数
	 * 	token:
	 * 	uid:
	 * 返回
	 * 	"data":{
	 * 		name:		// 名称(纳极)
	 * 		birthday:	// 生日(纳极)
	 * 		phone:		// 电话(纳极)
	 * 		address:	// 详细地址(纳极)
	 * 		nickname:	// 昵称(微信)
	 * 		sex:		// 性别(微信) 1-男 2-女
	 * 		region:		// 所属区域(微信)
	 * 		dname:		// 备注名(我的游客)
	 * 		profession:	// 职业(我的游客)
	 * 		marriage:	// 婚姻(我的游客)
	 * 		phonetag:	// 手机品牌(我的游客)
	 * 		qq:			// qq号码/邮箱(我的游客)
	 * 	}
	 */
	function user__touristdetail(){
		$lib = new \Modules\tourist\lib();
		return $lib -> touristdetail($this->user);
	}

	/**
	 * <ok class="我的游客(9)"/> 9.4 "我的标签"信息
	 * 参数
	 * 	token:
	 * 	type: 标签类型 1-社会关系  2-消费能力  3-个人标记
	 * 	(返回值规则:当不传type:all数有值,显示所有个人标签以及所有的系统标签;
	 * 	 	 	当传type:owner显示该类型个人标签汇总;system显示该类型下的系统标签汇总;;
	 * )
	 * 返回
	 * 	"data":{
	 *		owner(农户标签)
	 *		[{
	 *			id:	//标签
	 *			tag_name:	//标签名称
	 *			type:	//标记(我的标签type同上)
	 *		}]
	 *		system(系统标签)
	 *		[{
	 *			id:	//标签
	 *			tag_name:	//标签名称
	 *		}]
	 *		all(系统标签)
	 *		[{
	 *			id:	//标签
	 *			tag_name:	//标签名称
	 *			type:	//标记(我的标签type同上)
	 *			is_system:	//0-个人标签  1-系统标签
	 *		}]
	 * 	}
	 */
	function user__tcommontag(){
		$lib = new \Modules\tourist\lib();
		return $lib -> tcommontag($this->user);
	}

	/**
	 * <ok class="我的游客(9)"/> 9.4-1 当前用户已存在标签
	 * 参数
	 * 	token:
	 * 	uid:
	 * 	type: 标签类型 1-社会关系  2-消费能力  3-个人标记
	 * 返回
	 * 	"data":{
	 * 		tags:[1,2,3] (在tcommontag中的owner获取)
	 * 	}
	 */
	function user__tusertag(){
		$lib = new \Modules\tourist\lib();
		return $lib -> tusertag($this->user);
	}

	/**
	 * <ok class="我的游客(9)"/> 9.5 农户添加标签
	 * 参数
	 * 	token:
	 * 	tag_name:标签名称
	 * 	type:标签类型 1-社会关系  2-消费能力  3-个人标记
	 * 返回
	 * 	"data":{
	 * 		tag_id:	//标签id
	 * 	}
	 */
	function user__touristtagadd(){
		$lib = new \Modules\tourist\lib();
		return $lib -> touristtagadd($this->user);
	}

	/**
	 * <ok class="我的游客(9)"/> 9.6 向游客添加标签
	 * 参数
	 * 	token:
	 * 	uid:
	 * 	tags: string 标签id(用逗号分隔)
	 *	type: 1-社会关系  2-消费能力 3-个人标记
	 * 返回
	 * 	"data":{
	 * 		id:
	 * 	}
	 */
	function user__touristsign(){
		$lib = new \Modules\tourist\lib();
		return $lib -> touristsign($this->user);
	}

	/**
	 * <ok class="我的游客(9)"/> 9.7 游客备注信息的维护
	 * 参数
	 * 	token:
	 * 	uid:
	 * 	desc: string 向游客备注描述
	 * 返回
	 * 	"data":{
	 * 		id:
	 * 	}
	 */
	function user__touristadd(){
		$lib = new \Modules\tourist\lib();
		return $lib -> touristadd($this->user);
	}


	############################################################
	# 我的游客相关结束
	############################################################

	############################################################
	# 夏令营
	############################################################
	/**
	 * <ok class="夏令营(10)"/> 10.1 夏令营列表
	 * 参数
	 * 	token:
	 * 返回
	 * 	"data":{
	 * 		id:	//id
	 * 		title:	//名称
	 * 		desc:	//描述
	 * 		address:	//地址
	 * 		price:	//价格
	 * 		price_unit:	//价格单位
	 * 		cover_image:	//封面图片
	 * 	}
	 */
	function get__summerlist(){
		$lib = new \Modules\summer\lib();
		return $lib -> summerlist($this->user);
	}

	/**
	 * <ok class="夏令营(10)"/> 10.2 夏令营详情
	 * 参数
	 * 	token:
	 * 	id:  夏令营id
	 * 返回
	 * 	"data":{
	 * 		id:	//id
	 * 		title:	//名称
	 * 		desc:	//描述
	 * 		trip:	//日期描述
	 * 		address:	//地址
	 * 		price:	//价格
	 * 		price_unit:	//价格单位
	 * 		cover_image:	//封面图片
	 * 		activity_title:	//"活动内容"标题
	 * 		acitivity_content:	//"活动内容"内容
	 * 		cost_title:	//"费用说明"标题
	 * 		cost_content:	//"费用说明"内容
	 * 		sign_title:	//"报名须知"标题
	 * 		sign_content:	//"报名须知"内容
	 * 	}
	 */
	function get__summerone(){
		$lib = new \Modules\summer\lib();
		return $lib -> summerone($this->user);
	}

	/**
	 * <ok class="夏令营(10)"/> 10.3 我们的优势
	 * 参数
	 * 	token:
	 * 返回
	 * 	"data":{
	 * 		id:	//id
	 * 		desc:	//描述
	 * 		cover_image:	//封面图片
	 * 	}
	 */
	function get__summeradvantage(){
		$lib = new \Modules\summer\lib();
		return $lib -> summeradvantage($this->user);
	}
	/**
	 * <ok class="夏令营(10)"/> 10.4 精彩瞬间
	 * 参数
	 * 	token:
	 * 返回
	 * 	"data":{
	 * 		id:	//id
	 * 		desc:	//描述
	 * 		cover_image:	//封面图片
	 * 	}
	 */
	function get__summerhighlights(){
		$lib = new \Modules\summer\lib();
		return $lib -> summerhighlights($this->user);
	}

	############################################################
	# 夏令营相关结束
	############################################################

	############################################################
	# 我的菜谱
	############################################################
	/**
	 * <ok class="我的菜谱(11)"/> 11.1 发布/修改菜品
	 * 参数
	 * 	token:
	 * 	id: 针对已添加的菜谱进行修改操作时使用
	 * 	name: 菜品名称
	 * 	price: 价格
	 * 	type_id: 类型id
	 * 	cover_image: 图片
	 * 	desc: 描述
	 * 返回
	 * 	"data":{
	 * 		id: 菜品id
	 * 	}
	 */
	function user__cookbookadd(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> cookbookadd($this->user);
	}

	/**
	 * <ok class="我的菜谱(11)"/> 11.2 菜品详情
	 * 参数
	 * 	token:
	 * 	id: 菜品id
	 * 返回
	 * 	"data":{
	 * 		id: 菜品id
	 * 		name: 菜品名称
	 * 		price: 价格
	 * 		type_id: 类型id
	 * 		cover_image: 图片
	 * 		desc: 描述
	 * 		status: 状态
	 * 	}
	 */
	function user__cookbookone(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> cookbookone($this->user);
	}

	/**
	 * <ok class="我的菜谱(11)"/> 11.3 设置菜品状态
	 * 参数
	 * 	token:
	 * 	id: 针对已添加的菜谱进行修改操作时使用
	 * 	status:	菜品状态(0-正常,1-隐藏,2-删除)
	 */
	function user__setcookbook(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> setcookbook($this->user);
	}

	/**
	 * <ok class="我的菜谱(11)"/> 11.4 发布/修改菜品分类
	 * 参数
	 * 	token:
	 * 	id: 针对已添加的菜谱进行修改操作时使用
	 * 	name:
	 * 返回
	 * 	"data":{
	 * 		id: 分类id
	 * 	}
	 */
	function user__cooktypeadd(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> cooktypeadd($this->user);
	}

	/**
	 * <ok class="我的菜谱(11)"/> 11.5 菜品分类详情
	 * 参数
	 * 	token:
	 * 	id: 针对已添加的菜谱进行修改操作时使用
	 * 返回
	 * 	"data":{
	 * 		id: 分类id
	 * 		name: 分类名称
	 * 		status: 分类状态
	 * 	}
	 */
	function user__cooktypeone(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> cooktypeone($this->user);
	}

	/**
	 * <ok class="我的菜谱(11)"/> 11.6 设置分类状态
	 * 参数
	 * 	token:
	 * 	id: 针对已添加的分类进行修改操作时使用
	 * 	status:	分类状态(0-正常,1-隐藏,2-删除)
	 */
	function user__setcooktype(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> setcooktype($this->user);
	}

	/**
	 * <ok class="我的菜谱(11)"/> 11.7 商户的分类列表
	 * 参数
	 * 	token:
	 * 返回
	 * 	"data":{[
	 * 		id:	//id
	 * 		name:	//名称
	 * 		status:	//状态
	 * 		]...
	 * 	}
	 */
	function user__cooktypelist(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> cooktypelist($this->user);
	}

	/**
	 * <ok class="我的菜谱(11)"/> 11.8 商家端管理的菜品列表
	 * 参数
	 * 	token:
	 * 返回
	 * 	"data":[{
	 * 		name:分类名称
	 * 		status:分类状态
	 * 		type:(菜品类型){
	 *			[
	 *				id:	//id
	 *				name:	//名称
	 *				price:	//价格
	 *				cover_image:	//图片
	 *				desc:	//描述
	 *				status:	//状态
	 *			]...
	 * 		}
	 * 	}]...
	 */
	function user__cookbook(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> cookbook($this->user);
	}

	/**
	 * <ok class="我的菜谱(11)"/> 11.9 游客/商家端预定的菜品列表
	 * 参数
	 * 	token:
	 * 	provider_id: 当前菜谱所属农户id
	 * 	dno: 当前桌号
	 * 	offset:
	 * 	rows:10
	 * 返回
	 * 	"data":{[
	 * 		id:	//id
	 * 		name:	//名称
	 * 		price:	//价格
	 * 		cover_image:	//图片
	 * 		desc:	//描述
	 * 		num:	//已点数量
	 * 		status:	//状态  显示正常出售的菜品
	 * 		]......
	 * 	}
	 * 	"order_id":若无订单,或之前订单已结账,显示0;否则显示订单id
	 */
	function user__cookbooklist(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> cookbooklist($this->user);
	}

	/**
	 * <ok class="我的菜谱(11)"/> 11.10 针对点菜(增加/删除)
	 * 参数
	 * 	token:
	 * 	provider_id:
	 * 	dno:桌号
	 * 	id:所选菜的id
	 * 	name:所选菜的名称
	 * 	price:菜的价格
	 * 	type: 1/!1  加菜/减菜
	 * 	is_travel: 1-游客端操作,商户端可不传
	 * 返回
	 * 	"data":{
	 * 		id:	//所选菜品id
	 * 		type:	//1/!1  加菜/减菜
	 * 		cooknum:	//
	 * 		cookprice:	//
	 * 	}
	 */
	function user__cookchoose(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> cookchoose($this->user);
	}

	/**
	 * <ok class="我的菜谱(11)"/> 11.10-1 针对下单(增加/删除)
	 * 参数
	 * 	token:
	 * 	chid:	chooselist的id
	 * 	type: 1/!1  加菜/减菜
	 * 	is_travel: 1-游客端操作,商户端可不传
	 * 返回
	 * 	"data":{
	 *
	 * 	}
	 */
	function user__cookorderchoose(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> cookorderchoose($this->user);
	}

	/**
	 * <ok class="我的菜谱(11)"/> 11.11 游客/商家端已选菜品列表
	 * 参数
	 * 	token:
	 * 	provider_id: 当前菜谱所属农户id
	 * 	dno: 当前桌号
	 * 	offset:
	 * 	rows:10
	 * 返回
	 * 	"data":{
	 * 		cooknum:	//菜的数量
	 * 		cookprice:	//菜的价格
	 * 		list:[
	 * 			id:	//id
	 * 			name:	//名称
	 * 			price:	//价格
	 * 			cover_image:	//图片
	 * 			desc:	//描述
	 * 			num:	//已点数量
	 * 			nickname:	//点菜人昵称
	 * 			avatar:	//点菜人头像
	 * 			status:	//状态
	 * 		]......
	 * 	}
	 */
	function user__cookchooselist(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> cookchooselist($this->user);
	}

	/**
	 * <ok class="我的菜谱(11)"/> 11.12 商家端查看当前订单列表
	 *
	 * status状态: -1-超时,0-正在点餐中,1-待服务员确认,2-服务员已确认,3-已买单
	 *
	 * 参数
	 * 	token:
	 * 返回
	 * 	"data":[{
	 * 		status:订单状态
	 * 		type:(菜品类型){
	 *			[
	 *				id:	//id
	 *				dno:	//桌号
	 *				cno:	//单号
	 *				cookprice:	//价格
	 *				addtime:	//时间
	 *				status:	//状态
	 *				is_cash:	//是否现金 0-现金买单/1-在线买单
	 *				order_id:	//若已生成订单,显示订单id,否则显示"";
	 *			]...
	 * 		}
	 * 	}]...
	 */
	function user__cookorderlist(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> cookorderlist($this->user);
	}

	function user__cookflushlist(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> cookflushlist($this->user);
	}

	/**
	 * <ok class="我的菜谱(11)"/> 11.13 服务员确认订单
	 * 参数
	 * 	token:
	 * 	id: 订单order_id
	 * 	desc: 描述
	 * 返回
	 * 	"data":{
	 *
	 * 	}
	 */
	function user__cookorderconfirm(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> cookorderconfirm($this->user);
	}


	/**
	 * <ok class="我的菜谱(11)"/> 11.13-1 服务员取消该桌点餐
	 * 参数
	 * 	token:
	 * 	id:  cookorderlist中列表中的id
	 * 返回
	 * 	"data":{
	 *
	 * 	}
	 */
	function user__cookordercancel(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> cookordercancel($this->user);
	}

	/**
	 * <ok class="我的菜谱(11)"/> 11.14 收银员查看订单列表(已买单/未买单)
	 * 参数
	 * 	token:
	 * 	offset:
	 * 	rows:10
	 * 	type: 1/!1  已买单/未买单
	 * 返回
	 * 	"data":{[
	 * 		id:	//id
	 * 		dno:	//桌号
	 * 		cno:	//单号
	 * 		cookprice:	//价格
	 * 		addtime:	//时间
	 * 		is_cash:		//0-现金买单/1-在线买单
	 * 		order_id:	//
	 * 		]......
	 * 	}
	 */
	function user__cookcashierlist(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> cookcashierlist($this->user);
	}

	/**
	 * <ok class="我的菜谱(11)"/> 11.15 收银员确认收款
	 * 参数
	 * 	token:
	 * 	id:
	 * 返回
	 * 	"data":{
	 *
	 * 	}
	 */
	function user__cookcashconfirm(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> cookcashconfirm($this->user);
	}

	/**
	 * <ok class="我的菜谱(11)"/> 11.16 打印
	 * 参数
	 * 	token:
	 * 	content:
	 * 返回
	 * 	"data":{
	 *
	 * 	}
	 */
	function get__cookprint(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> cookprint($this->user);
	}

	/**
	 * <ok class="我的菜谱(11)"/> 11.17	打印模板
	 * 参数
	 * 	token:
	 * 	id:	订单id
	 * 	type:	类型  0-全部  1-未打印  默认是0
	 * 返回
	 * 	"data":{
	 * 		provider_name:农户名称
	 * 		dno:桌号
	 * 		cno:单号
	 * 		operator:操作员
	 * 		begin_time:下单时间
	 * 		print_time:打印时间
	 * 		desc:备注
	 * 		list:(菜品类型)[
	 * 			type:菜的分类
	 *			lists:[
	 *				id:	//id
	 *				name:	//菜品名称
	 *				price:	//价格
	 *				num:	//数量
	 *			]...
	 * 		]......
	 * 		cookprice:金额总数
	 * 		provider_address:农户地址
	 * 		provider_phone:农户电话
	 * 	}
	 */
	function user__cookprintone(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> cookprintone($this->user);
	}

	/**
	 * <ok class="异步"/> 延时刷新
	 */
	function asyn__flush(){
		$lib = new \Modules\cookbook\lib();
		return $lib -> asyn_flush($this -> user);
	}

	############################################################
	# 我的菜谱相关结束
	############################################################

	############################################################
	# 账户登录接口开始
	############################################################
	/**
	 * <ok class="账户登录(12)"/> 12.1微信登陆(APP)
	 * 参数
	token：*
	 * 	openid:  用户的微信标示
	nickname：昵称
	sex: 性别
	language: 语言
	city：国家
	province：省
	country：市
	headimgurl：头像地址
	unionid：*
	source：信息来源,1-app,0-web
	 * 返回
	 * 	{
	 * 	 “code”:200, // 200：成功
	 * 	 “data”:{
	 * 	     “token”:”用户身份”, //后期用户和服务器交互的令牌
	 * 	     “uid”:”1003730686”, //服务器后台给用户分配的唯一ID
	 * 		"provider_id":int //农家乐ID
	 * 		"nick": string 用户昵称
	 * 		"gender":int 性别 0-女 1-男
	 * 		"avatar": string 头像图片地址
	 * 		"birthday": string 生日
	 * 		"phone": string 手机号
	 * 		}
	 * 	}
	 */
	function wxlogin(){
		$lib = new \Modules\weixin\lib();
		return $lib -> wxlogin($this -> user);
	}

	/**
	 * <ok class="账户登录(12)"/> 12.2web端微信登陆
	 * 参数
	token：*
	 * 	openid:  用户的微信标示
	nickname：昵称
	sex: 性别
	language: 语言
	city：国家
	province：省
	country：市
	headimgurl：头像地址
	unionid：*
	 * 返回
	 * 	{
	 * 	 “code”:200, // 200：成功
	 * 	 “data”:{
	 * 	     “token”:”用户身份”, //后期用户和服务器交互的令牌
	 * 	     “uid”:”1003730686”, //服务器后台给用户分配的唯一ID
	 * 	     "device_id":int //验证码
	 * 	     "is_clerk":int // 0-非店员 1-店员
	 * 		}
	 * 	}
	 */
	function webwxlogin(){
		$lib = new \Modules\weixin\lib();
		return $lib -> webwxlogin($this -> user);
	}

	/**
	 * <ok class="账户登录(12)"/> 12.3小程序登陆
	 * 参数
	 *code：
	 *encryptedData:
	 *iv:
	encryptData：
	errMsg：
	rawData:
	signature：

	 * 返回
	 * 	{
	 * 	 “code”:200, // 200：成功
	 * 	 “data”:{
	 * 	     "openId": "OPENID",
	 * 	     "nickName": "NICKNAME",
	 * 	     "gender": GENDER,
	 * 	     "city": "CITY",
	 * 	     "province": "PROVINCE",
	 * 	     "country": "COUNTRY",
	 * 	     "avatarUrl": "AVATARURL",
	 * 	     "unionId": "UNIONID",
	 * 	     "watermark":
	 * 	      {
	 * 	      "appid":"APPID",
	 * 	      "timestamp":TIMESTAMP
	 * 	      }
	 * 		}
	 * 	}
	 */
	function xcxlogin(){
		$lib = new \Modules\weixin\lib();
		return $lib -> xcxlogin($this -> user);
	}
	/**
	 * <ok class="账户登录(12)"/> 12.4小程序登陆(农户版)
	 * 参数
	 *code：
	 *encryptedData:
	 *iv:
	 *encryptData：
	 *errMsg：
	 *rawData:
	 *signature：
	 *
	 * 返回
	 * 	{
	 * 	 “code”:200, // 200：成功
	 * 	 “data”:{
	 * 	     "openId": "OPENID",
	 * 	     "nickName": "NICKNAME",
	 * 	     "gender": GENDER,
	 * 	     "city": "CITY",
	 * 	     "province": "PROVINCE",
	 * 	     "country": "COUNTRY",
	 * 	     "avatarUrl": "AVATARURL",
	 * 	     "unionId": "UNIONID",
	 * 	     "watermark":
	 * 	      {
	 * 	      "appid":"APPID",
	 * 	      "timestamp":TIMESTAMP
	 * 	      }
	 * 		}
	 * 	}
	 */
	function xcxlogining(){
		$lib = new \Modules\weixin\lib();
		return $lib -> xcxlogining($this -> user);
	}
	/**
	 * <ok class="账户登录(12)"/> 12.5小程序登陆(图文版)
	 * 参数
	 *code：
	 *encryptedData:
	 *iv:
	 *encryptData：
	 *errMsg：
	 *rawData:
	 *signature：
	 *appid：
	 *secret：
	 *
	 * 返回
	 * 	{
	 * 	 “code”:200, // 200：成功
	 * 	 “data”:{
	 * 	     "openId": "OPENID",
	 * 	     "nickName": "NICKNAME",
	 * 	     "gender": GENDER,
	 * 	     "city": "CITY",
	 * 	     "province": "PROVINCE",
	 * 	     "country": "COUNTRY",
	 * 	     "avatarUrl": "AVATARURL",
	 * 	     "unionId": "UNIONID",
	 * 	     "watermark":
	 * 	      {
	 * 	      "appid":"APPID",
	 * 	      "timestamp":TIMESTAMP
	 * 	      }
	 * 		}
	 * 	}
	 */
	function xcxloginings(){
		$lib = new \Modules\weixin\lib();
		return $lib -> xcxloginings($this -> user);
	}

	/**
	 * <ok class="账户登录(12)"/> 12.6多账户登录判断(微信数据)
	 * 参数
	 * 	token：*
	 * 	openid:  用户的微信标示
	 * 	nickname：昵称
	 * 	sex: 性别
	 * 	language: 语言
	 * 	city：国家
	 * 	province：省
	 * 	country：市
	 * 	headimgurl：头像地址
	 * 	unionid：*
	 * 	provider_id：*
	 * 返回
	 * 	{
	 * 	 “code”:200, // 200：成功
	 * 	 “data”:{
	 * 	     status  0--微信信息未绑定商家   1--微信信息已绑定商家   2-微信信息已经是该农户的店员
	 * 	     provider_name: ""
	 * 		}
	 * 	}
	 */
	function multiplewechat(){
		$lib = new \Modules\weixin\lib();
		return $lib -> multiplewechat($this -> user);
	}

	/**
	 * <ok class="账户登录(12)"/> 12.7多账户登录判断(手机数据)
	 * 参数
	 * 	token：*
	 * 	phone：
	 * 	provider_id：*
	 * 返回
	 * 	{
	 * 	 “code”:200, // 200：成功
	 * 	 “data”:{
	 * 	     status  0--手机信息未绑定商家   1--手机信息已绑定商家
	 * 	     provider_name: ""
	 * 		}
	 * 	}
	 */
	function multiplephone(){
		$lib = new \Modules\weixin\lib();
		return $lib -> multiplephone($this -> user);
	}

	/**
	 * <ok class="账户登录(12)"/> 12.8多账户绑定
	 * 参数
	 * 	token：*
	 * 	openid:  用户的微信标示
	 * 	nickname：昵称
	 * 	sex: 性别
	 * 	language: 语言
	 * 	city：国家
	 * 	province：省
	 * 	country：市
	 * 	headimgurl：头像地址
	 * 	unionid：*
	 * 	source：*     3-微信小程序
	 * 	provider_id：*		二维码中农户id
	 * 	phone：*		电话
	 * 返回
	 * 	{
	 * 	 “code”:200, // 200：成功
	 * 	 “data”:{
	 * 	     “token”:”用户身份”, //后期用户和服务器交互的令牌
	 * 	     “uid”:”1003730686”, //服务器后台给用户分配的唯一ID
	 * 	     "provider_id":int //农家乐ID
	 * 	     "is_shopper":int //是否为店员
	 * 		}
	 * 	}
	 */
	function multiplebinding(){
		$lib = new \Modules\weixin\lib();
		return $lib -> multiplebinding($this -> user);
	}

	//数据库user表增加is_shopper字段,增加provider_shopper表记录店员:id,provider_id,user_id,level, -status

	/**
	 * <ok class="账户登录(12)"/> 12.9 店员账户列表
	 * 参数
	 * 	token：*
	 * 返回
	 * 	{
	 * 	 “code”:200, // 200：成功
	 * 	 “data”:{
	 * 	     “user_id”:”账户id”,
	 * 	     “name”:”账户名称”,
	 * 	     “avatar”:”账户头像”,
	 * 		}
	 * 	}
	 */
	function user__multiplelist(){
		$lib = new \Modules\weixin\lib();
		return $lib -> multiplelist($this -> user);
	}
	/**
	 * <ok class="账户登录(12)"/> 12.10 删除店员账户
	 * 参数
	 * 	token：*
	 * 	user_id：删除店员的uid
	 * 返回
	 * 	{
	 * 	 “code”:200, // 200：成功
	 * 	 “data”:{

	 * 		}
	 * 	}
	 */
	function user__multipledelete(){
		$lib = new \Modules\weixin\lib();
		return $lib -> multipledelete($this -> user);
	}

	/**
	 * <ok class="账户登录(12)"/> 12.11 增加店员二维码
	 * 参数
	 * 	token：*
	 * 返回
	 * 	{
	 * 	 “code”:200, // 200：成功
	 * 	 “data”:{
				二维码地址
	 * 		}
	 * 	}
	 */
	function user__multipleclerk(){
		$lib = new \Modules\weixin\lib();
		return $lib -> multipleclerk($this -> user);
	}

	/**
	 * <ok class="异步"/> 订餐发送消息
	 */
	function asyn__clerkMessage(){
		$lib = new \Modules\weixin\lib();
		return $lib -> asyn_clerkMessage($this -> user);
	}

	function clerkMessagetest(){
		$lib = new \Modules\weixin\lib();
		return $lib -> clerkMessagetest($this -> user);
	}
	############################################################
	# 账户登录接口结束
	############################################################


}
