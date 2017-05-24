<?php
/**
 * author vking
 * 文章
 */
namespace Modules\article;
class lib{
    function __construct($id="") {
    	$this->model=new model();
		$id=$id?$id:I('request.id');
		if($id){
			$this->model->relation(true)->find($id);
		}
    }
	function content(){
		$yy_flag=false;
		foreach($this->model->classic as $r){
			if($r['id']==17 || $r['id']==18){
				$yy_flag=true;
				break;
			}
		}
		$footer="";
		if(!strpos($_SERVER['HTTP_USER_AGENT'],'najiapp') && $yy_flag){
			$web_url=urlencode(share_url("tpl/us/dynamiclist.html"));
			$footer=<<<FOOTER
			<style>

        .dynamic_qr { width:90%; margin: 0 auto; height: 100px;}
        .dynamic_qr img{ width:100%;}
        .dynamic_qr div{ float: right;}
        .dynamic_qr div.text {
            text-align: right;
            margin-right: 10px;
        }
        .dynamic_qr .qrcode img{width:100%; float: left;}
        .dynamic_footer .avatar{
            background-image: url(../../assets/imgs/dynamic/dianpuxinxi_03.jpg);
            width:134px;
            height:111px;
            margin: 0 auto;
            z-index: 100;
            position: relative;
        }
        .dynamic_footer .avatar .comment-img{
            margin: 0;
            position: absolute;
            left: 27px;
            top: 16px;
            width: 80px;
            height: 80px;
        }
        .dynamic_footer .content_link{
            border: 2px solid #0bbd49;
            border-radius: 10px;
            width:80%;
            margin: -56px auto 0;
            min-height: 200px;
            z-index: 99;
            position: relative;
            text-align: center;
            padding-top: 50px;
        }
        .dynamic_footer .name {
            font-size: 15px;
            font-weight: bold;
            max-width: 200px;
            overflow: hidden;
            overflow-wrap: break-word;
            height:40px;
        }
        .dynamic_footer .longan{
            font-size: 12px;
            margin-top: -15px;
        }
        .dynamic_footer .content_link .phone,.dynamic_footer .content_link .address{ font-size: 16px;}

        fieldset.dynamic_hr {
            border-width: 0;
            border-top: 2px solid #ccc;
            width: 100%;
            margin: 0 0 20px 0;
            padding: 0;
        }
        fieldset.dynamic_hr legend{
            text-align: center;
            font-size: 12px;
            width: auto;
            margin-bottom: 0;
    		border-bottom: 0px solid #e5e5e5;
        }
        .dynamic_head_mask{
            width:100%;
            height:50px;
            position: absolute;
            z-index: 1;
            margin-top: -54px;
        }
        .dynamic_qr div.logo-view{float: left;}
        .logo-mini{
             background: url(../../../assets/img/logo.png) #fff;
             background-size: 50px;
             background-position: 5px 5px;
             background-repeat: no-repeat;
             width: 60px;
             height: 60px;
             border-radius: 5px;
             border: 1px solid #ccc;
             display: block;
             position: relative;
             top: 10px;
         }
			</style>
<div class="dynamic_footer">
    <fieldset class="dynamic_hr">
        <legend>此长文由<span style="color:green;">纳极APP</span>分享</legend>
    </fieldset>
    <div class="dynamic_qr">
        <div class="qrcode">
            <img id="dynamic_qr" src="http://pan.baidu.com/share/qrcode?w=80&amp;h=80&amp;url={$web_url}">
        </div>
        <div class="text">
            <p class="name">纳极官方出品</p>
            <p class="longan">长按识别，查看更多</p>
        </div>
        <div class="logo-view">
            <span class="logo-mini"></span>
        </div>
    </div>
</div>
FOOTER;

		}
		$formfield="";
		if(I('request.id')==66){
			$iframeurl=url('formfield');
			$formfield=<<<IFRAME
<script type="text/javascript" language="javascript">
var iFrameHeightInterval="";
function iFrameHeight() {

var ifm= document.getElementById("WJ_survey_field");
//ifm.height =500;
var subWeb = document.frames ? document.frames["WJ_survey_field"].document : ifm.contentDocument;
if(ifm != null && subWeb != null) {
ifm.height = subWeb.body.scrollHeight;
}
if(ifm.height,subWeb.body.innerHTML.indexOf('提交成功')>-1){
	ifm.height = 120;
	clearInterval(iFrameHeightInterval);
}
console.log('iheight',ifm.height,subWeb.body);
}
iFrameHeightInterval=setInterval(function(){
	iFrameHeight();
},500);
</script>
<iframe id="WJ_survey_field" name="WJ_survey_field" width="100%" height="500" src="{$iframeurl}" frameborder="0" style="min-height: 400px; border: 0px; overflow: hidden;" onload="iFrameHeight()"></iframe>

IFRAME;
		}
		$css=url('assets/css/xiumi.css');
		$html=<<<HTML
<html lang="zh-cn">
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="x-rim-auto-match" content="none">
	<title>{title}</title>
	<link rel="stylesheet" href="{$css}"/>
</head>
<body class="tn-transition-end tn-platform-class tn-platform-mobile">
<div class="tn-reader-paper container ng-scope">
     <section class="row tn-article-body">
             <article class="dock-loader tn-scene-paper tn-show-root ng-scope tn-comp-inst tn-comp tn-from-house-reader_paper-cp">
	{content}
	</article>
	{$formfield}
	</section>
	</div>
	{$footer}
</body>
HTML;
		$replace=array(
				'{title}'=>$this->model->title,
				'{content}'=>$this->model->content,
		);
		$html=strtr($html,$replace);
		die($html);
	}
	
