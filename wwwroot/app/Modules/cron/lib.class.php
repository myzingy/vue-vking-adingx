<?php
/**
 * author vking
 * 文章
 */
namespace Modules\cron;
class lib{
    const CRON_STATUS_DEF=0;
    const CRON_STATUS_RUN=1;
    const CRON_STATUS_RUN_FIL=-1;
    const CRON_STATUS_RUN_OK=2;
    
    const CRON_RETRY_COUNT=3;
    const CRON_RENEW_TIMEOUT=3600;
    const CRON_RENEW_TIMEOUT_7Y14=21600;

    function __construct($id="") {

    }
    function demon(){
        $this->implement();
    }
    function implement(){
        $where = ' `status`='.self::CRON_STATUS_DEF;
        $where .= ' or (`status`='.self::CRON_STATUS_RUN.' and `runtime`<'.(NOW_TIME-180).') ';
        $where .= ' and `retry`<'.self::CRON_RETRY_COUNT;
        $where .= ' and `crontime` <'.NOW_TIME;
        $cron=M('x_cron');
        $cron->where($where)
            ->order('`priority` desc,`addtime` asc')->limit(1)
            ->find();
        if($cron->id){
            $param=@json_decode($cron->param,true);
            $param['cron_id']= $cron->id;
            asyn_implement($cron->path,$param,$cron->method);
            $cron->retry+=1;
            $cron->status=self::CRON_STATUS_RUN;
            $cron->runtime=NOW_TIME;
            $cron->save();
        }
    }

    /**
     * @param $path
     * @param array $params
     *  CRON_RENEW_TIMEOUT 重复添加时间限制
     * @param $method
     * @param int $crontime
     * @param int $priority
     */
    static function create($path, $params=array(), $method,$crontime=0,$priority=0){
        $cron=M('x_cron');
        $CRON_RENEW_TIMEOUT=self::CRON_RENEW_TIMEOUT;
        if($params['CRON_RENEW_TIMEOUT']){
            $CRON_RENEW_TIMEOUT=$params['CRON_RENEW_TIMEOUT'];
            unset($params['CRON_RENEW_TIMEOUT']);
        }
        $param=json_encode($params);
        $hash=md5($path.$param.($crontime>0?$crontime:""));

        $cron->where("`hash`='{$hash}' and addtime>".(NOW_TIME-$CRON_RENEW_TIMEOUT))->find();
        if($cron->id) return;
        $cron->add(array(
            'hash'=>$hash,
            'path'=>$path,
            'param'=>$param,
            'method'=>$method,
            'addtime'=>NOW_TIME,
            'crontime'=>$crontime,
            'priority'=>$priority
        ));
    }
    static function result($result=true,$message=""){
        $cron_id=I('request.cron_id');
        if(!$cron_id) return;
        $cron=M('x_cron');
        $cron->find($cron_id);
        if(!$cron->id) return;
        if($result){
            $cron->status=self::CRON_STATUS_RUN_OK;
        }elseif($cron->retry>=self::CRON_RETRY_COUNT){
            $cron->status=self::CRON_STATUS_RUN_FIL;
        }
        $cron->message=$message;
        $cron->save();
    }
}