<?php
namespace Admin\Model;
use Think\Model;

/**
 * 在线留言
 * @author leiqianyong 2015-05-12
 */
class MessageModel extends CommonModel {		
	

	/* 自动验证规则 */
	protected $_validate = array(

			array('content','require','留言内容不能为空'),
			array('tel','isPhone','电话号码格式不正确',2,'function')
	);
	
		   
	/* 自动完成 */
	protected $_auto = array(
			array('time','time',1,'function'),
			array('ip','get_client_ip',1,'function')
	);	
    
}
