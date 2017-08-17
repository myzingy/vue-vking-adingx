<?php
namespace Home\Controller;
use Think\Controller;
use Think\Image;

class FeedsController extends Controller {
	function __construct() {
		parent::__construct();
        $this->lib = new \Modules\feeds\lib();
	}
	function index(){
	    exit;
    }
    private function __xml($name){
        header('Content-Type:application/xml');
        $lib=$this->lib;
        $filename=$lib::PATH_FEED_XML.$name;
        if(file_exists($filename)){
            die(file_get_contents($filename));
        }
        $mark_img_hash=strtr($name,array($lib::FEED_MARKS_PRE=>'','.xml'=>''));
        $mark=M('feeds_marks')->alias('FM')->where(['FM.id'=>$mark_img_hash])->join('feeds F ON F.id=FM.fid','left')
            ->find();
        $xml=<<<XML
<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
    <channel>
    <title>{title}</title>
    <link>{link}</link>
    <description>{description}</description>

XML;
        if($mark){
            $xml=strtr($xml,array(
                '{title}'=>$mark['title'],
                '{link}'=>$mark['link'],
                '{description}'=>$mark['description'],
            ));
        }
        $data=M('feeds_items')->where(['fid'=>$mark['fid']])->order('g_id asc')->select();
        foreach ($data as $r){
            //$r['g_description']=htmlentities($r['g_description']);
            //$r['g_title']=htmlentities($r['g_title']);
            //$r['g_brand']=htmlentities($r['g_brand']);
            //$r['g_google_product_category']=htmlentities($r['g_google_product_category']);
            $r['g_image_link']=url('feeds/'.$lib::FEED_IMAGE_PRE.$r['image_hash'].'-'.$mark['mark_img_hash'].'.jpeg');
            $r['g_link']=$r['g_link']
                .(strpos($r['g_link'],'?')>0?'&':'?')
                .http_build_query(array(
                    'utm_source'=>$mark['utm_source'],
                    'utm_medium'=>$mark['utm_medium'],
                    'utm_campaign'=>$mark['utm_campaign'],
                    'utm_content'=>$mark['utm_content'],
                    'utm_term'=>$mark['utm_term'],
                ));
            //$r['g_link']=htmlentities($r['g_link']);
            $xml.=<<<XML
        <item>
            <g:id><![CDATA[{$r['g_id']}]]></g:id>
            <g:availability><![CDATA[{$r['g_availability']}]]></g:availability>
            <g:condition><![CDATA[{$r['g_condition']}]]></g:condition>
            <g:description><![CDATA[{$r['g_description']}]]></g:description>
            <g:image_link><![CDATA[{$r['g_image_link']}]]></g:image_link>
            <g:link><![CDATA[{$r['g_link']}]]></g:link>
            <g:title><![CDATA[{$r['g_title']}]]></g:title>
            <g:price><![CDATA[{$r['g_price']}]]></g:price>
            <g:brand><![CDATA[{$r['g_brand']}]]></g:brand>
            <g:google_product_category><![CDATA[{$r['g_google_product_category']}]]></g:google_product_category>
            <g:custom_label_0><![CDATA[{$r['g_custom_label_0']}]]></g:custom_label_0>
            <g:custom_label_1><![CDATA[{$r['g_custom_label_1']}]]></g:custom_label_1>
            <g:custom_label_2><![CDATA[{$r['g_custom_label_2']}]]></g:custom_label_2>
            <g:custom_label_3><![CDATA[{$r['g_custom_label_3']}]]></g:custom_label_3>
            <g:custom_label_4><![CDATA[{$r['g_custom_label_4']}]]></g:custom_label_4>
        </item>
XML;
        }
        $xml.=<<<XML
    </channel>
</rss>
XML;
        file_put_contents($filename,$xml);
        die($xml);
    }
    private function __image($name){
        header('Content-Type:image/jpg');
        $lib=$this->lib;
        $filename=$lib::PATH_FEED_IMAGE_MARKS.$name;
        if(file_exists($filename)){
            die(file_get_contents($filename));
        }
        $mark_img_hash=strtr($name,array($lib::FEED_IMAGE_PRE=>'','.jpeg'=>''));
        list($image_hash,$mark_hash)=explode('-',$mark_img_hash);
        $image=$lib::PATH_FEED_IMAGE.$image_hash.'.jpg';
        if(!$mark_hash){
            if(is_numeric($image_hash)){
                $item=M('feeds_items')->where(['fid'=>$image_hash])->order('image_isdown desc,RAND() asc')->find();
                die(file_get_contents($lib::PATH_FEED_IMAGE.$item['image_hash'].'.jpg'));
            }
            die(file_get_contents($image));
        }
        $image_mark=$lib::PATH_FEED_MARKS.$mark_hash.'.png';
        mk($lib::PATH_FEED_IMAGE_MARKS);
        $iii = new \Think\Image();
        $iii->open($image)
            ->water($image_mark,\Think\Image::IMAGE_WATER_CENTER,100)
            ->save($filename,null,100);
        die(file_get_contents($filename));
    }
    public function _empty($name){
        $lib=$this->lib;
        if(strpos($name,$lib::FEED_MARKS_PRE)>-1){
            $this->__xml($name);
        }
        if(strpos($name,$lib::FEED_IMAGE_PRE)>-1){
            $this->__image($name);
        }
    }
}
