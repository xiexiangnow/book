<?php
namespace Admin\Model;
use Think\Model;
use Think\Page;

/**
 * 内容模型
 * @author viking 2015-02-23
 */
class GatherModel extends Model {
	
	/* 自动验证规则 */	
    protected $_validate = array(
        array('title', 'require', '标题不能为空!'),
    	array('description','require','投票介绍不能为空'),
    );       

    /* 自动完成 */
    protected $_auto = array(
    	array('time','time',1,'function'),

    );
    



    
    /**
     * 添加修改信息
     * @author zhangxu 2015-06-09
     */
    public function edit(){

		$data = $this->create();

		if(!$data){
			return $this->error;
		}

		$id = $data['id'];
        $endtime=I('endtime');

        if($endtime){//结束时间的处理
            $data['endtime']=strtotime($endtime);
        }else{
            $data['endtime']=0;
        }
		//==========操作数据
		//添加
		if(!$id){
			$id = $this->add($data);
			if(!$id)return false;

			$res = $this->edit_doc($id);

			return $res?true:false;
		}

		//修改
		$res = $this->where("id={$id}")->save($data);

       if(is_numeric($res)){
           $re= $this->edit_doc($id,true);
       }
		return is_numeric($re)?true:false;
    }
    
    
    /**
     * 添加投票选项详细内容
     * @param bool $edit 是否修改 
     * @author zhangxu 2015-06-09
     */
    public function edit_doc($id,$edit=false){
        if(!$id)return false;

        $xuan = I('xuan');
    	if(!$edit){//添加
            $data['pid'] = $id;
            foreach($xuan as $v){
                $data['content'] = $v;
               $re= M('gather_node')->add($data);
               if(!$re){
                 return false;
               }
            }
            return $re;
    	}

       //修改


        $ids=I('ids');
        foreach($xuan as $k=>$v){

            $where ="id=$ids[$k]";
            $data['content'] = $v;
            if(!$ids[$k]){
                $data['pid'] = $id;
                $re= M('gather_node')->add($data);
            }else{
                $re= M('gather_node')->where($where)->save($data);

            }
            if(!is_numeric($re)){
                return false;
            }
        }
        return $re;
    }


    /**
     *查询
     *@author zhangxu 2015-06-09
     *
     */

        public function search($query='',$order='',$pagesize=10){
            $count=$this->where($query)->count();
            $page=new \Think\Page($count,$pagesize);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $list = $this->where($query)->limit($page->firstRow. ',' . $page->listRows)->order($order)->select();

            $lists=array('list'=>$list,'page'=>$page->show());
            return $lists;
        }












    /**
     * 获取内容信息
     * @param int $id 内容id 
     */
    public function info($id){
    	$where = "id={$id}" ;
    	$info = $this->where($where)->find();
    	if(empty($info)){
    		return false;
    	}
    	
    	$content_obj = M('doc_content');
    	switch ($info['module']){    		
    		case 1:  $content = $content_obj->field('content')->where($where)->find();
    		         $info['content'] = $content['content'] ; 
    		         break;
    	    default:  $content = $content_obj->where($where)->find();
    		         $info['content'] = $content['content'] ;
    		         $info['sourceurls'] = $content['sourceurls'];     		        
    	}
    	
    	return $info;
    }
    
    
    /**
     * 获取分类的ids
     */
    private function getIds($pid){
    	$info = M('navigate')->field('ids')->where('id='.$pid)->find();
    	return $info['ids'];
    }
    
    
}
