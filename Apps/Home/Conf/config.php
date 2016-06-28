<?php
return array(
	//'配置项'=>'配置值'
		/* 模板相关配置 */
		'TMPL_PARSE_STRING' => array(								
				'__JS__'    => __ROOT__ . '/Public/' . MODULE_NAME .'/js',
				'__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME .'/css',	
				'__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME .'/img',
				'__PLUGIN__' => __ROOT__ . '/Public/Plugin',				
		),	
				
		//允许用户密码最多输入次数
		'MAX_LOGIN_TIMES'       => 3,          //最大登录失败次数，防止为0时不能登录，因此不包含第一次登录
		'LOGIN_WAIT_TIME'       => 60,         //登录次数达到后需要等待时间才能再次登录，单位：分钟		
		
		'URL_CASE_INSENSITIVE'=>true,
);