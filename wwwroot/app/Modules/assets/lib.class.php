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
        $debug=I('request.debug','');
        $ad_id=I('request.ad_id','');
        if(!$ad_id)   return;
        $insight_id=$ad_id.'.lifetime';
        if(!$debug){
            $count=M('assets_insights')->where("insight_id='{$insight_id}'")->count();
            if($count>0) return;
        }

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
                //$assets=[];
                //$assets_insights=[];
                foreach ($child as $r){
                    $id=$r['video_id']?$r['video_id']:"{$ac_id}:{$r['image_hash']}";
                    $ex_count=$this->model->where("id='{$id}'")->count();
                    if($ex_count<1){
                        $assets=array(
                            'account_id'=>$ac_id,
                            'hash'=>$r['image_hash'],
                            'id'=>$id,
                            'name'=>$r['name'],
                            'filehash'=>md5($id),
                            'type'=>$r['video_id']?1:0,
                            'permalink_url'=>$r['picture']
                        );
                        $this->model->add($assets);
                    }
                    $assets_insights=array(
                        'asset_id'=>$id,
                        'insight_id'=>$insight_id
                    );
                    M('assets_insights')->where($assets_insights)->delete();
                    M('assets_insights')->add($assets_insights);
                }
//                if(count($assets)>0){
//                    $assets=array_values($assets);
//                    $this->model->addAll($assets);
//                }
//                if(count($assets_insights)>0){
//                    $assets_insights=array_values($assets_insights);
//                    M('assets_insights')->addAll($assets_insights);
//                }
            }
            asyn('apido/asyn.setAssetsImageInfo',array('ac_id'=>$ac_id));
            asyn('apido/asyn.setAssetsVideoInfo',array('ac_id'=>$ac_id));
            return $data;
        }

    }
    function getAssetsImageInfo($ac_id,$hashes){
        $debug=I('request.debug');
        $ac_id=$ac_id?$ac_id:I('request.ac_id');
        $hashes=$hashes?$hashes:explode(',',I('request.hashes'));
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
        if($debug){
            return $adsets->current()->getData();
        }
        return $adsets;
    }
    function setAssetsImageInfo(){
        $ac_id=I('request.ac_id');
        if(!$ac_id) return;
        $hashes=[];
        $assets=$this->model->field('hash')
            ->where("account_id='{$ac_id}' and type=0 and `hash` is not null and created_time is null")
            ->limit(10)
            ->select();
        foreach ($assets as $r){
            array_push($hashes,$r['hash']);
        }
        if(!$hashes) return;
        $adsets = $this->getAssetsImageInfo($ac_id,$hashes);
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
    function getAssetsVideoInfo($ac_id,$video_id){
        $ac_id=$ac_id?$ac_id:I('request.ac_id');
        $video_id=$video_id?$video_id:I('request.video_id');
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
        $ad=new AdVideo($video_id);
        $_d=$ad->read($fields)->getData();
        return $_d;
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
        
        $video=$this->model->field('id,url')
            ->where("type=1 and url_128 is null and status is null")
            ->order('uptime desc')
            ->find();
        if($video){
            $filename='uploads/'.$video['id'].'.mp4';
            $this->model->where("id='{$video['id']}'")->save(array(
                'url_128'=>$filename,
                'status'=>'VIDEO'
            ));
            $cc=http($video['url']);
            file_put_contents($filename,$cc);
        }
    }
    function formatAccAssets(&$asset){
        $asset['amountspent']+=0;
        $asset['skus']=$asset['skus']?explode(',',$asset['skus']):[];
        $asset['inputVisible']=false;

        $asset['conversion_rate']=$asset['websitepurchases']/($asset['linkclicks']?$asset['linkclicks']:1);
        $asset['roas']=$asset['websitepurchasesconversionvalue']?
            $asset['amountspent']/$asset['websitepurchasesconversionvalue']
            :'X';
        $asset['positive_feedback_str']=explode(',',$asset['positive_feedback_str']);
        $asset['negative_feedback_str']=explode(',',$asset['negative_feedback_str']);
        $asset['positive_feedback']=$this->_getRecountValue($asset['positive_feedback_str']);
        $asset['negative_feedback']=$this->_getRecountValue($asset['negative_feedback_str']);
    }
    function _getDataChild($filehash){
        $dataType=I('request.dataType','lifetime');
        $where=[
            'A.filehash'=>$filehash
        ];
        $fields="count(*) as ads_num,A.*"
            .",sum(ADI.CLICK1D_WebsiteAddstoCart) as websiteaddstocart"
            .",avg(ADI.CLICK1D_CostperWebsiteAddtoCart) as costperwebsiteaddtocart"
            .",sum(ADI.spend) as amountspent"
            .",sum(ADI.CLICK1D_WebsitePurchases)as websitepurchases"
            .",sum(ADI.CLICK1D_WebsitePurchasesConversionValue)as websitepurchasesconversionvalue"
            .",sum(ADI.inline_link_clicks)as linkclicks"
            .",avg(ADI.CLICK1D_CPC)as cpc"
            .",avg(ADI.inline_link_click_ctr)as ctr"
            .",avg(ADI.cpm)as cpm1000"
            .",sum(ADI.reach)as reach"
            .",sum(ADI.CLICK1D_WebsitePurchases)as results"
            .",avg(ADI.frequency)as frequency"
            .",avg(ADI.relevance_score)as relevance_score"
            .",GROUP_CONCAT(ADI.positive_feedback)as positive_feedback_str"
            .",GROUP_CONCAT(ADI.negative_feedback)as negative_feedback_str"
            .",sum(ADI.CLICK1D_WebsiteAddstoCartConversionValue)as websiteaddstocartconversionvalue"
            .",sum(ADI.impressions)as impressions"
            .",ADI.ad_id as ad_id"
        ;
        $data=$this->model->alias('A')
            ->field($fields)
            ->join('assets_insights AI ON AI.asset_id=A.id')
            ->join("ads_insights ADI ON ADI.id=replace(AI.insight_id,'lifetime','{$dataType}')",'left')
            ->where($where)
            ->group('A.account_id')
            ->select();
        return $data;
    }
    function _sumAccAssets(&$par,$chi){
          foreach ($par as $key=>$value){
              if('type'==$key) continue;
              if('positive_feedback_str'==$key || 'negative_feedback_str'==$key){
                  $par[$key]= array_merge($par[$key],$chi[$key]);
                  continue;
              }
              if(is_numeric($value) && is_numeric($chi[$key])){
                  $par[$key]= $value+$chi[$key];
              }elseif(is_numeric($value)){
                  $par[$key]= $value;
              }elseif(is_numeric($chi[$key])){
                  $par[$key]= $chi[$key];
              }
          }

    }
    function _getRecountValue($arr){
        $maxnum=0;
        $maxval='';
        $tmp=[];
        foreach ($arr as $key){
            $tmp[$key]+=1;
            if($tmp[$key]>$maxnum){
                $maxval=$key;
            }
        }
        return $maxval;
    }
    function _avgAccAssets(&$par){
        $avg=['costperwebsiteaddtocart','cpc','cpm1000'
            ,'relevance_score','conversion_rate'
            //,'roas','ctr','frequency',
            ];
        $par['roas']= ($par['websitepurchasesconversionvalue'])?(($par['amountspent']/$par['websitepurchasesconversionvalue'])*100):'X';
        $par['ctr']= $par['linkclicks']/$par['impressions'];
        $par['frequency']= $par['impressions']/$par['reach'];
        $par['cpm1000']= ($par['amountspent']*1000)/$par['reach'];
        
        foreach ($avg as $key){
            if(is_numeric($par[$key])){
                $par[$key]= $par[$key]/$par['list_count'];
            }
        }
        $arr=array_count_values($par[positive_feedback_str]);
        $par['positive_feedback']=$this->_getRecountValue($par['positive_feedback_str']);
        $par['negative_feedback']=$this->_getRecountValue($par['negative_feedback_str']);
    }
    function getData(){
        $offset=I('request.offset',0);
        $limit=I('request.limit',30);
        $keyword=I('request.keyword');
        $assetType=I('request.assetType');
        $where=" 1=1 ";
        if($keyword){
            $where.=" and (account_id like '%$keyword%' "
                ." or author like '%$keyword%' "
                ." or skus like '%$keyword%' "
                ." or name like '%$keyword%') ";
        }
        if($assetType!=""){
            $where.=" and type='{$assetType}' ";
        }
        $filehashs=$this->model
            ->where($where)
            ->group('filehash')
            ->order('updated_time desc')
            ->limit($offset,$limit)
            ->getField('filehash',true);
        $fdata=[];
        foreach ($filehashs as $filehash){
            $data=$this->_getDataChild($filehash);
            foreach ($data as $i=>$r){
                $this->formatAccAssets($r);
                if($i==0){
                    $r['list']=[$r];
                    $r['list_count']=1;
                    $fdata[$filehash]=$r;
                }else{
                    $this->_sumAccAssets($fdata[$filehash],$r);
                    array_push($fdata[$filehash]['list'],$r);
                    $fdata[$filehash]['list_count']+=1;
                }
            }
        }
        foreach ($fdata as &$x){
            $this->_avgAccAssets($x);
            $x['url']=$x['url_128']?(url($x['url_128'])):$x['url'];
        }
        $cc=M()->query(
            'SELECT COUNT(DISTINCT `filehash`) AS tp_count FROM `assets` '
            .($where?" where $where":"")
        );
        return ['data'=>array_values($fdata),'total'=>$cc[0]['tp_count']];
    }
    function setAuthor(){
        $author=I('request.author','');
        $id=I('request.id');
        if($id){
            $this->model->where("id='{$id}'")->save(array('author'=>$author));
        }
    }
    function setSkus(){
        $skus=I('request.skus','');
        $id=I('request.id');
        if($id){
            $this->model->where("id='{$id}'")->save(array('skus'=>$skus));
        }
    }
}