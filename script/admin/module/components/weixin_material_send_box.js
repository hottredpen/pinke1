// define(['jquery'],function($){

// var cpk_weixin_material_box_choose = {
// 	createObj : function(){
// 		var obj = {};
// 		var o_document = $(document);
// 		var current_dialog_material_builder;

//         var config = {
//                 "materialTypeInput" : "",
//         };

// 		obj.init = function(userconfig){
// 			config       = $.extend({}, config, userconfig);

// 			_init_something();
// 			onSomeThingBtnEvent();
// 		}

// 		function _init_something(){
// 			// 初始化 当前发送素材类型
// 			_init_material_type_value();
// 		}
// 		function _init_material_type_value(){
// 			$('.j_sendbox_tabnav').each(function(i,ele){
// 				if($(this).hasClass('selected')){
// 					var _material_type = $(this).attr('data-type');
// 					$("input[name="+config.materialTypeInput+"]").val(_material_type);
// 				}
// 			});
// 		}

// 		function onSomeThingBtnEvent(){
// 			onChangeMaterialType();
// 			OnDeleteMaterialTextBox();
// 			OnDeleteMaterialImageBox();
// 			OnDeleteMaterialNewsBox();
// 			OnDeleteMaterialRedpackBox();
// 			OnDeleteMaterialVoiceBox();
// 		}
// 		function onChangeMaterialType(){
// 			$('.J_sendbox_tabnav').on('click',function(){
// 				var _material_type = $(this).attr('data-type');
// 				$('.j_sendbox_tabnav').removeClass('selected');
// 				$(this).addClass('selected');

// 				var tab_class = $(this).attr("data-tab");
// 				$('.j_sendbox_tab_content').hide();
// 				$(tab_class).parent().show();

// 				$("input[name="+config.materialTypeInput+"]").val(_material_type);
// 			});
// 		}

// 		function OnDeleteMaterialTextBox(){
// 			o_document.on('click','.J_delete_material_text_box',function(){
// 				var _trigger_name = $(this).attr('data-builder-trigger-name');
//             	var o_box_main = $('#j_material_box_name_text_'+_trigger_name);
//             	o_box_main.find('.u-box-info').css('display','block');
//             	o_box_main.find('.u-box-wrap').css('display','none');
//             	$('input[name='+_trigger_name+'_material_text_media_id]').val('');
// 			});
// 		}

// 		function OnDeleteMaterialImageBox(){
// 			o_document.on('click','.J_delete_material_image_box',function(){
// 				var _trigger_name = $(this).attr('data-builder-trigger-name');
// 				var media_id      = $('input[name='+_trigger_name+'_material_image_media_id]').val();
//             	var o_box_main    = $('#j_material_box_name_image_'+_trigger_name);
//             	console.log(_trigger_name);
//             	o_box_main.find('.u-box-info').removeClass('hidden');
//             	o_box_main.find('.u-box-wrap').addClass('hidden');
//             	o_box_main.find('.wrap-imgbox span').css('background-image','url()');
//             	$('input[name='+_trigger_name+'_material_image_media_id]').val('');
// 				// 新的订阅式			            	
//             	o_document.trigger('Jt_material_image_choosed_delete_callback',[media_id,_trigger_name]);
// 			});
// 		}

// 		function OnDeleteMaterialNewsBox(){
// 			o_document.on('click','.J_delete_material_news_box',function(){
// 				var _trigger_name = $(this).attr('data-builder-trigger-name');
// 				console.log(_trigger_name);
//             	var o_box_main = $('#j_material_box_name_news_'+_trigger_name);
//             	o_box_main.find('.u-box-info').removeClass('hidden');
//             	o_box_main.find('.u-box-wrap').addClass('hidden');
//             	o_box_main.find('.m-materialnews-list').html('');
//             	$('input[name='+_trigger_name+'_material_news_media_id]').val('');
// 			});
// 		}

// 		function OnDeleteMaterialRedpackBox(){
// 			o_document.on('click','.J_delete_material_redpack_box',function(){
// 				var _trigger_name = $(this).attr('data-builder-trigger-name');
//             	var o_box_main = $('#j_material_box_name_redpack_'+_trigger_name);
//             	o_box_main.find('.u-box-info').css('display','block');
//             	o_box_main.find('.u-box-wrap').css('display','none');
//             	o_box_main.find('.redpack_box').html('');
//             	$('input[name='+_trigger_name+'_material_redpack_media_id]').val('');
// 			});
// 		}

// 		function OnDeleteMaterialVoiceBox(){
// 			o_document.on('click','.J_delete_material_voice_box',function(){
// 				var _trigger_name = $(this).attr('data-builder-trigger-name');
//             	var o_box_main = $('#j_material_box_name_voice_'+_trigger_name);
//             	o_box_main.find('.u-box-info').removeClass('hidden');
//             	o_box_main.find('.u-box-wrap').addClass('hidden');
//             	$('input[name='+_trigger_name+'_material_voice_media_id]').val('');
// 			});
// 		}


// 		return obj;
// 	}
// }

// return cpk_weixin_material_box_choose;

// });