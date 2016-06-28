<?php
namespace Admin\Model;
use Think\Model;
use Think\Page;

/**
 *通用模型层
 *@author leiqianyong 2015-03-11
 */
class CommonModel extends Model{
  
	
	/**
	 * 根据条件分页查询
	 * @author leiqianyong 2015-03-05
	 *
	 * @param array $map 查询条件数组
	 * @param string $field 
	 * @param int $pagesize 单页记录条数
	 */
	public function search($map,$field='*',$pagesize=10,$order='id DESC'){
		 
		import("Org.Page");
		$count = $this->where($map)->count();		
		$page = new Page($count, $pagesize);
		$list = $this->field($field)->where($map)->limit($page->firstRow. ',' . $page->listRows)->order($order)->select();		
		$page->setConfig('first', '首页');		
		$page->setConfig('end', '尾页');
		$page->setConfig('theme','%FIRST% %LINK_PAGE% %END% %HEADER%');
		return array('list'=>$list,'page'=>$page->show());
	
	}	
	
		
}