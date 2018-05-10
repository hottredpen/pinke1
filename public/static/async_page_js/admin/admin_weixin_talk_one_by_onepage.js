define(['jquery'],function($){

var page_trigger = {
	createObj : function(){
		var obj = {};
		var o_document = $(document);
		var o_replce_send_box_manager = $('#j_replce_send_box_manager');
		var last_list_id = 0;

		
        var config = {
                "builderDiv"       : "",
        };

		obj.init = function(userconfig){
			console.log("talk_one_by_one init");
			config       = $.extend({}, config, userconfig);
			_init_something();
			_onDocumentBtn();
		}

		function _init_something(){
			$(function(){
			    $("html, body .m-message-list").scrollTop($('#j-anchor-message-replay').offset().top );

			    // 寻找最后一个回复，触发点击他
			    $(".j_this_msg_need_replace_send_box").each(function(i,ele){
			    	last_list_id = $(ele).attr("data-id");
			    });
				_fun_replace_a_b("#j_this_msg_need_replace_send_box_"+last_list_id,"#jt_load_send_box");
				o_replce_send_box_manager.attr('data-cur-replace-div',"#j_this_msg_need_replace_send_box_"+last_list_id);

			    console.log(last_list_id);

			});

			if($("#jt_load_send_box").length > 0){

				var url    = $("#jt_load_send_box").attr('data-url');
				var title  = $("#jt_load_send_box").attr('data-title');
				var width  = $("#jt_load_send_box").attr('data-width');
				var height = $("#jt_load_send_box").attr('data-height');

			    $.ajax({
			        type : 'get',
			        url  : url,
			        success: function(res) {
			            if(res.code==200){
			            	$("#jt_load_send_box").html(res.data);
						    $(document).trigger('Jt_builder_form_init');
			            }else{
			                $.custom.alert(res.msg)
			            }
			        }
			    });

			}


		}

		function _onDocumentBtn(){
			// 发送框里的取消
			$(document).on('J_builder_form_cancel_click',function(e,ele){
				var replace_cur = o_replce_send_box_manager.attr('data-cur-replace-div');
				if(replace_cur != ""){
					_fun_replace_a_b("#jt_load_send_box",replace_cur);
					o_replce_send_box_manager.attr('data-cur-replace-div',"");
				}
			});

			$(".J_clear_user_msg_tip_num").on('click',function(){
				var openid = $(this).attr('data-openid');
		        $.ajax({
		            type : 'POST',
		            url  : '/admin/weixin/clear_user_msg_tip_num',
		            data : {openid:openid},
		            success: function(res) {
		                if(res.code==200){
		                    $.custom.msg(res.msg)
		                }else{
		                    $.custom.alert(res.msg);
		                }
		            }
		        });
			});


			$(".J_taketo_use_show_send_box").on('click',function(){
				var replace_t   = $(this).attr('data-replace-div-id');
				var replace_cur = o_replce_send_box_manager.attr('data-cur-replace-div');
				// 先判断是否相同的（即影藏发送框）
				if(replace_t == replace_cur){
					_fun_replace_a_b("#jt_load_send_box",replace_t);
					o_replce_send_box_manager.attr('data-cur-replace-div',"");
				}else{
					// 注意这里的_fun_replace_a_b里面的顺序
					if(replace_cur == ""){ // 查看是不是第一次显示,第一次显示只需1个步骤
						_fun_replace_a_b(replace_t,"#jt_load_send_box");
						o_replce_send_box_manager.attr('data-cur-replace-div',replace_t);
					}else{ // 不是第一次显示，需要2个步骤
						_fun_replace_a_b("#jt_load_send_box",replace_cur);
						_fun_replace_a_b(replace_t,"#jt_load_send_box");
						o_replce_send_box_manager.attr('data-cur-replace-div',replace_t);
					}
				}
				$(document).trigger('Jt_builder_form_init'); // 部分按钮需重新绑定
			});
		}
		function _fun_replace_a_b(replace_a,replace_b){
            var $replace_a = $(replace_a).clone(true);  
            var $replace_b = $(replace_b).replaceWith($replace_a);  
            $(replace_a).replaceWith($replace_b);  
		}
		return obj;
	}
}

return page_trigger;

});