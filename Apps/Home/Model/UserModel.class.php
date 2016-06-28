<?php
namespace Common\Model;
use Think\Model;

class UserModel extends CommonModel{
	    
	public    $error;
	
	/* 自动验证规则 */
	protected $_validate = array(
			array('account', 'require', '帐号必填！'),
			array('account', '', '帐号已经存在！', self::MUST_VALIDATE, 'unique'), //验证用户名 是否已经被占用
			array('password', 'require', '密码必填！'),
			array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
	);
	
	protected $_auto = array (
			array('password','md5',1,'function') , // 对password字段在新增的时候使md5函数处理			
            array('reg_time','time',1,'function'),

    );
		
	
	/**
	 * 登录验证
	 */
	public function login($username, $password){
        //查询帐号
        $r = $this->where("account='{$username}' AND hide_flag=0")->find();
        if(!$r){
            $this->error = '账号不存在';
            return false;
        }
        if($r['state']==0){
        	$this->error = '账号被禁用';
        	return false;
        }
		
	    //密码错误剩余重试次数
        $times_db = M('times');	    
        $rtime = $times_db->where(array('account'=>$username, 'type'=>2))->find();
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
                $times_db->where(array('account'=>$username))->save(array('ip'=>$ip,'type'=>2));
                $times_db->where(array('account'=>$username))->setInc('times');               
            } else {
                $times_db->where(array('account'=>$username,'type'=>2))->delete();
                $times_db->add(array('account'=>$username,'ip'=>$ip,'type'=>2,'logintime'=>NOW_TIME,'times'=>1));
                $times = C('MAX_LOGIN_TIMES');
            }
            $this->error = "密码错误，您还有{$times}次尝试机会！";
            return false;
        }
        
        //登录成功记录信息
        $times_db->where(array('account'=>$username,'type'=>2))->delete();
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
        
        session('mem_auth', $auth);
        session('mem_auth_sign', data_auth_sign($auth));        
        
        return true;
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
	 * 获取账号信息
	 * @author leiqianyong 2015-05-23
	 */
	public function getInfo($field=false){
		$uid = is_login();
		$map['id'] = $uid ;
		if(false==$field){
		  return $this->field('password',true)->where($map)->find();
		}

		return $this->field($field)->where($map)->find();
	}
	
	
	/**
	 * 修改账号信息
	 * @author leiqianyong 2015-05-23
	 * @param unknown $data
	 */
	public function editInfo($data){


		$map['id'] = is_login();


		$res = $this->where($map)->save($data);




		return is_numeric($res)?true:false;




	}

    /**
     * 获取用户名是否存在
     * @aucthor zhangxu 2015-06-12
     * @param string $acout 用户名
     */
    public function is_user_info($acout){
        if(!$acout){
            return false;
        }
        $where['$acout']=$acout;

       return $this->where($where)->find();

    }



    /**
     * 注册用户添加
     * @author zhangxu 2015-06-12
     */

    public function register_add(){
        $data=$this->create();
        if(!$data){
            return $this->error;
        }
        $reg_ip=get_client_ip();
        $data['reg_ip']=$reg_ip;
        $data['truename']=$data['account'];
        $data['nickname']=$data['account'];
        $res=$this->add($data);

        if(!$res){
            return -1;
        }

        return $res;

    }



	
}