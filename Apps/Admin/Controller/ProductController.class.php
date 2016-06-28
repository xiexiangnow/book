<?php
namespace Admin\Controller;
use Think\Controller;

 /**
 *商品管理
 * @author xiexiang  2015-09-28
 */

 class ProductController extends ComController {
    

    /**
    *商品列表
    */
    Public function index(){

    	$this->display();
    }




    /**
    *商品添加
    **/
    Public function add (){

    	$this->display();
    }

 }