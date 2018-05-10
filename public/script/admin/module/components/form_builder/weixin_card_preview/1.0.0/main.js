define(['jquery'],function($){

var component = {
	getName : function(){
		return "weixin_card_preview@1.0.0";
	},
	getAuthKey : function(){
		return "8e296a067a37563370de-c4de9fe96892a873768e-b5b147bc522829841f1a-4dbf9899c0ee41f7cd1099e63d2697d7"; // 检测是授权组件
	},
	createObj : function(){
		var obj              = {};
		var o_document       = $(document);

		var advanced_info_use_condition_least_cost_is_open = 0;
		var advanced_info_use_condition_category_is_open   = 0;
		var advanced_info_use_condition_can_use_with_other_discount = 0;

		var use_condition_str_arr = [];

		use_condition_str_arr[0] = ""; // 消费满
		use_condition_str_arr[1] = ""; // 适用商品
		use_condition_str_arr[2] = ""; // 不适用商品


        var config = {
                "builderDiv"       : "",
                "cardType"         : "",
        };

		obj.init = function(userconfig){
			console.log("builder weixin_card_preview");
			config       = $.extend({}, config, userconfig);
			_init_something();
			_onDocumentBtn();
			setTimeout(function(){
				_change_use_condition();
			},0);
		}

		function _init_something(){
			_init_checkbox_value();


			_bind_title_change();
			_bind_cover_title_change();
			_bind_custom_cell1_name_change();
			_bind_custom_cell1_tips_change();
			_bind_advanced_info_use_condition_least_cost();
			_bind_advanced_info_use_condition_accept_category();
			_bind_advanced_info_use_condition_reject_category();
		}

		function _init_checkbox_value(){
			

			advanced_info_use_condition_least_cost_is_open = parseInt($('#j_form_item_val_advanced_info_use_condition_least_cost_is_open').val());
			if(isNaN(advanced_info_use_condition_least_cost_is_open)){
				advanced_info_use_condition_least_cost_is_open = 0;
			}
			advanced_info_use_condition_category_is_open   = parseInt($('#j_form_item_val_advanced_info_use_condition_category_is_open').val());
			if(isNaN(advanced_info_use_condition_category_is_open)){
				advanced_info_use_condition_category_is_open = 0;
			}


			console.log(advanced_info_use_condition_least_cost_is_open);

		}

		function _onDocumentBtn(){

			// 背景颜色发生改变
			o_document.on('Jt_weixin_card_color',function(e,color){
				$('.j_color_bg_preview').css('background-color',color);
			});

			// 背景图片
			o_document.on('cpk_imgbox_upload_callback',function(e,file_res){
				$('#j_cover_preview img').attr("src",file_res.url);
				$('#j_cover_preview img').addClass('j_thumb_fileid_'+file_res.fileid);
			});

			// imgbox 图片删除
			o_document.on('cpk_imgbox_upload_delete_callback',function(e,fileid){
				$('.j_weixin_card_preview_builder').find('.j_thumb_fileid_'+fileid).attr('src','');
			});

			_on_date_change();

			_on_use_condition_change();



		}

		function _change_use_time_preview_by_fix_ferm(fixed_begin_term,fixed_term){
			var start_unix    = parseInt( $.tools.CurTime() + parseInt(fixed_begin_term*24*3600) );
			var start_date    = $.tools.UnixToDate(start_unix,false);
			var end_unix = parseInt(start_unix + fixed_term*24*3600);
			var end_date = $.tools.UnixToDate(end_unix,false);
			$("#j_use_time_preview").text(start_date+"-"+end_date);
		}

		function _bind_title_change(){
	        $('.j_weixin_card_preview_builder').find('input[name=base_info_title]').unbind('input propertychange');
	        $('.j_weixin_card_preview_builder').find('input[name=base_info_title]').bind('input propertychange',function(){
	        	var _value = $(this).val();
	        	$('#j_title_preview').text(_value);
	        });
		}

		function _bind_cover_title_change(){
	        $('.j_weixin_card_preview_builder').find('input[name=advanced_info_abstract_abstract]').unbind('input propertychange');
	        $('.j_weixin_card_preview_builder').find('input[name=advanced_info_abstract_abstract]').bind('input propertychange',function(){
	        	var _value = $(this).val();
	        	$('#j_cover_title_preview').text(_value);
	        });
		}

		function _bind_custom_cell1_name_change(){
	        $('.j_weixin_card_preview_builder').find('input[name=custom_cell1_name]').unbind('input propertychange');
	        $('.j_weixin_card_preview_builder').find('input[name=custom_cell1_name]').bind('input propertychange',function(){
	        	var _value = $(this).val();
	        	$('.j_custom_cell1_name').text(_value);
	        });
		}

		function _bind_custom_cell1_tips_change(){
	        $('.j_weixin_card_preview_builder').find('input[name=custom_cell1_tips]').unbind('input propertychange');
	        $('.j_weixin_card_preview_builder').find('input[name=custom_cell1_tips]').bind('input propertychange',function(){
	        	var _value = $(this).val();
	        	$('.j_custom_cell1_tips').text(_value);
	        });
		}

		function _bind_advanced_info_use_condition_least_cost(){
	        $('.j_weixin_card_preview_builder').find('input[name=advanced_info_use_condition_least_cost_format]').unbind('input propertychange');
	        $('.j_weixin_card_preview_builder').find('input[name=advanced_info_use_condition_least_cost_format]').bind('input propertychange',function(){
	        	var _value = $(this).val();
	        	if(_value == ""){
	        		_value = "xxx";
	        	}
	        	use_condition_str_arr[0] = "消费满"+_value+"元可用;";

	        	$('#j_use_condition_preview').text(use_condition_str_arr.join(" "));
	        });
		}

		function _bind_advanced_info_use_condition_accept_category(){
	        $('.j_weixin_card_preview_builder').find('input[name=advanced_info_use_condition_accept_category]').unbind('input propertychange');
	        $('.j_weixin_card_preview_builder').find('input[name=advanced_info_use_condition_accept_category]').bind('input propertychange',function(){
	        	var _value = $(this).val();
	        	if(_value == ""){
	        		_value = "xxx";
	        	}
	        	use_condition_str_arr[1] = "适用于"+_value+";";
	        	$('#j_use_condition_preview').text(use_condition_str_arr.join(" "));
	        });
		}

		function _bind_advanced_info_use_condition_reject_category(){
	        $('.j_weixin_card_preview_builder').find('input[name=advanced_info_use_condition_reject_category]').unbind('input propertychange');
	        $('.j_weixin_card_preview_builder').find('input[name=advanced_info_use_condition_reject_category]').bind('input propertychange',function(){
	        	var _value = $(this).val();
	        	if(_value == ""){
	        		_value = "xxx";
	        	}
	        	use_condition_str_arr[2] = "不适用于"+_value+";";
	        	$('#j_use_condition_preview').text(use_condition_str_arr.join(" "));
	        });
		}

		function _on_date_change(){
			// 日期发生选择
			o_document.on('Jt_datepicker_change',function(e,input_id){
				console.log(input_id);
				var begin_date = $(input_id).find('input[name="base_info_date_info_begin_timestamp"]').val();
				var end_date   = $(input_id).find('input[name="base_info_date_info_end_timestamp"]').val();
				$("#j_use_time_preview").text(begin_date+"-"+end_date);
			});

			// 切换有效期
			o_document.on('Jt_builder_form_class_trigger_item_base_info_date_info_type',function(e,date_type){
				if(date_type == "DATE_TYPE_FIX_TIME_RANGE"){
					// 固定时间
					var begin_date = $('input[name="base_info_date_info_begin_timestamp"]').val();
					var end_date   = $('input[name="base_info_date_info_end_timestamp"]').val();
					$("#j_use_time_preview").text(begin_date+"-"+end_date);
				}
				if(date_type == "DATE_TYPE_PERMANENT"){
					// 永久有效
				}
				if(date_type == "DATE_TYPE_FIX_TERM"){
					// 领取后
					var fixed_begin_term = parseInt($('select[name="base_info_date_info_fixed_begin_term"]').val());
					var fixed_term       = parseInt($('select[name="base_info_date_info_fixed_term"]').val());
					if(isNaN(fixed_term)){
						fixed_term = 0;
					}
					_change_use_time_preview_by_fix_ferm(fixed_begin_term,fixed_term);
				}
			});

			// 设置新的日期
			o_document.on('Jt_builder_form_class_trigger_item_base_info_date_info_fixed_begin_term',function(e,fixed_begin_term){
				fixed_begin_term     = parseInt(fixed_begin_term);
				var fixed_term       = parseInt($('select[name="base_info_date_info_fixed_term"]').val());
				if(isNaN(fixed_term)){
					fixed_term = 0;
				}
				_change_use_time_preview_by_fix_ferm(fixed_begin_term,fixed_term);
			});

			o_document.on('Jt_builder_form_class_trigger_item_base_info_date_info_fixed_term',function(e,fixed_term){
				var fixed_begin_term = parseInt($('select[name="base_info_date_info_fixed_begin_term"]').val());
				fixed_term           = parseInt(fixed_term);
				if(isNaN(fixed_term)){
					fixed_term = 0;
				}
				_change_use_time_preview_by_fix_ferm(fixed_begin_term,fixed_term);
			});
		}

		function _on_use_condition_change(){
			// 使用条件
			// [最低消费]是否开启
			o_document.on('Jt_builder_form_class_trigger_item_advanced_info_use_condition_least_cost_is_open',function(e,checkbox_val){
				if(parseInt(checkbox_val) == 1){
					advanced_info_use_condition_least_cost_is_open = 1;
				}else{
					advanced_info_use_condition_least_cost_is_open = 0;
				}
				_change_use_condition();
			});
			// [适用范围]是否开启
			o_document.on('Jt_builder_form_class_trigger_item_advanced_info_use_condition_category_is_open',function(e,checkbox_val){
				console.log("adsfasdf:"+checkbox_val);
				if(parseInt(checkbox_val) == 1){
					advanced_info_use_condition_category_is_open = 1;
				}else{
					advanced_info_use_condition_category_is_open = 0;
				}
				_change_use_condition();
			});
			// [可与其它优惠共享]是否开启
			o_document.on('Jt_builder_form_class_trigger_item_advanced_info_use_condition_can_use_with_other_discount',function(e,checkbox_val){
				if(parseInt(checkbox_val) == 1){
					advanced_info_use_condition_can_use_with_other_discount = 1;
				}else{
					advanced_info_use_condition_can_use_with_other_discount = 0;
				}
				_change_use_condition();
			});
		}

		function _change_use_condition(){
			// 根据不同卡券显示
			if(config.cardType == "cash"){
				console.log("cash :" + advanced_info_use_condition_least_cost_is_open + " " + advanced_info_use_condition_category_is_open);
				if(advanced_info_use_condition_least_cost_is_open == 0 && advanced_info_use_condition_category_is_open == 0){
					$('#j_use_condition_preview_li').hide();
				}else{
					$('#j_use_condition_preview_li').show();
					// 




				}



			}


		}

		return obj;
	}
}
return component;

});