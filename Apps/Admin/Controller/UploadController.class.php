<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Page;	


class UploadController extends Controller {


    function index() {
        $this->display();
	}
	
	function s(){
		$src=$_GET['src'];
		$this->success('上传成功！');

	}
	function uploadImg() {
		$upload = new \Think\Upload();// 实例化上传类    
	      $upload->maxSize   =   3145728 ;// 设置附件上传大小    
	      $upload->exts      =   array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型    
	      $upload->savePath  =    '/Public/Uploads/Link/'; // 设置附件上传目录 
	      $upload->thumbRemoveOrigin = true; //上传图片后删除原图片
		  $info=$upload->upload(); // 上传文件

			 if($info){
			 	 //  $oldpathimg='./Uploads'.$info['pic']['savepath'].$info['pic']['savename'];
			 	 //  //var_dump($oldpathimg);die;
			 	 //  $imgname='sl_'.$info['pic']['savename'];//生成缩略图名称
				 // $pathimg='./Uploads'.$info['pic']['savepath'].$imgname;//生成缩略图路径
				 // $image = new \Think\Image(); //实例化缩略图
				 // $image->open($oldpathimg);
				 // $aa=$image->thumb($iw,$ih,\Think\Image::IMAGE_THUMB_CENTER)->save($pathimg);//生成缩略图
				
			 //返回图片路径
			 	$src='/Uploads'.$info['Filedata']['savepath'].$info['Filedata']['savename'];
			 	print_r($src); 
				}
			}
    //图片在上传的页面中做临时删除
	function del() {	
		$src=str_replace(__ROOT__.'/', '', str_replace('//', '/', $_GET['src']));
		if (file_exists($src)){
			unlink($src);
		}
		print_r($_GET['src']);
		exit();
	}




}







?>