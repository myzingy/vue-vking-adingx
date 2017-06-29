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
    
    const CRON_RETRY_COUNT=10;//断定不可访问
    const CRON_RETRY_TIME=300;//通话将被阻止一分钟;在这段时间内，最大分数会衰减，最多5分钟后降至0
    const CRON_RENEW_TIMEOUT=3000;
    const CRON_RENEW_TIMEOUT_7Y14=21600;
    const CRON_ERROR_ACID='CRON_ERROR_ACID';
    const CRON_CLEAR_FLAG='CRON_CLEAR_FLAG';

    const CRON_TIMEOUT=86400;//不再使用retry进行判断，每次执行错误，对cron降低优先级（priority）

    function __construct($id="") {

    }
    function demon(){
        $time_s=getDayTime("07:59:00",0);
        $time_e=getDayTime("08:00:00",0);
        if(NOW_TIME > $time_s && NOW_TIME < $time_e) {
            $ymd=date("Y-m-d",NOW_TIME);
            $CRON_CLEAR_FLAG=S('CRON_CLEAR_FLAG');
            if($ymd==$CRON_CLEAR_FLAG) return;
            M('x_cron')->where('`status`=2')->delete();
            $count=M('x_cron')->count();
            if($count<1){
                M()->query('TRUNCATE TABLE `x_cron`');
            }
            S('CRON_CLEAR_FLAG',$ymd);
        }
        $this->implement();
    }
    function getErrorACID(){
        $acids=S(self::CRON_ERROR_ACID);
        $error_acid=[];
        if($acids){
            foreach ($acids as $k=>$t){
                echo $k.':'.(NOW_TIME-($t+self::CRON_RETRY_TIME)).'s<br/>';
                if($t+self::CRON_RETRY_TIME < NOW_TIME){
                    unset($acids[$k]);
                    continue;
                }
                $error_acid[]="'{$k}'";
            }
            S(self::CRON_ERROR_ACID,$acids);

        }
        return implode(',',$error_acid);
    }
    function implement(){

//        $where = ' (`status`='.self::CRON_STATUS_DEF
//            .' or (`status`='.self::CRON_STATUS_RUN.' and `runtime`<'.(NOW_TIME-self::CRON_RETRY_TIME).')) ';
//        $where .= ' and `retry`<'.self::CRON_RETRY_COUNT;
        $where = '`status`='.self::CRON_STATUS_DEF;
        $where .= ' and `addtime` >'.(NOW_TIME-self::CRON_TIMEOUT);
        $where .= ' and (`runtime` is null or `runtime`<'.(NOW_TIME-self::CRON_RETRY_TIME).') ';
        $where .= ' and `crontime` <'.NOW_TIME;
        $error_acid=$this->getErrorACID();
        if($error_acid){
            $where .= " and `ac_id`  not in ($error_acid) ";
        }
        $cron=M('x_cron');
//        $cron->where($where)
//            ->order('`priority` desc,`addtime` asc')->limit(1)
//            ->find();
//        if($cron->id){
//            $param=@json_decode($cron->param,true);
//            $param['cron_id']= $cron->id;
//            asyn_implement($cron->path,$param,$cron->method);
//            $cron->retry+=1;
//            $cron->status=self::CRON_STATUS_RUN;
//            $cron->runtime=NOW_TIME;
//            $cron->save();
//        }
        $data=$cron->where($where)
            ->order('`priority` desc,`addtime` asc')->limit(50)
            ->select();
        echo $cron->getLastSql();
        if($data){
            foreach ($data as $r){
                $param=@json_decode($r['param'],true);
                $param['cron_id']= $r['id'];
                $url=url($r['path']).'?'.http_build_query($param, '', '&');
                echo '<br>'.$r['id'].'#'.$r['message'].'##<a href="'.$url.'" target="_blank">'.$url.'</a>';
                asyn_implement($r['path'],$param,$r['method']);
            }

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
    static function create($path, $params=array(), $method='GET',$crontime=0,$priority=1){
        $cron=M('x_cron');
        $CRON_RENEW_TIMEOUT=self::CRON_RENEW_TIMEOUT;
        if($params['CRON_RENEW_TIMEOUT']){
            $CRON_RENEW_TIMEOUT=$params['CRON_RENEW_TIMEOUT'];
            unset($params['CRON_RENEW_TIMEOUT']);
        }
        $param=json_encode($params);
        $hash=md5($path.$param.($crontime>0?$crontime:""));
        if($crontime>0){
            $cron->where("`hash`='{$hash}'")->find();
        }else{
            $cron->where("`hash`='{$hash}' and addtime>".(NOW_TIME-$CRON_RENEW_TIMEOUT))->find();
        }
        if($cron->id) return;
        $cron->add(array(
            'hash'=>$hash,
            'path'=>$path,
            'param'=>$param,
            'ac_id'=> $params['ac_id']?$params['ac_id']:0,
            'method'=>is_null($method)?'GET':$method,
            'addtime'=>NOW_TIME,
            'crontime'=>$crontime+0,
            'priority'=>$priority+0
        ));
    }
    static function result($result=true,$message=""){
        $cron_id=I('request.cron_id');
        if(!$cron_id) return;
        $cron=M('x_cron');
        $cron->find($cron_id);
        if(!$cron->id) return;
        $cron->runtime=NOW_TIME;
        $cron->retry+=1;
        if($result){
            $cron->status=self::CRON_STATUS_RUN_OK;
        }elseif($cron->retry>=self::CRON_RETRY_COUNT){
            $cron->status=self::CRON_STATUS_RUN_FIL;
        }
        if(!$result){
            $cron->priority-=1;
            $ac_id=I('request.ac_id');
            if($ac_id && strpos($message,'Exception:(#17)')!==false){
                $acids=S(self::CRON_ERROR_ACID);
                $acids[$ac_id]=NOW_TIME;
                S(self::CRON_ERROR_ACID,$acids);
            }
        }
        $cron->message=$message;
        $cron->save();
    }
}