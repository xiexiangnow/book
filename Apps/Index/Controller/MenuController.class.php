<?php
namespace Index\Controller;
use Index\Model\MenuModel;
use Think\Controller;
use Think\Model;

class MenuController extends CommonController {



    //菜单添加页面
    public function addMenu(){
        $id =  I('get.id');
        if($id){
            //根据id查询出详细信息
            $menuModel = new \Index\Model\MenuModel();
            $menu_info = $menuModel->findDetail($id);

            $parent_info = $menuModel->findDetail($menu_info['pid']);
            $menu_info['parent_name'] = $parent_info['title'];
            $menu_info['parent_id']   = $parent_info['id'];

            $this->assign('menu_info',$menu_info);
        }
        //上级目录
        $map['hide_flag'] = 0;
        $this->assign('menu_list',self::getMenu($map));
        $this->display();
    }

    //执行添加菜单
    public function addDataInfo()
    {
                $data['id']      = I('post.id');
                $data['title']   = I('post.title');
                $data['pid']     = I('post.pid');
                $data['path']    = I('post.path');
                $data['is_show'] = I('post.is_show');
                $data['sort']    = I('post.sort');
                $data['icon']    = I('post.icon');
                if (!$data['title']) {
                   $data['state'] = 0;
                   $data['msg'] = '名称不能为空！';
                   $this->ajaxReturn($data,'JSON');
                }

                if (!$data['icon']) {
                    $data['state'] = 0;
                    $data['msg'] = '请选择一个图标！';
                    $this->ajaxReturn($data,'JSON');
                }

          if(!empty($data['id']) && is_numeric($data['id'])){
              $menuModel = new \Index\Model\MenuModel();
              $update_result = $menuModel->update_menu_info($data);
              if($update_result){
                  $data['msg'] = '操作成功！';
                  $data['state']  = 1;
                  $this->ajaxReturn($data,'JSON');
              }else{
                  $data['msg'] = '操作失败或者没有做任务更改！';
                  $data['state']  = 0;
                  $this->ajaxReturn($data,'JSON');
              }

          }else{
              $menu = M('Menu');
              //等级
              $level = 1;
              if ($data['pid']) {
                  $where['id'] = $data['pid'];
                  $pid_info = $menu->where($where)->find($where);
                  $level = $pid_info['level'] ? ($pid_info['level'] + 1) : 1;
              }
              if ($level > 3) {
                  $data['state'] = 0;
                  $data['msg'] = '只允许添加三级栏目！';
                  $this->ajaxReturn($data,'JSON');
              }
              $data['level'] = $level;

              $menuModel = new \Index\Model\MenuModel();
              if($menuModel->insertMenuData($data)){
                  $data['state'] = 1;
                  $data['msg'] = '操作成功！';
                  $this->ajaxReturn($data,'JSON');
              }else{
                  $data['state'] = 0;
                  $data['msg'] = '操作失败！';
                  $this->ajaxReturn($data,'JSON');
              }
          }

    }

    //菜单的删除
    public function delete_menu(){
         $id  = I('post.id');
         if(empty($id) || !is_numeric($id)){
             $data['msg'] = '参数错误！';
             $data['state'] = 0;
             $this->ajaxReturn($data,'JSON');
         }
         $menuModel = new \Index\Model\MenuModel();
         $child_count = $menuModel->check_child($id);
         if($child_count>0){
             $data['msg'] = '存在下级菜单，无法删除！';
             $data['state'] = 0;
             $this->ajaxReturn($data,'JSON');
         }
         if($menuModel->delete_nav($id)){
             $data['msg'] = '删除成功！';
             $data['state'] = 1;
             $this->ajaxReturn($data,'JSON');
         }else{
             $data['msg'] = '操作失败！';
             $data['state'] = 0;
             $this->ajaxReturn($data,'JSON');
         }


    }

    //列表
    public function menuList(){
        $pid = I('pid');

        $pid = $pid ? $pid : 0;

        $map['hide_flag'] = 0;
        $map['pid'] = $pid;
        $order = "sort DESC,id DESC";
        $menu =M('Menu');
        $menu_list = $menu->where($map)->order($order)->select();    //菜单列表

        $top_where['id'] = $pid;
        $top = $menu->where($top_where)->find();        //返回主菜单

        $this->assign('top',$top);
        $this->assign('menu_list',$menu_list);

        $this->display();
    }



    /**
     * 得到菜单栏目树
     * @author xiexiang 2016-03-17
     * @params eg $map = "name = 'xiexiang'"   //查询条件
     **/
    public function getMenu($map)
    {
        //实例化数据表menu
        $menu = M('Menu');
        $order = "level ASC,sort DESC";
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

        return $tmp;

    }


    /**
     * 批量排序
     */
    public function sort()
    {
        //异常插入
        try {
            $sort = I('post.sort');
            if (empty($sort)) {
                $data['msg'] = '请选择需要排序的数据!';
                $data['state'] = 0;
                $this->ajaxReturn($data,'JSON');
            }
            $sql_bat_sort ='';
            $sql_ids = '';
            foreach ($sort as $k => $v) {
                if (!is_numeric($k) || !is_numeric($v)) {
                    continue;
                }
                $sql_bat_sort .= " WHEN {$k} THEN {$v}";
                $sql_ids .= ',' . $k;
            }

            $menu = M('Menu');
            $sql = "UPDATE bk_menu SET sort= CASE id " . $sql_bat_sort . " END WHERE id IN (" . trim($sql_ids, ', ') . ")";
            $menu->query($sql);

            $data['msg'] = '批量排序成功！';
            $data['state'] = 1;
            $this->ajaxReturn($data,'JSON');


        } catch (Exception $e) {

            return $this->printmsg($e->getMessage(), $e->getCode());

        }
    }




}



?>