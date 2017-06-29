<?php
/**
 * author vking
 * 文章
 */
namespace Modules\rules;

use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Cursor;
use FacebookAds\Object\Values\ArchivableCrudObjectEffectiveStatuses;
//广告组
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Fields\AdFields;

class exec
{
    const EXEC_TIMEOUT=86000;//下次执行时间
    function __construct($ad_adset,$type='ad',$debug=false)
    {
        $this->model = new model();
        $this->debug=$debug;
        $this->type=$type;
        $this->ad=(object)$ad_adset;
        $this->setRules();
        if(count($this->rules)<1) {
            if($this->debug){
                debug('construct::setRules is null');
            }
            return;
        };
        $this->adx=[];
        foreach ($this->ad->List as $list){
            if($list['Type']==99) break;
            $this->adx[$list['Type']]=(object)$list;
        }
        if($this->debug){
            debug('construct',$this->type,$this->ad,$this->adx);
        }

    }
    function setRules(){
        $this->rules=[];
        if($this->ad->RuleRuntime > NOW_TIME-self::EXEC_TIMEOUT) return;
        $where=array(
            'RL.target_id'=> $this->ad->CampaignId,
            'RL.exec_hour_minute'=>array('elt',date("H:i",NOW_TIME)),
            'rules.status'=>0
        );
        $this->rules=$this->model
            ->field('rules.*,RL.exec_hour_minute')
            ->join('rules_link RL ON RL.rule_id=rules.id')
            ->order('id desc')->where($where)->select();
        if(count($this->rules)>0){
            $table=$this->type.'s_rules_run';
            $rule_runtime=strtotime(date("Y-m-d ".$this->rules[0]['exec_hour_minute'].":00",NOW_TIME));
            M($table)->add(array('rule_runtime'=>$rule_runtime,'id'=>$this->ad->Id),null,true);
        }
        if($this->debug){
            $rules=[];
            foreach ($this->rules as $r){
                $rules[]=$r['id'].'#'.$r['name'].'#'.$r['exec_hour_minute'];
            }
            debug('setRules',$rules);
        }
    }
    function expression($date,$fun,$lt,$value){ //条件
        $this->date=$date;
        $this->expression_str="[".($date!=0?"last $date day":"今日").",$fun,$lt,$value]";
        $firstValue=$this->$fun();
        if($this->debug){
            debug('expression-->'.$this->expression_str." PK ".$firstValue);
        }
        if($lt=="LI" || $lt=="NLI"){
            $flag=stripos($firstValue,$value);
            if($lt=="LI"){
                return $flag!==false?true:false;
            }
            return  $flag===false?true:false;
        }
        switch ($lt){
            case ">":
                return   $firstValue > $value;
                break;
            case ">=":
                return   $firstValue >= $value;
                break;
            case "<":
                return   $firstValue < $value;
                break;
            case "<=":
                return   $firstValue <= $value;
                break;
            case "==":
                return   $firstValue == $value;
                break;
        }
        return false;
    }
    function implement($field,$do,$type,$number){ //执行
        if(strtoupper($this->type)=='AD' && 'Budget'==$field) return;
        $this->implement_str="[$field,$do,$type,$number]";
        if($this->debug){
            debug('implement-->'.$this->implement_str);
        }
        $spend_cut=0;
        $spend_put=0;
        if('Budget'==$field){
            $is_bai=false;
            if(strpos($number,'%')!==false){
                $is_bai=true;
                $number=preg_replace("/[^\.\d]+/","",$number);
            }
            $oldBudget=$this->getBudget();
            $newBudget=$oldBudget;
            if($type=='input'){
                $budget_fixed=$is_bai?($oldBudget*($number/100)):$number;
            }else if('ROAS'==$type){
                $roas=$this->getROAS();
                $budget_fixed=($number*$this->getPurchasesValue())/100;
            }else if('getAmountSpentNow'==$type){
                $newBudget=$this->getAmountSpent(0);
                $budget_fixed=$is_bai?($newBudget*($number/100)):$number;
            }
            if(!empty($budget_fixed)){
                 if($do=='+'){
                     $newBudget+=$budget_fixed;
                 }elseif ($do=='-'){
                     $newBudget-=$budget_fixed;
                 }elseif ($do=='='){
                     $newBudget=$budget_fixed;
                 }
                 if($this->BudgetLimitMAX>0){
                     $newBudget=$newBudget>$this->BudgetLimitMAX?$this->BudgetLimitMAX:$newBudget;
                 }
                 if($this->BudgetLimitMIN>0){
                     $newBudget=$newBudget<$this->BudgetLimitMIN?$this->BudgetLimitMIN:$newBudget;
                 }
                 if($newBudget!=$oldBudget){
                     $spend_put=($newBudget-$oldBudget);
                     if($spend_put<0){
                         $spend_cut=abs($spend_put);
                         $spend_put=0;
                     }
                     $this->implement_str.="==>[oldBudget:$oldBudget=>newBudget:$newBudget]";
                     asyn('apido/asyn.setBudget',array(
                         'ac_id'=>$this->ad->AccountId,
                         'adset_id'=>$this->ad->Id,
                         'budget'=>$newBudget,
                     ),null,null,99);
                 }
            }
        }elseif('Pause'==$field){
            $this->implement_str="[$field]";
        }
        M('rules_exec_log')->add(array(
            'time'=>NOW_TIME,
            'rule_id'=>$this->rule['id'],
            'rule_name'=>$this->rule['name'],
            'target'=>strtoupper($this->type),
            'target_id'=>$this->ad->Id,
            'target_data'=>json_encode($this->ad),
            'rule_exec'=>$this->expression_str."==>".$this->implement_str,
            'account_id'=>$this->ad->AccountId,
            'account_name'=>$this->ad->AccountName,
            'spend_cut'=>$spend_cut,
            'spend_put'=>$spend_put,
        ));

    }
    function run(){
        if(count($this->rules)<1) return;
        foreach ($this->rules as $r){
            $this->rule=$r;
            $r['code']=str_replace('$',"\$",$r['code']);
            //var_dump('<pre>',$r['code']);
            eval($r['code']);
        }
    }
    function setBudgetLimit($type,$val){
        $val+=0;
        $limit='BudgetLimit'.strtoupper($type);
        $this->$limit=$val>0?$val:0;
    }
    ////////////////
    function  getAmountSpent($date=0){ //花费
        $date=$date>-1?$date:$this->date;
        return preg_replace("/[$,]+/","",$this->adx[$date]->AmountSpent);
    }
    function  getPurchasesValue($date=0){ //收入
        $date=$date>-1?$date:$this->date;
        return preg_replace("/[$,]+/","",$this->adx[$date]->WebsitePurchasesConversionValue);
    }
    function  getBudget(){        //预算
        return preg_replace("/[$,]+/","",$this->ad->Budget);
    }
    function  getPurchase($date=0){ //订单数
        $date=$date>-1?$date:$this->date;
        return preg_replace("/[$,]+/","",$this->adx[$date]->WebsitePurchases);
    }
    function getPurchaseValue($date=0){ //收入
        $date=$date>-1?$date:$this->date;
        return preg_replace("/[$,]+/","",$this->adx[$date]->WebsitePurchasesConversionValue);
    }
    function  getROAS($date=0){ // 花费/收入
        $date=$date>-1?$date:$this->date;
        $PurchasesValue=$this->getPurchasesValue($date)+0;
        if($PurchasesValue==0) return 0;
        return ($this->getAmountSpent($date)/$PurchasesValue)*100;
    }
    function  getROI($date=0){ // 收入/花费
        $date=$date>-1?$date:$this->date;
        return ($this->getPurchasesValue($date)/$this->getAmountSpent($date));
    }
    function  getAddCart($date=0){ //加购物车数量
        $date=$date>-1?$date:$this->date;
        return preg_replace("/[$,]+/","",$this->adx[$date]->WebsiteAddstoCart);
    }
    function  getCPA($date=0){ //加购物车成本
        $date=$date>-1?$date:$this->date;
        return preg_replace("/[$,]+/","",$this->adx[$date]->CostperWebsiteAddtoCart);
    }
    function  getCPC($date=0){ //单次点击费率
        $date=$date>-1?$date:$this->date;
        return preg_replace("/[$,]+/","",$this->adx[$date]->CPC);
    }
    function  getAdName(){
        return $this->ad->AdName;
    }
    function  getAdsetName(){
        return $this->ad->AdsetName;
    }
    function  getCampaignName(){
        return $this->ad->CampaignName;
    }
}