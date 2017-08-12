(function () {

		
			var rr1=false;
			var rr2=false;
			var rr3=false;
			var rr4=false;
			var rr5=false;
			var rr6=true;
			$(function(){
				$("#inputUsername_Z").click(function(){
					
					if($("#user_name").attr("title")){
					    alert("您已经登录了！");
						rr6=false;
					}					
				}).bind('blur',function(){

					if($(this).val().length < 3 ){
						$("#usernametishi_Z").html("<font color='red'>字数小于3</font>");
						rr1=flase;
					}else{
					    if($(this).val().length > 9 ){
							$("#usernametishi_Z").html("<font color='red'>字数大于9</font>");
						    rr1=flase;
						}
						else{
							$("#usernametishi_Z").html("<img src='"+DOMAIN+"/public/images/loading.gif'/>");
							$.post(DOMAIN+"/logo/check_u/username/"+$(this).val(),null,function(data){
							if(data){
								$("#usernametishi_Z").html("<img src='"+DOMAIN+"/public/images/ok.png'/>");
								rr1=true;
							}else{
								$("#usernametishi_Z").html("<font color='red'>用户名已存在！</font>");
								rr1=flase;
								$("#inputUsername_Z").focus();	
							}
						})
						}
					}
				});
				
				$("#inputPassword_Z").click(function(){
					//$("#passwordtishi_Z").html("请输入6到12个字符");
				}).bind('blur',function(){
					if($(this).val().length < 6 ){
						$("#passwordtishi_Z").html("<font color='red'>密码小于6</font>");
						rr2=flase;
						//$("#pass1").focus();
					}else{
					    if($(this).val().length > 12 ){
							$("#passwordtishi_Z").html("<font color='red'>密码大于12</font>");
						    rr2=flase;
						}else{
						$("#passwordtishi_Z").html("<img src='"+DOMAIN+"/public/images/ok.png'/>");
						rr2=true;
						}
					}
				})
				
				$("#inputRePassword_Z").click(function(){
					//$("#repasswordtishi_Z").html("请再次输入密码");
				}).bind('blur',function(){
					if($(this).val() != $("#inputPassword_Z").val()){
						$("#repasswordtishi_Z").html("<font color='red'>密码输入不一致</font>");
						rr3=flase;
					}else{
						$("#repasswordtishi_Z").html("<img src='"+DOMAIN+"/public/images/ok.png'/>");
						rr3=true;
					}
				})
				
				$("#inputEmail_Z").click(function(){
					//$("#emailtishi_Z").html("请输入正确的邮箱地址");
				}).bind('blur',function(){
					var t=/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/
					if(!$(this).val().match(t)){
						$("#emailtishi_Z").html("<font color='red'>邮箱地址不合法</font>");
						rr4=flase;
					}else{
					    $("#emailtishi_Z").html("<img src='"+DOMAIN+"/public/images/loading.gif'/>");
					
						$.post(DOMAIN+"/logo/check_e/email/"+$(this).val(),null,function(data){
							if(data){
								$("#emailtishi_Z").html("<img src='"+DOMAIN+"/public/images/ok.png'/>");
								rr4=true;
							}else{
								$("#emailtishi_Z").html("<font color='red'>邮箱地址已占用！</font>");
								rr4=flase;
							}
						})	
					}
				})
				
				$("#inputYanzhengma_Z").click(function(){
					//$("#yanzhengmatishi_Z").html("<font color='red'>请输入验证码</font>");
				}).bind('blur',function(){
				
				    $("#yanzhengmatishi_Z").html("<img src='"+DOMAIN+"/public/images/loading.gif'/>");
				
					$.post(DOMAIN+"/logo/check_v/verify/"+$("#inputYanzhengma_Z").val(),null,function(data){
						if(data){
							$("#yanzhengmatishi_Z").html("<img src='"+DOMAIN+"/public/images/ok.png'/>");
							rr5=true;
						}else{
							$("#yanzhengmatishi_Z").html("<font color='red'>验证码错误</font>");
							rr5=false;
						}
					})
				})
				
			})
			function yz(){
				if(rr1 && rr2 && rr3 && rr4 && rr5 && rr6){ return true; }else{return false;}
			}
			
			
			
			
		$("#inputYanzhengma_D").click(function(){
					$("#yanzhengmatishi_D").html("<font color='red'>请输入验证码</font>");
				}).bind('blur',function(){
				
				
				    $("#yanzhengmatishi_D").html("<img src='"+DOMAIN+"/public/images/loading.gif'/>");
				
					$.post("/index.php?s=/Home/User/verify/check_v/verify/"+$("#inputYanzhengma_D").val(),null,function(data){
						if(data.u=="1"){
							$("#yanzhengmatishi_D").html("<img src='"+DOMAIN+"/public/images/ok.png'/>");
							
						}else{
							$("#yanzhengmatishi_D").html("<font color='red'>验证码错误</font>");
							
						}
					})
				})			

})();