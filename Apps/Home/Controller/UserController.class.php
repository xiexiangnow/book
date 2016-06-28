<?php
namespace Home\Controller;
use Think\Controller;

class UserController extends CommonController {

    /**
     * 注册
     * @author zhangxu 2015-06-12
     */
    public function register(){
        /* 读取数据库中的配置 */
        $config =   S('DB_CONFIG_DATA');
        if(!$config){
            $config =   api('Config/lists');
            S('DB_CONFIG_DATA',$config);
        }
        C($config); //添加配置

        if(!$_POST){
            $this->display();
            die;
        }


        $userobj=D('User');

        $password=I('password');
        $repass=I('repass');
        if($password!=$repass){
            $this->error("两次密码不一致");
        }

        $res=$userobj->register_add();
        if($res<0){
            switch($res){
                case -1: $result['msg']="添加信息失败";break;
            }
            $this->error($result['msg']);
        }
       if($res){
           $this->success("注册成功",U('Home/index/index'));
           die;
       }
    }



       /*注册验证是否存在*/
    public function reverf(){
        if(IS_AJAX){
            $username=I('username');
            $userobj=D('User');
            $res=$userobj -> is_user_info($username);

            if($res){//1存在 0不存在
                $this->ajaxReturn(1);
            }else{
                $this->ajaxReturn(0);
            }
        }
    }


    
}