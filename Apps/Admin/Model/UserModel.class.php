<?php
namespace Admin\Model;
use Think\Model;
use Think\Page;

/**
 *会员信息
 *@author leiqianyong 2015-03-12
 */
class UserModel extends CommonModel{
  
		
	
	/**
	 * 根据条件分页查询
	 * @author leiqianyong 2015-03-05
	 *
	 * @param array $map 查询条件数组
	 * @param string $field
	 * @param int $pagesize 单页记录条数
	 */
	public function _search($map,$field='*',$pagesize=10){
			
		import("Org.Page");
		$count = $this->where($map)->count();
		$page = new Page($count, $pagesize);
		$list = $this->field($field)->where($map)->limit($page->firstRow. ',' . $page->listRows)->order("id DESC")->select();
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		$page->setConfig('first', '首页');
		$page->setConfig('end', '尾页');
		$page->showNowPage=true;
		$page->setConfig('header', '<span class="rows">%TOTAL_ROW% %NOW_PAGE%/%TOTAL_PAGE%</span>');
		$page->setConfig('theme','%FIRST% %UP_PAGE% %DOWN_PAGE% %END% %HEADER%');	
		 
		return array('list'=>$list,'page'=>$page->show());
	
	}	
	
}