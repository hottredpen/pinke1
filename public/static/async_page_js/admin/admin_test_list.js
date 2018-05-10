define(['jquery'],function($){

var page_trigger = {
	createObj : function(){
		var obj = {};
		var admin_list_ids_arr = [];
		var test_data_assert_index_arr = [];
		var sence_id;
		var uuid;
	
		var max_data_index= 0;

        var config = {
                "builderDiv"       : "",
        };

		obj.init = function(userconfig){
			// console.log("talk_one_by_one init");
			config       = $.extend({}, config, userconfig);
			_init_something();
			_onDocumentBtn();
		}

		// 初始化，将检测内容添加到数组内
		function _init_something(){
			$('.j_task_id_for_test').each(function(i,ele){
				admin_list_ids_arr.push($(this).attr('data-local-no'));
			});

			$('.j_task_data_for_assert').each(function(){
				test_data_assert_index_arr.push($(this).attr('data-index'));
			});

			console.log(admin_list_ids_arr);
		}

		function _onDocumentBtn(){
			// J_init_post_data

			$('.J_init_post_data').on('click',function(){
				var id = $(this).attr('data-id');
			    $.ajax({
			        type : 'post',
			        url  : '/admin/adminTest/init_post_data',
			        data : {id:id},
			        success: function(res) {
			            if(res.code==200){
			            	$.custom.msg(res.msg);
			            }else{
			                $.custom.error_msg(res.msg);
			            }
			        }
			    });
			});

			$('.J_logging_test_origin_data').on('click',function(){
				var id = $(this).attr('data-id');
			    $.ajax({
			        type : 'post',
			        url  : '/admin/adminTest/logging_origin_data',
			        data : {id:id},
			        success: function(res) {
			            if(res.code==200){
			            	$.custom.msg(res.msg);
			            }else{
			                $.custom.alert(res.msg);
			            }
			        }
			    });
			});

			$('.J_logging_test_update_data').on('click',function(){
				var id = $(this).attr('data-id');
			    $.ajax({
			        type : 'post',
			        url  : '/admin/adminTest/logging_update_data',
			        data : {id:id},
			        success: function(res) {
			            if(res.code==200){
			            	$.custom.msg(res.msg);
			            }else{
			                $.custom.alert(res.msg);
			            }
			        }
			    });
			});

			$('.J_show_test_assert_change').on('click',function(){
				var id = $(this).attr('data-id');
			    $.ajax({
			        type : 'post',
			        url  : '/admin/adminTest/show_test_assert_change',
			        data : {id:id},
			        success: function(res) {
			            if(res.code==200){
			            	// $.custom.msg(res.msg);
			            	// 在底部生成新的数据断言列表
			            	$('.j_assert_data_list').html('');
			            	$.each(res.data.assert_data,function(i,ele){
			            		max_data_index = i;
			            		$('.j_assert_data_list').append("<li class='j_task_data_for_assert ' data-index='"+i+"'>\
			            					<div class='col-md-3'>【"+ele.table_name+"】"+ele.update_rule_info+"</div>\
			            					<div class='col-md-3'>"+ele.origin_field_value+"</div>\
			            					<div class='col-md-3'>"+ele.update_field_value+"</div>\
			            					<div class='j_test_data_assert_status_"+i+" j_test_data_assert_status col-md-3'></div>\
			            		</li>");
			            	});
			            }else{
			                $.custom.alert(res.msg);
			            }
			        }
			    });
			});

			$('.J_assert_change_is_passed').on('click',function(){
				var id = $(this).attr('data-id');
				$('.j_test_data_assert_status').html('');
				$('.j_test_data_assert_info').html('');
				_ajax_assert_change_is_passed(id,0);

			});
			$('.J_start_admin_test').on('click',function(){
				var id = $(this).attr('data-id');
				$('.j_test_assert_status').html('');
				$('.j_test_assert_info').html('');
				_ajax_test_action(id,0);
			});
		}

		function _ajax_assert_change_is_passed(id,_index){
			$.ajax({
			    type : 'post',
			    url  : '/admin/adminTest/assert_change_is_passed',
			    data : {id:id,assert_index:_index},
			    success: function(res) {
			        if(res.code==200){
			        	// $.custom.msg(res.msg);
		            	if(res.data.assert_status==1){
		            		$('.j_test_data_assert_status_'+_index).html('<span class="label label-success">success</span>');
		            	}else{
		            		$('.j_test_data_assert_status_'+_index).html('<span class="label label-danger">failed</span>');
		            	}
		            	if(max_data_index > _index){
		            		_index ++;
		            		_ajax_assert_change_is_passed(id,_index);
		            	}
			        }else{
			            $.custom.alert(res.msg);
			        }
			    }
			});
		}


		function _ajax_test_action(test_id,_index){
			var _local_no = admin_list_ids_arr[_index];
			if(_local_no != ""){
			    $.ajax({
			        type : 'post',
			        url  : '/admin/adminTest/start_task_by_local_no',
			        data : {local_no:_local_no,test_id:test_id},
			        success: function(res) {
			            if(res.code==200){
			            	if(res.assert_status==1){
			            		$('.j_test_assert_status_'+_local_no).html('<span class="label label-success">success</span>');
			            		$('.j_test_assert_info_'+_local_no).html(res.assert_info);
			            	}else{
			            		$('.j_test_assert_status_'+_local_no).html('<span class="label label-danger">failed</span>');
			            		$('.j_test_assert_info_'+_local_no).html(res.assert_info);
			            	}
			            	console.log(_index + "---" + (admin_list_ids_arr.length -1) );
			            	if( _index < (admin_list_ids_arr.length -1) ){
			            		_index++;
			            		_ajax_test_action(test_id,_index);
			            	}else{
			            		if($('input[name=insertdb]').is(':checked')){
			            			console.log(_local_no)
				            		_ajax_test_ok_action(test_id,_local_no);
				            		console.log('start okpost');
			            		}
			            	}
			            }else{
			                $.custom.alert(res.msg);
			            }
			        }
			    });
			}
		}
		function _ajax_test_ok_action(test_id,local_no){
		    $.ajax({
		        type : 'post',
		        url  : '/admin/adminTest/start_ok_task_by_local_no',
		        data : {local_no:local_no,test_id:test_id},
		        success: function(res) {
		            if(res.code==200){
		            	if(res.assert_status==1){
		            		$('.j_test_assert_status_0').html('<span class="label label-success">success</span>');
		            		$('.j_test_assert_info_0').html(res.assert_info);
		            	}else{
		            		$('.j_test_assert_status_0').html('<span class="label label-danger">failed</span>');
		            		$('.j_test_assert_info_0').html(res.assert_info);
		            	}
		            }else{
		                $.custom.alert(res.msg);
		            }
		        }
		    });
		}

		return obj;
	}
}

return page_trigger;

});