<?php
/**
 * author vking
 * 文章
 */
namespace Modules\keywords;
use FacebookAds\Api;
use FacebookAds\Exception\Exception;
use FacebookAds\Object\Ad;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\AdVideo;

class lib{
    const TYPE_DATE_ALL=0;
    const TYPE_DATE_TODAY=1;
    function __construct($id="") {
    	$this->model=new model();
    }
    function flushKeywordsInsight(){
        $ad_id=I('request.ad_id','');
        $date=I('request.date');
        if(!$ad_id)   return;

        $ac_id=I('request.ac_id');
        if(!$ac_id) return;
        $ac=FBC($ac_id);
        vendor("vendor.autoload");
        $fba=Api::init($ac['app_id'],$ac['app_secret'],$ac['access_tokens']);
        $api = Api::instance();

        $campaigns_data=array();
        $fields_str=<<<END
        ["actions"] => NULL
        ["clicks"] => string(12) "unsigned int"
        ["cost_per_total_action"] => string(5) "float"
        ["cost_per_unique_click"] => string(5) "float"
        ["cpc"] => string(5) "float"
        ["cpm"] => string(5) "float"
        ["cpp"] => string(5) "float"
        ["ctr"] => string(5) "float"
        ["frequency"] => string(5) "float"
        ["id"] => string(6) "string"
        ["impressions"] => string(12) "unsigned int"
        ["name"] => string(6) "string"
        ["reach"] => string(12) "unsigned int"
        ["spend"] => string(5) "float"
        ["total_actions"] => string(12) "unsigned int"
        ["total_unique_actions"] => string(12) "unsigned int"
        ["unique_clicks"] => string(12) "unsigned int"
        ["unique_ctr"] => string(5) "float"
        ["unique_impressions"] => string(12) "unsigned int"
END;
        preg_match_all("/\[\"(.*)\"\]/",$fields_str,$match);
        $fields=$match[1];
        $campaign = new Ad($ad_id);
        $query=array();
        if($date){
            $query['date']=$date;
        }
        $adsets = $campaign->getKeywordStats($fields,$query);
        if(!$adsets->valid()) return;
        $data=$adsets->current()->getData();
        foreach ($data as $r){
            if(is_array($r)){
                $r['account_id']=$ac_id;
                $r['ad_id']=$ad_id;
                $r['keyword_id']=$r['id'];
                $r['id']= md5($r['ad_id'].$r['keyword_id']);
                $r['add_to_cart']=0;
                foreach ($r['actions'] as $action){
                    if($action['action_type']=='add_to_cart'){
                        $r['add_to_cart']= $action['value'];
                        break;
                    }
                }
                unset($r['actions']);
                $r['type']=self::TYPE_DATE_ALL;
                if($date){
                    $r['date']=$date;
                    $r['type']=self::TYPE_DATE_TODAY;
                }
                array_push($campaigns_data,$r);
            }
        }
        if($campaigns_data){
            $this->model->addAll($campaigns_data,null,true);
        }
        return $campaigns_data;
    }
    function getData(){
        $offset=I('request.offset',0);
        $limit=I('request.limit',30);
        $keyword=I('request.keyword');
        $where=" 1=1 ";
        if($keyword){
            $where.=" and (account_id like '%$keyword%' "
                ." or name like '%$keyword%') ";
        }
        $fields="count(*) as ads_num,name"
            .",sum(clicks) as clicks"
            .",avg(cpc) as cpc"
            .",avg(cpm) as cpm"
            .",avg(cpp) as cpp"
            .",avg(ctr) as ctr"
            .",avg(frequency) as frequency"
            .",sum(impressions) as impressions"
            .",sum(reach)as reach"
            .",sum(spend)as spend"
        ;
        $order=I('request.order');
        $order=$order?$order:'total_actions';
        $sort=I('request.sort','desc');

        $fdata=$this->model
            ->field($fields)
            ->where($where)
            ->order($order." ".$sort)
            ->limit($offset,$limit)
            ->group('name')
            ->select();
        $cc=M()->query(
            'SELECT COUNT(DISTINCT `name`) AS tp_count FROM `keywords_stats` '
            .($where?" where $where":"")
        );
        return ['data'=>$fdata,'total'=>$cc[0]['tp_count']];
    }
}