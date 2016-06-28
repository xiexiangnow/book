<?php
namespace Admin\Model;
use Think\Model;

/**
 * Msg模型
 */
class MsgModel extends CommonModel{
	
	
	private $table_relation = 'msg_relation' ;
	
	/* 自动验证规则 */
	protected $_validate = array(
			array('content', 'require', '消息内容必填')			
	);
	
	
	
	/**
	 * 添加消息
	 */
	public function send(){
				
		$data = $this->create();
		if(!$data){
			return false;
		}		
		
		return $this->editmsg($data);
	}
	
		
	
	
	/**
	 * 发送消息
	 * @author leiqianyong 2015-03-09
	 * 
	 * @return boolean fasle 失败 ， int  成功 返回完整的数据	 
	 */
	private function editmsg($data){

		$data['time'] = NOW_TIME;
		
		/* 添加内容 */
		if(empty($data['id'])){ 		
			$id = $this->add($data);
			if(!$id){
				$this->error = '新增出错！';
				return false;
			}			
		} else { //更新数据
			$status = $this->save($data); 
			if(false === $status){
				$this->error = '更新出错！';
				return false;
			}
			$id = $data['id'];
		}
			
		//内容添加或更新完成
		return $id;
		
	}	
		

	/**
	 * 获取会员未读消息的数量
	 * 
	 * @param int $uid
	 */
	public function getmsgcount($uid){
		if(!$uid){
			return false;
		}
		
		$res = $this->getmsgids($uid);		
		return empty($res[0])?0:(substr_count($res[0],',')+1);
	}
	
	
	/**
	 * 查询某人的消息列表
	 * @author leiqianyong 2015-03-09
	 * 
	 * @param int $uid  会员id
	 * @param int $type 消息类型    0-未读  1-已读 2-删除 3-全部关联 4-会员能够看到全部记录  5-未阅读的公共记录 
	 */
	public function searchmsg($uid,$type=4){
		$midary = $this->getmsgids($uid);
		
		if(empty($midary)){
			return false;
		}
								
		$map['id'] = array('in',$midary[$type]);
		return $this->search($map,'id,title,time,type');		
	}
	
	
	/**
	 * 查询某条消息
	 * @author leiqianyong 2014-12-05
	 * 
	 * @param int $uid  会员id
	 * @param int $id   消息id
	 */
	public function readmsg($uid,$id){
		$midary = $this->getmsgids($uid);
		
		$mids = explode(',',$midary[4]);
		if(!in_array($id, $mids)){
			return false;
		} 
		
		$res = $this->where("id={$id}")->find();
		
		//未阅读公共信息
		$noreadpublicids = explode(',', $midary[5]);
		if($noreadpublicids && in_array($id, $noreadpublicids)){
			$data['uid'] = $uid ;
			$data['mid'] = $id ;
			$data['type'] = $res['type'] ;
			$data['state'] = 1 ;
			$data['read_time'] = NOW_TIME;			
			M($this->table_relation)->add($data);
			$this->where("id={$id}")->setInc('num_read',1);		  
		}
		
		//未阅读的关联信息
		$nowreadids = explode(',', $midary[0]);
		if($nowreadids && in_array($id, $nowreadids)){
			$data['state'] = 1 ;
			$data['read_time'] = NOW_TIME ;
			M($this->table_relation)->where("uid={$uid} AND mid={$id}")->save($data);
			$this->where("id={$id}")->setInc('num_read',1);			
		}
		
		return $res ;
	}
	
	
	/**
	 * 获取会员信息消息信息
	 * 
	 * @param int $uid 会员id
	 * @return array $ids  0-未阅读的id(包括公共的) 1-已阅读的id 2-删除的消息id 3-全部关联的id 4-当前能够看到的消息  5-未阅读的公共信息
	 */
	public function getmsgids($uid){
		if(!$uid){
			return false;
		}
		
		//查询会员关联的消息	
		$object = M();	
		$sql = "SELECT state,GROUP_CONCAT(mid) AS ids FROM zl_{$this->table_relation} WHERE uid={$uid} GROUP BY state" ;
		$res = $object->query($sql);
				
		$ids[0] = '' ;	//未阅读消息记录id	
		$ids[1] = '' ;  //已阅读消息记录id
		$ids[2] = '' ;  //删除的消息记录id
		$ids[3] = '' ;  //全部关联消息记录id	
		$ids[4]	= '' ;  //会员能够看到的记录id
		$ids[5] = '' ;  //未阅读的公共记录id
		
		//组合数据
		if($res){
			foreach($res as $k=>$v){
				$ids[$v['state']] = $v['ids'] ;
				$ids[3] .= $v['ids'].',' ;
			}
			$ids[3] = trim($ids[3],',');
		}
				
		$time = NOW_TIME ;
		//查询未读的公共消息    过期时间是为了防止数据的累计
		$sql = "SELECT GROUP_CONCAT(id) AS ids FROM {$this->trueTableName} WHERE state=1 AND type=1 AND (end_time=0 || end_time>{$time})" ;
		if($ids[3]){
			$sql .= " AND id NOT IN({$ids[3]})" ;
		}
		$result = $object->query($sql);
		if(empty($result[0])){
			$ids[4] = trim($ids[0].','.$ids[1],',');
			return $ids ;
		} 
		
		$ids[0] = trim($ids[0].','.$result[0]['ids'],',');
		$ids[4] = trim($ids[0].','.$ids[1],',');
		$ids[5] = $result[0]['ids'];
		return $ids ;
	}	
	
			
}
