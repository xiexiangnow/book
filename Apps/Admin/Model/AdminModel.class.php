<?php
namespace Admin\Model;
use Think\Model;

class AdminModel extends CommonModel{
	    
	protected $pk        = 'id';
	public    $error;
	
	/* 自动验证规则 */
	protected $_validate = array(
			array('account', 'require', '帐号必填！'),
			array('account', '', '帐号已经存在！', self::MUST_VALIDATE, 'unique'), //验证用户名 是否已经被占用
			array('password', 'require', '密码必填！'),
			array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
			//array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
	);
	
	protected $_auto = array (
			array('password','md5',1,'function') , // 对password字段在新增的时候使md5函数处理
			array('groups','groups',1,'callback') , // 对password字段在新增的时候使md5函数处理
	);	
	
	
	public function groups(){
		$groups = I('group');
		if($groups){
			return implode(',', $groups);
		}
		return '';
	}
	
	
	/**
	 * 登录验证
	 */
	public function login($username, $password){
        //查询帐号
        $r = $this->where("account='{$username}' AND hide_flag=0")->find();
        if(!$r){
            $this->error = '管理员不存在';
            return false;
        }
        if($r['state']==0){
        	$this->error = '账号被禁用';
        	return false;
        }
		
	    //密码错误剩余重试次数
        $times_db = M('times');	    
        $rtime = $times_db->where(array('account'=>$username, 'type'=>1))->find();
        if($rtime && ($rtime['times'] >= C('MAX_LOGIN_TIMES'))) {
            $minute = C('LOGIN_WAIT_TIME') - floor((NOW_TIME-$rtime['logintime'])/60);
            if ($minute > 0) {
                $this->error = "密码重试次数太多，请过{$minute}分钟后重新登录！";
                return false;
            }else {
                $times_db->where(array('account'=>$username,'type'=>1))->delete();
            }
        }
		
		$password = md5($password);
        $ip = get_client_ip();
        if($r['password'] != $password) {
            if($rtime && ($rtime['times'] < C('MAX_LOGIN_TIMES'))) {
                $times = C('MAX_LOGIN_TIMES') - intval($rtime['times']);
                $times_db->where(array('account'=>$username))->save(array('ip'=>$ip,'type'=>1));
                $times_db->where(array('account'=>$username))->setInc('times');               
            } else {
                $times_db->where(array('account'=>$username,'type'=>1))->delete();
                $times_db->add(array('account'=>$username,'ip'=>$ip,'type'=>1,'logintime'=>NOW_TIME,'times'=>1));
                $times = C('MAX_LOGIN_TIMES');
            }
            $this->error = "密码错误，您还有{$times}次尝试机会！";
            return false;
        }
        
        //登录成功记录信息
        $times_db->where(array('account'=>$username,'type'=>1))->delete();
        $condtion['lastloginip']=$ip;
        $condtion['lastlogintime']=NOW_TIME;
        if(!empty($r['lastloginlog'])){
        	$log = string2array($r['lastloginlog']);
        	array_unshift($log, NOW_TIME.'|'.$ip);
        	count($log)>3 && array_pop($log);
        }else{
        	$log = array(NOW_TIME.'|'.$ip);
        }
        $condtion['lastloginlog']=var_export($log,true);
        $condtion['logincount']=array('exp','logincount+1');
        $this->where(array('id'=>$r['id']))->save($condtion);
                        
        /* 记录登录SESSION和COOKIES */
        $auth = array(
        		'uid'             => $r['id'],
        		'account'        => $username,
        		'last_login_time' => NOW_TIME,
        );
        
        session('user_auth', $auth);
        session('user_auth_sign', data_auth_sign($auth));        
        
        return true;
	}
	
	
	/**
	 * 获取用户信息
	 */
	public function getUserInfo($userid){
	    $admin_role_db = D('AdminRole');
	    $info = $this->field('password', true)->where(array('id'=>$userid))->find();
		if($info) $info['rolename'] = $admin_role_db->getRoleName($info['roleid']);    //获取角色名称
	    return $info;
	}
    
	/**
	 * 修改密码
	 */
	public function editPassword($userid, $password){
		$userid = intval($userid);
		if($userid < 1) return false;
		$passwordinfo = password($password);
		return $this->where(array('id'=>$userid))->save($passwordinfo);
	}
	
	
	/**
	 * 获取会员对应的规则ids
	 */
	function getrules($uid=null){
		$uid = is_null($uid)?is_login():$uid;
		$res = M('admin')->field('groups')->where('id='.$uid)->find();		
		if(empty($res['groups'])){
			return false;
		}
		 
		$map['id'] = array('in',$res['groups']);
		$map['state'] = 1 ; 
		$map['hide_flag'] = 0 ;
		$groups = M('auth_group')->field('rules')->where($map)->select();
		$ids = array();//保存用户所属用户组设置的所有权限规则id
		foreach ($groups as $g) {
			$ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
		}
		$ids = array_unique($ids);
		return $ids ;
	}

    /**
     *获取用户栏目权限ids
     */
        function getcolumns($uid=null){
            $uid = is_null($uid)?is_login():$uid;
            $res = M('admin')->field('groups')->where('id='.$uid)->find();

            if(empty($res['groups'])){
                return false;
            }
            $map['id'] = array('in',$res['groups']);
            $map['state'] = 1 ;
            $map['hide_flag'] = 0 ;
            $groups = M('auth_group')->field('column')->where($map)->select();

            $ids = array();//保存用户所属用户组设置的所有权限规则id
            foreach ($groups as $g) {
                $ids = array_merge($ids, explode(',', trim($g['column'], ',')));
            }
            $ids = array_unique($ids);
            return $ids ;

        }


}