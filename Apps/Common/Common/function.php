<?php
header('Content-type:text/html;charset=utf-8');

/**
 * 调用系统的API接口方法（静态方法）
 * api('User/getName','id=5'); 调用公共模块的User接口的getName方法
 * api('Admin/User/getName','id=5');  调用Admin模块的User接口
 * @param  string  $name 格式 [模块名]/接口名/方法名
 * @param  array|string  $vars 参数
 */
function api($name,$vars=array()){
    $array     = explode('/',$name);
    $method    = array_pop($array);
    $classname = array_pop($array);
    $module    = $array? array_pop($array) : 'Common';
    $callback  = $module.'\\Api\\'.$classname.'Api::'.$method;
    if(is_string($vars)) {
        parse_str($vars,$vars);
    }
    return call_user_func_array($callback,$vars);
}
/**
 * 检测验证码
 * @param  integer $id 验证码ID
 * @return boolean     检测结果 
 */
function check_verify($code, $id = 1){
	$verify = new \Think\Verify();
	return $verify->check($code, $id);
}


/**
 * 记录日志信息
 * @param mixed $content  日志内容
 * @param string $file   日志目录  否则在 logs的根目录
 * @param string $filename 文件前缀 否则是按时间设置  20150401
 */
function logRec($content,$file=false,$filename=false){
	if(!$content){
		return false;
	}
	if(is_array($content)){
		$content = var_export($content,true);
	}   
	$filenamestr =  RUNTIME_PATH . 'Logs/' . MODULE_NAME .'/' ;
	//目录
	if($file){
		$filenamestr .= $file ;
		if(!is_dir($filenamestr)){
			mkdir($filenamestr);
		}
	}
	//日志文件名称
	$filenamestr .= empty($filename)?'/'.date('Ymd'):'/'.$filename.'_'.date('Ymd');

	$str = "<pre>\r\n";
	$str .= $content ;
	$str .= "\r\n".date('Y-m-d H:i:s')."===================================\r\n</pre>\r\n";
	$filenamestr = $filenamestr .'.html';

	$fp = fopen($filenamestr, 'a+');
	$res = fwrite($fp, $str);
}


