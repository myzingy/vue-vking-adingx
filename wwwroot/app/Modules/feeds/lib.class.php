<?php
/**
 * author vking
 * 文章
 */
namespace Modules\feeds;

use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Cursor;
use FacebookAds\Object\Values\ArchivableCrudObjectEffectiveStatuses;
//广告组
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Fields\AdFields;

class lib
{
    const FEED_UPTIME=43200;//半天更新一次
    const ITEM_IMAGE_DOWN_NOK=0;
    const ITEM_IMAGE_DOWN_OK=1;
    const PATH_FEED_XML='uploads/feeds/xmls/';
    const PATH_FEED_IMAGE='uploads/feeds/images/';
    const PATH_FEED_MARKS='uploads/feeds/marks/';
    const PATH_FEED_IMAGE_MARKS='uploads/feeds/images-marks/';
    const PATH_FEED_IMAGE_BASE64='uploads/feeds/images-base64/';
    const FEED_MARKS_PRE='mark-';
    const FEED_IMAGE_PRE='img-';

    function __construct($id = "")
    {
        $this->model = new model();
    }

    function getFeeds(){
        $data=$this->model->order('id desc')->select();
        return array('data'=>$data);
    }
    function setFeeds(){
        $id=I('request.id');
        $keys=array('brand','url','utm_source','utm_medium','utm_campaign','utm_content','utm_term');
        foreach ($keys as $key){
            $data[$key]=I('request.'.$key);
        }
        if($id){
            $data['uptime']=0;
            $this->model->where(array('id'=>$id))->save($data);
            $marks=M('feeds_marks')->field('id')->where(['fid'=>$id])->select();
            foreach ($marks as $mark){
                @unlink(self::PATH_FEED_XML.self::FEED_MARKS_PRE.$mark['id'].'.xml');
            }
        }else{
            $data['addtime']=NOW_TIME;
            $id=$this->model->add($data);
        }
    }
    //flushFeedDownXML
    function flushFeed(){
        set_time_limit(0);
        $id=I('request.id');
        if($id){
            $feed=$this->model->where(array(
                'id'=>$id
            ))->find();
        }else{
            $feed=$this->model->where(array(
                'uptime'=>array('lt',NOW_TIME-self::FEED_UPTIME)
            ))->find();
        }
        if($feed['id'] && $feed['url']){
            //$xml=file_get_contents($feed['url']);
            $xml=http($feed['url'],'',300);
            mk(self::PATH_FEED_XML);
            file_put_contents(self::PATH_FEED_XML.$feed['id'].'.xml',$xml);
            asyn('apido/asyn.flushFeedParseXML',array('id'=>$feed['id'],'time'=>date("YmdH")));
        }
    }
    function __parseDataitem(&$item){
        $keys='g_id,g_title,g_description,g_link,g_image_link,g_brand,g_availability,g_condition,g_price,g_custom_label_0,g_custom_label_1,g_custom_label_2,g_custom_label_3,g_custom_label_4,g_google_product_category';
        foreach (explode(',',$keys) as $key){
            if(empty($item[$key])){
                $item[$key]="";
            }
        }
    }
    function flushFeedParseXML(){
        mk(self::PATH_FEED_IMAGE);
        $feed_id=I('request.id');
        if(!$feed_id) return 'feed_id is null';
        $xml_path=self::PATH_FEED_XML.$feed_id.'.xml';
        if(file_exists($xml_path)){
            $reader=new \XMLReader();
            $reader->open($xml_path, 'UTF-8');
            //$reader->xml($xml);
            $data=['count_items'=>0];
            $data_items=[];
            while ($reader->read()){
                if($reader->nodeType == \XMLReader::ELEMENT
                    && in_array($reader->name,['title','link','description'])!=false){
                    $datakey=$reader->name;
                    $reader->read();
                    $data[$datakey]=$reader->value;
                    continue;
                }
                if($reader->nodeType == \XMLReader::ELEMENT && $reader->name==='item'){
                    $data['count_items']+=1;
                    $data_item=[];
                    while ($reader->read()){
                        if($reader->nodeType == \XMLReader::ELEMENT){
                            $datakey=str_replace(':','_',$reader->name);
                            $reader->read();
                            $data_item[$datakey]=$reader->value;
                        }
                        if($datakey==='g_google_product_category'){
                            $datakey="";
                            break;
                        }
                    }
                    $this->__parseDataitem($data_item);
                    $data_item['fid']=$feed_id;
                    $data_item['image_isdown']=self::ITEM_IMAGE_DOWN_NOK;
                    $data_item['image_hash']=md5($data_item['g_image_link']);
                    if(!file_exists(self::PATH_FEED_IMAGE.$data_item['image_hash'].'.jpg')){
                        asyn_implement('apido/asyn.downFeedImage',array(
                            'url'=>urlencode($data_item['g_image_link'])
                        ));
                    }else{
                        $data_item['image_isdown']=self::ITEM_IMAGE_DOWN_OK;
                    }
                    array_push($data_items,$data_item);
                }
            }
            $reader->close();
            $data['uptime']=NOW_TIME;
            $this->model->where(array(
                'id'=>$feed_id
            ))->save($data);
            if($data_items){
                M('feeds_items')->where(array(
                    'fid'=>$feed_id
                ))->delete();
                M('feeds_items')->addAll($data_items);
            }
        }
        return ['data'=>$data];
    }
    function downFeedImage(){
        set_time_limit(0);
        $url=urldecode(I('request.url'));
//        $context['http'] = array(
//            'timeout'=>120,
//            'method' => 'GET',
//            'content' => '',
//        );
//        $cc=file_get_contents($url,false, stream_context_create($context));
        $cc=post($url,null,null,true);
        if($cc && strlen($cc)>5000){
            file_put_contents(self::PATH_FEED_IMAGE.md5($url).'.jpg',$cc);
        }

    }
    function getFeedsImageInfo(){
        $fid=I('request.fid');
        $item=M('feeds_items')->where(['fid'=>$fid])->order('image_isdown desc,RAND() asc')->find();
        if($item){
            $image=self::PATH_FEED_IMAGE.$item['image_hash'].'.jpg';
            if(file_exists($image)) {
                $arr = getimagesize($image);
                $url=url("feeds/".self::FEED_IMAGE_PRE."{$item['image_hash']}.jpeg");
                return ['data'=>[
                    'width'=>$arr[0],
                    'height'=>$arr[1],
                    'url'=>$url,
                ]];
            }
        }
        return "此 Feed 未更新完成，不能获取图片信息";
    }
    function getFeedsImage(){
        $file=explode('.',I('request.file'));
        if(strpos('-',$file[0])===false){
            $image=self::PATH_FEED_IMAGE.$file[0].'.jpg';
            die(file_get_contents($image));
        }
    }
    function getFeedsMark(){
        $data=M('feeds_marks')->alias('FM')
            ->field('FM.*,F.url,F.count_items,F.brand')
            ->join('feeds F ON F.id=FM.fid','left')
            ->order('FM.id desc')
            ->select();
        $image_hash=[];
        if($data){
            foreach ($data as &$r){
                if(!$image_hash[$r['fid']]){
                    $image_hash[$r['fid']]=M('feeds_items')->where(['fid'=>$r['fid']])->order('image_isdown desc,RAND() asc')
                        ->getField('image_hash');
                }
                $r['mark_url']=url('feeds/'.self::FEED_MARKS_PRE.$r['id'].'.xml');
                //$r['mark_img_path']=url($r['mark_img_path']);
                //$r['mark_bgimg']=url("feeds/".self::FEED_IMAGE_PRE."{$r['fid']}.jpeg");
                $r['background']=json_decode($r['background'],true);
                //$r['mark_object']=$r['mark_object']?$r['mark_object']:[];
                $r['mark_img_path']=url("feeds/img-{$image_hash[$r['fid']]}-{$r['mark_img_hash']}.jpeg");
            }
        }
        return array('data'=>$data);
    }
    function __setFeedsMarkImage($filename){
        $image= I('request.image_base64','','trim');
        $image=explode(",",$image);
        file_put_contents($filename,base64_decode($image[1]));
    }
    function __setFeedsMarkBase64Image(&$jsonstr){
        if($jsonstr){
            mk(self::PATH_FEED_IMAGE_BASE64);
            $object=json_decode($jsonstr,true);
            foreach ($object['objects'] as &$obj){
                if($obj['type']=='image' && strpos($obj['src'],'base64,')>0){
                    $image=explode(",",$obj['src']);
                    $ext_flag=preg_match("/\/([^;]+);/",$image[0],$ext);
                    if($ext_flag){
                        $ext='.'.$ext[1];
                    }else{
                        $ext='.jpg';
                    }
                    $filename=self::PATH_FEED_IMAGE_BASE64.md5($obj['src']).$ext;
                    if(!file_exists($filename)) {
                        file_put_contents($filename,base64_decode($image[1]));
                    }
                    $obj['src']=url($filename);
                }
            }
            $jsonstr=json_encode($object);
        }
    }
    function __setFeedsMarkBackImage(&$data){
        $image= I('request.bg_img_path','','trim');
        if(!$image)return;
        if(strpos($image,'base64')!==false){
            $data['bg_img_hash']=substr(md5($image),8,16);
            $image=explode(",",$image);
            $ext_flag=preg_match("/\/([^;]+);/",$image[0],$ext);
            if($ext_flag){
                $ext='.'.$ext[1];
            }else{
                $ext='.jpg';
            }
            $data['bg_img_path']=self::PATH_FEED_MARKS.$data['bg_img_hash'].$ext;
            if(!file_exists($data['bg_img_path'])) {
                file_put_contents($data['bg_img_path'],base64_decode($image[1]));
            }
        }else{
            $data['bg_img_path']=$image;
            $data['bg_img_hash']=I('request.bg_img_hash','','trim');
        }
    }
    function setFeedsMark(){
        mk(self::PATH_FEED_MARKS);
        $id=I('request.id');
        $data=[];
        //background-img
        $this->__setFeedsMarkBackImage($data);
        
        $data['fid']=I('request.fid');
        $data['name']=I('request.name');
        $data['mark_object']=I('request.json','','trim');
        $data['background']=I('request.background','','trim');
        $data['mark_img_hash']=substr(md5($data['mark_object'].$data['background']
            .($data['bg_img_hash']?$data['bg_img_hash']:"")),8,16);
        $data['mark_img_path']=self::PATH_FEED_MARKS.$data['mark_img_hash'].'.png';
        $this->__setFeedsMarkBase64Image($data['mark_object']);

        if($id){
            $mark=M('feeds_marks')->find($id);
            if($mark['mark_img_hash']!=$data['mark_img_hash']){
                //保存图片信息
                $this->__setFeedsMarkImage($data['mark_img_path']);
                @unlink(self::PATH_FEED_XML.self::FEED_MARKS_PRE.$id.'.xml');
            }
            M('feeds_marks')->where(array('id'=>$id))->save($data);
        }else{
            $this->__setFeedsMarkImage($data['mark_img_path']);
            M('feeds_marks')->add($data);
        }
    }
}