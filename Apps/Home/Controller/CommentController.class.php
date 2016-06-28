<?php
namespace Home\Controller;
use Think\Controller;
use Think\Page;


/**
 * 文章评论
 * @author leiqianyong 2015-02-27
 */
class CommentController extends Controller {
	
			   
    
	/**
	 * 添加评论信息
	 * @author leiqianyong 2015-02-27
	 */
	public function plus(){
	
		$record_id = I('id');
		if(!$record_id){
			$this->error('缺少文章ID');
		}
	
		$doc = M('comment');
		$base = $doc->where("id={$id}")->find();
		if(!$base){
			$this->error('文章不存在');
		}
		$info = M('document_article')->where("pid={$id}")->find();
	
		//上一篇下一篇 按id降序排列
		//上一篇
		$pre_page = $doc->field("id,title")->where("id>{$id} AND display>0")->find();
		$this->assign('pre',$pre_page);
		//下一篇
		$next_page = $doc->field("id,title")->where("id<{$id} AND display>0")->order("id desc")->find();
		$this->assign('next',$next_page);
	
		$this->assign('base',$base);
		$this->assign('info',$info);
		$this->assign('page_title',$base['title']);
		$this->display();
	
	}	
	
	
}