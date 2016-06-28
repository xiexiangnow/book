<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Page;	
 /*
  *投票管理系统
  *
  */

  class VoteController extends CommonController {

  		//加载页面
  	  public function index(){
  	  	 $this->display();
  	  }


  	  //数据处理
  	  public function ad(){

  	  	$xuan=$_POST['xuan'];
  	  	var_dump($xuan);
  	  }
  }



?>