<?php
namespace Admin\Model;
use Think\Model;

class UmenuModel extends Model{
				
	/**
	 * 获取后台菜单结构树(不含操作级别)
	 * @author leiqianyong 2015-01-20	 
	 */
	public function getTree(){
		
		$admin_ids_ary = C('USER_ADMINISTRATOR');
		//非管理员的顶部菜单项
		$map = array();
		if(empty($admin_ids_ary) || !in_array(is_login(), $admin_ids_ary)){		
    		$ruels = get_rules();
	    	$map['id'] = array('in',implode(',', $ruels));
		}
		$map['level'] = array('elt',3);
		$map['is_show'] = 1 ;
		//左侧菜单 后台二三级目录
        return $this->getMenu($map);
		
	}
	
	
	/**
	 * 获取全部菜单项
	 * @author viking 2015-01-23
	 */
	public function getAll(){
		
		$map = array();
		$tmp = $this->getMenu($map);
						
		$id_arr = $this->array_get_by_key($tmp,'id');
		$level_arr = $this->array_get_by_key($tmp,'level');
		$title_arr = $this->array_get_by_key($tmp,'title');
		$pid_arr = $this->array_get_by_key($tmp,'pid');
		
		$res = array();
		foreach($id_arr as $k=>$v){
			$res[$k]['id'] = $v ;
			$res[$k]['title'] = $title_arr[$k] ;
			$res[$k]['level'] = $level_arr[$k] ;
			$res[$k]['pid'] = $pid_arr[$k] ;
		}
		
		return $res ;				
	}
	
	
	private function getMenu($map){
		
		$mod_list = $this->where($map)->order('level ASC,sort DESC')->select();
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
	
		
	/**
	 * 获取一维或多维数组某个特定键的所有值
	 * @author leiqianyong 2013-03-16
	 *
	 * @param array $array 数组
	 * @param 键值 $string
	 */
	private function array_get_by_key($array, $string){
		if (!trim($string) || !is_array($array)) return false;
		preg_match_all("/\"{$string}\";\w{1}:(?:\d+:|)\"(.*?)\";/", serialize($array), $res);
		return $res[1];
	}	
	
}