<?php
/**
 * author vking
 * 文章
 */
namespace Modules\campaigns;
use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Cursor;
//广告系列
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\Values\CampaignObjectiveValues;
use FacebookAds\Object\Values\ArchivableCrudObjectEffectiveStatuses;
class lib{
    function __construct($id="") {
    	$this->model=new model();
        $id=$id?$id:I('request.id');
		if($id){
			$this->model->relation(array())->find($id);
		}
    }
    function flushCampaignsInit(){
        //20:00-08:00 不获取数据
        $time_s=getDayTime("15:00:00",0);
        $time_e=getDayTime("08:00:00",0);
        $fouce=I('request.fouce','');
        if(!$fouce){
            if(NOW_TIME > $time_s || NOW_TIME < $time_e) return;
        }
        $acs=FBC();
        $acts=array('Campaigns','Adsets','Ads','Accounts');
        if($fouce){
            $acts=array(ucfirst($fouce));
        }
        if($acs){
            foreach ($acs as $ac){
                foreach ($acts as $act){
                    asyn('apido/asyn.flush'.$act,array(
                        'ac_id'=>$ac['account_id'],
                        'active'=>'active'
                    ),null,null,2);
                }
            }
            if(count($acs)==\Modules\accounts\lib::FBC_LIMIT_NUM){
                $offset=I('request.offset',0)+\Modules\accounts\lib::FBC_LIMIT_NUM;
                asyn('apido/asyn.flushCampaignsInit',array(
                    'offset'=>$offset,
                ),null,null,3);
            }
        }

    }
	function flushCampaigns(){
        $ac_id=I('request.ac_id');
        if(!$ac_id) return;
        $ac=FBC($ac_id);
        vendor("vendor.autoload");
        $fba=Api::init($ac['app_id'],$ac['app_secret'],$ac['access_tokens']);
        $api = Api::instance();
        $account =new AdAccount($ac['act_id']);
        $campaigns_data=array();
        $after=I('request.after','');
        $active=I('request.active','');
        $EFFECTIVE_STATUS=array(
            ArchivableCrudObjectEffectiveStatuses::ACTIVE,
        );
        $asyn_param=array('after'=>'','ac_id'=>$ac_id);
        if(!$active){
            array_push($EFFECTIVE_STATUS,ArchivableCrudObjectEffectiveStatuses::PAUSED);
            //array_push($EFFECTIVE_STATUS,ArchivableCrudObjectEffectiveStatuses::ARCHIVED);
        }else{
            $asyn_param['active']=$active;
        }
        $fields=array(
            CampaignFields::ACCOUNT_ID,
            CampaignFields::ADLABELS,
            CampaignFields::BRAND_LIFT_STUDIES,
            CampaignFields::BUDGET_REBALANCE_FLAG,
            CampaignFields::BUYING_TYPE,
            CampaignFields::CAN_CREATE_BRAND_LIFT_STUDY,
            CampaignFields::CAN_USE_SPEND_CAP,
            CampaignFields::CONFIGURED_STATUS,
            CampaignFields::CREATED_TIME,
            CampaignFields::EFFECTIVE_STATUS,
            CampaignFields::ID,
            CampaignFields::NAME,
            CampaignFields::OBJECTIVE,
            CampaignFields::RECOMMENDATIONS,
            CampaignFields::SPEND_CAP,
            CampaignFields::START_TIME,
            CampaignFields::STATUS,
            CampaignFields::STOP_TIME,
            CampaignFields::UPDATED_TIME,
            CampaignFields::EXECUTION_OPTIONS,
        );
        $campaigns = $account->getCampaigns($fields, array(
            CampaignFields::EFFECTIVE_STATUS =>$EFFECTIVE_STATUS,
            //limit => 25,
            after=>$after,
        ));
        //var_dump($campaigns);
        $asyn_param['after']=$campaigns->getAfter();
        while ($campaigns->valid()) {
            $_d=$campaigns->current()->getData();
            $_campaigns_data=array();
            foreach ($fields as $i=>$fk){
                $_campaigns_data[$fk]= $_d[$fk];
            }
            array_push($campaigns_data,$_campaigns_data);
            $campaigns->next();
            //只获取 1593565507558990 的数据
            if($ac_id!='1593565507558990') continue;
            if(ArchivableCrudObjectEffectiveStatuses::ACTIVE==$_campaigns_data[CampaignFields::EFFECTIVE_STATUS]) {
//                asyn('apido/asyn.flushAdsets',array(
//                    'campaign_id'=>$_campaigns_data['id'],
//                    'active'=>'active'
//                ));
                asyn('apido/asyn.flushCampaignsInsights',array(
                    'campaign_id'=>$_campaigns_data['id'],
                    'ac_id'=>$ac_id
                ));
            }

        }

        if(count($campaigns_data)>0){
            //$model=new \Modules\campaigns\model();
            //$model->truncate();
            $this->model->addAll($campaigns_data,null,true);
        }
        if($campaigns->getNext() && count($campaigns_data)==25){
            asyn('apido/asyn.flushCampaigns',$asyn_param,null,null,2);
        }
        return $campaigns_data;
    }
    //用于替换 getCampaignsInsightsData
    function getCampaignsData($ac_id=null,$date_stop=null){
        $date_stop_str=$date_stop?$date_stop:date('Y-m-d',NOW_TIME);
        $where=" AI.type!=100 AND AI.date_stop='".$date_stop_str."' ";
        $ac_id=$ac_id?$ac_id:I('request.ac_id');
        if($ac_id){
            $where.=" AND campaigns.account_id='$ac_id' ";
        }
        $keyword_type=I('request.keyword_type');
        if($keyword_type=='campaign'){
            $keyword=trim(I('request.keyword'));
            if($keyword){
                $where.=" and ( AI.`{$keyword_type}_id` like '%{$keyword}%' OR AI.`{$keyword_type}_name` like '%{$keyword}%' ) ";
            }
        }
        if($date_stop){
            $this->model->setDateStop($date_stop,date('Y-m-d',NOW_TIME)==$date_stop?0:99);
        }
        $data=$this->model
            ->field('campaigns.id,campaigns.name,campaigns.effective_status')
            ->relation(array('insights'))
            ->join('campaigns_insights AI ON AI.campaign_id=campaigns.id')
            ->where($where)
            ->order('campaigns.updated_time desc')
            ->group('campaigns.id')
            ->select();
        $formatData=formatInsightsData($data,'campaign');
        return array('data'=>$formatData);
    }
    function postErpCampaign(){
        return;
    }
    function getAcconutByOperator(){
        $date=I('request.date');
        if(!$date) return "param is null";
        $ac_ids_rules=array(
            //jeulia
            '561910137324149'=>'戴婷',
            '836196303228863'=>'权文娟',
            '559461654235664'=>[
                ['权文娟','US-Q'],['武艳云','US-W']
            ],
            '769185753263252'=>'李婷',
            '564914007023762'=>'王乐',
            '1593565507558990'=>[
                ['王乐','-mar'],['王乐','us-g-w'],
                ['杨超英','Kelly'],['DPA','dpa']
            ],
            '639275016254327'=>'DPA',
            '909992302470836'=>'杨超英',
            '673582062823622'=>'徐健',
//            '769185746596586'=>[
//                ['徐健','Evan'],['何慧敏','Janet']
//            ],
            '769185746596586'=>'戴婷',
            '836196296562197'=>'何慧敏',
            '836196299895530'=>'葛芳',
            '639275062920989'=>'姚青',
            //gnoce
            //'670806899767805'=>'陈灿',
            //'639275086254320'=>'胡美莹',
            //'769185763263251'=>'员燕子',
        );
        $brands=C('brand');
        $brands=$brands['jeulia'];
        $data=M('campaigns_insights')
            ->field('account_id,account_name,date_start as date,campaign_name
            ,CLICK1D_WebsiteAddstoCart,CLICK1D_WebsitePurchases,CLICK1D_CostperWebsiteAddtoCart
            ,CLICK1D_CPC,CLICK1D_WebsiteAddstoCartConversionValue,CLICK1D_WebsitePurchasesConversionValue
            ,spend,cpm,inline_link_click_ctr as ctr,inline_link_clicks as link_clicks,impressions,reach')
            ->where([
                'date_start'=>$date,
                'date_stop'=>$date,
                'type'=>99,
                'account_id'=>array('in',explode(',',$brands))
            ])
            ->select();
        $pdata=[];
        $fbc=FBC();
        foreach ($fbc as $r){
            $account_names[$r['account_id']]=$r['account_name'];
        }
        foreach ($ac_ids_rules as $acI=>$acU){
            if(is_array($acU)){
                foreach ($acU as $acU2){
                    $acUx=$acU2[0];
                    $pdata[$acUx]=$pdata[$acUx]?$pdata[$acUx]:[];
                    $pdata[$acUx][$acI]=[
                        'fee_date'=>$date,
                        'account_id'=>$acI,
                        'account_name'=>$account_names[$acI],
                        'username'=>$acUx,
                        'cost'=>0,
                        'purchase'=>0,
                        'add_to_cart'=>0,
                        'cpm'=>0,
                        'ctr'=>0,
                        'link_click'=>0,
                        'income'=>0,
                        'impressions'=>1,
                        'reach'=>1,
                        '__count'=>0,
                    ];
                }
            }else{
                $pdata[$acU]=$pdata[$acU]?$pdata[$acU]:[];
                $pdata[$acU][$acI]=[
                    'fee_date'=>$date,
                    'account_id'=>$acI,
                    'account_name'=>$account_names[$acI],
                    'username'=>$acU,
                    'cost'=>0,
                    'purchase'=>0,
                    'add_to_cart'=>0,
                    'cpm'=>0,
                    'ctr'=>0,
                    'link_click'=>0,
                    'income'=>0,
                    'impressions'=>1,
                    'reach'=>1,
                    '__count'=>0,
                ];
            }
        }
        foreach ($data as $r){
            $ac_id=$r['account_id'];
            $rule=$ac_ids_rules[$ac_id];
            $day_click=getDayClick($r);
            if(is_array($rule)){
                $key=$ac_id;
                foreach ($rule as $rl){
                    if(stripos($r['campaign_name'],$rl[1])>-1){
                        $key=$rl[0];
                        break;
                    }
                }
            }else{
                $key=$rule?$rule:$ac_id;
            }
            if($key==$ac_id) continue;
            $pdata[$key][$ac_id]['cost']+=$r['spend']*100;
            $pdata[$key][$ac_id]['purchase']+=$day_click['WebsitePurchases'];
            $pdata[$key][$ac_id]['add_to_cart']+=$day_click['WebsiteAddstoCart'];
            $pdata[$key][$ac_id]['cpm']+=$r['cpm']*100;
            $pdata[$key][$ac_id]['ctr']+=$r['ctr'];
            $pdata[$key][$ac_id]['link_click']+=$r['link_clicks'];
            $pdata[$key][$ac_id]['income']+=$day_click['WebsitePurchasesConversionValue']*100;
            //$pdata[$key]['campaign_name'].=$r['campaign_name']." |||| ";
            $pdata[$key][$ac_id]['impressions']+=$r['impressions'];
            $pdata[$key][$ac_id]['reach']+=$r['reach'];
            $pdata[$key][$ac_id]['__count']+=1;
        }
        foreach ($pdata as &$xxd){
            foreach ($xxd as &$xd){
                //$xd['cpm']=$xd['cpm']/$xd['__count']+0;
                //$xd['ctr']=$xd['ctr']/$xd['__count']+0;
                $xd['cpm']=($xd['cost']*10)/$xd['impressions'];
                $xd['ctr']=($xd['link_click']/$xd['impressions'])*100;
                unset($xd['__count']);
                $this->setAcconutByOperatorPatch($xd);
            }
        }
        return ['data'=>$pdata];
    }
    function setAcconutByOperatorPatch(&$user_account){
        $patch=array(
            '杨超英|909992302470836|2017-10-17'=>array(
                "fee_date"=> "2017-10-17",
                "account_id"=> 909992302470836,
                "account_name"=> "Xi'an Zhule -1212-3",
                "username"=> "杨超英",
                "cost"=> 494559,
                "purchase"=> 56,
                "add_to_cart"=> 2100,
                "cpm"=> 8.23879149931449,
                "ctr"=> 2.9889335161366,
                "link_click"=> 17942,
                "income"=> 662383,
                "impressions"=> 600281,
                "reach"=> 483780
            ),
            '姚青|639275062920989|2017-10-19'=>array(
                "fee_date"=> "2017-10-19",
                "account_id"=> 639275062920989,
                "account_name"=> "BF-YM-Zhule-0817-02",
                "username"=> "姚青",
                "cost"=> 130972,
                "purchase"=> 21,
                "add_to_cart"=> 445,
                "cpm"=> 10.75224326609,
                "ctr"=> 0.987611752826146,
                "link_click"=> 1203,
                "income"=> 315772,
                "impressions"=> 121809,
                "reach"=> 108947
            ),
            '姚青|639275062920989|2017-10-20'=>array(
                "fee_date"=> "2017-10-20",
                "account_id"=> 639275062920989,
                "account_name"=> "BF-YM-Zhule-0817-02",
                "username"=> "姚青",
                "cost"=> 156481,
                "purchase"=> 23,
                "add_to_cart"=> 384,
                "cpm"=> 10.406882012197,
                "ctr"=> 0.95169689351769,
                "link_click"=> 1433,
                "income"=> 324896,
                "impressions"=> 150363,
                "reach"=> 136817
            ),
        );
        $key=$user_account['username'].'|'.$user_account['account_id'].'|'.$user_account['fee_date'];
        if(!empty($patch[$key])){
            $user_account=$patch[$key];
        }
    }
}