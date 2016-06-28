<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\MenuModel;


/**
 * 权限组信息管理
 * @author leiqianyong 2015-02-09
 */
class GroupController extends CommonController {
	
	
    /**
     * 查看权限组信息
     * @author leiqianyong 2015-02-09
     */	
	public function index(){
		
		$list = M('auth_group')->field("description",true)->order("sort desc")->select();
		$this->assign('list',$list);
		$this->display();
		
	}
    
  
    /**
     * 修改权限组信息
     * @author leiqianyong 2015-02-09
     */
    public function edit(){
    
    	$id = I('id');
    	if(!$id){
    		$this->error('缺少参数');
    	}
    	
    	if(IS_POST){
    		$title = I('title');  		
    		if(empty($title)){
    			$this->error('名称必填');
    		}
    		$data['title'] = $title;
    		$data['rules'] = I('ids') ;
            $data['column'] = I('cids') ;
    		$data['state'] = I('state',1);
    		if(is_numeric(I('sort',0)))
    		$data['sort'] = I('sort',0);    
    		$data['description'] = I('description');
    		M('auth_group')->where("id={$id}")->save($data);
    		$this->success("修改成功",U("Group/edit",array('id'=>$id)));
    		exit;
    	}
    	

    	$menu = new MenuModel();
    	$list = $menu->getAll();
    
    	$group = M('auth_group');
    	$info = $group->where("id={$id}")->find();
    	$ids = array();    	
    	if(!empty($info['rules'])){
    		$ids = explode(',', $info['rules']);
    	}
        if(!empty($info['column'])){
            $cids = explode(',', $info['column']);
        }
        /*栏目节点*/
        $navobj=D('Navigate');
        $colary= $navobj->field("id,title,pid,level")->select();
        $this->assign('colary',$colary);
        $this->assign('collen',count($colary));
    	$this->assign('menulen',count($list));
    	$this->assign('info',$info);
    	$this->assign('ids',$ids);
        $this->assign('cids',$cids);
    	$this->assign('list',$list);
    	$this->display();      
    }
    
    
    /**
     * 添加权限组
     * @author leiqianyong 2015-02-09
     */
    public function gadd(){
    	if(IS_POST){
    		$title = I('title');
    		if(empty($title)){
    			$this->error('名称必填');
    		}
    		$data['title'] = $title;
    		$data['rules'] = I('ids') ;
    		if(empty($data['rules'])){
    			$this->error('请选择权限节点');
    		}

            // $data['column'] = I('cids') ;
            //  if(empty($data['column'])){
            //     $this->error('请选择栏目节点');
            //  }


    		$data['state'] = I('state',1);
    		if(is_numeric(I('sort',0)))
    		$data['sort'] = I('sort',0);
    		$data['description'] = I('description');
    		M('auth_group')->add($data);
    		$this->success("添加成功",U("Group/index"));
    		exit;    		
    	}
    	
    	$menu = new MenuModel();
    	$list = $menu->getAll();
       //dump($list);

        /*栏目节点*/
        $navobj=D('Navigate');
        $colary= $navobj->field("id,title,pid,level")->select();
        $this->assign('colary',$colary);

        $this->assign('list',$list);
    	$this->assign('menulen',count($list));
    	$this->assign('act','add');
    	$this->display('edit');
    	
    }
    
    
    /**
     * 删除权限组
     * @author leiqianyong 2015-02-09
     */
    public function del(){
    	$id = I('id');
    	if(!$id){
    		$this->error('缺少参数');
    	}    	
    	    	
    	$res = M('auth_group')->where("id={$id}")->delete();
    	if(!$res){
    		$this->error('删除失败');
    	}
    	
    	$this->success('删除成功');
    }
    
    
}