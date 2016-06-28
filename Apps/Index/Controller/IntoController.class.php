<?php
namespace Index\Controller;
use Think\Controller;
use Think\Model;

class IntoController extends CommonController{

    protected $bookTypeModel;

    public function _initialize(){
        parent::_initialize();
        $this->bookTypeModel = new \Index\Model\BooktypeModel();
    }



    //图书入库
    public function intoIndex(){

        //获取图书类型
       $this->assign('booType',$this->bookTypeModel->selectAllList());
       $this->display();
    }


    //图书入库执行
    public function intoBookInfo(){
       $data['type_id']     = I('post.type_id');
       $data['num']         = I('post.num');
       $data['name']        = I('post.name');
       $data['publisher']   = I('post.publisher');
       $data['author']      = I('post.author');
       $data['translator']  = I('post.translator');
       $data['outtime']     = I('post.outtime');
       //$data['outface']   = $_FILES['outface'];
        dump($data);
        dump($_FILES);
    }


    //图书封面图上传
    public function uploadPic(){

        $outtime = I('outtime');

        $config = array(
            'maxSize'    =>    2097152,  // 设置附件上传大小 2M
            'savePath'   =>    './book/', // 设置附件上传目录
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'), // 设置附件上传类型
            );
        $upload = new \Think\Upload($config);// 实例化上传类
        // 上传文件
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{
            $pic_info  =  './Uploads'.ltrim($info['file']['savepath'].$info['file']['savename'],'.');

            // 上传成功
            $data['state']  = 1;
            $data['msg'] = '上传成功！';
            $data['path'] = $pic_info;
            $this->ajaxReturn($data,'JSON');
        }




    }

}
