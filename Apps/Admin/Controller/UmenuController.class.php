<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * 管理员信息
 * @author Administrator
 */
class UmenuController extends CommonController {
	
	
	/**
	 * 菜单列表野   
	 * @author viking 2015-01-23
	 */
    public function index(){    	
        $pid = I('pid',0);    	    
        $top = M('umenu')->where("id={$pid}")->find();
    	$list = M('umenu')->where("pid={$pid}")->order("sort DESC")->select();
    	$this->assign('top',$top);    	
    	$this->assign('list',$list);
    	$this->display();
    	    	
    }
    
    
    /**
     * 删除菜单
     * @author viking 2015-01-23
     */
    public function del(){
    	$id = I('id');
    	
    	 $menu_obj = M('umenu');
    	 $child = $menu_obj->where("pid={$id}")->find();
    	 if($child){
    	 	$this->error('存在下级目录，不能删除');
    	 }
    	 
    	 $res = $menu_obj->where("id={$id}")->delete();
    	 if(!$res){    	 	
    	 	$this->error('删除失败');
    	 }
    	     	 
    	 $this->success('删除成功');
    }
  
    
    /**
     * 修改项目
     * @author viking 2015-01-23
     */
    public function edit(){
    	$id = I('id');
    	$menu_obj = M('umenu');
    	if(IS_POST){   
    		if(!$id){
    			$this->error('缺少参数');
    		}
    		$pid = I('pid');
    		$title = I('title');
    		if(!$title){
    			$this->error('名称必须');
    		}    		
    		
    		$path = I('path');
    		$sort = I('sort');
    		$pid = I('pid');
    		$icon = I('icon');
    		$is_show = I('is_show',1);
    		$data = compact('pid','title','path','sort','is_show','icon');
    		
    		//等级
    		$level = 1 ;
    		if($pid){
    			$pidinfo = $menu_obj->field('level')->where("id={$pid}")->find();
    			$level = $pidinfo['level']?($pidinfo['level']+1):1;
    		}
    		$data['level'] = $level ;    		
    		
    		$res = $menu_obj->where("id={$id}")->save($data);
    		$this->success('修改成功',U('index',array('pid'=>$pid)));
    		exit;    		
    	}
    	
    	$map['hide_flag'] = 0 ;
    	$order = "sort desc" ;
    	$icon = M('uicon')->where($map)->order($order)->select();    	    	   
    	$this->assign('icon',$icon);  
    	
    	$info = $menu_obj->where("id={$id}")->find();    	
    	$this->assign('menu',D('umenu')->getAll());    	
    	$this->assign('info',$info);
    	$this->display();
    }
    
    
    /**
     * 添加项目
     * @author viking 2015-01-23
     */
    public function addmenu(){   
    	$pid = I('pid'); 	
    	
    	if(IS_POST){
    		$menu_obj = M('umenu');
    		
    		$pid = I('pid');    	    		
    		$title = I('title');
    		if(!$title){
    			$this->error('名称必须');
    		}
    		
    		$path = I('path');
    		$sort = I('sort');
    		$pid = I('pid');
    		$is_show = I('is_show',1);
    		$icon = I('icon');
    		$data = compact('pid','title','path','sort','is_show','icon');
    
    		//等级
    		$level = 1 ;
    		if($pid){
    			$pidinfo = $menu_obj->field('level')->where("id={$pid}")->find();
    			$level = $pidinfo['level']?($pidinfo['level']+1):1;
    		}
    		$data['level'] = $level ;
    		
    		$res = $menu_obj->add($data);
    		if($res){
	    		$this->success('添加成功',U('index',array('pid'=>$pid)));    		
	    		exit;
    		}else{
                $this->error('添加失败');    			
    		}
    	}
    	
    	$map['hide_flag'] = 0 ;
    	$order = "sort desc" ;
    	$icon = M('uicon')->where($map)->order($order)->select();    	    	   
    	$this->assign('icon',$icon);    	 
    	     	 
    	$this->assign('menu',D('umenu')->getAll());    
    	$this->assign('info',array('pid'=>$pid));
    	$this->assign('act','add');	
    	$this->display('edit');
    }    
    
    
    /**
     * 批量排序
     */
    public function sort(){
    	$sort = I('sort');
    	if(empty($sort)) {
    		$this->error('请选择要操作的数据!');
    	}
    
    	foreach($sort as $k=>$v){
    		if(!is_numeric($v)){
    			continue;
    		}
    		$sql_bat_sort   .= " WHEN {$k} THEN {$v}" ;
    		$sql_ids .= ','.$k ;
    	}
    
    	$menu = M('umenu');
    	$sql = "UPDATE {$menu->getTableName()} SET sort= CASE id ".$sql_bat_sort." END WHERE id IN (".trim($sql_ids,', ').")" ;
    	$menu->query($sql);
    
    	$this->success('操作成功！');
    }    
    
}