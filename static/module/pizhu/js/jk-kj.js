/**  编辑模式里的隐藏显示  **/
$('.bianjichakan').bind('click', function () {
	$(".bianji").hide();
	$(".xbianji").removeClass('panel_tags');
	$(".xbianji").removeClass('panel');
	$(this).addClass('hide');
	$('.rebianji').removeClass('hide');
});
$('.rebianji').bind('click', function () {
	$(".bianji").show();
	$(".xbianji").addClass('panel_tags');
	$(".xbianji").addClass('panel');
	$(this).addClass('hide');
	$('.bianjichakan').removeClass('hide');
});
/*    */


/*  段意
    ****   */

$('.J_duanyi_btn').bind('click', function () {
		var catid=$(this).data("catid");
		var pid=$(this).data("pid");
		var uid=$(this).data("uid");
		var duanid=$(this).data("duanid");
		$.ajax({
			type: "POST",
			url: DOMAIN+"/index.php?s=/Home/Hits/C_duanyi",
			dataType: "json",
			jsonp: 'callback',
			data: {
				'catid': catid,
				'pid':pid,
				'uid':uid,
				'duanid':duanid,
				'domain':DOMAIN
			},
			success: function (backdata) {
				$("#duan-"+duanid+"-tijiao").html(backdata.html);
				//把提交按钮给取消,绑定
				postbtn(); 
			},
			error: function (jxhr, msg, err) {
				alert("未知错误");
			}
		}); 
});
/*
*  
    概要
*
*/
$('.J_gaiyao_btn').bind('click', function () {

	            var pid=$(this).data("pid");
			    var uid=$(this).data("uid");
			    var catid=$(this).data("catid");

                $.ajax({
                    type: "POST",
                    url: DOMAIN+"/index.php?s=/Home/Hits/C_gaiyao",
                    dataType: "json",
					jsonp: 'callback',
                    data: {
                        'catid': catid,
						'pid':pid,
						'uid':uid,
						'domain':DOMAIN,
                    },
                    success: function (backdata) {
					    //alert("dd");
					    $("#gaiyao-tijiao").html(backdata.html);
						//把提交按钮给取消,绑定
						postbtn();
                    }
                }); 
				
				
				
});
$('.J_buchong_btn').bind('click', function () {
			    var catid=$(this).data("catid");
	            var pid=$(this).data("pid");
			    var uid=$(this).data("uid");
				var duanid=$(this).data("duanid");
                $.ajax({
                    type: "POST",
                    url: DOMAIN+"/index.php?g=Comments&m=Tjpizhu&a=C_buchong",
                    dataType: "json",
                    jsonp: 'callback',
                    data: {
                        'catid': catid,
						'pid':pid,
						'uid':uid,
						'duanid':duanid,
						'domain':DOMAIN,
                    },
                    success: function (backdata) {
					    if(backdata.lock==1){
						    alert(backdata.lockinfo);
						}else{
							$("#duan-"+duanid+"-tijiao").html(backdata.html);
							//把提交按钮给取消,绑定
							postbtn(); 
						}
                    },
					error: function (jxhr, msg, err) {
						alert("未知错误");
					}
                }); 		
});
/*  划句子
    ******  */
$('.J_huajuzi_btn').bind('click', function () {


    //获取选中的文字
	var txt = funcGetSelectText();
	            var pid=$(this).data("pid");
			    var uid=$(this).data("uid");
			    var catid=$(this).data("catid");
				var duanid=$(this).data("duanid");
                $.ajax({
                    type: "POST",
                    url: DOMAIN+"/index.php?s=/Home/Hits/C_huaju",
                    dataType: "json",
                    jsonp: 'callback',
                    data: {
                        'catid': catid,
						'pid':pid,
						'uid':uid,
						'duanid':duanid,
						'txt':txt,
						'domain':DOMAIN,
                    },
                    success: function (backdata) {
					    $("#duan-"+duanid+"-tijiao").html(backdata.html);
                        postbtn();
                    }
                }); 
});
/*  圈字
    ******  */
$('.J_quanzi_btn').on('click', function () {
    //获取选中的文字
	var txt = funcGetSelectText();

					var pid    = $(this).data("pid");
					var uid    = $(this).data("uid");
					var catid  = $(this).data("catid");
					var duanid = $(this).data("duanid");
				
					console.log("dfsdf");
                $.ajax({
                    type: "POST",
                    url: DOMAIN+"/index.php?s=/Home/Hits/C_quanzi",
                    dataType: "json",
                    jsonp: 'callback',
                    data: {
                        'catid': catid,
						'pid':pid,
						'uid':uid,
						'duanid':duanid,
						'txt':txt,
						'domain':DOMAIN,
                    },
                    success: function (backdata) {
					    $("#duan-"+duanid+"-tijiao").html(backdata.html);
                        postbtn();
                    }
                }); 
});


