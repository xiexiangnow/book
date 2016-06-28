<?php
namespace Index\Model;
use Think\Model;
use Common\Model\CommonModel;

class BooktypeModel extends CommonModel{

    //数据的添加
    public function addTypeData($data){
        return $this->add($data);
    }

    //判断名称是否重名
    public function checkName($name){
        $where['name'] = $name;
        $where['is_delete'] = 0;
        return  $this->where($where)->count();
    }

    //查询类型列表
    public function selectAllList(){
        $where['is_delete'] = 0;
        $order = "id DESC";
        return $this->where($where)->order($order)->select();
    }

     //根据id查询详情
    public function findTypeInfo($id){
        $where['id'] = $id;
        $where['is_delete'] = 0;
        return $this->where($where)->find();
    }

   //更改
    public function updateTypeData($data){
        return $this->save($data);
    }

    //删除
    public function deleteTypeInfo($id){
        $data['id'] = $id;
        $data['is_delete'] = 1;
        return $this->save($data);
    }


}
