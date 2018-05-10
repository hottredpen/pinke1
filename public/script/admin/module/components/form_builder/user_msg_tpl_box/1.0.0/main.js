define(['jquery','layer_dialog'],function($,layer){

var cpk_weixin_material_box_choose = {
	getName : function(){
		return "user_msg_tpl_box@1.0.0";
	},
	getAuthKey : function(){
		return "37693cfc748049e45d87-8fc4c7ab4853d244ed87-b4b148bc522828731f1a-cb8d3db4d543ac894692908cb5059106"; // 检测是授权组件
	},
	createObj : function(){
		var obj = {};
		var o_document = $(document);
		var current_dialog_material_builder;

        var config = {
                "componentTriggerFromId" : ""
        };

		obj.init = function(userconfig){
			config       = $.extend({}, config, userconfig);
			_init_something();
			onSomeThingBtnEvent();
		}

		function _init_something(){

		}

		function onSomeThingBtnEvent(){
			onChangeCurrentMsgType();
			onChooseUserMsgTplBtn();
		}
		function onChangeCurrentMsgType(){
			$('.J_sendbox_tabnav').on('click',function(){
				var _material_type = $(this).attr('data-type');
				// j_weixin_send_box_main_div
				$(this).closest('.j_weixin_send_box_main_div').find('.j_sendbox_tabnav').removeClass('selected');
				$(this).addClass('selected');

				var tab_class = $(this).attr("data-tab");
				$(this).closest('.j_weixin_send_box_main_div').find('.j_sendbox_tab_content').hide();
				$(this).closest('.j_weixin_send_box_main_div').find(tab_class).parent().show();

			});
		}

		function onChooseUserMsgTplBtn(){

			$(document).off('click','.J_choose_this_user_msg_tpl');
			$(document).on('click','.J_choose_this_user_msg_tpl',function(){
				var o_this        = $(this);
				var _trigger_name = $(this).attr('data-builder-trigger-name');
				var id      = $(this).val();
			    $.ajax({
			        type : 'post',
			        url  : '/admin/user/set_choose_user_msg_tpl',
			        data : {id:id},
			        success: function(res) {
			            if(res.code==200){
			            	$('input[name='+_trigger_name+'_user_msg_tpl_id]').val(res.data.id);
			            	$('input[name='+_trigger_name+'_msg_title]').val(res.data.msg_title);

			            	var o_box_main = $('#j_user_msg_tpl_box_main_'+_trigger_name);
			            	// 设置sms内容
			            	o_box_main.find('textarea[name='+_trigger_name+'_sms_send_content]').val(res.data.content_sms);
			            	// 设置localmsg && email内容
			            	// 从window.g_ueditor_arr 中获取所有已经被实例化的ueditor
			            	var ueditor_arr = window.g_ueditor_arr;
			            	$.each(ueditor_arr,function(i,ele){
			            		if(ele.ueditor_obj.key == "UEditor_for_user_msg_tpl_localmsg_"+_trigger_name){
			            			var _content = ele.ueditor_obj.setContent(res.data.content_localmsg);
			            		}
			            		if(ele.ueditor_obj.key == "UEditor_for_user_msg_tpl_email_"+_trigger_name){
			            			var _content = ele.ueditor_obj.setContent(res.data.content_email);
			            		}
			            	});
			            	closeLayer(o_this);
			            }else{
			                $.custom.alert(res.msg);
			            }
			        }
			    });
			});
		}

		function redpack_html(content){
			var str = "";
			str     += "<div style='background-color:rgb(228, 228, 228); color:#666;padding:10px;width:95%;'>"+content+"</div>";
			return str;   
		}

		function closeLayer(o_this){
	        var this_layer_index = o_this.closest('.layui-layer-page').attr('times');
	        layer.close(this_layer_index);
		}

		return obj;
	}
}

return cpk_weixin_material_box_choose;

});