	function helpcenter(){
		
		/*$data=M('cms_classify')->field('name')->where("id in('5','6','7','8')")->find();
		
		$data['list']=M('cms_article a')->field('a.id,a.title')->join('cms_article_classify z ON a.id=z.aid','left')->where("z.cid in('5','6','7','8')")->select();*/
		
		$data=M('cms_article a')->field('z.aid,a.title,z.cid,c.name')->join('cms_article_classify z ON a.id=z.aid','left')->join('cms_classify c ON z.cid=c.id','left')->where("c.id in('5','6','7','8')")->select();
		//print_r($data);
		$help=[];
		foreach($data as $key=>$value){
			if(!$help[$value['cid']]){
				$help[$value['cid']]=[
					'name'=>$value['name'],
					'list'=>[]
				];
			}
			array_push($help[$value['cid']]['list'],[
				'id'=>$value['aid'],
				'title'=>$value['title'],
				'url'=>url('apido/article/id/'.$value['aid']),
			]);
			
			//$help[$key]['name']=$data[$key]['name'];
			//$help[$key]['list']=$data->field(array('aid','title'))->where("cid=5")->select();
			
		}
		$help=array_values($help);
	  return	array('data'=>$help);
	}
	
	//$user is admin
	function getArticleList($user){
		$offset=I('request.offset',0);
		$rows=I('request.rows',20);
		//$where="name is not null and name<>''";
		$total=$this->model->where($where)->count();
		$data=$this->model->relation(array('classic'))->where($where)->order('id desc')->limit("$offset,$rows")->select();
		foreach ($data as $key => $value) {
			if($value['classic']){
				$classic=array();
				foreach ($value['classic'] as $item) {
					$classic[]=$item['name'];
				}
				$value['classic']=implode(',', $classic);
				$data[$key]=$value;
			}
			
		}
		return array('data'=>$data,'total'=>$total);
	}
	//$user is admin
	function getArticleInfo($user){
		$data=$this->model->data();
		if($data['classic']){
			$classic=array();
			foreach ($data['classic'] as $item) {
				$classic[$item['id']]=$item['name'];
			}
			$data['classic']=$classic;
		}
		return array('data'=>$data);
	}
	//$user is admin
	function setArticleInfo($user){
		//$data=$this->model->data();
		$data=array(
			'title'=>I('request.title'),
			'content'=>I('request.content'),
			'image'=>I('request.image',$param['image']+0)
		);
		$flag=$this->model->create($data);
		if ($flag===false){ // 指定必填数据
		     // 如果创建失败 表示验证没有通过 输出错误提示信息
		     return array('message'=>$this->model->getError());
		}
		$this->model->title=I('request.title');
		$this->model->content=array('content'=>I('request.content','','trim'));
		$this->model->src=I('request.image');
		$aid=I('request.id');
		if($this->model->id=$aid){
			$this->model->relation(array('content'))->save();
		}else{
			$aid=$this->model->relation(array('content'))->add();
		}
		if($aid){

			$this->model->url=$_SERVER['HTTP_HOST']."/apido/article/id/".$aid;
			$this->model->where('id='.$aid)->save();
		}

		//classify
		M('cms_article_classify')->where('aid='.$aid)->delete();
		$classdata=array();
		foreach (I('request.classify',array()) as $cid) {
			$classdata[]=array(
				'aid'=>$aid,
				'cid'=>$cid,
			);
		}
		if($classdata) M('cms_article_classify')->addAll($classdata);
	}
	//$user is admin
	function delArticle($user){
		return array('data'=>$this->model->delete(I('request.id')));
	}
}