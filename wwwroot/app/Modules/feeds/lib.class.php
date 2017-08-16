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
    const FEED_UPTIME=86400;
    const ITEM_IMAGE_DOWN_NOK=0;
    const ITEM_IMAGE_DOWN_OK=1;
    const PATH_FEED_XML='uploads/feeds/xmls/';
    const PATH_FEED_IMAGE='uploads/feeds/images/';
    const PATH_FEED_MARKS='uploads/feeds/marks/';
    const PATH_FEED_IMAGE_MARKS='uploads/feeds/images-marks/';
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
            $this->model->where(array('id'=>$id))->save($data);
        }else{
            $data['addtime']=NOW_TIME;
            $id=$this->model->add($data);
        }
        asyn('apido/asyn.flushFeed',array('id'=>$id));
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
            $xml=file_get_contents($feed['url']);
            mk(self::PATH_FEED_XML);
            file_put_contents(self::PATH_FEED_XML.$feed['id'].'.xml',$xml);
            asyn('apido/asyn.flushFeedParseXML',array('id'=>$feed['id']));
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
        $url=urldecode(I('request.url'));
        $cc=file_get_contents($url);
        if($cc){
            file_put_contents(self::PATH_FEED_IMAGE.md5($url).'.jpg',$cc);
        }

    }
    function getFeedsImageInfo(){
        $fid=I('request.fid');
        $item=M('feeds_items')->where(['fid'=>$fid])->order('image_isdown desc')->find();
        if($item){
            $image=self::PATH_FEED_IMAGE.$item['image_hash'].'.jpg';
            if(file_exists($image)) {
                $arr = getimagesize($image);
                $url=url("feeds/".self::FEED_IMAGE_PRE."{$item['image_hash']}.jpeg");
                $url=str_replace(':8080','',$url);
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
        foreach ($data as &$r){
            $r['mark_url']=url('feeds/'.self::FEED_MARKS_PRE.$r['id'].'.xml');
        }
        return array('data'=>$data);
    }
    function __setFeedsMarkImage($filename){
        $image= I('request.image_base64','','trim');
        $image=explode(",",$image);
        file_put_contents($filename,base64_decode($image[1]));
    }
    function setFeedsMark(){
        mk(self::PATH_FEED_MARKS);
        $id=I('request.id');
        $data['fid']=I('request.fid');
        $data['name']=I('request.name');
        $data['mark_object']=I('request.json','','trim');
        $data['mark_img_hash']=substr(md5($data['mark_object']),8,16);
        $data['mark_img_path']=self::PATH_FEED_MARKS.$data['mark_img_hash'].'.png';

        if($id){
            $mark=M('feeds_marks')->find($id);
            if($mark['mark_img_hash']!=$data['mark_img_hash']){
                //保存图片信息
                $this->__setFeedsMarkImage($data['mark_img_path']);
            }
            M('feeds_marks')->where(array('id'=>$id))->save($data);
        }else{
            $this->__setFeedsMarkImage($data['mark_img_path']);
            M('feeds_marks')->add($data);
        }
    }
}