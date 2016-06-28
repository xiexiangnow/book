<?php
return array(
	//'配置项'=>'配置值'        		
	'URL_CASE_INSENSITIVE'=>true,


    /*路由*/
    'URL_ROUTER_ON'   => true, //开启路由
    'URL_ROUTE_RULES' => array(//定义路由规则

        '/^news\/(\d+)\-*(\d*)$/'=>'News/lis?nv=:1&mu=:2',
        '/^show\/(\d+)$/'=>'News/show?id=:1',

    ),




    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__JS__'    => __ROOT__ . '/Public/' . MODULE_NAME .'/js',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME .'/css',
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME .'/img',
    ),


    //最大错误登录次数
    'MAX_LOGIN_NUM' => 3,

    //记录cookie随机字符串
    'COOKIE_KEY' => 'Zhubajie_xiexiang_0915',

    /* 模板引擎设置 */
    'TMPL_ACTION_ERROR'     => MODULE_PATH.'View/Public/dispatch_jump.html',   // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   => MODULE_PATH.'View/Public/dispatch_jump.html',   // 默认成功跳转对应的模板文件
    'TMPL_EXCEPTION_FILE'   => MODULE_PATH.'View/Public/exception.html',       // 异常页面的模板文件
    'JUMP_WAIT_TIME' => 3,  //跳转等待时间秒


//
//    'HTML_CACHE_ON'     =>    true, // 开启静态缓存
//    'HTML_CACHE_TIME'   =>    60,   // 全局静态缓存有效期（秒）
//    'HTML_FILE_SUFFIX'  =>    '.html', // 设置静态缓存文件后缀
//    'HTML_CACHE_RULES'  =>     array(  // 定义静态缓存规则     // 定义格式1 数组方式
//     'Index:index'    =>     array('index', '43200'),
//    )










);