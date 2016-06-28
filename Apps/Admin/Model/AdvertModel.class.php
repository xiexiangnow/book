<?php
namespace Admin\Model;
use Think\Model;

/**
 * 广告
 * @author viking 2015-05-13
 */
class AdvertModel extends CommonModel {		
	

	/* 自动验证规则 */
	protected $_validate = array(
			array('title', 'require', '标题不能为空!'),
			array('pic','require','广告图片不能为空'),
			array('posi','require','广告位置必须')
	);
	
		   
	/* 自动完成 */
	protected $_auto = array(
			array('time','time',1,'function')			
	);	
    
}
