<?php
namespace Admin\Model;
use Think\Model;

/**
 * 课程模型
 * @author leiqianyong 2015-05-21
 */
class CourseModel extends CommonModel {		
	
	/* 自动验证规则 */	
    protected $_validate = array(
        array('title', 'require', '名称不能为空!'),
    	array('teacher','require','讲师不能为空'),    	
    );       

    /* 自动完成 */
    protected $_auto = array(
    	array('time','time',1,'function'),    	
    );
           

    
}

?>