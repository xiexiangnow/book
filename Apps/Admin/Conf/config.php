<?php
return array(
		/* 模板相关配置 */
		'TMPL_PARSE_STRING' => array(								
				'__JS__'    => __ROOT__ . '/Public/' . MODULE_NAME .'/js',
				'__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME .'/css',	
				'__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME .'/img',
				'__PLUGIN__' => __ROOT__ . '/Public/Plugin',
				'__STYLE__' =>  __ROOT__ . '/Uploads/Public/Uploads/Link/' ,
		),	
		
		'DEFAULT_CONTROLLER' => 'Public', // 默认控制器名称
		'DEFAULT_ACTION' => 'login', // 默认操作名称		
				
		/* 模板引擎设置 */
	    'TMPL_ACTION_ERROR'     => MODULE_PATH.'View/Public/dispatch_jump.html',   // 默认错误跳转对应的模板文件
	    'TMPL_ACTION_SUCCESS'   => MODULE_PATH.'View/Public/dispatch_jump.html',   // 默认成功跳转对应的模板文件
		'TMPL_EXCEPTION_FILE'   => MODULE_PATH.'View/Public/exception.html',       // 异常页面的模板文件
		'JUMP_WAIT_TIME' => 1,  //跳转等待时间秒

		//允许用户密码最多输入次数
		'MAX_LOGIN_TIMES'       => 3,          //最大登录失败次数，防止为0时不能登录，因此不包含第一次登录
		'LOGIN_WAIT_TIME'       => 60,         //登录次数达到后需要等待时间才能再次登录，单位：分钟		

		//超级管理员  不需要验证权限 对应用户的id
		'USER_ADMINISTRATOR' => array(1,2),
		
		'URL_CASE_INSENSITIVE'=>true,


				
);