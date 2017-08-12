var cpk_weixin_material_box_choose = {
	createObj : function(){
		var obj = {};
		var o_document = $(document);
		var current_dialog_material_builder;

		obj.init = function(){
			_init_something();
			onSomeThingBtnEvent();
		}

		function _init_something(){
			clickTheRadio();
			_show_material_box();
		}

		function _show_material_box(){
			var curr_num = parseInt($('input[name=reply_num]:checked').val());
			_show_material_box_num(curr_num);
		}
		function _show_material_box_num(curr_num){
			var num = parseInt(parseInt(curr_num) + 1 );
			for (var i = 1; i < 6; i++) {
				console.log(num);
				if(i < num){
					$('.item_reply_type_'+i).removeClass('hidden');
					$('#j_material_box_builder_reply_type_'+i).removeClass('hidden');
				}else{
					$('.item_reply_type_'+i).addClass('hidden');
					$('#j_material_box_builder_reply_type_'+i).addClass('hidden');
				}
			}
		}

		function clickTheRadio(){
			$("input[type='radio']:checked").on('Jt_click',function(){
				_ReplyTypeDoThing($(this));
			});
			$("input[type='radio']:checked").trigger('Jt_click');
		}



		function onSomeThingBtnEvent(){
			onReplyTypeChange();
			onChooseTextOkBtn();
			onChooseImageOkBtn();
			onChooseNewsOkBtn();
			OnDeleteMaterialTextBox();
			OnDeleteMaterialImageBox();
			OnDeleteMaterialNewsBox();
			OnNumChange();
		}
		function onChooseTextOkBtn(){
			o_document.on('click','.J_choose_this_text',function(){
				var _trigger_name = $(this).attr('data-builder-trigger-name');
				var reply_type = 1;
				var media_id   = $(this).find('.content-check').find('input[name=text_media_id]').val();
			    $.ajax({
			        type : 'post',
			        url  : '/admin/weixin/set_media_id_from_dialog_choose',
			        data : {media_id:media_id,reply_type:reply_type},
			        success: function(res) {
			            if(res.status==1){
			            	console.log(res);
			            	var o_box_main = $('#j_material_box_name_text_'+_trigger_name);
			            	o_box_main.find('.u-box-info').css('display','none');
			            	o_box_main.find('.u-box-wrap').css('display','block');
			            	o_box_main.find('.wrap-textbox').text(res.data.data.content);
			            	$('input[name='+_trigger_name+'_material_text_media_id]').val(media_id);
			            	closeDialog();
			            	
			            }else{
			                cpk_alert(res.msg)
			            }
			        }
			    });
			});
		}

		function onChooseImageOkBtn(){
			o_document.on('click','.J_choose_this_image',function(){
				var _trigger_name = $(this).attr('data-builder-trigger-name');
				var reply_type = 2;
				var media_id   = $(this).find('.content-check').find('input[name=image_media_id]').val();
			    $.ajax({
			        type : 'post',
			        url  : '/admin/weixin/set_media_id_from_dialog_choose',
			        data : {media_id:media_id,reply_type:reply_type},
			        success: function(res) {
			            if(res.status==1){
			            	console.log(res);
			            	console.log(_trigger_name);
			            	var o_box_main = $('#j_material_box_name_image_'+_trigger_name);
			            	o_box_main.find('.u-box-info').css('display','none');
			            	o_box_main.find('.u-box-wrap').css('display','block');
			            	o_box_main.find('.wrap-imgbox span').css('background-image','url('+res.data.data.url+')');
			            	$('input[name='+_trigger_name+'_material_image_media_id]').val(media_id);
			            	closeDialog();
							// 新的订阅式			            	
			            	o_document.trigger('Jt_material_image_choosed_callback',[res.data.data.url,media_id,_trigger_name]);


			            }else{
			                cpk_alert(res.msg)
			            }
			        }
			    });
			});
		}



		function onChooseNewsOkBtn(){
			o_document.on('click','.J_choose_this_news',function(){
				var _trigger_name = $(this).attr('data-builder-trigger-name');
				var this_html = "<div class='u-newslist-newsitem'>";
				this_html     += $(this).html();
				this_html     += "</div>";
				console.log(this_html);
				console.log('J_choose_this_news');
				var reply_type = 3;
				var media_id   = $(this).find('.content-check').find('input[name=news_media_id]').val();
			    $.ajax({
			        type : 'post',
			        url  : '/admin/weixin/set_media_id_from_dialog_choose',
			        data : {media_id:media_id,reply_type:reply_type},
			        success: function(res) {
			            if(res.status==1){
			            	console.log(res);
			            	var o_box_main = $('#j_material_box_name_news_'+_trigger_name);
			            	o_box_main.find('.u-box-info').css('display','none');
			            	o_box_main.find('.u-box-wrap').css('display','block');
			            	o_box_main.find('.m-materialnews-list').html(this_html);
			            	// o_box_main.find('.wrap-textbox').text(res.data.data.content);
			            	$('input[name='+_trigger_name+'_material_news_media_id]').val(media_id);
			            	closeDialog();
			            	// $("#j_builder_form").submit();

			            	// 
			            	
			            }else{
			                cpk_alert(res.msg)
			            }
			        }
			    });
			});
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

		function OnNumChange(){
			$('input[name=reply_num]').on('click',function(){
				var _num = $(this).val();
				console.log(_num);
				_show_material_box_num(_num);
			});
		}

		function closeDialog(){
			$('.ui-dialog-close').trigger('click');
		}

		function onReplyTypeChange(){
			$('input.radio').change(function(){
				_ReplyTypeDoThing($(this));
			});



		}
		function _ReplyTypeDoThing(o_this){
			var trigger_from_name  = o_this.attr('name');
			// 如果是reply_type
			if(trigger_from_name.indexOf('reply_type') > -1){

				$('#j_material_box_builder_'+trigger_from_name).find('.j_material_box').removeClass('is_show');
				var o_material_box;
				if(o_this.val() == 1){
					o_material_box = $('#j_material_box_name_text_'+trigger_from_name);
				}
				if(o_this.val() == 2){
					o_material_box = $('#j_material_box_name_image_'+trigger_from_name);
				}
				if(o_this.val() == 3){
					o_material_box = $('#j_material_box_name_news_'+trigger_from_name);
				}
				if(o_this.val() == 4){
					o_material_box = $('#j_material_box_name_voice_'+trigger_from_name);
				}
				if(o_this.val() == 5){
					o_material_box = $('#j_material_box_name_video_'+trigger_from_name);
				}
				o_material_box.addClass('is_show');
				_material_box_is_show(o_material_box);

			}
		}

		function _material_box_is_show(o_material_box){
			console.log(o_material_box);
			if(o_material_box.find('.j_material_box_media_id_input').val() == ''){
				o_material_box.find('.u-box-info').css('display','block');
				// o_material_box.find('.u-box-wrap').css('display','none');
			}else{
				o_material_box.find('.u-box-wrap').css('display','block');
				// o_material_box.find('.u-box-info').css('display','none');
			}
		}

		return obj;
	}
}