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
    const EXEC_TIMEOUT=85000;//下次执行时间
    function __construct($ad_adset,$type='ad')
    {
        $this->model = new model();
        $this->ad=(object)$ad_adset;
        $this->adx=[];
        foreach ($this->ad->List as $list){
            if($list['Type']==99) break;
            $this->adx[$list['Type']]=(object)$list;
        }
        $this->type=$type;
        $this->setRules();
    }
    function setRules(){
        $this->rules=[];
        $sub_where=array(
            'target'=>$this->type,
            'target_id'=>$this->ad->Id,
            'runtime'=>array('lt',NOW_TIME-self::EXEC_TIMEOUT),
            'exec_hour_minute'=>array('gt',date("H:i",NOW_TIME))
        );
        $subsql=M('rules_link')->field('rule_id')->where($sub_where)->buildSql();
        $where=" status=0 and id in ($subsql) ";
        $this->rules=$this->model
            ->where($where)
            ->select();
        M('rules_link')->where($sub_where)->save(array(
            'runtime'=>NOW_TIME
        ));
    }
    function expression($date,$fun,$lt,$value){ //条件
        $this->date=$date;
        $this->expression_str="[".($date!=0?"last $date day":"今日").",$fun,$lt,$value]";
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
    function implement($field,$do,$type,$number){ //执行
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
                $budget_fixed=($number*$this->getPurchasesValue())/100;
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
    function  getROAS($date=0){ // 花费/收入
        $date=$date>-1?$date:$this->date;
        return ($this->getAmountSpent($date)/$this->getPurchasesValue($date))*100;
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