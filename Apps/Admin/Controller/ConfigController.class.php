<?php

namespace Admin\Controller;
use Think\Page;
/**
 * 后台配置控制器
 * @author leiqianyong 2015-02-14
 */
class ConfigController extends CommonController {
	
	protected $config ;
	
	function _initialize(){
		parent::_initialize();
		$this->config = new \Admin\Model\ConfigModel();
	}

    /**
     * 配置管理
     */
    public function index(){    	        
        $map = array();
        $map  = array('status' => 1);
        if(I('group')){
            $map['group'] = I('group',0);
            $this->assign('group',I('group'));
        }
        if(is_numeric(I('type'))){
        	$map['type'] = I('type');
        	$this->assign('type',I('type'));
        }        
        if(I('keyword')){
            $map['name|title']    =   array('like', '%'.(string)I('keyword').'%');
            $this->assign('keyword',I('keyword'));
        }
        
       $res = $this->config->search($map,'*',13,'sort DESC');  
        	        
        $this->assign ('list', $res['list'] );
        $this->assign('page',$res['page']);                                
        $this->display();
    }

    /**
     * 新增配置
     */
    public function add(){
        if(IS_POST){
            $Config = D('Config');
            $data = $Config->create();
            if($data){
                if($Config->add()){
                    S('DB_CONFIG_DATA',null);
                    $this->success('添加成功', U('index'));
                } else {
                    $this->error('添加失败');
                }
            } else {
                $this->error($Config->getError());
            }
        } else {
            $this->assign('act','add');
            $this->assign('info',null);
            $this->display('edit');
        }
    }

    /**
     * 编辑配置
     */
    public function edit($id = 0){
        if(IS_POST){
            $Config = D('Config');
            $data = $Config->create();
            if($data){
                if($Config->save()){
                    S('DB_CONFIG_DATA',null);                   
                    $this->success('修改成功');
                } else {
                    $this->error('修改失败');
                }
            } else {
                $this->error($Config->getError());
            }
        } else {
            $info = array();            
            $info = M('Config')->field(true)->find($id);
            if(false === $info){
                $this->error('获取配置信息错误');
            }
            $this->assign('info', $info);            
            $this->display();
        }
    }

    /**
     * 批量保存配置
     */
    public function save($config){
        if($config && is_array($config)){
            $Config = M('Config');
            foreach ($config as $name => $value) {
                $map = array('name' => $name);
                $Config->where($map)->setField('value', $value);
            }
        }
        S('DB_CONFIG_DATA',null);
        $this->success('保存成功！');
    }

    /**
     * 删除配置
     */
    public function del(){
        $id = I('id');
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map['id'] = $id ;
        if(M('Config')->where($map)->delete()){
            S('DB_CONFIG_DATA',null);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    // 获取某个标签的配置参数
    public function group() {
    	$id   =   I('get.id',1);
        $type   =   C('CONFIG_GROUP_LIST');
        $list   =   M("Config")->where(array('status'=>1,'group'=>$id))->field('id,name,title,extra,value,remark,type')->order('sort DESC')->select();
        if($list) {
            $this->assign('list',$list);
        }

        $this->id = $id;
        $this->type = $type;            
        $this->display();
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
    	$sql = "UPDATE {$_prefix}config SET sort= CASE id ".$sql_bat_sort." END WHERE id IN (".trim($sql_ids,', ').")" ;
    	M()->query($sql);
    
    	$this->success('操作成功！');
    } 
        
}
