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
                $url=url("apido/getFeedsImage?file={$item['image_hash']}.jpg");
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
}