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
    function __construct($ad_adset,$type='ad')
    {
        $this->model = new model();
        $this->ad=(object)$ad_adset;
        $this->type=$type;
        $this->setRules();
    }
    function setRules(){
        $this->rules=[];
        $subsql=M('rules_link')->field('rule_id')->where(array(
            'target'=>$this->type,
            'target_id'=>$this->ad->Id
        ))->buildSql();
        $where=" status=0 and id in ($subsql) ";
        $this->rules=$this->model
            ->where($where)
            ->select();
    }
    function expression($fun,$lt,$value){
        $this->expression_str="[$fun,$lt,$value]";
        $firstValue=$this->$fun();
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
    function implement($field,$do,$type,$number){
        if(strtoupper($this->type)=='AD' && 'Budget'==$field) return;
        $this->implement_str="[$field,$do,$type,$number]";
        if('Budget'==$field){
            $is_bai=false;
            if(strpos($number,'%')!==false){
                $is_bai=true;
                $number=preg_replace("/[^\.\d]+/","",$number);
            }
            $oldBudget=$this->getBudget();
            if($type=='input'){
                $budget_fixed=$is_bai?($oldBudget*($number/100)):$number;
            }else if('ROAS'==$type){
                $roas=$this->getROAS();
                $budget_fixed=($number*$this->getNowPurchasesValue())/100;
            }
            $newBudget=$oldBudget;
            if(!empty($budget_fixed)){
                 if($do=='+'){
                     $newBudget+=$budget_fixed;
                 }elseif ($do=='-'){
                     $newBudget+=$budget_fixed;
                 }elseif ($do=='='){
                     $newBudget=$budget_fixed;
                 }
                 if($newBudget!=$oldBudget){
                     $this->implement_str.="==>[oldBudget:$oldBudget=>newBudget:$newBudget]";
                 }
            }
        }elseif('Pause'==$field){
            
        }
        M('rules_exec_log')->add(array(
            'time'=>NOW_TIME,
            'rule_id'=>$this->rule['id'],
            'rule_name'=>$this->rule['name'],
            'target'=>strtoupper($this->type),
            'target_id'=>$this->ad->Id,
            'target_data'=>json_encode($this->ad),
            'rule_exec'=>$this->expression_str."==>".$this->implement_str,
        ));

    }
    function run(){
        foreach ($this->rules as $r){
            $this->rule=$r;
            $r['code']=str_replace('$',"\$",$r['code']);
            eval($r['code']);
        }
    }
    ////////////////
    function  getNowAmountSpent(){ //今日花费
        return preg_replace("/[$,]+/","",$this->ad->AmountSpent);
    }
    function  getNowPurchasesValue(){ //今日shou ru
        return preg_replace("/[$,]+/","",$this->ad->WebsitePurchasesConversionValue);
    }
    function  getBudget(){        //当前预算
        return preg_replace("/[$,]+/","",$this->ad->Budget);
    }
    function  getPurchase(){
        return preg_replace("/[$,]+/","",$this->ad->WebsitePurchases);
    }
    function  getROAS(){
        return ($this->getNowAmountSpent()/$this->getNowPurchasesValue())*100;
    }
    function  getAddCart(){
        return preg_replace("/[$,]+/","",$this->ad->WebsiteAddstoCart);
    }
    function  getCPC(){
        return preg_replace("/[$,]+/","",$this->ad->CPC);
    }
    function  getAdsetName(){
        return $this->ad->AdsetName;
    }
    function  getCampaignName(){
        return $this->ad->CampaignName;
    }
}