/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function format_bytes($size, $delimiter = '') {
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 根据用户ID获取用户账号
 * @param  integer $uid 用户ID
 * @return string       用户昵称
 */
function get_account($uid = 0){
	static $list;

	/* 获取缓存数据 */
	if(empty($list)){
		$list = S('sys_user_account_list');
	}

	/* 查找用户信息 */
	$key = "u{$uid}";
	if(isset($list[$key])){ //已缓存，直接使用
		$name = $list[$key];
	} else { //调用接口获取用户信息
		$_table = "user";
		$where = "id={$uid}" ;
		$info = M($_table)->field('account')->where($where)->find();
		if($info !== false && $info['account']){
			$name = $list[$key] = $info['account'];
			/* 缓存用户 */
			S('sys_user_account_list', $list);
		} else {
			$name = '';
		}
	}
	return $name;
}


/**
 * 字符串到数组
 * @param unknown $data
 * @return multitype:|unknown
 */
function string2array($data) {
	if($data == '') return array();
	@eval("\$array = $data;");
	return $array;
}


/*
*得到星期
*/
function get_week(){
   $weekarray=array('日','1','2','3','4','5','6');
   return  $weekarray[date("w")];
}

/**
 * 截取字符串
 *
 * @param  string $str           字符串
 * @param  int    $length        长度
 * @param  string $charset       编码
 * @param  bool   $suffix        是否加省略号
 * @return string
 */
function subs($str, $length, $charset="utf-8", $suffix=true) {
	$start = 0;
	if (function_exists("mb_substr")) {
		if (mb_strlen($str, $charset) <= $length) {
			return $str;
		}
		$slice = mb_substr($str, $start, $length, $charset);
	} else {
		$re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		if (count($match[0]) <= $length) {
			return $str;
		}
		$slice = join("", array_slice($match[0], $start, $length));
	}
	if ($suffix) {
		return $slice . "...";
	}
	return $slice;
}


/**
 * 格式数据库文本内容
 *
 * @param mixed $str
 */
function formatdb($str){
	if(!$str){
		return false;
	}
        return nl2br(str_replace(" ","&nbsp;",$str));
}





/**
 * 导出电子表格
 * @author leiqianyong 2014-12-10
 *
 * @param array  $list  输出结果
 * @param array  $cols  输出的列
 * @param string $title 表格标题
 * @param string $filename  文件标题
 */
function export_excel($list,$cols_ary,$title_ext=NOW_TIME,$filename=NOW_TIME){
		
	$html  ='<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">' ;
	$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' ;
	$html .= '<html>' ;
	$html .= '<head>' ;
	$html .= '<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />' ;
	$html .= '</head>' ;
	$html .= '<body>' ;
	$html .= '<table x:str border=1 cellpadding=0 cellspacing=0>';

	$cols_count = count($cols_ary);
	$html .= '<tr><td width=”100″ colspan='.$cols_count.' align=center style=\'font-size:14.0pt;font-weight:700;font-family:"Arial Unicode MS", sans-serif;height:25pt\'>'.$title_ext.'</td></tr>';

	//导出标题
	if (!empty($cols_ary)){
		$html .= '<tr>' ;
		foreach ($cols_ary as $k => $v) {
			$html .= '<td width=”100″ align=center style=\'height:25pt;background:silver;\'>'.$v['title'].'</td>' ;
		}
		$html .= '</tr>' ;
	}

	$_num = 0 ;
	if (!empty($list)){
		foreach($list as $key=>$cv){
			$html .= '<tr>' ;

			foreach($cols_ary as $val){
				if($val['fun']){
					$_res = call_user_func($val['fun'],$cv[$val['key']]);
				}else{
					$_res = $cv[$val['key']] ;
				}
					
				$html .= str_replace('%value%',$_res,$val['value']) ;
			}

			$html .= '</tr>' ;
		}
		$_num = $key+1 ;
	}

	$html .= '<tr><td width=”100″ colspan='.$cols_count.' align=right>总计：'.$_num.'条记录&nbsp;</td></tr>' ;
	$html .= '</body></html></table>';

	header("Content-type:application/vnd.ms-excel");
	header("Content-Disposition:attachment;filename={$filename}.xls");
	echo $html ;
}


/**
 * 验证手机号格式
 * @param string $mobile 手机号
 */
function is_mobile($mobile) {
	if(!$mobile) {
		return false;
	}

	$exp = "/^1[3|5|8|7]{1}[0-9]{9}$/";
	if(preg_match($exp, $mobile)){
		return true;
	}
	
	return false;
}

/**
 * 检查是否是电话号码(手机、座机)
 * @param string $phone
 */
function isPhone($phone){
	if(!$phone)return false;

	$isMob="/^1[3|5|8|7]{1}[0-9]{9}$/";
	$isTel="/^([0-9]{3,4}-)?[0-9]{7,8}$/";	
	
	if(preg_match($isMob, $phone) || preg_match($isTel, $phone)){
		return true;
	}
	return false;
}

/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
    $str = trim($str);
    $str = htmlspecialchars_decode($str);
    if (function_exists("mb_substr")) {
        if (mb_strlen($str, $charset) <= $length) {
            return $str;
        }
        $slice = mb_substr($str, $start, $length, $charset);
    } else {
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        if (count($match[0]) <= $length) {
            return $str;
        }
        $slice = join("", array_slice($match[0], $start, $length));
    }
    if ($suffix) {
        return $slice . "...";
    }
    return $slice;
    ////////////////以上修改 @author leiqianyong 2015-05-05

    $len = strlen($str);
    if($start == 0 && $len < $length){
        //前后都没有省略的情况下，直接返回 @author zhufu 2015-04-03 09:28:46
        return $str;
    }
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
        if(false === $slice) {
            $slice = '';
        }
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice.'...' : $slice;
}


/*获取用户IP*/
function getRealIp(){
    $ip=false;
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
        if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
        for ($i = 0; $i < count($ips); $i++) {
            if (!eregi ("^(10│172.16│192.168).", $ips[$i])) {
                $ip = $ips[$i];
                break;
            }
        }
    }
    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}






/*
*
*编辑器中的图片处理  （手机中的自适应）
**/
 function changeParse($content){
				
		        return preg_replace(array('/(?:height|width|style)=(\'|").*?\\1/','/<p[^>]+>/'),array("style='width:100%'",'<p>'),$content);            
               
	}


	/**
  *获取当前的URL地址
  *@author xiang 2015-07-10
  */
function curPageURL() 
{
    $pageURL = 'http';

    if ($_SERVER["HTTPS"] == "on") 
    {
        $pageURL .= "s";
    }
    $pageURL .= "://";

    if ($_SERVER["SERVER_PORT"] != "80") 
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } 
    else 
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}



function code_create($url){
	include "phpqrcode.php";
    QRcode::png($url); 
}

//使用图片设置的缩略图
function img_thumb($imgpath, $size = '') {
	if(!$imgpath) {
		return false;
	}

	if(!$size) {
		return $imgpath;
	}

	$ext = substr(strrchr($imgpath, '.'), 0);
	if($ext=='.jpg')$ext='.jpeg';
	return "{$imgpath}_{$size}{$ext}";
}





