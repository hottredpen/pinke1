define(['jquery'],function($){
	var cpk_getvcode = {
		createObj : function(){
			var Obj = {};
			var o_document      = $(document);

			var phoneWaitTime   = 120;
			var phone_time      = 0;
			var isSending_phone = false;

            var config = {
                    'sendBtnClass'   : ".J_phone_getvcode",
                    'phoneInputId'   : "#j_phone_input",
                    'sendApi'        : "/car/sellcar/phonecode",

            };


			Obj.init = function(userconfig,_callback){
				config  = $.extend({}, config, userconfig);
				onSendClickBtn();
			}

			function onSendClickBtn(){
				$(config.sendBtnClass).click(function(){
					var phone = $(config.phoneInputId).val();
					if(isSending_phone){
						return;
					}else{
						$(this).html('<font style="color:#999;">正在发送...</font>');
		                $.ajax({
		                    url : config.sendApi,
		                    type : "POST",
		                    dataType : "json",
		                    data : {phone:phone},
		                    success : function(res){
		                        if(res.status == 0){
		                            alert(res.msg);
		                            $(config.sendBtnClass).text("获取验证码");
		                        }else{
				                    phone_time = phoneWaitTime;
				                    phonetimeChange();
		                        }
		                    }
		                });
					}
				});
			}

            function phonetimeChange(){
                if(phone_time==0){
                    $(config.sendBtnClass).text("获取验证码");
                    isSending_phone = false;
                }else{
                    phone_time--;
                    isSending_phone = true;
                    $(config.sendBtnClass).html('<font style="color:#999;font-size:14px;">'+phone_time+ "秒</font>");
                    setTimeout(function() {
                        phonetimeChange();
                    },1000);
                }
            }
			return Obj;
		}
	};
	return cpk_getvcode;
});