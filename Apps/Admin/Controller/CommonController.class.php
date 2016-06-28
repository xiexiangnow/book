<?php
namespace Admin\Controller;
use Think\Controller;

class CommonController extends Controller {
	
	
	function _initialize(){
		/* 读取数据库中的配置 */
		$config =   S('DB_CONFIG_DATA');
		if(!$config){
			$config =   api('Config/lists');
			S('DB_CONFIG_DATA',$config);
		}
		C($config); //添加配置	
				
		//判断用户是否登录	
    	self::check_login();        
            	
    	// 是否是超级管理员    	
    	if(is_administrator()){
    		return true;
    	}
    	  	    	
    	//检查是否是公共模块，公共模块不需要验证
    	if(!empty($this->public_action) && is_array($this->public_action) && in_array(ACTION_NAME, $this->public_action)){
    		return true;
    	}    	
    	
    	$rule  = strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME);      	
    	if(I('method')){
    		$rule .= "?method=".I('method') ;
    	}
    	    	
    	//验证是否有访问权限
    	if(self::checkRule($rule)){
    		return true ;
    	}
    	
    	$this->error('您没有该链接的访问权限','s');
    }
    
    
    /**
     * 权限检测
     * @param string  $rule    检测的规则
     * @param string  $mode    check模式
     */
    final protected function checkRule($rule){
    	//检查权限是否已经检测过
    	$author = session('author');
    	$author_ary = $author?unserialize($author):false;
    	if(is_array($author_ary) && in_array(md5($rule), $author_ary)){
    		return true ;
    	}   	
    	
        $ids = get_rules();
        if(empty($ids)){
        	return false;
        }
    	                
    	$res = M('menu')->where("path LIKE '{$rule}%'")->find();
        if(empty($res)){
        	return false;
        }
        
        //权限存在，保存权限节点，下次访问时不再验证
        if(in_array($res['id'],$ids)){
        	$author_ary[] = md5($rule);
        	session('author',serialize($author_ary));
        	return true;
        }
    	
        return false;
    }        
    
    
    /**
     * 判断用户是否已经登陆
     */
    final public function check_login() {    	
    	$uid = is_login();
    	define('UID',$uid);
    	if(!UID){
    	    $this->error('请先登录后台管理', U('Public/login'));
    	}    	
    }   

   
}