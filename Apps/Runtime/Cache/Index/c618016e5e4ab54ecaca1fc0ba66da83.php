<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>提示消息</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
 if($jumpUrl!='s'){ ?>
<meta http-equiv='Refresh' content='<?php echo C('JUMP_WAIT_TIME');?>;URL=<?php echo($jumpUrl); ?>'>
<?php
} ?>
<style>
body {font-size:12px; font-family:'微软雅黑', '黑体', Verdana;color:#333333; background-color: transparent;line-height:150%;}
.table {font-size:14px;margin-top:100px;background:#289121}
.content {font-size:12px; background:#FFFFFF; padding:10px 20px;}
.inframe {padding:2px; margin:0 5px; background:none;}
</style>
</head>
<body>
	
<table border="0" align="center" cellpadding="5" cellspacing="1" class="table">
<tr style="color:#FFFFFF">
    <th>提示消息</th>
</tr>
<tr><td class="content">
    <div style="margin:5px;">
	
	<?php  if(isset($message)) { echo($message); }else{ echo($error); } ?>	
	
	</div>
	<?php
 if($jumpUrl!='s'){ ?>
    <div style="margin:5px;text-align:center">
    [ <a href="javascript:history.go(-1)" style="color:#289121;">返回</a> ] 
    [ <a href="<?php echo($jumpUrl); ?>" style="color:#289121;"><span id="limit_time">点此直接跳转</span></a> ] 
    </div>
    <?php
 } ?>
</td></tr>
</table>
	
	<?php
 if($jumpUrl!='s'){ ?>			
<script language='javascript' type='text/javascript'>
	var wait = <?php echo C('JUMP_WAIT_TIME');?>;
	var url = '<?php echo($jumpUrl); ?>';
	function showWaitTime() {
		if (wait <= 0) {
			document.location.href = url;	
			return;
		}
		document.getElementById('limit_time').innerHTML = wait + '秒后自动跳转';
		wait -= 1;
		setTimeout(showWaitTime, 1000);
	}
	showWaitTime();
</script>
<?php
} ?>
	
</body>
</html>