var cpk_weixin_dialog_material_choose = {
	createObj : function(){
		var obj = {};
		var o_document = $(document);

		obj.init = function(){
			onSomeThingBtnEvent();
		}

		function onSomeThingBtnEvent(){
			onChooseTextOkBtn();
			onChooseImageOkBtn();
			onChooseNewsOkBtn();
			onChooseRedpackBtn();
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
				var reply_type = 3;
				var media_id   = $(this).find('.content-check').find('input[name=news_media_id]').val();
			    $.ajax({
			        type : 'post',
			        url  : '/admin/weixin/set_media_id_from_dialog_choose',
			        data : {media_id:media_id,reply_type:reply_type},
			        success: function(res) {
			            if(res.status==1){
			            	var o_box_main = $('#j_material_box_name_news_'+_trigger_name);
			            	o_box_main.find('.u-box-info').css('display','none');
			            	o_box_main.find('.u-box-wrap').css('display','block');
			            	o_box_main.find('.m-materialnews-list').html(this_html);
			            	// o_box_main.find('.wrap-textbox').text(res.data.data.content);
			            	$('input[name='+_trigger_name+'_material_news_media_id]').val(media_id);
			            	closeDialog();

			            }else{
			                cpk_alert(res.msg)
			            }
			        }
			    });
			});
		}

		function onChooseRedpackBtn(){

			o_document.on('Jt_choose_redpack_id',function(e,media_id){
				var _trigger_name = "reply_type";
				console.log('来自onChooseRedpackBtn');
				console.log(media_id);

				var reply_type = 99;
			    $.ajax({
			        type : 'post',
			        url  : '/admin/weixin/set_media_id_from_dialog_choose',
			        data : {media_id:media_id,reply_type:reply_type},
			        success: function(res) {
			            if(res.status==1){
			            	console.log(res);

			            	var o_box_main = $('#j_material_box_name_redpack_'+_trigger_name);
			            	o_box_main.find('.u-box-info').css('display','none');
			            	o_box_main.find('.u-box-wrap').css('display','block');
			            	o_box_main.find('.u-box-wrap').html(redpack_html(res.data.data));
			            	$('input[name='+_trigger_name+'_material_redpack_media_id]').val(media_id);
			            	closeDialog();
			            	
			            }else{
			                cpk_alert(res.msg)
			            }
			        }
			    });
			});

			// o_document.on('click','.J_choose_this_redpack',function(){
			// 	var _trigger_name = $(this).attr('data-builder-trigger-name');
			// 	var reply_type = 99;
			// 	var media_id   = $(this).find('.content-check').find('input[name=redpack_media_id]').val();

			// });
		}

		function redpack_html(data){
			var str = "";
			str     += "<div style='background-color:#d26a5c;color:#fff;padding:10px;width:400px;'>[红包]"+data.wishing+" 金额："+data.min_value+"~"+data.max_value+"</div>";
			return str;   
		}


		function closeDialog(){
			$('.ui-dialog-close').trigger('click');
		}

		return obj;
	}
}