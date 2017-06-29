<?php

namespace Modules\common;

class common_lib {

    function getApiDoc($reflection) {
    	//$class=new api_lib();
		$methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC + \ReflectionMethod::IS_PROTECTED + \ReflectionMethod::IS_PRIVATE);
        $arr=array('status', 'check', 'getApiDoc', '__construct', 'auth');
		$apilist=array('api'=>array());
		foreach ($methods as $func) {
			if(in_array($func->getName(), $arr)) continue;
			 //$func  = new ReflectionMethod($class,$fun);
			 $doc=$func->getDocComment();
			 $doc_arr=preg_split("/[\n\r]/",$doc);
			 $apilist['api'][]=array(
				'fun'=>str_replace('__', '.', $func->getName()),
				'doc'=>$doc,
				'title'=>trim($doc_arr[2])
			);
		}
		return $apilist;
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC + \ReflectionMethod::IS_PROTECTED + \ReflectionMethod::IS_PRIVATE);
        //遍历所有的方法 
        echo "<div class=\"main\"><table border=1 width=100%>";
        foreach ($methods as $method) {
            $name = $method->getName();
            if (in_array($name, array(
                        'status', 'check', 'getApiDoc', '__construct', 'auth'
                    )))
                continue;

            //获取方法的注释
            $doc_txt = $method->getDocComment();
            $doc = preg_split('/[\r\n]+/', $doc_txt);
            $method_name = str_replace('	 * ', '', $doc[2]);              //方法中文名
            $auth_arr = array('seller__', 'common__', 'distr__', 'member__', 'ztd__');
            foreach ($auth_arr as $m => $val) {
                if (stripos($name, $val) !== false) {
                    $m_name = explode('__', $name);
                    $method_name_arr[$m_name[0]][$m_name[1]] = array('name_info' => $method_name . ' ==> ' . $name);
                }
            }
            if (!stripos($name, '__'))
                $method_name_arr['common'][$name] = array('name_info' => $method_name . ' ==> ' . $name);
            echo '<tr style="height:1px;background:#ccc;"><td><a href="#' . $name . '" name="' . $name . '">&nbsp;</a></td><td></td></tr>';
            echo '<tr>
		    	<td style="vertical-align:top;padding-top:20px;"><h2><font color=green><a href="#' . $name . '">' . $name . '</a>&nbsp;[<a href="http://apptest.shequren.cn/debug.html?apiname=' . $name . '" target="_blank">点我测试</a>]</font></h2></td>
		    	<td class="td_info">
		    	<a href="http://apptest.shequren.cn/index.php/api/app/' . $name . '" target="_blank">http://apptest.shequren.cn/index.php/api/app/' . $name . '</a>
		    	<pre>' . $doc_txt . '</pre>
		    	</td>
		    </tr>';
        }
        echo "</table>
		</div>
		     <script>var method_name_arr='" . json_encode($method_name_arr, JSON_UNESCAPED_UNICODE) . "'</script>   
		        ";
        exit;
    }
    
    /**
     * LAST param T|P|TP
     */
    static function debug(){
        $args=func_get_args();
        $debug=debug_backtrace();
        $str="";
        $traceFlag=false;
        $paramFlag=false;
        if($ac=count($args)){
            $last=$args[$ac-1];
            if(is_string($last)){
                $last=strtoupper($last);
                switch($last){
                    case 'T':$traceFlag=true;break;
                    case 'P':$paramFlag=true;break;
                    case 'TP':
                    case 'PT':
                        $traceFlag=true;
                        $paramFlag=true;
                        break;
                }
            }
        }
        foreach ($debug as $i=>$r){
            $pt=str_pad("",$i,"\t");
            $str.="$pt{$r['file']}# {$r['line']}\n";
            if($i==0){
                $title=" <== DEBUG ==> ";
                if (is_string($args[0])){
                    $title=array_shift($args);
                    $title=" <== $title ==> ";
                }
                $str.="#########{$title}#########\n";
                if(count($args)>0){
                    if(is_array($args[0]) && count($args[0])<1){
                        break;
                    }
                    $str.=var_export($args,true)."\n" ."#########{$title}#########\n";
                }
                if(!$traceFlag) break;
            }else{
                $str.="\t$pt".(!empty($r['class'])?($r['class'].$r['type']):"")."{$r['function']}("
                    .((!$paramFlag)?"~PARAM~":json_encode($r['args']))
                    .")\n";
            }
        }
        //Logging::DebugLog($str."\n",PATH_LOG .'debug.'.date("Ymd",time()).'.log');
        $file = LOG_PATH . date('Ymd', time()) . '_debug.log';
        $fp = @fopen($file, 'a+');
        if ($fp) {
            fwrite($fp, $title);
            fwrite($fp, $str . "\n");
        }
        @fclose($fp);
    }

    //post 函数
    static function http_post($url, $param = "", $header = array(), $isGET = false) {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
        }
        if (is_string($param)) {
            $strPOST = $param;
        } elseif ($param) {
            $aPOST = array();
            foreach ($param as $key => $val) {
                $aPOST[] = $key . "=" . urlencode($val);
            }
            $strPOST = join("&", $aPOST);
        }

        if ($isGET) {
            curl_setopt($oCurl, CURLOPT_URL, $url . (strpos('?', $url) === false ? '?' : '&') . $strPOST);
        } else {
            curl_setopt($oCurl, CURLOPT_URL, $url);
            curl_setopt($oCurl, CURLOPT_POST, true);
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
        }
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        if ($header)
            curl_setopt($oCurl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($oCurl, CURLOPT_TIMEOUT, 20);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        return $sContent;
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }

    /**
     * 获取随机字符串
     *  参数
     *      长度
     */
    function getRandChar($length,$type='all') {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
		if(in_array($type, ['num','int','number'])){
			$strPol= "0123456789";
		}
        $max = strlen($strPol) - 1;
        for ($i = 0; $i < $length; $i++) {
            $str.=$strPol[rand(0, $max)]; //rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }

    
	static function url($path=""){
        if(strpos($path,'http')!==false){
			return $path;
		}
		$path=str_replace('./', '/', $path);
        $server=defined('URL_SERVER')?URL_SERVER:('http://'.$_SERVER['HTTP_HOST'].__ROOT__);
        return $server
			.'/'
			.(is_numeric($path)?"apido/download/id/$path":$path);
	}
	static function findArr($s_arr,$k,$v){
		foreach ($s_arr as $key => $value) {
			if($value[$k]==$v){
				return ['key'=>$key,'val'=>$value];
			}
		}
	}
    //简易请求
    function http($url,$params='',$timeout=10,$type='GET'){
        if(is_array($params)){
            $params=ksort($params);
            $params=http_build_query($params, '', '&');
        }
        $context['http'] = array(
            'timeout'=>$timeout,
            'method' => $type,
            'content' => $params,
        );
        for($cnt=0;$cnt<4;$cnt++){
            $cc=file_get_contents($url, false, stream_context_create($context));
            if($cc!==false) return $cc;
        }
        return false;
    }
    /*
	 * 异步请求
	 * 客服端需要以下代码：
	 * #如果客户端断开连接，不会引起脚本abort
	 *	ignore_user_abort(true);
	 *	#取消脚本执行延时上限
	 *	//set_time_limit(0);
	 */
    function http_asyn($path,$params=array(
        'file_type'=>'',
        'MediaId'=>'',
        'ThumbMediaId'=>'',
        'fp_id'=>'',
    ),$method="GET"){
        $data=http_build_query($params);
        $url=url($path).'?'.$data;
        //var_dump($url);
        $url_array = parse_url($url); //获取URL信息
        $def_port=(__APP__POS=='CC__DEV')?80:80;
        $port = isset($url_array['port'])? $url_array['port'] : $def_port;
        $getPath = $method=='POST'?$url_array['path']:($url_array['path'] ."?". $url_array['query']);
        //var_dump(array($url_array['host'],$port,$getPath,$data));
        $fp = fsockopen($url_array['host'],$port,$errno,$errstr,5);
        if(!$fp){
            console(__CLASS__,"http_asyn fsockopen::$errstr ($errno)");
            return;
        }

        $header = "$method " . $getPath ." HTTP/1.1\r\n";
        $header .= "Host: ". $url_array['host'] . "\r\n"; //HTTP 1.1 Host域不能省略
        if($method=='POST'){
            $header .= "Content-type:application/x-www-form-urlencoded\r\n";
            $header .= "Content-length:".strlen($data)."\r\n";
            $header .= "Connection:close\r\n\r\n";
            $header .= "$data";
        }else{
            $header .= "Connection:Close\r\n\r\n";
        }
        //console($header);
        fwrite($fp, $header);
        fclose($fp);
    }

}
