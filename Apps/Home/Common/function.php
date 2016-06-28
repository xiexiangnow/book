<?php

/**
 * 检测用户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function is_login(){          
    $user = session('mem_auth');
    if (empty($user)) {
        return 0;
    } else {
        return session('mem_auth_sign') == data_auth_sign($user) ? $user['uid'] : 0;
    }
}


/**
 * 登录密码格式验证
 */
function checkPwd($param){
	//密码判断 数字、字母或下滑线   3-20位以内
	if(preg_match("/[\w]{3,20}/", $param)){	
		return true;
	}
	return false;
}


/**
 * 获取配置的类型
 * @param string $type 配置类型
 * @return string
 */
function get_config_type($type=0,$all=false){
	$list = C('CONFIG_TYPE_LIST');
	if($all)return $list;
	
	return $list[$type];
}

/**
 * 数据签名认证
 * @param  array  $data 被认证的数据
 * @return string       签名
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function data_auth_sign($data) {
	//数据类型检测
	if(!is_array($data)){
		$data = (array)$data;
	}
	ksort($data); //排序
	$code = http_build_query($data); //url编码并生成query字符串
	$sign = sha1($code); //生成签名
	return $sign;
}
