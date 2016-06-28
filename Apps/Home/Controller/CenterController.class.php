<?php
namespace Home\Controller;
use Think\Controller;

class CenterController extends CommonController {
			
	function _initialize(){
		parent::_initialize();		
	}
	
	
	/**
	 * 后台首页
	 */
    public function index(){
        $this->display();
    }
    

    /**
     * 账号管理
     */
    public function account(){
    	$info = D('user')->getInfo();    	
    	if(!empty($info['lastloginlog'])){
    		$log = string2array($info['lastloginlog']);
    		if($log[1]){
    			$logary = explode('|', $log[1]);
    			$info['lastlogin'] = date('Y-m-d H:i:s',$logary[0]).' | '.$logary[1];
    		}else{
    			$info['lastlogin'] = '--' ;
    		}
    	}    	
    	$this->assign('uinfo',$info);    	    	
    	$this->display();
    }
    
    /**
     * 账号编辑
     */ 
    public function edit(){
    	
    	if(IS_POST){
    	  $data['truename']	= I('truename');
    	  $data['nickname'] = I('nickname');
    	  $data['sex'] = I('sex');
    	       	  
    	  if(D('user')->editInfo($data)){
    	  	$this->success('修改成功');
    	  	exit;
    	  }
    	  $this->error('修改失败');
    	}
    	
    	$field = "truename,nickname,sex";
    	$info = D('user')->getInfo($field);
    	$this->assign('uinfo',$info);
    	$this->display();
    }
    

    /**
     * 修改密码
     */
    public function editpwd(){
    	
    	if(IS_POST){    	   
    	   $password = I('password', '', 'trim') ? I('password', '', 'trim') : $this->error('原密码不能为空');
    	   $newpwd = I('newpwd', '', 'trim') ? I('newpwd', '', 'trim') : $this->error('新密码不能为空');
    	   $repwd = I('repwd', '', 'trim') ? I('repwd', '', 'trim') : $this->error('确认码不能为空');
    		
    	   if($newpwd!=$repwd){
    	   	  $this->error('确认码不正确');
    	   }
    	   
    	   //检查原密码是否正确
    	   $userObj = D('User');
    	   $info = $userObj->getInfo('password');
    	   if(empty($info) || $info['password']!=md5($password)){
    	   	  $this->error('原密码输入不正确');
    	   }
    	   
    	   $data['password'] = md5($newpwd);
    	   if($userObj->editInfo($data)){
    	   	   $this->success('修改成功');
    	   	   exit;
    	   }
    	   
    	   $this->error('修改失败');
    	}
    	
    	$this->display();
    }

    /*修改头像*/

    public function headify(){



        $this->display();
    }

}