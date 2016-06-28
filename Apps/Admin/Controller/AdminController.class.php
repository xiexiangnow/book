<?php
namespace Admin\Controller;
use Think\Controller;


/**
 * 管理员信息
 * @author Administrator
 */
class AdminController extends ComController {
	
	//公共模块，无需权限检测
	protected $public_action = array('info');  
	
	protected  $admin;
	
	function _initialize(){
		parent::_initialize();
		$this->admin = new \Admin\Model\AdminModel();
	}	
	
	/**
	 * 管理账号列表 
	 * @author viking 2015-01-13
	 */
    public function index(){
        
    	$cols = "id,account,lastlogintime,lastloginip,realname,state,logincount";     	       	
    	$map['hide_flag'] = 0;
    	$pagesize = 20 ;
    	$list = $this->admin->search($map,$cols,$pagesize,'lastlogintime desc,id desc');       	
    	$this->assign('list',$list['list']);
    	$this->assign('page',$list['page']);    	
    	$this->display();
    	    	
    }
    
  
    /**
     * 添加管理员
     * @author viking 2015-02-10
     */
    public function adduser(){
    	if(IS_POST){
    		$data = $this->admin->create();
    		if(!$data){
    			$this->error($user->getError());
    		}
    		
    		$uid = $this->admin->add($data);
    		if(!$uid){
    			$this->error("账号添加失败 ");
    		}
    		$this->success("账号添加成功 ",U('index'));
    		exit;
    	}
    	
    	$list = M('auth_group')->field('id,title')->where("state=1 AND hide_flag=0")->select();    	
    	$this->assign('group',$list);    	
    	$this->display('edit');
    }
    
    
    /**
     * 修改会员信息
     */
    public function edituser(){
    	$id = I('id');
    	if(!$id){
    		$this->error('缺少参数');
    	}
    	
    	if(IS_POST){
    		$password = I('password');
    		if($password){
    			if(!checkPwd($password)){
    				$this->error('密码格式不正确');
    			}
    			$data['password'] = md5($password) ;
    		}
    		
    		$data['realname'] = I('realname');
			$data['groups'] = implode(',', I('group'));
            $this->admin->where("id={$id}")->save($data);
    		$this->success("修改成功",U("admin/edituser",array('id'=>$id)));
    		exit;
    	}
    	
    	$user = $this->admin->where("id={$id}")->find();
    	$list = M('auth_group')->field('id,title')->where("state=1 AND hide_flag=0")->select();
    	$this->assign('roles',explode(',', $user['groups']));
    	$this->assign('group',$list);
    	$this->assign('info',$user);
    	$this->display('edit');    	
    }
    
       
    
    /**
     * 个人账号信息管理
     * @author leiqianyong 2015-02-14
     */
    public function info(){    	    	    	
    	$info = $this->admin->where('id='.UID)->find();
    	if(IS_POST){
    	   $realname = trim(I('realname'));
    	   $password = trim(I('pwd'));
    	   if($info['realname']==$realname && empty($password)){
    	   	$this->success('修改成功',U('Admin/info'));
    	   	exit;
    	   }
    	   
    	   //只修改姓名时
    	   if(!$password){
    	   	   $data['realname'] = $realname;
    	       $this->admin->where("id=".UID)->save($data);
    	       $this->success('修改成功',U('Admin/info'));
    	       exit;    	       
    	   }
    	   
    	   if($info['password']!=md5($password)){
    	   	   $this->error('原密码不正确');
    	   }
    	   
    	   $newpwd = I('newpwd');
    	   if(!$newpwd){
    	   	   $this->error('请输入新密码');
    	   }

    	   if(!checkPwd($newpwd)){
    	   	   $this->error('新密码格式不正确');
    	   }
    	   
    	   $repwd = I('repwd');
    	   if(!$repwd){
    	   	   $this->error('请输入确认密码');
    	   }
    	   
    	   if($repwd!=$newpwd){
    	   	   $this->error('确认密码不正确');
    	   }
    	   
    	   $data['realname'] = $realname ;
    	   $data['password'] = md5($newpwd);
    	   $this->admin->where("id=".UID)->save($data);
    	   $this->success('修改成功',U('Admin/info'));
    	   exit;
    	}
    	
        unset($info['password']);    	    	    	
    	$this->assign('info',$info);
    	$this->display();
    }
    
    
    /**
     * 检查账号是否存在
     * @author viking 2015-04-19
     */
    public function checkAccount($account=''){    	
    	$data['status'] = false;
    	if(!$account){
    		$this->ajaxReturn($data);
    	}
    	
    	$cond['account'] = $account ;
    	$field = 'id' ;
    	$res = $this->admin->field($field)->where($cond)->find();    	
    	if(!$res){
    		$data['status'] = true ;
    	}    	    	
    	$this->ajaxReturn($data);
    }
    
    
}