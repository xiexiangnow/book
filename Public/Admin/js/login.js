var login = {
	
	//初始化
	init:function(){
		$('#login_main').find('label').bind({
            click:function(){
                $(this).hide().next().focus();                
            }
        });

        $('#login_main').find('input:text,input:password').bind({
            blur:function() {
                if($(this).val() != '') {
                    return;
                }

                $(this).removeClass('hover').prev().show();
            },
            focus:function() {
                $(this).addClass('hover').prev().hide();
                
                if(!$(this).attr('auto_code')) {
                    return;                    
                }                

                if($(this).next().css('display') != 'none') {
                    return;                    
                }            

                $(this).next().show();
                if($('#auth_code_img').attr('src') != '') {
                    return;                    
                }
                
                $('#auth_code_img').click();                                
            }
        });
	},
	//登录
	submit: function(){
		
		$('form').submit(function(){
			var login_flag = true;
			var $this = $(this);
			
			$('#login_main').find('input:text,input:password').each(function(i){
				if ($(this).val() == '') {
					$(this).focus();
					login_flag = false;
					return false;
				}
			});
			
			if (!login_flag) {
				return false;
			}
			
			if ($this.data('lock')) {
				return false;
			}
			
			$this.data('lock', true);
			$.getJSON($('form').attr('action'), $this.serialize(), function(result){													
				if (!result.status) {										
					$this.data('lock', false);
					layer.msg(result.info,{icon: 3,shade: 0.5},function(){
						$('#auth_code_img').click();
					});					
					return false;
				}
				
				layer.msg(result.info,{icon: 1,shade: 0.5},function(){
					location.href = result.url ;
				});									
			});
			
			return false;
		});				
	},	
	//验证码
	auth_code:function() {
		
		if($('#auth_code_img').attr('id') == null) {
			return;
		}
			
		$('#auth_code_img').click(function(){
			$(this).attr('src', $('#verify_url').val() + '?rand=' + Math.random());				
		});
		
		$('#auth_code_img').click();						
	}
}


$(document.body).ready(function(){
	
	//初始化
	login.init();
    //验证码
	login.auth_code();
	//登录操作
	login.submit();
});