<?php
namespace Admin\Controller;
use Think\Controller;

/**
*评论管理
* @author xiexiang  2015-08-20
*
*/
 class CommentController extends ComController {
      /**
      *评论列表
      */
      public function index(){
      	$this->display();
      }



      /**
      *评论页面
      */
      public function add(){

      	$this->display();
      }

       

       //验证码
       //评论中的验证码
		public function code(){
			$config =    array(    
				'fontSize'    =>    30,    // 验证码字体大小    
				'length'      =>    4,     // 验证码位数    
				'useNoise'    =>    false, // 关闭验证码杂点useNoise
			 );
			$Verify = new \Think\Verify($config);
			$Verify->codeSet = '0123456789';
			$Verify->entry();
		}

    // 检测输入的验证码是否正确，$code为用户输入的验证码字符串  
	  public function check_verify($code, $id = ''){    
	 	$verify = new \Think\Verify();    
	 	return $verify->check($code, $id);
	 }

      

        /**
        *AJAX的执行
        **/
       public function get_ajax(){
       	   $content=I('content');

       	   $this->ajaxReturn($content);
       }

}














 	?>