<?php
/**
 * author vking
 * 文章
 */
namespace Modules\assets;
use FacebookAds\Api;
use FacebookAds\Object\Ad;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\AdVideo;

class lib{
    function __construct($id="") {
    	$this->model=new model();
    }
    function getAssetForAd(){
        $ad_id=I('request.ad_id','');
        if(!$ad_id)   return;
        $insight_id=$ad_id.'.lifetime';
        $count=M('assets_insights')->where("insight_id='{$insight_id}'")->count();
        if($count>0) return;

        $ac_id=I('request.ac_id');
        if(!$ac_id) return;
        $ac=FBC($ac_id);
        vendor("vendor.autoload");
        $fba=Api::init($ac['app_id'],$ac['app_secret'],$ac['access_tokens']);
        $api = Api::instance();

        $fields_str=<<<END
        ["account_id"] => string(6) "string"
        ["actor_id"] => string(6) "string"
        ["adlabels"] => string(13) "list<AdLabel>"
        ["applink_treatment"] => string(16) "ApplinkTreatment"
        ["body"] => string(6) "string"
        ["call_to_action_type"] => string(16) "CallToActionType"
        ["effective_instagram_story_id"] => string(6) "string"
        ["effective_object_story_id"] => string(6) "string"
        ["id"] => string(6) "string"
        ["image_crops"] => string(13) "AdsImageCrops"
        ["image_hash"] => string(6) "string"
        ["image_url"] => string(6) "string"
        ["instagram_actor_id"] => string(6) "string"
        ["instagram_permalink_url"] => string(6) "string"
        ["instagram_story_id"] => string(6) "string"
        ["link_og_id"] => string(6) "string"
        ["link_url"] => string(6) "string"
        ["name"] => string(6) "string"
        ["object_id"] => string(6) "string"
        ["object_story_id"] => string(6) "string"
        ["object_story_spec"] => string(25) "AdCreativeObjectStorySpec"
        ["object_type"] => string(10) "ObjectType"
        ["object_url"] => string(6) "string"
        ["platform_customizations"] => string(6) "Object"
        ["product_set_id"] => string(6) "string"
        ["status"] => string(6) "Status"
        ["template_url"] => string(6) "string"
        ["template_url_spec"] => string(6) "Object"
        ["thumbnail_url"] => string(6) "string"
        ["title"] => string(6) "string"
        ["url_tags"] => string(6) "string"
        ["use_page_actor_override"] => string(4) "bool"
        ["video_id"] => string(6) "string"
        ["call_to_action"] => string(6) "Object"
        ["dynamic_ad_voice"] => string(14) "DynamicAdVoice"
        ["image_file"] => string(6) "string"
END;
        preg_match_all("/\[\"(.*)\"\]/",$fields_str,$match);
        $fields=$match[1];

        $ad=new Ad($ad_id);
        $adsets=$ad->getAdCreatives($fields);
        if($adsets->valid()){
            $data=$adsets->current()->getData();
            if($child=$data['object_story_spec']['link_data']['child_attachments']){
                $assets=[];
                $assets_insights=[];
                foreach ($child as $r){
                    $id=$r['video_id']?$r['video_id']:"{$ac_id}:{$r['image_hash']}";
                    array_push($assets_insights,array(
                        'asset_id'=>$id,
                        'insight_id'=>$insight_id
                    ));
                    $count=$this->model->where("id='{$id}'")->count();
                    if($count<1){
                        array_push($assets,array(
                            'account_id'=>$ac_id,
                            'hash'=>$r['image_hash'],
                            'id'=>$id,
                            'name'=>$r['name'],
                            'filehash'=>md5($id),
                            'type'=>$r['video_id']?1:0,
                            'permalink_url'=>$r['picture']
                        ));
                    }
                }
                if(count($assets)>0){
                    $this->model->addAll($assets);
                    asyn('apido/asyn.setAssetsImageInfo',array('ac_id'=>$ac_id));
                    asyn('apido/asyn.setAssetsVideoInfo',array('ac_id'=>$ac_id));
                }
                if(count($assets_insights)>0){
                    M('assets_insights')->addAll($assets_insights);
                }
            }
            return $data;
        }

    }
    function setAssetsImageInfo(){
        $ac_id=I('request.ac_id');
        if(!$ac_id) return;
        $hashes=[];
        $assets=$this->model->field('hash')
            ->where("account_id='{$ac_id}' and type=0 and created_time is null")
            ->limit(10)
            ->select();
        foreach ($assets as $r){
            array_push($hashes,$r['hash']);
        }
        if(!$hashes) return;
        $ac=FBC($ac_id);
        vendor("vendor.autoload");
        $fba=Api::init($ac['app_id'],$ac['app_secret'],$ac['access_tokens']);
        $api = Api::instance();
        $fields_str=<<<END
        ["account_id"] => string(6) "string"
        ["created_time"] => string(8) "datetime"
        [~"creatives"] => string(12) "list<string>"
        ["hash"] => string(6) "string"
        ["height"] => string(12) "unsigned int"
        ["id"] => string(6) "string"
        [~"is_associated_creatives_in_adgroups"] => string(4) "bool"
        ["name"] => string(6) "string"
        ["original_height"] => string(12) "unsigned int"
        ["original_width"] => string(12) "unsigned int"
        ["permalink_url"] => string(6) "string"
        ["status"] => string(6) "Status"
        ["updated_time"] => string(8) "datetime"
        ["url"] => string(6) "string"
        ["url_128"] => string(6) "string"
        ["width"] => string(12) "unsigned int"
        ["bytes"] => string(6) "string"
        ["copy_from"] => string(6) "Object"
        ["zipbytes"] => string(6) "string"
        ["filename"] => string(4) "file"
END;
        preg_match_all("/\[\"(.*)\"\]/",$fields_str,$match);
        $fields=$match[1];

        $account=new AdAccount($ac['act_id']);
        $adsets = $account->getAdImages(
            $fields,
            array(
                'hashes'=>$hashes
            )
        );
        $data=[];
        while ($adsets->valid()) {
            $_d= $adsets->current()->getData();
            $data=array(
                'name'=>$_d['name'],
                'created_time'=>$_d['created_time'],
                'updated_time'=>$_d['updated_time'],
                'width'=>$_d['width'],
                'height'=>$_d['height'],
                'original_width'=>$_d['original_width'],
                'original_height'=>$_d['original_height'],
                'permalink_url'=>$_d['permalink_url'],
                'uptime'=>NOW_TIME,
                'url'=>$_d['permalink_url'],
            );
            $this->model->where(array('id'=>$_d['id']))->save($data);
            $adsets->next();
        }
        asyn_implement('apido/asyn.setAssetsFileHash');
        return $data;
    }
    function setAssetsVideoInfo(){
        $ac_id=I('request.ac_id');
        if(!$ac_id) return;
        $hashes=[];
        $assets=$this->model->field('id')
            ->where("account_id='{$ac_id}' and type=1 and created_time is null")
            ->limit(3)
            ->select();
        if(!$assets) return;
        
        $ac=FBC($ac_id);
        vendor("vendor.autoload");
        $fba=Api::init($ac['app_id'],$ac['app_secret'],$ac['access_tokens']);
        $api = Api::instance();
        $fields_str=<<<END
        ["created_time"] => NULL
        ["description"] => NULL
        ["embed_html"] => NULL
        ["embeddable"] => NULL
        ["format"] => NULL
        ["from"] => NULL
        ["icon"] => NULL
        ["id"] => string(16) "1928026167434181"
        ["is_instagram_eligible"] => NULL
        [`"name"] => NULL
        ["picture"] => NULL
        ["published"] => NULL
        [`"slideshow_spec"] => NULL
        ["source"] => NULL
        ["thumbnails"] => NULL
        ["updated_time"] => string(24) "2017-06-22T07:02:47+0000"
END;
        preg_match_all("/\[\"(.*)\"\]/",$fields_str,$match);
        $fields=$match[1];

        foreach ($assets as $r){
            $ad=new AdVideo($r['id']);
            $_d=$ad->read($fields)->getData();
            $data=array(
                //'name'=>$_d['name'],
                'created_time'=>$_d['created_time'],
                'updated_time'=>$_d['updated_time'],
                //'width'=>$_d['width'],
                //'height'=>$_d['height'],
                //'original_width'=>$_d['original_width'],
                //'original_height'=>$_d['original_height'],
                //'permalink_url'=>$_d['permalink_url'],
                'uptime'=>NOW_TIME,
                'url'=>$_d['source'],
            );
            $this->model->where(array('id'=>$_d['id']))->save($data);
        }
        asyn_implement('apido/asyn.setAssetsFileHash');
        return $data;
    }
    function setAssetsFileHash(){
        $assets=$this->model->field('id,permalink_url')
            ->where("is_filehash=0 and permalink_url is not null")
            ->limit(2)
            ->order('uptime desc')
            ->select();
        foreach ($assets as $r){
            $cont=http($r['permalink_url'],'',30);
            if($cont){
                $this->model->where("id='{$r['id']}'")->save(array(
                    'filehash'=>md5($cont),
                    'is_filehash'=>1
                ));
            }
        }
        if(count($assets)>1) asyn_implement('apido/asyn.setAssetsFileHash');
    }
    function getData($filehash=''){
        $where=[];
        if($filehash){
            $where['A.filehash']=$filehash;
        }
        $data=$this->model->alias('A')
            ->field('A.*,sum(ADI.*)')
            ->join('assets_insights AI ON AI.asset_id=A.id')
            ->join('ads_insights ADI ON ADI.id=AI.insight_id','left')
            ->where($where)
            ->group('A.account_id')
            ->limit(30)
            ->select();
        return ['data'=>$data];
    }
}