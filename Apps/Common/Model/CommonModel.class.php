<?php
namespace Common\Model;
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
		$page->setConfig('UP_PAGE', '上一页');
		$page->setConfig('DOWN_PAGE', '下一页');
		$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		
		return array('list'=>$list,'page'=>$page->show());
	
	}

   
    public function changeParse($content){
				
		     return preg_replace(array('/(?:height|width)=(\'|").*?\\1/','/<p[^>]+>/'),array("style='width:100%'",'<p>'),$content);            
               
	}










	
		
}