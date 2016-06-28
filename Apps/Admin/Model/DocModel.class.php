<?php
namespace Admin\Model;
use Think\Model;
/**
 * 内容模型
 * @author viking 2015-02-23
 */
class DocModel extends CommonModel {		
	
	/* 自动验证规则 */	
    protected $_validate = array(
        array('title', 'require', '标题不能为空!'),
    	array('content','checkContent','内容不能为空',0,'callback'),
    	array('files_url','checkAlbum','请选择附件',1,'callback')	
    );       

    /* 自动完成 */
    protected $_auto = array(
    	array('time','time',1,'function'),
    	array('updatetime','time',3,'function'),
    	array('endtime','endtimed',3,'callback'),
    );
    
    /**
     * 检查不允许内容为空的情况
     */
    public function checkContent(){    	
    	if(1!=I('module')){
    		return true;
    	}    
    	$content = I('content');
    	if(empty($content)){
    		return false;
    	}    
    	return true;
    }    

    
    /**
     * 检查不允许图片为空的情况
     */
    public function checkAlbum(){    	
    	if(I('module')<=1){
    		return true;
    	}
    	$content = I('files_url');
    	if(empty($content)){
    		return false;
    	}
    	return true;
    }    
    
    
    public function endtimed($param){
    	if(!$param)return 0;
    	
    	return strtotime($param);
    }
    
    
    /**
     * 添加修改内容信息
     * @author leiqianyong 2015-04-29
     */
    public function edit(){
		$data = $this->create();
		if(!$data){
			return $this->error;
		}

        $time=I('time');
        if($time){
            $data['time']=strtotime($time);
        }
        
		$id = $data['id'];
		$data['category_ids'] = $this->getIds($data['category_id']);
		
		//==========操作数据		
		//添加
		if(!$id){
			$id = $this->add($data);		
			if(!$id)return false;

			switch ($data['module']){								
				case 1:$res = $this->edit_doc($id);break;
				case 2:
				case 3:
				case 4:$res = $this->edit_album($id);break;				
				default:break;
			}
			
			return $res?true:false;				
		}
		
		//修改	
		$res = $this->where("id={$id}")->save($data);		
		
		switch ($data['module']){
			case 1:$res = $this->edit_doc($id,true);break;
			case 2:
			case 3:			
			case 4:$res = $this->edit_album($id,true);break;
			default:break;
		}		
		
		return is_numeric($res)?true:false;  	
    }
    
    
    /**
     * 添加文章详细内容
     * @param bool $edit 是否修改 
     * @author leiqianyong 2015-04-29
     */
    public function edit_doc($id,$edit=false){
    	$content = I('content');    	
    	$data['content'] = $content;
    	if(!$edit){
    	   $data['id'] = $id;
    	   return M('doc_content')->add($data);
    	}
    	
    	$where = "id=".$id ;
    	return M('doc_content')->where($where)->save($data);
    }
    
    
    /**
     * 添加相册详细内容
     * @param bool $edit 是否修改
     * @author leiqianyong 2015-04-29
     */
    public function edit_album($id,$edit=false){    	
    	$album = I('files_url');
    	$alt = I('files_alt');
    	$ary = array();
    	foreach($album as $key=>$value){
    		$ary[] = array('url'=>$value,'alt'=>$alt[$key]);
    	}
    	
    	$data['sourceurls'] = var_export($ary,true);
    	$data['content'] = I('content');
    	if(!$edit){
    		$data['id'] = $id;
    		return M('doc_content')->add($data);
    	}
    	 
    	$where = "id=".$id ;
    	return M('doc_content')->where($where)->save($data);
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