/*   kuaisuliulang 快速浏览  */
	        $('a.ksliulan').bind('click', function (e) {

				var pid=$(this).attr("data-pid");
			    var uid=$(this).attr("data-uid");
			    var mokuai=$(this).attr("data-mokuai");
			    //ajax
                $.ajax({
				    //获取用GET方便测试
                    type: "POST",
					//获取url路径
                    url: DOMAIN+"index.php?s=/Home/Hits/getuserpizhu",
                    dataType: "jsonp",
                    jsonp: 'callback',
                    data: {
                        'mokuai': mokuai,
						'pid':pid,
						'uid':uid,
                    },
                    success: function (backdata) {
                        if (backdata.status) {
						    //alert("assds");
						    //概要html
						    gaiyao_html="<div class='panel-heading'><i>用户("+backdata.username+")的简要说明：</i>";
							gaiyao_html+="<p class='text-primary'>"+backdata.gydata.gaiyao+"</p></div>";
							$('#gaiyao').html(gaiyao_html);
                            //内容html
						    content_html="";
							$.each(backdata.data, function (key, duan) {
							    content_html += "<div id='duan-"+duan.whichduan+"' >";
								if(duan.leixing=="1"){
								     content_html += "<div id='duan-"+duan.whichduan+"-yuanwen' class='hottimg' >";
								     content_html +=duan.c_name+"<p></p>\
									                  </div>";
								}else{
								     content_html += "<div id='duan-"+duan.whichduan+"-yuanwen' class='hotten' >";
								     content_html +="<p>"+duan.c_name+"</p>\
									                  </div>";
								}
								if(duan.duanyi){
								    content_html +="<div id='duan-"+duan.whichduan+"-duanyi' class='row hottcn' >";
									content_html +="<div class='alert alert-info col-md-10 col-md-offset-2'>"+duan.duanyi+"</div></div>";
								}
								    content_html += "</div>"
                            })

                            $('#content_bd').html(content_html);
                        }
						
                        //绑定ajax回来的
						    //激活tooltip
                            $('a[rel=tooltip]').tooltip({});
							//激活popover
	                        $('.jiexiceng').popover();

                    }
                });
            });

//重新刷新页面，使用location.reload()有可能导致重新提交
function reloadPage(win) {
    var location = win.location;
    location.href = location.pathname + location.search;
}
//获取选中文字
function funcGetSelectText(){
	var txt = '';
	if(document.selection){
		txt = document.selection.createRange().text;//ie
	}else{
		txt = document.getSelection();
	}
	return txt.toString();
}

