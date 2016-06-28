<?php
namespace Admin\Controller;
use Admin\Model\MsgModel;


/**
 * 站内消息管理
 * @author Administrator
 */
class MsgController extends CommonController {	
	
	protected  $msg ;
	
	function _initialize(){
		parent::_initialize();
		$this->msg = new MsgModel();
	}

	/**
	 * 列表
	 * @author leiqianyong 2015-03-09
	 */
    public function index(){
    	    	
    	$keywords = I('keywords');
    	if($keywords){
    		$map['title'] = array("LIKE","%{$keywords}%");
    		$this->assign('keywords',$keywords);
    	}
    	$type = I('type');
    	if($type){
    		$map['type'] = array('eq',$type);
    		$this->assign('type',$type);
    	}
    	
    	//时间
        $map=$this->condtime($map);
    	
    	$map['hidden'] = 0 ;    	
    	$field = 'id,title,time,type,num,num_read' ;
    	$res = $this->msg->search($map,$field);        	
    	
    	$this->assign('list',$res['list']);
    	$this->assign('pagestr',$res['page']);
    	$this->display();
    	    	
    }
    
    
    /**
     * 查询详情
     * @author leiqianyong 2015-03-09
     */
    public function info(){
    	$id = I('id');
    	if(!$id){
    		$this->error('缺少参数');
    	}
    	
    	$where['id'] = $id;
    	$info = $this->msg->where($where)->find();
    	$this->assign('info',$info);
    	$this->display();
    }    
    
    
    /**
     * 发送站内消息
     * @author leiqianyong 2015-03-09
     */
    public function addmsg(){
    	
    	if(IS_POST){
    		$msg = D('Msg');
    		$_POST['aid'] = UID;
    		//发送类型
    		if(1==I('type')){
    		  $_POST['num'] = $this->get_member();
    		  //公众消息如果未读，可显示时间
    		  $_POST['end_time'] = strtotime("+7 days");
    		}else{
    		  $uids	= I('uid');    		
    		  if(empty($uids)){
    		  	$this->error('请选择会员信息');
    		  }  
    		  $_POST['num']  = count($uids);
    		  $_POST['uids'] = implode(',',$uids);      		   		      		  
    		}
    		    		    		    		
    		$res = $msg->send();
    		if(!$res){
    			$this->error($msg->getError());
    		}    			
    		
    		if(2==I('type')){
    			$this->addrelation($uids, $res);
    		}

    		$this->success('发送成功', U('addmsg'));    			
    		return;    		    		
    	}
    	
    	$this->display('edit');
    }
    
    
    /**
     * 获取会员总数
     * 
     */
    public function get_member(){
    	return M('user')->count();
    }
    
    
    /**
     * 添加阅读关联信息
     * @author leiqianyong 2015-03-09
     * 
     * @param array $uids_ary  接受者id
     * @param int $mid         消息id
     */
    public function addrelation($uids_ary,$mid){
    	if(empty($uids_ary) || !$mid){
    		return false;
    	}
    	
    	$read_time = NOW_TIME;
    	$sql = "INSERT INTO zl_msg_relation (uid,mid,type,state,read_time) VALUES " ;
    	$sql_bak = "";
    	foreach($uids_ary as $value){
    		$sql_bak .= "({$value},{$mid},2,0,{$read_time})," ;
    	}
    	$sql .= trim($sql_bak,",");
    	return M()->query($sql);
    }
    
        
    public function getuid(){
    	
    	$uid = 2 ;
    	$res = $this->msg->getmsgids($uid);
    	
    	print_r($res);
    	
    }
    
    

    
}