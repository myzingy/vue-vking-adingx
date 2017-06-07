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
        $data=$this->model->order('id desc')->select();
        return array('data'=>$data);
    }
    function getRulesLog(){
        $data=M('rules_exec_log')->order('id desc')->select();
        foreach ($data as &$r){
            $r['time_format']=date('m-d H:i',$r['time']);
        }
        return array('data'=>$data);
    }
}