<?php
namespace Home\Controller;
use Think\Controller;

class CriteoController extends Controller {
	function __construct() {
		parent::__construct();
        //$this->lib = new \Modules\feeds\lib();
	}
    function test(){
	    $client=new \SoapClient('https://advertising.criteo.com/API/v201305/AdvertiserService.asmx?WSDL');
        $loginResponse=$client->partnerLogin(array(
	        'username'=>'goto999@126.com',
            'password'=>'Zule@Oneday2017',
            'source'=>'zule-oneday-1.0.0',
            'appToken'=>'6659629684833486848',
        ));
        dump($loginResponse);
        
//        $apiHeader = new \stdClass();
//        $apiHeader->appToken = '4679075827648887808';
//        $apiHeader->authToken = $loginResponse->clientLoginResult;
//        $apiHeader->clientVersion = 'zule-oneday-1.0.0';
//
//        $soapHeader = new \SOAPHeader('https://advertising.criteo.com/API/v201305', 'apiHeader', $apiHeader, false);
//        $client->__setSoapHeaders($soapHeader);
//        $res=$client->getAccount();
//        dump($res);
    }
    function index()
    {
        vendor('Criteo#class');
        $ini = ini_set("soap.wsdl_cache_enabled","0");
        define('APP_TOKEN', '6659629684833486848');
        define('APP_SOURCE', 'zule-oneday-1.0.0');
        define('USERNAME', 'goto999@126.com');
        define('PASSWORD', 'Zule@Oneday2017');
        define('WSNAMESPANCE', 'https://advertising.criteo.com/API/v201010');
        define('WSURL', 'https://advertising.criteo.com/api/v201010/advertiserservice.asmx?WSDL');
        //instantiate the SOAP client
        $options = array(
            'soap_version' => SOAP_1_2
            , 'exceptions' => true //true
            , 'trace' => 0
            , 'cache_wsdl' => WSDL_CACHE_NONE
        );
        $soap_client = new \CriteoAdvertiserAPI(WSURL,$options);
        // CLIENTLOGIN
        echo '<br/><b>clientLogin...</b><br/>';
        $clientLogin = new \clientLogin();
        $clientLogin->username = USERNAME;
        $clientLogin->password = PASSWORD;
        $clientLogin->source = APP_SOURCE;
        $loginResponse = $soap_client->clientLogin($clientLogin);
        $authToken = $loginResponse->clientLoginResult;

        var_dump([$loginResponse->clientLoginResult,$authToken]);
        $apiHeader = new \apiHeader();
        $apiHeader->appToken = APP_TOKEN;
        $apiHeader->authToken = $authToken;
        $apiHeader->clientVersion = APP_SOURCE;
        //Create Soap Header, then set the Headers of Soap Client.
        $soapHeader = new \SOAPHeader(WSNAMESPANCE, 'apiHeader', $apiHeader, false);
        dump($soapHeader);
        $res=$soap_client->__setSoapHeaders($soapHeader);
        dump(['__setSoapHeaders'=>$res]);
        //get account
        $getAccount = new \getAccount();
        $res=$soap_client->getAccount($getAccount);
        dump($res);

        

    }
//    public function _empty($name){
//        $lib=$this->lib;
//        if(strpos($name,$lib::FEED_MARKS_PRE)>-1){
//            $this->__xml($name);
//        }
//        if(strpos($name,$lib::FEED_IMAGE_PRE)>-1){
//            $this->__image($name);
//        }
//    }
}
