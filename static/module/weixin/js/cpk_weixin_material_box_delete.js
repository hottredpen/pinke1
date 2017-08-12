var cpk_weixin_material_box_delete = {
	createObj : function(){
		var obj = {};
		var o_document = $(document);
		var current_dialog_material_builder;

		obj.init = function(){
			onSomeThingBtnEvent();
		}

		function onSomeThingBtnEvent(){
			OnDeleteMaterialTextBox();
			OnDeleteMaterialImageBox();
			OnDeleteMaterialNewsBox();
		}

		function OnDeleteMaterialTextBox(){
			o_document.on('click','.J_delete_material_text_box',function(){
				var _trigger_name = $(this).attr('data-builder-trigger-name');
            	var o_box_main = $('#j_material_box_name_text_'+_trigger_name);
            	o_box_main.find('.u-box-info').css('display','block');
            	o_box_main.find('.u-box-wrap').css('display','none');
            	$('input[name='+_trigger_name+'_material_text_media_id]').val('');
			});
		}

		function OnDeleteMaterialImageBox(){
			o_document.on('click','.J_delete_material_image_box',function(){
				var _trigger_name = $(this).attr('data-builder-trigger-name');
				var media_id      = $('input[name='+_trigger_name+'_material_image_media_id]').val();
            	var o_box_main    = $('#j_material_box_name_image_'+_trigger_name);
            	o_box_main.find('.u-box-info').css('display','block');
            	o_box_main.find('.u-box-wrap').css('display','none');
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
            	o_box_main.find('.u-box-info').css('display','block');
            	o_box_main.find('.u-box-wrap').css('display','none');
            	o_box_main.find('.m-materialnews-list').html('');
            	$('input[name='+_trigger_name+'_material_news_media_id]').val('');
			});
		}
		return obj;
	}
}