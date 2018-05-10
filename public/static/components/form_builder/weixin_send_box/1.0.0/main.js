define(['jquery'],function($){

var cpk_weixin_material_box_choose = {
	getName : function(){
		return "weixin_send_box@1.0.0";
	},
	getAuthKey : function(){
		return "34173cb38f07f89ddbeb-9f9cee5b98b2e35ddbe9-b4b147bc522828731f1a-35dbe4694c8b62508b2d62a7819d0056"; // 检测是授权组件
	},
	createObj : function(){
		var obj = {};
		var o_document = $(document);
		var current_dialog_material_builder;

        var config = {
                "materialTypeInput"      : "",
                "componentTriggerFromId" : ""
        };

		obj.init = function(userconfig){
			config       = $.extend({}, config, userconfig);
			console.log( 'weixin_send_box' );
			_init_something();
			onSomeThingBtnEvent();
		}

		function _init_something(){
			// 初始化 当前发送素材类型
			_init_material_type_value();
		}
		function _init_material_type_value(){
			$(config.componentTriggerFromId).find('.j_sendbox_tabnav').each(function(i,ele){
				if($(this).hasClass('selected')){
					var _material_type = $(this).attr('data-type');
					$(config.componentTriggerFromId).find("input[name="+config.materialTypeInput+"]").val(_material_type);
				}
			});
		}

		function onSomeThingBtnEvent(){
			onChangeMaterialType();
			OnDeleteMaterialTextBox();
			OnDeleteMaterialImageBox();
			OnDeleteMaterialNewsBox();
			OnDeleteMaterialRedpackBox();
			OnDeleteMaterialVoiceBox();
			OnDeleteMaterialVideoBox();
			OnDeleteMaterialCardBox();
		}
		function onChangeMaterialType(){

			o_document.on('click','.J_sendbox_tabnav',function(){
				var _material_type = $(this).attr('data-type');
				// j_weixin_send_box_main_div
				$(this).closest('.j_weixin_send_box_main_div').find('.j_sendbox_tabnav').removeClass('selected');
				$(this).addClass('selected');

				var tab_class = $(this).attr("data-tab");
				$(this).closest('.j_weixin_send_box_main_div').find('.j_sendbox_tab_content').hide();
				$(this).closest('.j_weixin_send_box_main_div').find(tab_class).parent().show();

				$("input[name="+config.materialTypeInput+"]").val(_material_type);
			});
		}

		function OnDeleteMaterialTextBox(){
			o_document.on('click','.J_delete_material_text_box',function(){
				var _trigger_name = $(this).attr('data-builder-trigger-name');
            	var o_box_main = $('#j_material_box_name_text_'+_trigger_name);
            	o_box_main.find('.u-box-info').removeClass('hidden');
            	o_box_main.find('.u-box-wrap').addClass('hidden');
            	$('input[name='+_trigger_name+'_material_text_media_id]').val('');
			});
		}

		function OnDeleteMaterialImageBox(){
			o_document.on('click','.J_delete_material_image_box',function(){
				var _trigger_name = $(this).attr('data-builder-trigger-name');
				var media_id      = $('input[name='+_trigger_name+'_material_image_media_id]').val();
            	var o_box_main    = $('#j_material_box_name_image_'+_trigger_name);
            	o_box_main.find('.u-box-info').removeClass('hidden');
            	o_box_main.find('.u-box-wrap').addClass('hidden');
            	o_box_main.find('.wrap-imgbox span').css('background-image','url()');
            	$('input[name='+_trigger_name+'_material_image_media_id]').val('');
				// 新的订阅式			            	
            	o_document.trigger('Jt_material_image_choosed_delete_callback',[media_id,_trigger_name]);
			});
		}

		function OnDeleteMaterialNewsBox(){
			o_document.on('click','.J_delete_material_news_box',function(){
				var _trigger_name = $(this).attr('data-builder-trigger-name');
            	var o_box_main = $('#j_material_box_name_news_'+_trigger_name);
            	o_box_main.find('.u-box-info').removeClass('hidden');
            	o_box_main.find('.u-box-wrap').addClass('hidden');
            	o_box_main.find('.m-materialnews-list').html('');
            	$('input[name='+_trigger_name+'_material_news_media_id]').val('');
			});
		}

		function OnDeleteMaterialRedpackBox(){
			o_document.on('click','.J_delete_material_redpack_box',function(){
				var _trigger_name = $(this).attr('data-builder-trigger-name');
            	var o_box_main = $('#j_material_box_name_redpack_'+_trigger_name);
            	o_box_main.find('.u-box-info').removeClass('hidden');
            	o_box_main.find('.u-box-wrap').addClass('hidden');
            	o_box_main.find('.redpack_box').html('');
            	$('input[name='+_trigger_name+'_material_redpack_media_id]').val('');
			});
		}

		function OnDeleteMaterialVoiceBox(){
			o_document.on('click','.J_delete_material_voice_box',function(){
				var _trigger_name = $(this).attr('data-builder-trigger-name');
            	var o_box_main = $('#j_material_box_name_voice_'+_trigger_name);
            	o_box_main.find('.u-box-info').removeClass('hidden');
            	o_box_main.find('.u-box-wrap').addClass('hidden');
            	o_box_main.find('.voice_box').html('');
            	$('input[name='+_trigger_name+'_material_voice_media_id]').val('');
			});
		}

		function OnDeleteMaterialVideoBox(){
			o_document.on('click','.J_delete_material_video_box',function(){
				var _trigger_name = $(this).attr('data-builder-trigger-name');
            	var o_box_main = $('#j_material_box_name_video_'+_trigger_name);
            	o_box_main.find('.u-box-info').removeClass('hidden');
            	o_box_main.find('.u-box-wrap').addClass('hidden');
            	o_box_main.find('.video_box').html('');
            	$('input[name='+_trigger_name+'_material_video_media_id]').val('');
			});
		}

		function OnDeleteMaterialCardBox(){
			o_document.on('click','.J_delete_material_card_box',function(){
				var _trigger_name = $(this).attr('data-builder-trigger-name');
            	var o_box_main = $('#j_material_box_name_card_'+_trigger_name);
            	o_box_main.find('.u-box-info').removeClass('hidden');
            	o_box_main.find('.u-box-wrap').addClass('hidden');
            	o_box_main.find('.card_box').html('');
            	$('input[name='+_trigger_name+'_material_card_media_id]').val('');
			});
		}


		return obj;
	}
}

return cpk_weixin_material_box_choose;

});