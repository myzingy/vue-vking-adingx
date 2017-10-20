<?php
/**
 * author vking
 * 文章
 */
namespace Modules\products;
use FacebookAds\Api;
use FacebookAds\Exception\Exception;
use FacebookAds\Object\Ad;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\AdVideo;
use FacebookAds\Object\ProductCatalog;
use FacebookAds\Object\ProductSet;

class lib{
    const AdAccountID='1593565507558990';
    const PRODUCTS_SET_ID='698747693656559';
    //const PRODUCTS_SET_ID='1967716870107460';
    //https://s3.us-east-2.amazonaws.com/facebook-da-videos/3d-video/BG6C001.mp4
    const PRODUCTS_VIDEO_SER='https://s3.us-east-2.amazonaws.com/facebook-da-videos/3d-video/';
    const PRODUCTS_VIDEO_EXT='.mp4';
    const PRODUCTS_SKU_FIELD='custom_label_4';

    function __construct($id="") {
    	$this->model=new model();
    }
    function flushProducts(){
        $app=C('fbapp');
        vendor("vendor.autoload");
        $fba=Api::init($app['app_id'],$app['app_secret'],$app['robot_token']);
        $api = Api::instance();

        $fields_str=<<<END
            [`"age_group"] => string(0) ""
            [`"availability"] => string(8) "in stock"
            ["brand"] => string(6) "jeulia"
            [`"category"] => string(112) "Engagement,Bridal Ring Sets,Flash Sale,WEDDING,Wedding Sets,Halo Ring Sets,TOP50,Affiliate,Affiliate Best Seller"
            [`"condition"] => string(3) "new"
            [`"currency"] => string(3) "USD"
            [`"custom_data"] => array(0) 
            ["custom_label_0"] => string(4) "4139"
            ["custom_label_1"] => string(4) "4499"
            ["custom_label_2"] => string(5) "1.087"
            ["custom_label_3"] => string(3) "112"
            ["custom_label_4"] => string(9) "RRG1231-6"
            [`"description"] => string(189) "If you're looking for unique jewelry with reasonable price then you've come to the right place. Jeulia makes great diamond alternatives and most importantly is a financially smart decision."
            [`"gender"] => string(0) ""
            ["id"] => string(16) "1415645021883508"
            ["image_url"] => string(142) "https://scontent.xx.fbcdn.net/v/t45.5328-4/18623764_1246470155465882_7788418665782706176_n.jpg?oh=2a048d214b3c1db3e52a195478bb93a5&oe=5A71084C"
            ["name"] => string(67) "Jeulia Halo Princess Cut Created White Sapphire Wedding Set 2.05 CT"
            [`"ordering_index"] => int(0)
            ["price"] => string(7) "$139.95"
            [`"product_catalog"] => array(2) 
            [`"product_feed"] => array(3) 
            ["retailer_id"] => string(4) "1276"
            [`"retailer_product_group_id"] => string(0) ""
            [`"review_status"] => string(8) "approved"
            [`"shipping_weight_unit"] => string(0) ""
            [`"shipping_weight_value"] => int(0)
            ["url"] => string(160) "http://www.jeulia.com/vintage-milgrain-sculptural-halo-princess-cut-created-sapphire-rhodium-plated-925-sterling-silver-women-s-wedding-ring-set-bridal-set.html"
            [`"visibility"] => string(9) "published"
            ["video_ids"] => array(1)  
END;
        preg_match_all("/\[\"(.*)\"\]/",$fields_str,$match);
        $fields=$match[1];
        $productId=I('request.productId','');
        $campaigns_data=array();
        if(!$productId){
            $ps = new ProductSet(self::PRODUCTS_SET_ID);
            $asyn_param=array('after'=>I('request.after',''));
            $products = $ps->getProducts(
                $fields,
                $asyn_param
            );
            $asyn_param['after']=$products->getAfter();
            while ($products->valid()) {
                $_d=$products->current()->getData();
                $_campaigns_data=array();
                foreach ($fields as $i=>$fk){
                    if('video_ids'==$fk){
                        $_campaigns_data[$fk]= $_d[$fk]?json_encode($_d[$fk]):"[]";
                    }else{
                        $_campaigns_data[$fk]= $_d[$fk];
                    }
                }
                array_push($campaigns_data,$_campaigns_data);
                $products->next();
            }
            if($products->getNext() && count($campaigns_data)==25){
                asyn('apido/asyn.flushProducts',$asyn_param,null,null,2);
            }else{
                asyn('apido/asyn.flushProducts',[],null,getDayTime('04:00:00',1),2);
            }
        }else{
            $products = new ProductCatalog($productId);
            $products=$products->getSelf($fields);
            $_d=$products->getData();
            $_campaigns_data=array();
            foreach ($fields as $i=>$fk){
                if('video_ids'==$fk){
                    $_campaigns_data[$fk]= $_d[$fk]?json_encode($_d[$fk]):"[]";
                }else{
                    $_campaigns_data[$fk]= $_d[$fk];
                }
            }
            array_push($campaigns_data,$_campaigns_data);
        }

        if(count($campaigns_data)>0){
            $this->model->addAll($campaigns_data,null,true);
        }
        return $campaigns_data;
    }
    function uploadBindVideo($prod,$video_id=""){
        $app=C('fbapp');
        //up
        ### {"id":"147164259356494"}
        if($video_id || 'UNBIND'==$video_id){
            $res='{"id":"'.$video_id.'"}';
        }else{
            $file_url=$this->getVideoUrl($prod[self::PRODUCTS_SKU_FIELD]);
            //var_dump($file_url);
            $res=post('https://graph-video.facebook.com/v2.10/act_'.self::AdAccountID.'/advideos',array(
                'file_url'=>$file_url,
                'access_token'=>$app['robot_token'],
            ));
        }
        if(!$res){
            return ['code'=>500,'message'=>'up fail.'];
        }
        $json=json_decode($res,true);
        //var_dump($json);
        if(!$video_id=$json['id']){
            return $json['error'];
        }
        //bind video
        vendor("vendor.autoload");
        $fba=Api::init($app['app_id'],$app['app_secret'],$app['robot_token']);
        $api = Api::instance();
        $ps = new ProductCatalog($prod['id']);
        ### {"success":true}
        $ps->save([
            'video_ids'=>$video_id=='UNBIND'?'[]':[$video_id]
        ]);
        $res= post(url('apido/asyn.flushProducts'),array('productId'=>$prod['id']));
        return ['code'=>200,'data'=>json_decode($res)];
    }
    function  flushProductsItem(){

        $item=$this->model->where(['video_ids'=>'[]'])->order('rand()')->find();
        if($item){
            $this->uploadBindVideo($item);
        }
        return $item;
    }
    function getProducts(){
        $offset=I('request.offset',0);
        $limit=I('request.limit',30);
        $where=" 1=1 ";
        $keyword=trim(I('request.keyword',''));
        if($keyword){
            $where.=" AND (name like '%$keyword%' OR ".self::PRODUCTS_SKU_FIELD." like '%$keyword%')";
        }
        $data=M('products')->where($where)->order('retailer_id asc')
            ->limit($offset,$limit)->select();
        foreach ($data as &$r){
            //$r['time_format']=date('m-d H:i',$r['time']);
            $r['video_url']=$this->getVideoUrl($r[self::PRODUCTS_SKU_FIELD]);
        }
        $total=M('products')->where($where)->count();
        return array('data'=>$data,'total'=>$total+0);
    }
    function bindProductVideo(){
        $productId=I('request.productId','');
        $item=$this->model->where(['id'=>$productId])->find();
        if($item){
            $res=$this->uploadBindVideo($item);
            return $res;
        }
    }
    function unBindProductVideo(){
        $productId=I('request.productId','');
        $item=$this->model->where(['id'=>$productId])->find();
        if($item){
            $res=$this->uploadBindVideo($item,'UNBIND');
            return $res;
        }
    }
    function getVideoUrl($sku){
        return self::PRODUCTS_VIDEO_SER
            .urlencode(strtoupper($sku))
            .self::PRODUCTS_VIDEO_EXT;
    }
}