<?php
namespace Admin\Controller;
use Think\Controller;

/*
 * 友情链接管理
 * @author leiqianyong 2015-05-11
 */
class LinkController extends ComController{

	protected $link ;
		
	function _initialize(){
		parent::_initialize();		
		$this->link = new \Admin\Model\LinkModel();
	}
	
	
    /**
     * 友情链接列表
     * @author leiqianyong 2015-05-11
     */
	public function index(){		
		$map['hide_flag'] = 0 ;
		$order = "top desc,sort desc,id desc";
		$pagesize = 16 ;
		
		$res = $this->link->search($map,'*',$pagesize,$order);

		$this->assign('linkType',C('LINK_TYPE'));
		$this->assign('list',$res['list']);
		$this->assign('page',$res['page']);
		$this->display();
	}

	
	/**
	 * 添加删除链接
	 * @author leiqianyong 2015-05-11
	 */
	public function edit(){
		
		$id = I('id');
		
		if(IS_POST){
 		   //添加
		   $data = $this->link->create();
		   if(!$data){
		   	$this->error($this->link->getError());
		   }		   
		   
				
			//添加
			if(!$id){
				$res = $this->link->add($data);
				if(!$res){
					$this->error('添加失败!');
				}
				$this->success('添加成功',U('index'));
				exit;
			}
					
			$res = $this->link->save($data);
			if(false===$res){
				$this->error('修改失败');
			}
				
			$this->success('修改成功',U('index'));
			exit;
		}
		
		if($id){
		   $map['hide_flag'] = 0 ;
		   $map['id'] = $id ;
		   $linkinfo = $this->link->where($map)->find();
		   $this->assign('linkinfo',$linkinfo);
		}
		
		$this->display('edit');
	}
	
	

}

?>