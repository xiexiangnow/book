<?php
namespace Admin\Model;
use Think\Model;

/**
 * 友情链接模型
 * @author leiqianyong 2015-05-11
 */
class LinkModel extends CommonModel {		
	
	/* 自动验证规则 */	
    protected $_validate = array(
        array('title', 'require', '名称不能为空!'),
    	array('url','require','链接地址不能为空'),    	
    	array('url', 'url', 'URL格式不正确', 1, 'regex', 3),
    );       

    /* 自动完成 */
    protected $_auto = array(
    	array('time','time',1,'function'),    	
    );
           

    
}

?>