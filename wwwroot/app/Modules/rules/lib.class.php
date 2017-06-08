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

class lib
{
    function __construct($id = "")
    {
        $this->model = new model();
        $id = $id ? $id : I('request.id');
        if ($id) {
            $this->model->find($id);
        }
    }

    function updateRulesData()
    {
        if ($this->model->id) {//update
            I('request.code')?$this->model->code=I('request.code','','trim'):"";
            I('request.xml')?$this->model->xml=I('request.xml','','trim'):"";
            I('request.name')?$this->model->name=I('request.name'):"";
            (I('request.type')!="")?$this->model->type=I('request.type'):"";
            I('request.status')?$this->model->status=I('request.status'):"";
            $this->model->save();
        } else {
            if(!I('request.name')) return "规则名称必须填写!";
            if(!I('request.code')) return "规则内容必须填写!";
            $this->model->code=I('request.code','','trim');
            $this->model->xml=I('request.xml','','trim');
            $this->model->name=I('request.name');
            if(I('request.type')!=""){
                $this->model->type=I('request.type');
            }else{
                $size=strlen(I('request.code'));
                $this->model->type=$size>500?2:($size>200?1:0);
            }
            $this->model->add();
        }
    }
    function getRulesData(){
        $where=array();
        I('request.status','-1')>-1?$where['status']=I('request.status'):"";
        $data=$this->model->order('id desc')->where($where)->select();
        return array('data'=>$data);
    }
    function getRulesLog(){
        $data=M('rules_exec_log')->order('id desc')->select();
        foreach ($data as &$r){
            $r['time_format']=date('m-d H:i',$r['time']);
        }
        return array('data'=>$data);
    }
    function getRulesForAd(){
        $where=array(
            'RL.target_id'=> I('request.id'),
            'RL.target'=> I('request.type')=='getAdsetsData'?'adset':'ad',
        );
        //$subwhere=" id in (".M('rules_link')->field('rule_id')->where($where)->buildSql().")";
        $data=$this->model
            ->field('rules.*,RL.exec_hour_minute')
            ->join('rules_link RL ON RL.rule_id=rules.id')
            ->order('id desc')->where($where)->select();
        return array('data'=>$data);
    }
    function saveRulesForAd(){
        $target_id= I('request.target_id');
        M('rules_link')->where("`target_id`='$target_id'")->delete();
        $rules_ids= I('request.rules_ids');
        if(!$rules_ids) return;
        $data=array();
        foreach ($rules_ids as $rule_id){
            $data[]=array(
                'target_id'=>$target_id,
                'target'=>I('request.target')=='getAdsetsData'?'adset':'ad',
                'rule_id'=>$rule_id,
                'exec_hour_minute'=>I('request.date')
            );
        }
        M('rules_link')->addAll($data);
    }
}