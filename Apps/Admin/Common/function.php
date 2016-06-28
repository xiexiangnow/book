<?php

/**
 * 检测用户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function is_login(){          
    $user = session('user_auth');
    if (empty($user)) {
        return 0;
    } else {
        return session('user_auth_sign') == data_auth_sign($user) ? $user['uid'] : 0;
    }
}

/**
 * 检测当前用户是否为管理员
 * @return boolean true-管理员，false-非管理员 
 */
function is_administrator($uid = null){
	$uid = is_null($uid) ? is_login() : $uid;	
	$manage_uids = C('USER_ADMINISTRATOR');
	if(empty($manage_uids) || !is_array($manage_uids)){
		return false;
	}	
	return in_array($uid, $manage_uids);
}


/**
 * 获取权限ids
 */
function get_rules(){	
	$rule = session('rules');
    $rules = $rule && unserialize($rule);
	if(empty($rules)){
		$admin = new \Admin\Model\AdminModel();
		$ids_ary = $admin->getrules();			
		session('rules',serialize($ids_ary));
	}else{
		$ids_ary = unserialize(session('rules'));
	}

	return $ids_ary;
}


/**
 * 菜单结构
 */
function branch_level($level){
	return '|'.str_repeat('--', $level);
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
 * 获取配置的分组
 * @param string $group 配置分组
 * @return string
 */
function get_config_group($group=0,$all=false){
	$list = C('CONFIG_GROUP_LIST');
	if($all)return $list;
		
	return $group?$list[$group]:'';	
}


// 分析枚举类型配置值 格式 a:名称1,b:名称2
function parse_config_attr($string) {
	$array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
	if(strpos($string,':')){
		$value  =   array();
		foreach ($array as $val) {
			list($k, $v) = explode(':', $val);
			$value[$k]   = $v;
		}
	}else{
		$value  =   $array;
	}
	return $value;
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


/**
 * 获取文章类型的树形结构
 * @param int $selected 选择的值
 * @param mixed $map 筛选条件
 */
function get_doc_tree($select=0,$map){
	$navigate = new \Admin\Model\NavigateModel();
	$cats = $navigate->getAll($map);
	 
	$res = '';
	foreach($cats as $k=>$vo){
		$level = $vo['level']-1;
		$str='';
		if($level){
			$str = str_repeat("&nbsp;",$level).($cats[$k+1]['level']==$vo['level']?"├":"└");
		}
	
		$res .= '<option value="'.$vo['id'].'" '.($select==$vo['id']?'selected':'').'>'.$str.' '.$vo['title'].'</option>' ;
	}
	
	return $res;	
}