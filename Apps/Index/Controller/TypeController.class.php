<?php
namespace Index\Controller;
use Think\Controller;
use Think\Model;

class TypeController extends CommonController{


    //类型添加
    public function addIndex(){
        try {
            $id = I('get.id');
            if($id) {
                $bookTypeModel = new \Index\Model\BooktypeModel();
                $this->assign('type_info', $bookTypeModel->findTypeInfo($id));
            }
            $this->display();
        }catch (Exception $e){
            $this->error($e->getMessage());
        }
    }


    //类型添加执行
    public function addTypeData(){
       try{
           $id                       = I('post.id');
           $data['name']   = $name   = trim(I('post.name'));
           $data['remark'] = $remark = I('post.remark');
           if(empty($name)){
               $data['msg'] = '名称不能为空！';
               $data['state'] = 0;
               $this->ajaxReturn($data,'JSON');
           }
           $bookTypeModel = new \Index\Model\BooktypeModel();
           if($id){
               $data['id'] = $id;
               if($bookTypeModel->updateTypeData($data)){
                   $data['msg'] = '操作成功！';
                   $data['state'] = 1;
                   $this->ajaxReturn($data,'JSON');
               }else{
                   $data['msg'] = '操作失败或没有做任何更改！';
                   $data['state'] = 0;
                   $this->ajaxReturn($data,'JSON');
               }
           }else{
               //判断是否重名
               if(($bookTypeModel->checkName($data['name']))>0) {
                   $data['msg'] = '类型名称重复！';
                   $data['state'] = 0;
                   $this->ajaxReturn($data, 'JSON');
               }
               if($bookTypeModel->addTypeData($data)){
                   $data['msg'] = '添加成功！';
                   $data['state'] = 1;
                   $this->ajaxReturn($data,'JSON');
               }else{
                   $data['msg'] = '操作失败！';
                   $data['state'] = 0;
                   $this->ajaxReturn($data,'JSON');
               }
           }
       }catch (Exception $e ){
           $this->error($e->getMessage());
       }
    }

    //类型列表
    public function typeList(){

        try{
            $bookTypeModel = new \Index\Model\BooktypeModel();
            $this->assign('type_list',$bookTypeModel->selectAllList());

            $this->display();

        }catch (Exception $e){
            $this->error($e->getMessage());
        }

    }

    //删除
    public function delete_typeInfo(){
        try{
           $id = I('post.id');
           if(empty($id) || !is_numeric($id)){
               $data['msg'] = '参数错误！';
               $data['state'] = 0;
               $this->ajaxReturn($data,'JSON');
           }
            $bookTypeModel = new \Index\Model\BooktypeModel();
            if($bookTypeModel->deleteTypeInfo($id)){
                $data['msg'] = '删除成功！';
                $data['state'] = 1;
                $this->ajaxReturn($data,'JSON');
            }else{
                $data['msg'] = '操作失败！';
                $data['state'] = 0;
                $this->ajaxReturn($data,'JSON');
            }

        }catch (Exception $e){
            $this->error($e->getMessage());
        }
    }
}