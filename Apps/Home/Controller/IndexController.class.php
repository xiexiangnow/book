<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {
	
	
    public function index(){
    	
    	$uid = is_login();
    	if($uid){
    		$this->redirect('center/index');
    		exit;
    	}
    	
    	
    	/* 读取数据库中的配置 */
    	$config =   S('DB_CONFIG_DATA');
    	if(!$config){
    		$config =   api('Config/lists');
    		S('DB_CONFIG_DATA',$config);
    	}
    	C($config); //添加配置    	
    	
        $this->display();
    }
    
    
    /**
     * 登录操作
     * @author leiqianyong 2015-05-15
     */
    public function dologin(){
    	
    	$code=I('code');
    	if(!check_verify($code)){
    		$this->error('验证码不正确');
    	}
    	    	    	
    	$admin_db = D('User');
    	
    	$account = I('username', '', 'trim') ? I('username', '', 'trim') : $this->error('用户名不能为空');
    	$password = I('password', '', 'trim') ? I('password', '', 'trim') : $this->error('密码不能为空');
    	 
    	if($admin_db->login($account, $password)){
    		$this->success('登录成功',U('center/index'));
    		exit;
    	}else{
    		$this->error($admin_db->error);
    	}    	
    	    	
    }
    
    
    

    /* 验证码，用于登录和注册 */
    public function verify(){
    
    	$verifyConfig = array(
    			'length' => 4,
    			'fontSize' => 12,
    			'useCurve' => false,
    			'useNoise' => false,
    			'codeSet' =>'0123456789',
    			'fontttf' => '4.ttf'
    	);
    
    	$verify = new \Think\Verify($verifyConfig);
    	$verify->entry(1);
    }
    
    
    /**
     * 退出系统
     * @author viking 2015-01-14
     */
    public function logout(){
    	session('mem_auth', null);
    	session('mem_auth_sign', null);
    
    	$this->success('安全退出！', U('index'));
    }    
    
    
}