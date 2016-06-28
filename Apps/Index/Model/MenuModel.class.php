<?php
namespace Index\Model;
use Think\Model;
use Common\Model\CommonModel;

class MenuModel extends CommonModel{


    //数据的插入/写入
    public function insertMenuData($data){
       return   $this->add($data);
    }

    //根据控制器/方法名获取 菜单名
    //return array
    public function find_name($controller_method){
        $where['path'] = $controller_method;
        $child_info = $this->where($where)->field('pid,title')->find();
        $parent_info = $this->findDetail($child_info['pid']);
        $data['child']  = $child_info['title'];
        $data['parent'] = $parent_info['title'];
        return $data;
    }


    //根据id得到详情
    public function findDetail($id){
        $where['id'] = $id;
        $where['hide_flag'] = 0;
        return $this->where($where)->find();
    }

   //数据更新
    public function update_menu_info($data){
       return  $this->save($data);

    }


    //检查对应的id下是否有子级
    public function check_child($id){
        $where['pid'] = $id;
        $where['hide_flag'] = 0;
        return $this->where($where)->count();
    }

    //菜单删除
    public function delete_nav($id){
        $data['id'] = $id;
        $data['hide_flag'] = 1;
        return $this->save($data);

    }


    //根据id查询pid
    public function findParentId($id){
        $where['id'] = $id;
        return $this->where($where)->field('pid')->find();
    }


}



?>