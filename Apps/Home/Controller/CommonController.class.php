<?php
namespace Home\Controller;
use Think\Controller;

class CommonController extends Controller{
		
	function _initialize(){
		
		self::check_login();		
		
		/* 读取数据库中的配置 */
		$config =   S('DB_CONFIG_DATA');
		if(!$config){
			$config =   api('Config/lists');
			S('DB_CONFIG_DATA',$config);
		}
		C($config); //添加配置

		$uinfo = session('mem_auth');

		$this->assign('uinfo',$uinfo);
		$this->assign('t',I('t',1));
		$this->assign('umenu',$this->getMenu());
	}
    
    
	/**
	 * 判断用户是否已经登陆
	 */
	final public function check_login() {
		$uid = is_login();
		define('UID',$uid);
		if(!UID){
			$this->error('请先登录后台管理', U('Index/index'));
		}
	}	
	
	
	private function getMenu(){
        $map['is_show']=1;
			$mod_list = M('Umenu')->where($map)->order('level ASC,sort DESC')->select();
			if(!$mod_list || empty($mod_list)){
				return false ;
			}
		
			$tmp = array();
			$address = array(); //父级地址
			foreach($mod_list as $k=>$v){
				$id = $v['id'];
				if($v['level'] <= 1){
					//一级
					$tmp[$id] = $v;
					$address[$id] = & $tmp[$id];
				}else{
					//子集
					$pid = $v['pid'];
					if(!$address[$pid]['child']){
						$address[$pid]['child'] = array();
					}
					$address[$pid]['child'][$id] = $v;
					$address[$id] = & $address[$pid]['child'][$id];
				}
			}
		
			return $tmp ;			
	}
	
	
	
}