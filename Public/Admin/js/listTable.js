/**
 * @name 列表操作(排序，修改值，状态切换，批量操作)
 * @description Based on jQuery 1.4+
 * @author andery@foxmail.com
 */
;(function($) {
	$.fn.listTable = function(options) {
		var self = this,
			local_url = window.location.search,
			settings = {
				url: $(self).attr('data-acturi')
			}
		if(options) {
			$.extend(settings, options);
		}
		//整理排序
		var params  = local_url.substr(1).split('&');
		var sort,order;
		for(var i=0; i < params.length; i++) {
			var param = params[i];
			var arr   = param.split('=');
			if(arr[0] == 'sort') {
				sort = arr[1];
			}
			if(arr[0] == 'order') {
				order = arr[1];
			}
		}	
		//全选反选
		$('.J_checkall').live('click', function(){
			$('.J_checkitem').attr('checked', this.checked);
			$('.J_checkall').attr('checked', this.checked);
    	});
    	//历史排序
		$('span[data-tdtype="order_by"]', $(self)).each(function() {
			if($(this).attr('data-field') == sort) {
				if(order == 'asc') {
					$(this).attr('data-order', 'asc');
					$(this).addClass("sort_asc");
				} else if (order == 'desc') {
					$(this).attr('data-order', 'desc');
					$(this).addClass("sort_desc");
				}
			}
		}).addClass('sort_th');
		//排序
		$('span[data-tdtype="order_by"]', $(self)).live('click', function() {
			var s_name = $(this).attr('data-field'),
				s_order  = $(this).attr('data-order'),
				sort_url = (local_url.indexOf('?') < 0) ? '?' : '';
				sort_url += '&sort=' + s_name + '&order=' + (s_order =='asc' ? 'desc' : 'asc');
				local_url = local_url.replace(/&sort=(.+?)&order=(.+?)$/, '');
			location.href = local_url + sort_url;
			return false;
		});
		//修改
		$('span[data-tdtype="edit"]', $(self)).live('click', function() {
			var s_val   = $(this).text(),
			s_name  = $(this).attr('data-field'),
			s_id    = $(this).attr('data-id'),
			width   = $(this).width();
			$('<input type="text" class="lt_input_text" value="'+s_val+'" />').width(width).focusout(function(){
				$(this).prev('span').show().text($(this).val());
				if($(this).val() != s_val) {
					$.getJSON(settings.url, {id:s_id, field:s_name, val:$(this).val()}, function(result){
						if(result.status == 0) {
							$.ftxia.tip({content:result.msg, icon:'error'});
							$('span[data-field="'+s_name+'"][data-id="'+s_id+'"]').text(s_val);
							return;
						}
					});
				}
				$(this).remove();
			}).insertAfter($(this)).focus().select();
			$(this).hide();
			return false;
		});
		//切换
		$('img[data-tdtype="toggle"]', $(self)).live('click', function() {
			var img    = this,
				s_val  = ($(img).attr('data-value'))== 0 ? 1 : 0,
				s_name = $(img).attr('data-field'),
				s_id   = $(img).attr('data-id'),
				s_src  = $(img).attr('src');
			$.getJSON(settings.url, {id:s_id, field:s_name, val:s_val}, function(result){
				if(result.status == 1) {
					if(s_src.indexOf('disabled')>-1) {
						$(img).attr({'src':s_src.replace('disabled','enabled'),'data-value':s_val});
					} else {
						$(img).attr({'src':s_src.replace('enabled','disabled'),'data-value':s_val});
					}
				}
			});
			return false;
		});
		//批量操作
		$('input[data-tdtype="batch_action"]').live('click', function() {
			var btn = this;
			if($('.J_checkitem:checked').length == 0){
                layer.msg(lang.plsease_select_rows, {icon: 3,shade:0.5});                
				return false;
            }
			var ids = '';
			$('.J_checkitem:checked').each(function(){
				ids += $(this).val() + ',';
			});
			ids = ids.substr(0, (ids.length - 1));
			var uri = $(btn).attr('data-uri') + '&' + $(btn).attr('data-name') + '=' + ids,
				msg = $(btn).attr('data-msg'),
				acttype = $(btn).attr('data-acttype'),
				title = ($(btn).attr('data-title') != undefined) ? $(this).attr('data-title') : lang.confirm_title;
			if(msg != undefined){
				layer.confirm(
					msg,
				    {shade: 0.5},
				    function(){
					action();
				});								
			}else{
				action();
			}
			function action(){
				if(acttype == 'ajax_form'){
					var did = $(btn).attr('data-id'),
						dwidth = parseInt($(btn).attr('data-width')),
						dheight = parseInt($(btn).attr('data-height'));
					$.dialog({
						id:did,
						title:title,
						width:dwidth ? dwidth : 'auto',
						height:dheight ? dheight : 'auto',
						padding:'',
						lock:true,
						ok:function(){
							var info_form = this.dom.content.find('#info_form');
							if(info_form[0] != undefined){
								info_form.submit();
								return false;
							}
						},
						cancel:function(){}
					});
					$.getJSON(uri, function(result){
						if(result.status == 1){
							$.dialog.get(did).content(result.data);
						}
					});
				}else if(acttype == 'ajax'){
					$.getJSON(uri, function(result){
						if(result.status == 1){							
							layer.msg(result.info, {icon: 1,shade:0.5});
							window.location.reload();
						}else{
							layer.msg(result.info, {icon: 3,shade:0.5});							
						}
					});
				}else{
					location.href = uri;
				}
			}
		});
	};
	
	/*获取复选框的值*/
	$.checkBoxValue = function (name, string) {
		var ids = [];
		$("input=[name='"+name+"']:checked").each(function(i) {
			ids.push($(this).val());
		});
		return string ? ids.join(',') : ids;
	}	
	
	
})(jQuery);