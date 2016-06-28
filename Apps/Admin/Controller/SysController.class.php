<?php
namespace Admin\Controller;
use Think\Controller;


/**
 * 系统信息
 * @author Administrator
 */
class SysController extends Controller {
	
	/**
	 * 登陆界面   
	 * @author viking 2015-01-13
	 */
    public function info(){ 
    	$uid = is_login();    	
    	if($uid){
    		$cols='account,lastloginlog,logincount';
    		$info=M('admin')->field($cols)->where('id='.$uid)->find();    
    		if(!empty($info['lastloginlog'])){
    			$log = string2array($info['lastloginlog']);
    			if($log[1]){
    				$logary = explode('|', $log[1]);
    				$info['lastlogin'] = date('Y-m-d H:i:s',$logary[0]).' | '.$logary[1];
    			}else{
    				$info['lastlogin'] = '--' ;
    			}
    		}		
    		$this->assign('info',$info);
    	}   	
    	$this->display();
    	    	
    }
    
  
    
}