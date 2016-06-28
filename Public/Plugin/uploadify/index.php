<?php

function fileSuffix($filename){

    return strtolower(trim(substr(strrchr($filename, '.'), 1)));

}
$file = '/Uploads/Picture/2015-03-25/55126c8b1312d.zip';
echo fileSuffix($file);






?>


<html>
<head>
<meta charset='utf-8'>
<title></title>
<script src="jquery-1.9.min.js" type="text/javascript"></script> 
<script type="text/javascript" src="jquery.uploadify-3.1.js"></script>
<link rel="stylesheet" type="text/css" href="uploadify.css"/>
<style>
.uploadify-queue-item {
    background-color: #f5f5f5;
    border-radius: 3px;
    float: left;
    font: 11px Verdana,Geneva,sans-serif;
    height: 180px;
    margin: 5px;
    padding: 10px;
    position: relative;
    width: 200px;
}
.uploadify-progress {
    background-color: #e5e5e5;
    bottom: 0;
	left:0;
    margin-top: 40px;
    position: absolute;
    width: 100%;
}
.uploadify-queue-item .face a {
	background: url('home_cancel.png') 0 0 no-repeat;
	float: right;
	height:	16px;
	text-indent: -9999px;
	width: 25px;
}
a {outline: none;}
</style>
<script type="text/javascript">
var img_id_upload=new Array();//初始化数组，存储已经上传的图片名
var i=0;//初始化数组下标
var img='';
var face='';

SWFUpload.movieCount = 5;

$(function() {
   $('#file_upload').uploadify({
    	'auto'     : true,//关闭自动上传
    	'removeCompleted':false, //上传后进度条不移除
        'swf'      : 'uploadify.swf',
        'uploader' : 'uploadify.php',
        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
		'buttonText' : '选择图片',//设置按钮文本
        'multi'    : true,//允许同时上传多张图片
        'width':'75', 
        'height':'24', 	
        //'progressData':'speed',		
        'uploadLimit' : 10,//一次最多只允许上传10张图片
        'fileTypeDesc' : 'Image Files',//只允许上传图像
        'fileTypeExts' : '*.gif; *.jpg; *.png',//限制允许上传的图片后缀
        'fileSizeLimit' : '20000KB',//限制上传的图片不得超过200KB 
        'onUploadSuccess' : function(file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端             
              img = '<img style="width:200px;max-height:164px" src="'+data+'">';
			  face = '<div class="face"><a title="设置为封面图片" href="javascript:faced(\'' + file.id + '\');">X</a></div>';              
              $('#' + file.id).find('.cancel').after(face);
              $('#' + file.id).find('.data').after(img);
              $('#' + file.id).find('.cancel').html("<a href='javascript:del(\""+file.id+"\");'>x</a>");              
              $('#' + file.id).find('.data').html(' 图片上传成功');
              $('#' + file.id).find('.fileName').remove();	
        },
        'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数
           // if(img_id_upload.length>0)
           // alert('成功上传的文件有：'+encodeURIComponent(img_id_upload));
        }  
        // Put your options here
    });
});

//删除图片
function del(id){	
	var delay=1;
	$("#"+id).find('.data').removeClass('data').html('图片删除成功');
	$("#"+id).find('.uploadify-progress-bar').remove();
	$("#"+id).delay(1000 + 100 * delay).fadeOut(500, function() {
		$("#"+id).remove();
	});	
}

//封面也
function faced(id){
	$('.face a').css("backgroundImage","url(home_cancel.png)");
	$('#' + id).find('.face a').css("backgroundImage","url(home_selected.png)");
}

</script>
</head>
<body>
<div id="num"></div>
<input type="file" name="file_upload" id="file_upload" />
<input type="hidden" value="1215154" name="tmpdir" id="id_file">
</body>
</html>