<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 管理后台
 * @author Administrator
 */
class DesktopController extends Controller {
			
	/**
	 * 后台页面框架
	 * 
	 * @author viking 2015-01-13
	 */
	public function main() {
		if(!is_login()){
			$this->error('请先登录后台管理', U('Public/login'));
		}		
		
		$this->assign('userinfo',session('user_auth'));
		$this->assign('menu_top',$this->top());
		$this->display ( 'index' );
	}
	
	/**
	 * 后台顶部
	 * 
	 * @author viking 2015-01-13
	 */
	public function top() {		
		$admin_ids_ary = C('USER_ADMINISTRATOR');
		//非管理员的顶部菜单项
		$map = array();		
		if(empty($admin_ids_ary) || !in_array(is_login(), $admin_ids_ary)){
		  $ruels = get_rules();	
		  $map['id'] = array('in',implode(',', $ruels));
		}		
		
		// 获取一级目录
		$map['pid'] = 0 ;
	    $menu_top = M('menu')->where($map)->field('id,title')->order('sort desc,id desc')->select();	    
		return $menu_top;
	}
	
	/**
	 * 后台左侧菜单
	 * @author viking 2015-04-18
	 */
	public function left() {		
		//一级目录
		$top_key = I('top',1) ; 				
		$menu_tree = D('Menu')->getTree();
		$res = $menu_tree[$top_key]['child'] ;
		$html = "" ;
		foreach($res as $value){
			$html .= "<h3 class='f14'><span class='J_switchs cu on' title='展开或关闭'></span>".$value['title']."</h3>" ;		
	        $html .= "<ul>" ;						
			foreach($value['child'] as $val){
		      $html .= "<li class='sub_menu'>" ;
			  $html .= "<a href='javascript:;' data-uri='/".$val['path']."' data-id='".$val['id']."' hidefocus='true'>".$val['title']."</a>" ;
			  $html .= "</li>" ;
			}		
			 $html .= "</ul>" ;		
		}				
		echo $html ;
	}
	
}