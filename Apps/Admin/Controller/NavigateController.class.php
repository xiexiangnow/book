<?php
namespace Admin\Controller;
use Think\Controller;


/**
 * 文章导航信息管理
 * @author Administrator
 */
class NavigateController extends CommonController {	
	
	protected $navigate ;
	
	function _initialize(){
		parent::_initialize();
		$this->navigate = new \Admin\Model\NavigateModel();
		$this->assign('nav_menu',$this->menu());
	}
	
	
	public function menu(){		 
		$menu = array(
			array('title'=>'栏目管理','navk'=>0,'url'=>U('Navigate/index',array('type'=>0))),	
			array('title'=>'添加栏目','navk'=>1,'url'=>U('Navigate/add',array('type'=>1))),
			array('title'=>'添加单网页','navk'=>2,'url'=>U('Navigate/add',array('type'=>2))),				
			array('title'=>'添加外部链接','navk'=>3,'url'=>U('Navigate/add',array('type'=>3))),
		);
		
		return $menu ;
	}
	
	
	/**
	 * 菜单列表
	 * @author viking 2015-01-23
	 */
    public function index(){
    	
    	$this->assign('list',$this->navigate->getAll());
    	$this->assign('info',array('pid'=>$pid));
    	//文档模型  
        $this->assign('doc_type',C('DOC_TYPE')); 
    	//栏目类型
        $type_ary=array(1=>'内部栏目','单网页','外部链接');
    	$this->assign('type_ary',$type_ary);
    	$this->assign('navk',0);
    	$this->display();
    	    	
    }
    
    

    /**
     * 将分类信息组合成select树形结构
     * @author leiqianyong 2015-04-28
     */
    public function option($select=0){
       $cats = $this->navigate->getAll();
       
       $res = '';
       foreach($cats as $k=>$vo){
           $level = $vo['level']-1;
           $str='';
           if($level){
       	      $str = str_repeat("&nbsp;",$level).($cats[$k+1]['level']==$vo['level']?"├":"└");
            }
                  
           $res .= '<option value="'.$vo['id'].'" '.($select==$vo['id']?'selected':'').'>'.$str.' '.$vo['title'].'</option>' ;
       }

       return $res;
    }    
    
     
    public function tree(){

        //栏目权限
        $conf['type'] = array('neq',3);
        if(is_administrator()){ // 是否是超级管理员
            $this->assign('tree',$this->navigate->getAll($conf));
        }else{
            $adminobj=D('Admin');
            $colids=$adminobj->getcolumns();
            $coids=implode(',',$colids);
            $map['category_id']=array('in',$coids);
            $conf['id']=array('in',$coids);
            $this->assign('tree',$this->navigate->getAll($conf));


        }

    	$this->display();
    }
   
    
    /**
     * 修改栏目页分类
     * @param int $module 模型  1-文章 2-相册 3-视频 4-下载资料
     */
    public function cats($module=1){
    	$find = $this->navigate->field('id,title,pid,ids')->where('type=1 AND module='.$module)->select();    	
    	if(!$find){
    		return false;
    	}
    	
    	$ids = '';
    	foreach($find as $val){
    		$ids = trim($ids.','.$val['ids'],' ') ;
    	}
    	

    	$order='level ASC,sort DESC';


        //栏目权限显示
        if(is_administrator()){ // 是否是超级管理员
            $map['id'] = array('in',$ids);

            $res = $this->navigate->field('id,title,pid')->where($map)->order($order)->select();
        }else{
            $adminobj=D('Admin');
            $colids=$adminobj->getcolumns();
            $coids=implode(',',$colids);
            $map['id']=array('in',$coids);
            $res = $this->navigate->field('id,title,pid')->where($map)->order($order)->select();

        }
    	$this->assign('tree',$res);
    	$this->assign('cat',true);
    	$this->display('tree');
    }
    
    
    /**
     * 删除菜单
     * @author viking 2015-01-23
     */
    public function del(){
    	 $id = I('id');
    	    	 
    	 $child = $this->navigate->where("pid={$id}")->find();
    	 if($child){
    	 	$this->error('存在下级目录，不能删除');
    	 }
    	 
    	 //检查是否有对应的内容记录，如果有则不能删除
    	 $record = M('doc')->where('category_id='.$id)->field('id')->find();
    	 if($record){
    	 	$this->error('该栏目下有记录，不能删除');
    	 }
    	 
    	 $res = $this->navigate->where("id={$id}")->delete();
    	 if(!$res){    	 	
    	 	$this->error('删除失败');
    	 }
    	     	 
    	 $this->success('删除成功');
    }
  
    
    /**
     * 修改栏目信息
     * @author viking 2015-01-23
     */
    public function edit(){
    	$id = I('id');    	
    	if(IS_POST){       		    		
    		$res	= $this->navigate->edit();    		
    		if(true===$res){
    			$this->success('修改成功',U('index',array('pid'=>$pid)));   
    			exit; 			
    		}else{    			
    			$this->error($res);
    		}    		
    		 		
    	}

    	//对应的栏目编辑模板
    	$tpl = array(1=>'menu',2=>'single','url');    	
    	
    	$info = $this->navigate->where("id={$id}")->find();    	
    	$this->assign('menu',$this->navigate->getAll());
    	$this->assign('info',$info);
    	$this->assign('options',$this->option($info['pid']));
    	$this->display($tpl[$info['type']]);
    }
    
    
    /**
     * 添加栏目
     * @author leiqianyong 2015-04-28
     */
    public function add(){       	
    	$type = I('type');	    	
    	if(IS_POST){
    	   $res	= $this->navigate->edit();
    		
    		if(true===$res){
	    		$this->success('添加成功',U('index'));    		
	    		exit;
    		}else{
                $this->error($res);    			
    		}
    	}
    	
    	//对应的栏目编辑模板
    	$tpl = array(1=>'menu',2=>'single','url');
    	     	 
    	$this->assign('menu',$this->navigate->getAll());    
    	$this->assign('navk',$type);    	 
    	$this->assign('options',$this->option());		
    	$this->display($tpl[$type]);
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
    
    	$_prefix = C('DB_PREFIX');
    	$sql = "UPDATE {$_prefix}navigate SET sort= CASE id ".$sql_bat_sort." END WHERE id IN (".trim($sql_ids,', ').")" ;
    	M()->query($sql);
    
    	$this->success('操作成功！');
    }    
    
}