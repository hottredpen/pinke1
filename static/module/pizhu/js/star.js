var description = new Array("非常差","真的是差","一般","很好","太完美了");
function loadarticlestars(catid,id){
                 $.ajax({
                    type: "POST",
                    url: DOMAIN+"/index.php?s=/Home/Hits/chapingxing",
                    dataType: "json",
                    jsonp: 'callback',
                    data: {
                        'catid': catid,
						'id':id,
                    },
                    success: function (backdata) {
					    //当前会员的评星数
						ustar=backdata.xingxing;
						islogin=backdata.islogin;
						//所有会员的评星，及人数
						$(".panel-pingjia").append("<div id='pstars' class='rateit' data-rateit-value='0' title='0' data-rateit-ispreset='true' data-rateit-readonly='true'></div><font>("+backdata.renshu+"人评价)</font>");
						//$('div.rateit').rateit();
						$("#pstars").data("rateit-value",backdata.pingjunfen).rateit();
						$("#pstars").attr("title",backdata.pingjunfen);
						
						if(ustar!==0){
						     //显示星数
						     $("#mystars").data("rateit-value",ustar).rateit();
							 $(".description").text("您当前的评价为："+description[ustar-1]);
							 //设置不可评分
							 $("#mystars").data("rateit-readonly",true).rateit();
						}else{
						     //可评分
						     $("#mystars").data("rateit-readonly",false).rateit();
							 //提交几星
							 tijiaojixing(catid,id,islogin,DOMAIN);
							 $('.description').text('是好是差，请您打个分！');
						}

                    }
                });	
}
function tijiaojixing(catid,id,islogin,DOMAIN){
	//未点击前
	$("#mystars").bind('over', function (event, value) {
			if(value>0){
			    $('.description').text(description[value-1]); 
			}else{
			    $('.description').text('');
			}
	});
    //点击
    $('#mystars').bind('rated', function (e) {
         var ri = $(this);
         var value = ri.rateit('value');

		 //判断是否登录
		 if(!islogin){
              //alert("请登录后再评分！敬请原谅");
			  op_error("请登录后再评分！敬请原谅","温馨提示");
		 }else{
			 //锁定评分
			 ri.rateit('readonly', true);
			 //ajax
			  $.ajax({
				url: DOMAIN+"/index.php?s=/Home/Hits/dianpingxing",
				data: { "catid": catid,"id":id, "jixing": value }, 
				type: 'POST',
				dataType: "json",
				jsonp: 'callback',
				success: function (backdata) {
					//所有会员的评星，及人数
					$(".panel-pingjia").html("<span class='glyphicon glyphicon-thumbs-up mr10'></span><font>本文评价</font><div id='jstars' class='rateit' data-rateit-value='0' title='0' data-rateit-ispreset='true' data-rateit-readonly='true'></div><font>("+backdata.renshu+"人评价)</font>");
					$("#jstars").data("rateit-value",backdata.pingjunfen).rateit();
					$("#jstars").attr("title",backdata.pingjunfen);
					$('.description').text('您当前的评价为：' + description[value-1]); 
				},
				error: function (jxhr, msg, err) {
					op_error("未知错误");
				}
			 });
		 }
     });
}