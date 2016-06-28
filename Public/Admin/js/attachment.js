/**
 * 上传附件（图片、文件、视频）
 * @param module      附件类型 0-图片  1-文件  2-视频 
 * @param name        弹窗标题
 * @param num         附件数量
 * @param ext         允许的格式  jpg|bmp|png , zip|rar  , flv ....
 * @param size        大小单位MB   
 * @param textareaid  返回内容位置
 * @param funcName    调用数据的处理函数
 */
function flashupload(module,name,num,ext,size,textareaid,funcName) {
	var setting = 'module='+module+'&num='+num+'&ext='+ext+'&size='+size ;
	var uploadid = 'piclist' ;
    art.dialog.open("/admin/attachment/plus?"+setting,
            {
			    title: name,
			    id:uploadid,
				width: 520,
				height: 420,
				lock: true,
			    ok: function(topWin){
 			    funcName.apply(this, [uploadid, textareaid]);
               },
                cancel: true
			 }
		 );

}


function croppic(pic,textareaid) {		
	var uploadid = 'croppic' ;	
	var funcName = '' ;
    art.dialog.open("/admin/attachment/crop?pic="+pic,
            {
			    title: '图片裁切',
			    id:uploadid,
			    lock: true,
			    ok: function(topWin){  
			    	var d = this.iframe.contentWindow;			    	
			    	
			    	var in_content = d.$("#pic").val();
			    	if (in_content == '') return false;
			    	if (!IsImg(in_content)) {
			    		alert('选择的类型必须为图片类型');
			    		return false;
			    	}
			    	if ($('#' + textareaid + '_preview').attr('src')) {
			    		$('#' + textareaid + '_preview').attr('src', in_content);
			    	}
			    	$('#' + textareaid).val(in_content);			    	
               },
                cancel: true
			 }
		 );
	
}


function remove_div(k){
	$('#'+k).remove();
}

function change_images(uploadid,returnid){
	var d = art.dialog({
		id: uploadid
	}).iframe.contentWindow;

	var in_content = d.$("#att-status").html().substring(1);
	var in_filename = d.$("#att-name").html().substring(1);
	var str = $('#' + returnid).html();
	var contents = in_content.split('|');
	var filenames = in_filename.split('|');
	$('#' + returnid + '_tips').css('display', 'none');
	if (contents == '') return true;
	$.each(contents, function(i, n) {
		var ids = parseInt(Math.random() * 10000 + 10 * i);
		var filename = filenames[i].substr(0, filenames[i].indexOf('.'));
		str += "<li id='image" + ids + "'><input type='text' name='" + returnid + "_url[]' value='" + n + "' style='width:310px;' ondblclick='image_priview(this.value);' class='input-text'> <input type='text' name='" + returnid + "_alt[]' value='" + filename + "' style='width:160px;' class='input-text' onfocus=\"if(this.value == this.defaultValue) this.value = ''\" onblur=\"if(this.value.replace(' ','') == '') this.value = this.defaultValue;\"> <a href=\"javascript:remove_div('image" + ids + "')\">移除</a> </li>";
	});

	$('#' + returnid).html(str);
}


function change_multifile_update(uploadid, returnid){
	var d = art.dialog({
		id: uploadid
	}).iframe.contentWindow;
	
	var in_content = d.$("#att-status").html().substring(1);
	var in_filename = d.$("#att-name").html().substring(1);
	var str = '';
	var contents = in_content.split('|');
	var filenames = in_filename.split('|');
	$('#' + returnid + '_tips').css('display', 'none');
	if (contents == '') return true;
	$.each(contents, function(i, n) {
		var ids = parseInt(Math.random() * 10000 + 10 * i);
		var filename = filenames[i].substr(0, filenames[i].indexOf('.'));
		str += "<li id='multifile" + ids + "'><input type='text' name='" + returnid + "_url[]' value='" + n + "' style='width:160px;' class='input-text'> <input type='text' name='" + returnid + "_alt[]' value='" + filename + "' style='width:310px;' class='input-text' onfocus=\"if(this.value == this.defaultValue) this.value = ''\" onblur=\"if(this.value.replace(' ','') == '') this.value = this.defaultValue;\"> <a href=\"javascript:remove_div('multifile" + ids + "')\">移除</a> </li>";
	});
	$('#' + returnid).html(str);	
}


