define(['jquery','layer_dialog'],function($,layer){

var cpk_weixin_material_dialog_box = {
	createObj : function(){
		var obj = {};
		var o_document = $(document);

		obj.init = function(){
			console.log('material_dialog_init');
			onSomeThingBtnEvent();
		}

		function onSomeThingBtnEvent(){
			onChooseTextOkBtn();
			onChooseImageOkBtn();
			onChooseNewsOkBtn();
			onChooseRedpackBtn();
			onChooseVoiceBtn();
			onChooseVideoBtn();
			onChooseCardBtn();
		}
		function onChooseTextOkBtn(){
			$('.J_choose_this_text').off('click');
			$('.J_choose_this_text').on('click',function(){
				var o_this  = $(this);
				var _trigger_name = $(this).attr('data-builder-trigger-name');
				var reply_type    = 1;
				var media_id      = $(this).val();
			    $.ajax({
			        type : 'post',
			        url  : '/admin/weixin/set_media_id_from_dialog_choose',
			        data : {media_id:media_id,reply_type:reply_type},
			        success: function(res) {
			            if(res.status==1){
			            	var o_box_main = $('#j_material_box_name_text_'+_trigger_name);
			            	o_box_main.find('.j_material_text_textarea_'+_trigger_name).val(res.data.data.content);
			            	$('input[name='+_trigger_name+'_material_text_media_id]').val(res.data.media_id);
			            	closeLayer(o_this);
			            	
			            }else{
			                $.custom.alert(res.msg);
			            }
			        }
			    });
			});
		}

		function onChooseImageOkBtn(){
			$('.J_choose_this_image').off('click');
			$('.J_choose_this_image').on('click',function(){
				var o_this  = $(this);
				var _trigger_name = $(this).attr('data-builder-trigger-name');
				console.log(_trigger_name);
				var reply_type = 2;
				var media_id   = $(this).find('.content-check').find('input[name=image_media_id]').val();
			    $.ajax({
			        type : 'post',
			        url  : '/admin/weixin/set_media_id_from_dialog_choose',
			        data : {media_id:media_id,reply_type:reply_type},
			        success: function(res) {
			            if(res.status==1){
			            	var o_box_main = $('#j_material_box_name_image_'+_trigger_name);
			            	o_box_main.find('.u-box-info').addClass('hidden');
			            	o_box_main.find('.u-box-wrap').removeClass('hidden');
			            	o_box_main.find('.wrap-imgbox span').css('background-image','url('+res.data.data.url+')');
			            	$('input[name='+_trigger_name+'_material_image_media_id]').val(res.data.media_id);
			            	closeLayer(o_this);
							// 新的订阅式(用于  news 编辑时的后侧内容修改)			            	
			            	o_document.trigger('Jt_material_image_choosed_callback',[res.data.data.url,media_id,_trigger_name]);
			            }else{
			                $.custom.alert(res.msg);
			            }
			        }
			    });
			});
		}

		function onChooseNewsOkBtn(){
			$('.J_choose_this_news').off('click');
			$('.J_choose_this_news').on('click',function(){
				console.log('click');
				var o_this  = $(this);
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
			            	o_box_main.find('.u-box-info').addClass('hidden');
			            	o_box_main.find('.u-box-wrap').removeClass('hidden');
			            	o_box_main.find('.m-materialnews-list').html(this_html);
			            	// o_box_main.find('.wrap-textbox').text(res.data.data.content);
			            	$('input[name='+_trigger_name+'_material_news_media_id]').val(res.data.media_id);
			            	closeLayer(o_this);

			            }else{
			                $.custom.alert(res.msg);
			            }
			        }
			    });
			});
		}

		function onChooseRedpackBtn(){

			$('.J_choose_this_redpack').off('click');
			$('.J_choose_this_redpack').on('click',function(){
				console.log('J_choose_this_redpack click');

				var o_this        = $(this);
				var _trigger_name = $(this).attr('data-builder-trigger-name');
				var reply_type    = 99;
				var media_id      = $(this).val();
			    $.ajax({
			        type : 'post',
			        url  : '/admin/weixin/set_media_id_from_dialog_choose',
			        data : {media_id:media_id,reply_type:reply_type},
			        success: function(res) {
			            if(res.status==1){
			            	console.log(res);

			            	var o_box_main = $('#j_material_box_name_redpack_'+_trigger_name);
			            	o_box_main.find('.u-box-info').addClass('hidden');
			            	o_box_main.find('.u-box-wrap').removeClass('hidden');
			            	o_box_main.find('.u-box-wrap').find('.redpack_box').html(redpack_html(res.data.data));
			            	$('input[name='+_trigger_name+'_material_redpack_media_id]').val(res.data.media_id);
			            	closeLayer(o_this);
			            	
			            }else{
			                $.custom.alert(res.msg);
			            }
			        }
			    });
			});
		}

		function onChooseVoiceBtn(){

			$('.J_choose_this_voice').off('click');
			$('.J_choose_this_voice').on('click',function(){
				console.log('J_choose_this_voice click');

				var o_this        = $(this);
				var _trigger_name = $(this).attr('data-builder-trigger-name');
				var reply_type    = 4;
				var media_id      = $(this).val();
			    $.ajax({
			        type : 'post',
			        url  : '/admin/weixin/set_media_id_from_dialog_choose',
			        data : {media_id:media_id,reply_type:reply_type},
			        success: function(res) {
			            if(res.status==1){
			            	console.log(res);

			            	var o_box_main = $('#j_material_box_name_voice_'+_trigger_name);
			            	o_box_main.find('.u-box-info').addClass('hidden');
			            	o_box_main.find('.u-box-wrap').removeClass('hidden');
			            	o_box_main.find('.u-box-wrap').find('.voice_box').html(voice_html(res.data.data));
			            	$('input[name='+_trigger_name+'_material_voice_media_id]').val(res.data.media_id);
			            	closeLayer(o_this);
			            	
			            }else{
			                $.custom.alert(res.msg);
			            }
			        }
			    });
			});
		}

		function onChooseVideoBtn(){
			$('.J_choose_this_video').off('click');
			$('.J_choose_this_video').on('click',function(){
				console.log('J_choose_this_video click');

				var o_this        = $(this);
				var _trigger_name = $(this).attr('data-builder-trigger-name');
				var reply_type    = 5;
				var media_id      = $(this).val();
			    $.ajax({
			        type : 'post',
			        url  : '/admin/weixin/set_media_id_from_dialog_choose',
			        data : {media_id:media_id,reply_type:reply_type},
			        success: function(res) {
			            if(res.status==1){
			            	console.log(res);

			            	var o_box_main = $('#j_material_box_name_video_'+_trigger_name);
			            	o_box_main.find('.u-box-info').addClass('hidden');
			            	o_box_main.find('.u-box-wrap').removeClass('hidden');
			            	o_box_main.find('.u-box-wrap').find('.video_box').html(video_html(res.data.data));
			            	$('input[name='+_trigger_name+'_material_video_media_id]').val(res.data.media_id);
			            	closeLayer(o_this);
			            	
			            }else{
			                $.custom.alert(res.msg);
			            }
			        }
			    });
			});
		}

		function onChooseCardBtn(){

			$('.J_choose_this_card').off('click');
			$('.J_choose_this_card').on('click',function(){
				console.log('J_choose_this_card click');

				var o_this        = $(this);
				var _trigger_name = $(this).attr('data-builder-trigger-name');
				var reply_type    = 98;
				var media_id      = $(this).val();
			    $.ajax({
			        type : 'post',
			        url  : '/admin/weixin/set_media_id_from_dialog_choose',
			        data : {media_id:media_id,reply_type:reply_type},
			        success: function(res) {
			            if(res.status==1){
			            	console.log(res);

			            	var o_box_main = $('#j_material_box_name_card_'+_trigger_name);
			            	o_box_main.find('.u-box-info').addClass('hidden');
			            	o_box_main.find('.u-box-wrap').removeClass('hidden');
			            	o_box_main.find('.u-box-wrap').find('.card_box').html(card_html(res.data.data));
			            	$('input[name='+_trigger_name+'_material_card_media_id]').val(res.data.media_id);
			            	closeLayer(o_this);
			            	
			            }else{
			                $.custom.alert(res.msg);
			            }
			        }
			    });
			});
		}

		function card_html(data){
			var str = "";
			str     += "<div style='background-color:"+data.base_info_color+";color:#fff;padding:10px;width:400px;'>[卡券]"+data.base_info_title+"</div>";
			return str;  
		}

		function redpack_html(data){
			var str = "";
			str     += "<div style='background-color:#d26a5c;color:#fff;padding:10px;width:400px;'>[红包]"+data.wishing+" 金额："+data.min_value+"~"+data.max_value+"</div>";
			return str;   
		}

		function voice_html(data){
			var str = "";
			str     += "<div style='background-color:#2b99ff;color:#fff;padding:10px;width:400px;'>[语音]"+data.name+"</div>";
			return str;  
		}

		function video_html(data){
			var str = "";
			str     += "<div style='background-color:#2b99ff;color:#fff;padding:10px;width:400px;'>[视频]"+data.name+"</div>";
			return str;  
		}

		function closeDialog(){
			$('.ui-dialog-close').trigger('click');
		}

		function closeLayer(o_this){
	        var this_layer_index = o_this.closest('.layui-layer-page').attr('times');
	        layer.close(this_layer_index);
		}
		return obj;
	}
}

return cpk_weixin_material_dialog_box;

});