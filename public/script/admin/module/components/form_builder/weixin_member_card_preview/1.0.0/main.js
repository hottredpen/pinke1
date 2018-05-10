define(['jquery'],function($){

var component = {
	getName : function(){
		return "weixin_member_card_preview@1.0.0";
	},
	getAuthKey : function(){
		return "33e75ff09dd601bbe69f-cf2e4952be52a8fbe6a8-b4b157bc522829731f1a-26c7f84caeafc5fb6d2a45ed882d68c3"; // 检测是授权组件
	},
	createObj : function(){
		var obj              = {};
		var o_document       = $(document);

		obj.init = function(){
			init_something();
			onDocumentBtn();
		}

		function init_something(){
			_bind_title_change();
			_bind_custom_cell1_name_change();
			_bind_custom_cell1_tips_change();

		}

		function onDocumentBtn(){

			// 背景颜色发生改变
			$(document).on('Jt_weixin_card_color',function(e,color){
				$('.j_color_bg_preview').css('background-color',color);
			});

			// 背景图片
			$(document).on('cpk_imgbox_upload_callback',function(e,file_res){
				$('.j_background_pic_url_preview').css("background-image","url("+file_res.url+")");
				$('.j_background_pic_url_preview').addClass('j_thumb_fileid_'+file_res.fileid);
			});

			o_document.on('cpk_imgbox_upload_delete_callback',function(e,fileid){
				console.log("监听到"+ fileid);
				$('.j_weixin_member_card_preview_builder').find('.j_thumb_fileid_'+fileid).css('background-image','');
			});
		}

		function _bind_title_change(){
	        $('.j_weixin_member_card_preview_builder').find('input[name=base_info_title]').unbind('input propertychange');
	        $('.j_weixin_member_card_preview_builder').find('input[name=base_info_title]').bind('input propertychange',function(){
	        	var _value = $(this).val();
	        	$('#j_title_preview').text(_value);
	        });
		}

		function _bind_custom_cell1_name_change(){
	        $('.j_weixin_member_card_preview_builder').find('input[name=custom_cell1_name]').unbind('input propertychange');
	        $('.j_weixin_member_card_preview_builder').find('input[name=custom_cell1_name]').bind('input propertychange',function(){
	        	var _value = $(this).val();
	        	$('.j_custom_cell1_name').text(_value);
	        });
		}

		function _bind_custom_cell1_tips_change(){
	        $('.j_weixin_member_card_preview_builder').find('input[name=custom_cell1_tips]').unbind('input propertychange');
	        $('.j_weixin_member_card_preview_builder').find('input[name=custom_cell1_tips]').bind('input propertychange',function(){
	        	var _value = $(this).val();
	        	$('.j_custom_cell1_tips').text(_value);
	        });
		}
		return obj;
	}
}
return component;

});