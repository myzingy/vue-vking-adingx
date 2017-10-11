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
        $xml=<<<XMLXML
    <rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
    <channel>
    <title>{title}</title>
    <link>{link}</link>
    <description>{description}</description>
XMLXML;
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
            $xml.=<<<XMLX
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
XMLX;
        }
        $xml.=<<<XMLM
    </channel>
</rss>
XMLM;
        file_put_contents($filename,$xml);
        die($xml);
    }
    private function __isRandMarkImage(&$name){
        $q=I('request.q',0)+0;
        $lib=$this->lib;
        $mark_hash=strtr($name,array($lib::FEED_IMAGE_PRE=>'','.jpeg'=>''));
        if(strlen($mark_hash)==16){
            $fid=M('feeds_marks')->where(['mark_img_hash'=>$mark_hash])->getField('fid');
//            $image_hash=M('feeds_items')->where(['fid'=>$fid])
//            ->order('image_isdown desc,RAND() asc')
//            ->getField('image_hash');
            $image_hash=M('feeds_items')->where(['fid'=>$fid])
                ->field('image_hash')
                ->order('g_id asc')
                ->limit($q,1)
                ->select();
            $image_hash=$image_hash[0]['image_hash'];
            $name="$image_hash-$mark_hash.jpeg";
        }
    }
    private function __image($name){
        $this->__isRandMarkImage($name);
        $cachetime=8640000;
        header ('Content-Type: image/jpeg');
        header ('Pragma: private');
        header ("Cache-Control: private, max-age=$cachetime, pre-check=$cachetime");
        if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])){
            // if the browser has a cached version of this image, send 304
            header('Last-Modified: '.$_SERVER['HTTP_IF_MODIFIED_SINCE'],true,304);
            exit;
        }
        
        $lib=$this->lib;
        $filename=$lib::PATH_FEED_IMAGE_MARKS.$name;
        if(file_exists($filename)){
            header ("Expires: " . gmdate ("r", (filectime($filename) + $cachetime)));
            header ("Last-Modified: " . gmdate ('r', filectime($filename)));
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
            header ("Expires: " . gmdate ("r", (filectime($image) + $cachetime)));
            header ("Last-Modified: " . gmdate ('r', filectime($image)));
            die(file_get_contents($image));
        }
        $image_mark=$lib::PATH_FEED_MARKS.$mark_hash.'.png';
        mk($lib::PATH_FEED_IMAGE_MARKS);

        
        $feeds_marks=M('feeds_marks')->field('background,bg_img_path')->where(['mark_img_hash'=>$mark_hash])->find();
        $background=$feeds_marks['background'];
        if($background || $feeds_marks['bg_img_path']){
            $background=json_decode($background,true);
            $im = imagecreatetruecolor($background['image']['width'], $background['image']['height']);
            $cfff = imagecolorallocate($im, 255, 255, 255);
            imagefill($im, 0, 0, $cfff);
            //+bg
            if(file_exists($feeds_marks['bg_img_path'])){
                list($width, $height, $type, $attr) = getimagesize($feeds_marks['bg_img_path']);
                $im_bgb=new \Think\Image(\Think\Image::IMAGE_GD,$feeds_marks['bg_img_path']);
                $im_bgb_src=$im_bgb->img->img;
                imagecopy($im,$im_bgb_src,0,0
                    ,0,0
                    ,$width<$background['image']['width']?$width:$background['image']['width']
                    ,$height<$background['image']['height']?$height:$background['image']['height']
                );
                imagedestroy($im_bgb_src);
            }
            //+bg - sucai
            list($su_width, $su_height, $su_type, $su_attr) = getimagesize($image);
            $bg_size=$background['size']/100;
            //$bg_width=ceil($background['image']['width']*$bg_size);
            //$bg_height=ceil($background['image']['height']*$bg_size);
            $bg_width=ceil(800*$bg_size);
            $bg_height=$bg_width;
            if($background['image']['width']==$su_width){
                $su_width = $background['image']['width'];
                $su_height = $background['image']['height'];
            }
            $im2=new \Think\Image(\Think\Image::IMAGE_GD,$image);
            $im_src=$im2->img->img;
            //$im_src=imagecreatefromjpeg($image);
            imagecopyresized($im,$im_src,$background['position']['x'],$background['position']['y']
                ,0,0
                ,$bg_width,$bg_height
                //,$background['image']['width'],$background['image']['height']
                ,$su_width,$su_height
            );
            imagedestroy($im_src);
            //+mark
            $im_src=imagecreatefrompng($image_mark);
            imagecopy($im,$im_src,0,0
                ,0,0
                ,$background['image']['width'],$background['image']['height']
            );
            imagedestroy($im_src);
            //+save
            imagejpeg ($im,$filename,100);
            imagedestroy($im);
        }else{
            $iii = new \Think\Image();
            $iii->open($image)
                ->water($image_mark,\Think\Image::IMAGE_WATER_CENTER,100)
                ->save($filename,null,100);
        }
        header ("Expires: " . gmdate ("r", (filectime($filename) + $cachetime)));
        header ("Last-Modified: " . gmdate ('r', filectime($filename)));
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
