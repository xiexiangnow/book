<?php
namespace Index\Controller;
use Think\Controller;

class CommonController extends Controller {

    public function _initialize(){
        //判断是否登录
        self::check_login();

        //用户信息提取
        $user_auth_decryption = encrypt($_COOKIE['user_auth'],'D',C('COOKIE_KEY'));
        $this->assign('user_info',explode('|',$user_auth_decryption));


        //左侧菜单的提取
        $where['is_show'] = 1;
        $where['hide_flag'] = 0;
        $order = "level ASC,sort DESC";
        $this->assign('menu_list_nav',self::getnav($where,$order));

        //目前的菜单id(做选中效果用)
        $this->assign('now_nav',I('nav'));
        $menuModel = new \Index\Model\MenuModel();
        $parent_id = $menuModel->findParentId(I('nav'));
        $this->assign('now_farent_id',$parent_id['pid']);

        //顶部面包导航
        $now_nav = self::get_bread_nav(CONTROLLER_NAME,ACTION_NAME);
        $this->assign('child',$now_nav['child']);
        $this->assign('parent',$now_nav['parent']);

    }


    //登录监测
    public function check_login(){
        $username  = $_COOKIE['username'];
        $user_id   = $_COOKIE['user_id'];
        $user_auth = $_COOKIE['user_auth'];
        $user_auth_decryption = encrypt($user_auth,'D',C('COOKIE_KEY'));
        if($user_auth_decryption == $user_id.'|'.$username){
            return true;
        }else{
            //$this->error('请先登录后台管理', U('Login/index'));
            $this->redirect('Login/index');
        }

    }

   //根据控制器/方法名获取面包导航
    public function get_bread_nav($controller,$method){
        $controller_method= "/".$controller."/".$method;
        $menuModel = new \Index\Model\MenuModel();
        return  $menuModel->find_name($controller_method);
    }


  /**
   * ajax的返回格式
   * @param $msg 提示信息
   * @param $state 状态值
   *
   **/
    function print_msg($msg,$state){
        $data['state'] = $state;
        $data['msg'] = $msg;
       $this->ajaxReturn($data,'JSON');
    }


    /**
     * 权限中的菜单列表获取
     * @author xiexiang 2016-04-05
     * @params $map=>查询条件，$order=>查询排序条件
     * @return array 返回菜单数组
     */
    private function getnav($map, $order)
    {
        $menu =M('Menu');
        $menu_list = $menu->where($map)->order($order)->select();

        if (!$menu_list || empty($menu_list)) {
            return false;
        }

        $tmp = array();
        $address = array(); //父级地址
        foreach ($menu_list as $k => $v) {
            $id = $v['id'];
            if ($v['level'] <= 1) {
                //一级
                $tmp[$id] = $v;
                $address[$id] = &$tmp[$id];
            } else {
                //子集
                $pid = $v['pid'];
                if (!$address[$pid]['child']) {
                    $address[$pid]['child'] = array();
                }
                $address[$pid]['child'][$id] = $v;
                $address[$id] = &$address[$pid]['child'][$id];
            }
        }
        return $tmp;       //左侧导航列表
    }


    /**
     * 文件上传
     *
     **/
    public function uploadFile(){
        $config = array(
            'maxSize'    =>    3145728,
            'savePath'   =>    './Public/Uploads/',
            'saveName'   =>    array('uniqid',''),
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub'    =>    true,
            'subName'    =>    array('date','Ymd'),);
        $upload = new \Think\Upload($config);// 实例化上传类
        // 上传文件
        $info   =   $upload->upload();
            if(!$info) {// 上传错误提示错误信息
                    $this->error($upload->getError());
            }else{
            // 上传成功
                   $this->success('上传成功！');
            }
    }


}

?>