/**
 * 上传附件（图片、文件、视频）
 * @param module      附件类型 0-图片  1-文件  2-视频 
 * @param num         附件数量
 * @param ext         允许的格式  jpg|bmp|png , zip|rar  , flv ....
 * @param size        大小单位MB   
 * @param textareaid  返回内容位置
 * @param formName    提交表单的名称
 * @param funcName    调用数据的处理函数
 */
function flashupload(module,num,ext,size,textareaid,formName, funcName) {
	var setting = '&module='+module+'&num='+num+'&textareaid='+textareaid+'&funcName='+funcName+'&ext='+ext+'&size='+size+'&formName='+formName ;

	layer.open({
	    type: 2,
	    title:'上传附件',
	    area : ['630px','395px'],
	    content: "/admin/attachment/edit.html?"+setting
	});

}

function remove_div(k){
	$('#'+k).remove();
}


function change_images(uploadid,returnid){
	var d = window.top.art.dialog({id:uploadid}).data.iframe;
	var in_content = d.$("#att-status").html().substring(1);
	var in_filename = d.$("#att-name").html().substring(1);
	var str = $('#'+returnid).html();
	var contents = in_content.split('|');
	var filenames = in_filename.split('|');
	$('#'+returnid+'_tips').css('display','none');
	if(contents=='') return true;
	$.each( contents, function(i, n) {
		var ids = parseInt(Math.random() * 10000 + 10*i); 
		var filename = filenames[i].substr(0,filenames[i].indexOf('.'));
		str += "<li id='image"+ids+"'><input type='text' name='"+returnid+"_url[]' value='"+n+"' style='width:310px;' ondblclick='image_priview(this.value);' class='input-text'> <input type='text' name='"+returnid+"_alt[]' value='"+filename+"' style='width:160px;' class='input-text' onfocus=\"if(this.value == this.defaultValue) this.value = ''\" onblur=\"if(this.value.replace(' ','') == '') this.value = this.defaultValue;\"> <a href=\"javascript:remove_div('image"+ids+"')\">移除</a> </li>";
		});
	
	$('#'+returnid).html(str);
}