function postbtn(){
    //所有的ajax form提交,由于大多业务逻辑都是一样的，故统一处理
    var ajaxForm_list = $('form.J_ajaxForm');
            $('button.J_ajax_submit_btn').on('click', function (e) {
                e.preventDefault();
                //alert("gasdf");
                var btn = $(this),
                form = btn.parents('form.J_ajaxForm');

                form.ajaxSubmit({
                    url: btn.data('action') ? btn.data('action') : form.attr('action'), //按钮上是否自定义提交地址(多按钮情况)
                    dataType: 'json',
					jsonp: 'callback',
                    beforeSubmit: function (arr, $form, options) {
                        var text = btn.text();
                        //按钮文案、状态修改
                        btn.text(text + '中...').prop('disabled', true).addClass('disabled');
                    },
                    success: function (data, statusText, xhr, $form) {
                        var text = btn.text();

						op_success(data.info);
						$(".close").click();
                        if(data.btn=="duanyi"){
						    //直接显示提交
							$("#duan-"+data.duanid+"-duanyi").html('<div class="alert alert-info col-md-10 col-md-offset-2">'+data.duanyi+'</div>');
						}else if(data.btn=="gaiyao"){
						    //直接显示提交
							$("#gaiyao div:first-child").html("<i>简要说明：</i><p class='text-primary'>"+data.gaiyao+"</p>");
						}else if(data.btn=="huaju"){
							//刷新当前页
							reloadPage(window);
						}else if(data.btn=="quanzi"){
							//刷新当前页
							reloadPage(window);
						}else if(data.btn=="buchong"){
							//刷新当前页
							reloadPage(window);
						}
                        //按钮文案、状态修改
                        btn.removeClass('disabled').prop('disabled', false).text(text.replace('中...', '')).parent().find('span').remove();
                    }
                });
            });

}
/********ajax返回绑定快速浏览********/
function ksllbind(catid,id){
	//评星
	//pingxingstar(catid,id);	
	//loadarticlestars(catid,id);
/*   kuaisuliulang 快速浏览  */
	        $('a.ksliulan').bind('click', function (e) {

				var pid=$(this).attr("data-pid");
			    var uid=$(this).attr("data-uid");
			    var mokuai=$(this).attr("data-mokuai");
				
                $.ajax({
				    //获取用GET方便测试
                    type: "GET",
					//获取url路径
                    url: DOMAIN+"/index.php?s=/Home/Hits/getuserpizhu",
                    dataType: "jsonp",
                    jsonp: 'callback',
                    data: {
                        'mokuai': mokuai,
						'pid':pid,
						'uid' : uid,
						'domain':DOMAIN,
                    },
                    success: function (backdata) {
						
                        if (backdata.status) {
						    //alert("assds");
						    //概要html
						    gaiyao_html="<div class='panel-heading'><i>用户("+backdata.username+")的简要说明：</i>";
							gaiyao_html+="<p class='text-primary'>"+backdata.gydata.gaiyao+"</p></div>";
							$('#gaiyao').html(gaiyao_html);
                            //内容html
 						    content_html="";
							$.each(backdata.data, function (key, duan) {
							    content_html += "<div id='duan-"+duan.duanid+"' >";
								if(duan.leixing=="1"){
								     content_html += "<div id='duan-"+duan.duanid+"-yuanwen' class='hottimg' >";
                                     content_html += "<a class='jk-kj_com' href='/Uploads/"+duan.content+"' ><img class='img-thumbnail' src='"+duan.content+"' alt='' title='' /></a>";
								     content_html += "<p></p>\
									                  </div>";
								}else{
								     content_html += "<div id='duan-"+duan.duanid+"-yuanwen' class='hotten' >";
								     content_html +="<p>"+duan.content+"</p>\
									                  </div>";
								}
								if(duan.duanyi){
								    content_html +="<div id='duan-"+duan.duanid+"-duanyi' class='row hottcn' >";
									content_html +="<div class='alert alert-info col-md-10 col-md-offset-2'>"+duan.duanyi+"</div></div>";
								}
								    content_html += "</div>"
                            });

                            $('#content_bd').html(content_html);
                        }
						                //绑定ajax回来的
						    //激活tooltip
                            $('a[rel=tooltip]').tooltip({});
							//激活popover
	                        $('.jiexiceng').popover();	
						
                    },
					error: function (jxhr, msg, err) {
						alert("未知错误");
					}

                });        
            });

}
$(".hotten").mouseover(function(){
		var str = $(this).attr("id");
		var reg  = '[1-9]\d*';
		var date = str.match(reg);
		console.log(date[0]);
		article.duanid = date[0];
		console.log(article);
});


/**********************************************/



/**********************************************/
   // 正文区，选中文本抬起鼠标后
   var  	downLock        = false,
    		downAndMoveLock = false,
    		downXY          = {},
    		upXY            = {};
	jQuery('body').on('mousedown', '.content_body', function (event) {
			downLock        = true;
			downAndMoveLock = false;
			downXY.x        = event.pageX;
			downXY.y        = event.pageY;
			console.log("down");
			$("#zhailu_div").hide();//准备下一次的显示
    });
   jQuery('body').on('mousemove', '.content_body', function (event) {
        	if(!downLock){
            return;
        	}
        	downAndMoveLock = true;
    });
   jQuery('body').on('mouseleave', '.content_body', function (event) {
        	downLock = false;
        	downAndMoveLock = false;
    });
   jQuery('body').on('mouseup', '.content_body', function (event) {
        	if(!downAndMoveLock){
            return;
        	}
			downLock        = false;
			downAndMoveLock = false;
			var txt         = funcGetSelectText();
			upXY.x          = event.pageX;
			upXY.y          = event.pageY;
			txt = $.trim(txt);
			if(txt.length > 0 && txt!=" "){
				show_picked(downXY,upXY,txt);
				console.log(txt);
				console.log(txt.length);
			}
	});
//显示摘抄框
function show_picked(down,up,txt) {
	var pickedDiv = $("#zhailu_div");
   var topPos,leftPos;
   if(down.y > up.y){
      topPos  = down.y;
      leftPos = down.x;
   }else{
      topPos  = up.y;
      leftPos = down.x;
   }
   pickedDiv.html('<div class="panel-heading">' +
        '<input name="pick-note-content" type="hidden" value="'+ txt +'">'+
        '<a class="btn btn-default"><span class="glyphicon glyphicon-pencil J_quanzi_btn"></span>说点什么...</a>' +
        //'<a class="submit btn88 btn  post-pick-note-cancle" href="#" id="close_zc" onclick="return false;">取消</a>' +
        '<a class="btn btn-success pull-right">摘抄下来</a></div></div> </div>' +
        '<div class="bottom" style="display:none;"></div><b class="border-notch notch"></b><b class="notch"></b>'
		);
   pickedDiv.css({position: "absolute", top: topPos+20,left: leftPos,width: "270px"}).show();
}