function change_multifile(uploadid, returnid) {
	var d = art.dialog({
		id: uploadid
	}).iframe.contentWindow;
	
	var in_content = d.$("#att-status").html().substring(1);
	var in_filename = d.$("#att-name").html().substring(1);
	var str = '';
	var contents = in_content.split('|');
	var filenames = in_filename.split('|');
	$('#' + returnid + '_tips').css('display', 'none');
	if (contents == '') return true;
	$.each(contents, function(i, n) {
		var ids = parseInt(Math.random() * 10000 + 10 * i);
		var filename = filenames[i].substr(0, filenames[i].indexOf('.'));
		str += "<li id='multifile" + ids + "'><input type='text' name='" + returnid + "_url[]' value='" + n + "' style='width:310px;' class='input-text'> <input type='text' name='" + returnid + "_alt[]' value='" + filename + "' style='width:160px;' class='input-text' onfocus=\"if(this.value == this.defaultValue) this.value = ''\" onblur=\"if(this.value.replace(' ','') == '') this.value = this.defaultValue;\"> <a href=\"javascript:remove_div('multifile" + ids + "')\">移除</a> </li>";
	});
	$('#' + returnid).append(str);
}


function thumb_images(uploadid, returnid) {
	var d = art.dialog({
		id: uploadid
	}).iframe.contentWindow;
	
	var in_content = d.$("#att-status").html().substring(1);
	if (in_content == '') return false;
	if (!IsImg(in_content)) {
		alert('选择的类型必须为图片类型');
		return false;
	}
	if ($('#' + returnid + '_preview').attr('src')) {
		$('#' + returnid + '_preview').attr('src', in_content);
	}
	$('#' + returnid).val(in_content);
}


function submit_images(uploadid, returnid) {
	var d = art.dialog({
		id: uploadid
	}).iframe.contentWindow;
	
	var in_content = d.$("#att-status").html().substring(1);
	var in_content = in_content.split('|');
	IsImg(in_content[0]) ? $('#' + returnid).attr("value", in_content[0]) : alert('选择的类型必须为图片类型');
}


function submit_attachment(uploadid, returnid) {
	var d = art.dialog({
		id: uploadid
	}).iframe.contentWindow;
	
	var in_content = d.$("#att-status").html().substring(1);
	var in_content = in_content.split('|');
	$('#' + returnid).attr("value", in_content[0]);
}

function submit_files(uploadid, returnid) {
	var d = art.dialog({
		id: uploadid
	}).iframe.contentWindow;
	
	var in_content = d.$("#att-status").html().substring(1);
	var in_content = in_content.split('|');
	var new_filepath = in_content[0].replace(uploadurl, '/');
	$('#' + returnid).attr("value", new_filepath);
}


function IsImg(url) {
	var sTemp;
	var b = false;
	var opt = "jpg|gif|png|bmp|jpeg";
	var s = opt.toUpperCase().split("|");
	for (var i = 0; i < s.length; i++) {
		sTemp = url.substr(url.length - s[i].length - 1);
		sTemp = sTemp.toUpperCase();
		s[i] = "." + s[i];
		if (s[i] == sTemp) {
			b = true;
			break;
		}
	}
	return b;
}

function IsSwf(url) {
	var sTemp;
	var b = false;
	var opt = "swf";
	var s = opt.toUpperCase().split("|");
	for (var i = 0; i < s.length; i++) {
		sTemp = url.substr(url.length - s[i].length - 1);
		sTemp = sTemp.toUpperCase();
		s[i] = "." + s[i];
		if (s[i] == sTemp) {
			b = true;
			break;
		}
	}
	return b;
}

function image_priview(img) {
	art.dialog({title:'图片查看',fixed:true, content:'<img src="'+img+'" />',id:'image_priview'});
}

function removeImg(textareaid){	
	$('#' + textareaid + '_preview').attr('src', '/Public/Admin/img/upload-pic.png');	
	$('#' + textareaid).val('');
}