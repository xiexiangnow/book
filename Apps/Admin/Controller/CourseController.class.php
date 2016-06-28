<?php
namespace Admin\Controller;
use Think\Controller;

/*
 * 课程管理
 * @author leiqianyong 2015-05-21
 */
class CourseController extends ComController{

	protected $course ;
		
	function _initialize(){
		parent::_initialize();		
		$this->course = new \Admin\Model\CourseModel();
	}
	
	
    /**
     * 列表
     * author viking 2015-05-21
     */
	public function index(){		
		$title = I('title');
		if($title){
			$map['title'] =  array('like', '%'.$title.'%');
			$this->assign('title',$title);
		}
		
		$teacher = I('teacher');
		if($teacher){
			$map['teacher'] =  array('like', '%'.$teacher.'%');
			$this->assign('teacher',$teacher);			
		}
		
		$map['hide_flag'] = 0 ;
		$order = "top desc,sort desc,id desc";
		$pagesize = 16 ;
		
		$res = $this->course->search($map,'*',$pagesize,$order);		

		$conf['type'] = array('neq',3);
		$conf['_string'] = 'ids IN (4,14)';
		$tree=M('navigate')->field('id,title')->where($conf)->select();
		$ary = array();
		foreach($tree as $val){
			$ary[$val['id']] = $val['title'];
		}
		$this->assign('ary',$ary);
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
		   $data = $this->course->create();
		   if(!$data){
		   	$this->error($this->course->getError());
		   }		   
		   
				
			//添加
			if(!$id){
				$res = $this->course->add($data);
				if(!$res){
					$this->error('添加失败!');
				}
				$this->success('添加成功',U('index'));
				exit;
			}
					
			$res = $this->course->save($data);
			if(false===$res){
				$this->error('修改失败');
			}
				
			$this->success('修改成功',U('index'));
			exit;
		}
					
		if($id){
		   $map['hide_flag'] = 0 ;
		   $map['id'] = $id ;
		   $info = $this->course->where($map)->find();		   
		   $this->assign('info',$info);
		}
		
		$key=$info['sid']?$info['sid']:0;		
		$conf['type'] = array('neq',3);
		$conf['_string'] = 'ids IN (4,14)';
		$tree = get_doc_tree($key,$conf);
		$this->assign('tree',$tree);		
		
		$this->display('edit');
	}
	
	

}

?>