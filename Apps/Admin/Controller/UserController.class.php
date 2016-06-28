<?php
namespace Admin\Controller;
use Think\Controller;

class UserController extends CommonController{
	
	//会员详情页分类导航
	protected $navigate = array(
		array('id'=>1,'title'=>'账号基本信息','address'=>'edit'),	
		array('id'=>2,'title'=>'邮寄信息','address'=>'mail'),
		array('id'=>3,'title'=>'银行卡信息','address'=>'bank'),
	);
	
	
	public function lists(){
		$type = I('type');
		$state = I('state');
		$keyword = I('keyword');
		$userobj = new \Home\Model\User();
		$options = array();
		if(is_numeric($type)) $options['type'] = $type;
		if(is_numeric($state)) $options['state'] = $state;
		if($keyword) $options['keyword'] = $keyword;
		
		
		//审核状态0未审核1审核通过2审核不通过
		$list = $userobj->search($options, $sort);
		
		$this->assign('list', $list['list']);
		$this->assign('pagestr', $list['page']);
		$this->assign('type', $type);
		$this->assign('keyword', $keyword);
		$this->display();
	}
	
	/**
	 * 设置用户状态
	 * @author zhufu 2015-03-02 16:49:25
	 */
	public function update(){
		$state = I('state');
		$uid = I('uid');
		$forbid = I('forbid');
		$result = array('state'=>false);
		if(!$uid){
			$result['msg'] = '没有用户ID';
			$this->ajaxReturn($result);
		}
		$userobj = new \Home\Model\User();
		$data = array('id'=>$uid);
		if(is_numeric($type)) $data['type'] = $type;
		if(is_numeric($state)) $data['state'] = $state;
		if(is_numeric($forbid)) $data['forbid'] = $forbid;
		$res = $userobj->save($data);
		
		$result = array('state'=>true, 'msg'=>'操作成功');
		$this->ajaxReturn($result);
	}
	
	public function edit(){
		$uid = I('id');	//用户的ID
		$userobj = new \Home\Model\User();
		$info = $userobj->info_by_id($uid);
		if(!$info){
			$this->error('没有用户信息');			
		}		
		$usertype = array(1=>'需求者（个人）', 2=>'需求者（企业）', 3=>'服务商（个人）', 4=>'服务商（公司）');
		$userstate = array(0=>'未审核', 1=>'审核通过',2=>'审核不通过');
		
		$this->assign('state', $userstate);
		$this->assign('type', $usertype);
		$this->assign('info', $info);
		$this->assign('nav',$this->navigate);
		$this->assign('navid',1);				
		$this->assign('uid',$uid);
		$this->display();
	}
	
	
	/**
	 * 查询会员的邮寄信息
	 * @author leiqianyong 2015-03-06
	 */
	public function mail(){
		$uid = I('id');
		if(!$uid){
			$result['msg'] = '没有用户ID';
			$this->ajaxReturn($result);
		}	

		$map['uid'] = $uid ;		
		$mail = new \Home\Model\MailaddressModel();
		$res = $mail->search($map);
		
		$this->assign('list',$res['list']);
		$this->assign('page',$res['page']);
		$this->assign('uid',$uid);
		$this->assign('nav',$this->navigate);
		$this->assign('navid',2);		
		$this->display('mail');
		
	}
	
	
	/**
	 * 查询会员的银行卡信息
	 * @author leiqianyong 2015-03-06
	 */
	public function bank(){
		$uid = I('id');
		if(!$uid){
			$result['msg'] = '没有用户ID';
			$this->ajaxReturn($result);
		}
	
		$map['uid'] = $uid ;
		$map['state'] = array('neq',2);
		$mail = new \Home\Model\BankModel();
		$res = $mail->search($map);
		
		$this->assign('list',$res['list']);
		$this->assign('page',$res['page']);		
		$this->assign('uid',$uid);
		$this->assign('nav',$this->navigate);
		$this->assign('navid',3);	
		$this->display('bank');
	
	}	
	
	
	/**
	 * 根据条件查询会员信息
	 * @author leiqianyong 2015-03-12
	 *
	 * @param array $map 查询条件
	 */
	public function search(){					
	
		$keywords = I('keywords');
 		$map['account|truename'] = array('LIKE',"{$keywords}%");
		
			
 		$field = 'id,account,truename' ;
 		$pagesize = 20 ;
		$res = D('User')->_search($map,$field,$pagesize);

		if(IS_AJAX){
			$data['list'] = $res['list'] ;			
			$data['pagestr'] = $res['page'] ;			
			$this->ajaxReturn($data);
			exit;
		}
				

		
 		$this->assign('list',$res['list']); 		
 		$this->assign('pagestr',$res['page']);
		$this->display('User/collect');
	
	}	
	
	
	public function test(){
		$mobile = 18996380233 ;
		$content = rand(1000, 9999);    
		$type = 1;
		$res = sendVerifySms($mobile,$content,$type);
		
		var_dump($res);
	}
	
	
	public function check(){
		$mobile = 18996380233 ;
		$content = 9486 ;//rand(1000, 9999);
		$type = 1;
		$res = checkVerifySms($mobile,$content,$type);
		
		var_dump($res);		
	}
	
}
