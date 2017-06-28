<?php
/**
 * author vking
 * 文章
 */
namespace Modules\user;
use Facebook\Facebook;

class lib{
    function __construct($id="") {
    	$this->model=new model();
		$id=$id?$id:I('request.id');
		if($id){
			$this->model->find($id);
		}
    }
    function login(){
        $data['id']=I('request.id');
        $data['name']=I('request.name');
        $data['email']=I('request.email');
        $data['token']=I('request.token');
        $data['time']=NOW_TIME;
        if($this->model->id){
            $this->model->save($data);
        }else{
            $this->getRoot($data['email'],$data['group_id']);
            $this->model->add($data);
        }
        asyn('apido/asyn.getLongToken',array('token'=>$data['token']),null,null,5);
    }
    function getUserForToken(){
        $this->model->getByToken(I('request.token'));
        if(!$this->model->id) return false;
        $this->model->root=$this->getRoot($this->model->email);
        return $this->model;
    }
    function getUserForEmail(){
        $this->model->getByEmail(I('request.email'));
        if(!$this->model->id) return false;
        $this->model->root=$this->getRoot($this->model->email);
        return $this->model;
    }
    function getRoot($email,&$group_id){
        $mod=M('user_children');
        $data=$mod->getByEmail($email);
        $group_id=$data['group_id']+0;
        return $data['user_id'];
    }
    function addUsers($user){
        $email=trim(I('request.email'));
        if($email==$user->email) return "Not allowed to add yourself.";
        $root=$this->getRoot($email);
        if($root) return "The mailbox already exists.".$root;
        $group_id=I('request.group_id');
        M('user_children')->add(array(
            'user_id'=>$user->root?$user->root:$user->id,
            'email'=>$email,
            'group_id'=>$group_id
        ),null,true);
    }
    function delUsers($user){
        $email=I('request.email');
        M('user_children')->where("email='{$email}'")->delete();
        M('user')->where("email='{$email}'")->delete();
        M('user_accounts_links')->where("email='{$email}'")->delete();
    }
    function getUsers($user){
        $user_id=$user->id;
        if($user->root && $user->group_id==\Modules\group\lib::GROUP_ID_ADMIN){
            $user_id=$user->root;
        }
        $data=M('user_children')
            ->alias('UC')
            ->field('UC.email,U.id,U.name,U.group_id,UC.group_id as group_id_old')
            ->join('user U ON U.email=UC.email','left')
            ->where("UC.user_id={$user_id}")
            ->select();
        foreach ($data as &$r){
            $r['group_id']=$r['id']?$r['group_id']:$r['group_id_old'];
        }
        return ['data'=>$data];
    }
    function updateUsers($user){
        $email=I('request.email');
        $group_id=I('request.group_id');
        $user_id=I('request.user_id');
        if($user_id){
            M('user')->where("id='{$user_id}'")->save(array('group_id'=>$group_id));
        }else{
            M('user_children')->where("email='{$email}'")->save(array('group_id'=>$group_id));
        }
    }
    function getLongToken(){
        $token=I('request.token');
        if(!$token) reutrn;
        vendor("vendor.autoload");
        $app=C('fbapp');
        $fb=new Facebook(array(
            'app_id'=>$app['app_id'],
            'app_secret'=>$app['app_secret']
        ));
        $res=$fb->get("oauth/access_token?client_id={$app['app_id']}&client_secret={$app['app_secret']}&grant_type=fb_exchange_token&fb_exchange_token={$token}",$token);
        $res=$res->getDecodedBody();
        if($res['access_token']){
            M('user')->where(array(
                'token'=>$token
            ))->save(array('long_token'=>$res['access_token']));
        }
    }
}