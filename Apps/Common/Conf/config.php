<?php
return array (
		'SYS_TITLE' => '博优内容',
		'SYS_VERSION' => '1.0',
		
		'MODULE_ALLOW_LIST'    =>    array('Home','Admin','Index','Mobile'),
		'URL_MODEL' => 2,
		'DEFAULT_MODULE' => 'Index', // 默认模块
		'DEFAULT_CONTROLLER' => 'Index', // 默认控制器名称
		'DEFAULT_ACTION' => 'index', // 默认操作名称
		
		/* 模板相关配置 */
		'TMPL_PARSE_STRING' => array (
				'__STATIC__' => __ROOT__ . '/Public/Js',
				'__PLUGIN__' => __ROOT__ . '/Public/Plugin'
		),
		
		
// 		'TOKEN_ON'      =>    false,  // 是否开启令牌验证 默认关闭
// 		'TOKEN_NAME'    =>    '__hash__',    // 令牌验证的表单隐藏字段名称，默认为__hash__
// 		'TOKEN_TYPE'    =>    'md5',  //令牌哈希验证规则 默认为MD5
// 		'TOKEN_RESET'   =>    true,  //令牌验证出错后是否重置令牌 默认为true		
		

		/* 数据库配置 */
		'DB_TYPE'   => 'mysql', // 数据库类型
		'DB_HOST'   => 'localhost', // 服务器地址
		'DB_NAME'   => 'book', // 数据库名
		'DB_USER'   => 'root', // 用户名
		'DB_PWD'    => '123456',  // 密码
		'DB_PORT'   => '3306', // 端口
		'DB_PREFIX' => 'bk_', // 数据库表前缀





	     //是否生成缩略图
	    "IMAGES_CROP_SAVE"=>true,
	    "IMAGES_THUMB_WIDTH"=>155,
	    "IMAGES_THUMB_HEIGHT"=>200,





);