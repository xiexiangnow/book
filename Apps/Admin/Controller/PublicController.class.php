<?php
namespace Admin\Controller;
use Think\Controller;

class PublicController extends Controller {
	
	/**
	 * 管理员登录
	 * @author viking 2015-01-13
	 */
    public function login(){  	
    	/* 读取数据库中的配置 */
    	$config =   S('DB_CONFIG_DATA');
    	if(!$config){
    		$config =   api('Config/lists');
    		S('DB_CONFIG_DATA',$config);
    	}
    	C($config); //添加配置    	
    	
    	if(IS_AJAX){    		
    		$checkVerify = I('auth_code', '', 'trim');    
            if(!$checkVerify){
            	$this->error('验证码不能为空');
            }
            if(!check_verify($checkVerify)){
            	$this->error('验证码输入错误！');
            }    		
            
    		$admin_db = D('Admin');    		    		
    		
    		$account = I('username', '', 'trim') ? I('username', '', 'trim') : $this->error('用户名不能为空');
    		$password = I('password', '', 'trim') ? I('password', '', 'trim') : $this->error('密码不能为空');
    			
    		if($admin_db->login($account, $password)){    			
    			$this->success('登录成功',U('Desktop/main'));
    			exit;
    		}else{
    			$this->error($admin_db->error);
    		}      		    		  		    		    		    		
    	}

    	if(is_login()){    	
           	$this->redirect('Desktop/main');    	
    		exit;
    	}
    	
    	//登录界面
    	$this->display('login');    	    	
    }
    
    
    /**
     * 退出系统
     * @author viking 2015-01-14
     */
    public function logout(){
		session('user_auth', null);
		session('user_auth_sign', null);
		session('rules', null);
		session('author',null);
		
    	$this->success('安全退出！', U('login'));    	
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
     * 编辑器上传文件
     * @author leiqianyong 2015-02-25
     */
    public function editupload(){    	
    	//运行上传的文件指令
    	$allow_file = array('image','flash','file','media');

    	//图片 image,flash,附件 file,视频 media    	
        if($_SERVER['REQUEST_URI']){
          $order = explode("=",strstr($_SERVER['REQUEST_URI'],"dir"));
          if(empty($order) || empty($order[1]) || !in_array($order[1], $allow_file)){
          	$msg = array("error"=>1,"message"=>'上传指令不正确');
          	echo json_encode($msg);
          	exit;
          }
          $type = $order[1];
        }    	    	
    	
	
    	$upload = new \Think\Upload();  
    	
    	//上传图片
    	if('image'==$type){
    		$allow_ext = array('jpg','png','jpeg','bmp','gif');
    		$rootPath = "./Uploads/doc/image/" ;
    		$upload->__set('exts', $allow_ext);
    		$upload->__set('rootPath', $rootPath);
    		$res = $upload->upload($_FILES);
    		if($res){
    			//上传成功
    			$filename = $rootPath.$res['imgFile']['savepath'].$res['imgFile']['savename'] ;
    			$back = substr($filename, 1);
    			
    			$data ['module'] = 0;
    			$data ['path'] = $back;
    			$data ['time'] = NOW_TIME;
    			$data ['ip'] = get_client_ip ();
    			$id = M ( 'attachment' )->add ( $data );    			
    			
    			$msg = array("error"=>0,"url"=>$back);
    		}else{
    			$error = $upload->getError();
    			$msg = array("error"=>1,"message"=>$error);
    		}    		    		
    	}
    	
    	//上传flash
    	if('flash'==$type){
    		$allow_ext = array('swf');
    		$rootPath = "./Uploads/doc/flash/" ;
    		$upload->__set('exts', $allow_ext);
    		$upload->__set('rootPath', $rootPath);
    		$res = $upload->upload($_FILES);
    		if($res){
    			//上传成功
    			$filename = $rootPath.$res['imgFile']['savepath'].$res['imgFile']['savename'] ;
    			$back = substr($filename, 1);
    			
    			$data ['module'] = 2;
    			$data ['path'] = $back;
    			$data ['time'] = NOW_TIME;
    			$data ['ip'] = get_client_ip ();
    			$id = M ( 'attachment' )->add ( $data );    			
    			
    			$msg = array("error"=>0,"url"=>$back);
    		}else{
    			$error = $upload->getError();
    			$msg = array("error"=>1,"message"=>$error);
    		}    		
    	}
   	
    	
    	//上传附件
    	if('file'==$type){
    		$allow_ext = array('rar','zip');
    		$rootPath = "./Uploads/doc/file/" ;
    		$upload->__set('exts', $allow_ext);
    		$upload->__set('rootPath', $rootPath);
    		$res = $upload->upload($_FILES);
    		if($res){
    			//上传成功
    			$filename = $rootPath.$res['imgFile']['savepath'].$res['imgFile']['savename'] ;
    			$back = substr($filename, 1);
    			
    			$data ['module'] = 1;
    			$data ['path'] = $back;
    			$data ['time'] = NOW_TIME;
    			$data ['ip'] = get_client_ip ();
    			$id = M ( 'attachment' )->add ( $data );    			
    			
    			$msg = array("error"=>0,"url"=>$back);
    		}else{
    			$error = $upload->getError();
    			$msg = array("error"=>1,"message"=>$error);
    		}
    	}    	
    	
    	echo json_encode($msg);
    }
    
    
    
}