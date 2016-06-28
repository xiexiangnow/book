<?php
namespace Index\Controller;
use Think\Controller;
class LoginController extends Controller {


   //登录页面的加载
    public function index(){

    	$this->display();
    }

    //登录验证
    public function login_check(){

        $username = I('username');
        $password = I('passwd');
        $code     = I('verify');

        if($username==null || $password==null || $code==null){
          return false;
        }

        if(!check_verify($code)){
            $data['state'] = 0;
            $data['msg'] = '验证码输入错误';
            $this->ajaxReturn($data,'JSON');
        }

       if($this->check_account($username,$password)){
           //提取用户信息
           $accountModel = new \Index\Model\AccountModel();
           $user_info = $accountModel->findUserInfo($username);

           //设定cookie
           $this->record_cookie($user_info['id'],$username);

           //返回状态
           $data['state'] = 1;
           $data['msg'] = '恭喜，登录成功！';
           $this->ajaxReturn($data,'JSON');

       }else{
           $data['state'] = 0;
           $data['msg'] = '用户名或者密码错误！您还剩下xxxx次错误登录机会！';
           $this->ajaxReturn($data,'JSON');
       }

    }


    //设置cookie
    public function record_cookie($user_id,$username){
        $random_data = encrypt($user_id.'|'.$username,'E',C('COOKIE_KEY'));
        setcookie('user_id', $user_id, time() + 3600*5, '/', $_SERVER ['HTTP_HOST'], 0, 1);
        setcookie('username', $username, time() + 3600*5, '/', $_SERVER ['HTTP_HOST'], 0, 1);
        setcookie('user_auth', $random_data, time() + 3600*5, '/', $_SERVER ['HTTP_HOST'], 0, 1);

    }



    //登陆时的用户名和密码的验证
    public function check_account($username,$password){
        if($username=='' || $password==''){
            return false;
        }
        $login = M('Account');
        $where['username'] = $username;
        $where['password'] = md5($password);
        $is_have = $login->where($where)->count();
        if($is_have>0){
            return true;
        }else{
            return false;
        }
    }
     

    //注册页面
    public function regiter(){

        $this->display();
    }

    /**
     * 注册新用户
     * @author xiexiang 2015-12-13
     ***/
    public function regiter_new(){
         $username = I('username','','trim');
         $password = I('password','','trim');
         $code     = I('code','','trim');
        if(!check_verify($code)){
            //$this->ajaxReturn('验证码输入错误','JSON');
            echo "2";      //状态2：验证码错误！
            exit;
        }
        if($username=='' || $password==''){
            return false;
        }
        $login = M('Login');
        $data['username']  = $username;
        $data['password']  = md5($password);
        $data['time']      = NOW_TIME;
        //验证用户名是否重复
        $num = $login->where("username='{$username}'")->count();
        if($num>0){
           //$this->ajaxReturn('用户名重复！');
            echo "3";    //状态3：用户名重复！
            exit;
        }
        $res = $login->add($data);
        if($res){
           // $this->ajaxReturn('恭喜，注册成功！','JSON');   //返回1：状态  注册成功！
            echo "1";     //返回1：状态  注册成功！
        }else{
           echo "0";      //状态0：注册失败
        }
    }



    // 检测输入的验证码是否正确，$code为用户输入的验证码字符串
    public function check_verify($code, $id = ''){
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }


    /* 验证码，用于登录和注册 */
    public function verify(){

        $verifyConfig = array(
            'length' => 4,
            'fontSize' => 12,
            'useCurve' => false,
            'useNoise' => false,
            'codeSet' =>'0123456789',
            'fontttf' => '5.ttf'
        );

        $verify = new \Think\Verify($verifyConfig);
        $verify->entry(1);
    }


    //退出操作
    public function login_out(){
        setcookie('user_id', '', -1, '/', $_SERVER ['HTTP_HOST'], 0, 1);
        setcookie('username', '', -1, '/', $_SERVER ['HTTP_HOST'], 0, 1);
        setcookie('user_auth', '', -1, '/', $_SERVER ['HTTP_HOST'], 0, 1);
        $this->redirect('Login/index');

    }

}
