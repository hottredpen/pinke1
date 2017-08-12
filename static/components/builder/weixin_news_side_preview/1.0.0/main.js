define(['jquery'],function($,ueditor){

var cpk_weixin_newspublish = {
	getName : function(){
		return "weixin_news_side_preview@1.0.0";
	},
	getAuthKey : function(){
		return "6ea9ab1baa0efb9e1909-5c7d57051b73466e1989-b4b148bc522828732f1a-dade9b5c59c6961635a65c5b900a2c1a"; // 检测是授权组件
	},
	createObj : function(){
		var obj              = {};
		var o_document       = $(document);
		var current_max_item_index = 0;
		var show_index_arr   = [];

		var site_map = [];

		obj.init = function(){
			init_something();
			onDocumentBtn();

		}

		obj.removeArrayItem = function(arr,val){
			arr.splice($.inArray(val,arr),1);
			return arr;
		}

		function init_something(){
			_init_side_cover_pic_show();
			_init_site_map();
			_init_detail_show_items();
			_init_builder_show();
			_init_title_bind();
		}

		function onDocumentBtn(){
			sideItemClick();
			addNewItem();
			// onImageUpload();  // 老的图片上传监听
			onMaterialImageChoosed(); // 新的图片选择监听
			// onDeleteImage();  // 老的图片上传删除监听
			onChangeSideItemSite();
			onMaterialImageChoosedDelete(); // 新的图片上传删除监听
			onDeleteNews();
		}
		function _init_side_cover_pic_show(){
			for (var i = 0; i < 8; i++) {
				if($('#j_material_box_name_image_reply_type_'+i).find('.j_material_box_media_id_input').val() == ''){
					$('.j_thumb_fileid_reply_type_'+i).css('background-image','url(/static/components/builder/weixin_card_preview/images/no_cover_pic.png)');
				}
			}
		}

		function _init_site_map(){
			$('.j_sidenews_item').each(function(i){
				site_map[i] = parseInt($(this).attr('data-index'));
			});
		}

		function _init_detail_show_items(){

			var _show_items_indexids = $('input[name=show_items_indexids]').val();
			var _show_items_indexids_arr = _show_items_indexids.split(",");
			console.log(_show_items_indexids_arr);
			if(_show_items_indexids_arr.length > 0){
				current_max_item_index = parseInt(_show_items_indexids_arr.length - 1 );
			}else{
				current_max_item_index = 0;
			}
			
			console.log(current_max_item_index);



			for (var i = 0; i < parseInt(current_max_item_index + 1); i++) {
				show_index_arr.push(site_map[i]);
			}
			_change_set_post_items_indexids();
		}

		function _init_builder_show(){
			// 获取右侧的 editing 状态的indexid
			var curr_builer_show_indexid = parseInt($('.j_sidenews_item.editing').attr('data-index'));

			$('.j_newspublishitem_builder_'+curr_builer_show_indexid).removeClass('hidden');
			removeSideNewsHiddenClass();
		}

		function _init_title_bind(){
			for (var i = 0; i < 8; i++) {
				_title_change_trigger(i);
				_init_bind_title_change(i);
			}
		}

		function _change_set_post_items_indexids(){
			


			var edit_index_id_arr = [];
			$.each(site_map,function(i,val){
				edit_index_id_arr.push(val);
			});
			var _val = $.unique(edit_index_id_arr).join(',');

			var _show_val = $.unique(show_index_arr).join(',');



			$('input[name=show_items_indexids]').val(_show_val);
			$('input[name=post_items_indexids]').val(_val);
		}


		function sideItemClick(){
		    o_document.on('click','.J_choose_this_side_item',function(){
		        var item_index_id = $(this).attr('data-index');
		        _click_item_do_something(item_index_id);
		    });
		}

		function _click_item_do_something(item_index_id){
			// 排除删除时的触发 ， 检测是否在显示的数组内
			if( $.inArray( parseInt(item_index_id),show_index_arr) < 0 ){
				return ;
			}

	        var o_sidenews_item = $('.j_sidenews_item_'+ item_index_id);

	        // add editing
	        $('.j_sidenews_item').removeClass('editing');
	        o_sidenews_item.addClass('editing');

	        // left content hidden and show
	        $('.j_newspublishitem_builder').addClass('hidden');
	        $('.j_newspublishitem_builder_'+item_index_id).removeClass('hidden');

	        // for ueditor bug
	        $('.edui-editor').css('width',$('.edui-editor').parent().css('width'));
	        $('.edui-editor-iframeholder').css({'height':500,'width':400});

	        // sync title
	        _title_change_trigger(item_index_id);
		}


		function addNewItem(){
		    o_document.on('click','.J_add_news_item',function(){
		    	console.log(site_map);
		    	console.log(show_index_arr);
		    	if(current_max_item_index >= 7){
		    		return;
		    	}
		    	current_max_item_index ++;
		    	show_index_arr.push(site_map[current_max_item_index]);
		    	_change_set_post_items_indexids();

		    	removeNextSideNewsHiddenClass();
		    	_title_change_trigger(current_max_item_index);
		    	_click_item_do_something(current_max_item_index);
		    });
		}

		function onChangeSideItemSite(){
			o_document.on('click','.J_sidenews_down',function(){
				var o_down_indexid = parseInt($(this).attr('data-index'));
				var o_down         = $('.j_sidenews_item_'+o_down_indexid);
				var down_index;
				var up_index;
				$.each(site_map,function(i,val){
					if(val == o_down_indexid){
						down_index = i;
						up_index   = parseInt(i + 1);
					}
				});
				var o_up_indexid = site_map[up_index];
				var o_up         = $('.j_sidenews_item_'+o_up_indexid);

				change_site_down_up(o_down,o_up,down_index,up_index,o_down_indexid,o_up_indexid);
			});

			o_document.on('click','.J_sidenews_up',function(){
				var o_up_indexid = parseInt($(this).attr('data-index'));
				var o_up         = $('.j_sidenews_item_'+o_up_indexid);
				var down_index;
				var up_index;
				$.each(site_map,function(i,val){
					if(val == o_up_indexid){
						up_index     = i;
						down_index   = parseInt(i - 1);
					}
				});
				var o_down_indexid = site_map[down_index];
				var o_down         = $('.j_sidenews_item_'+o_down_indexid);

				change_site_down_up(o_down,o_up,down_index,up_index,o_down_indexid,o_up_indexid);
			});
		}

		function onDeleteNews(){
			o_document.on('click','.J_sidenews_del',function(){
				var o_del_indexid = parseInt($(this).attr('data-index'));

				var del_index;
				var pre_index;
				$.each(site_map,function(i,val){
					if(val == o_del_indexid){
						del_index   = i;
						pre_index   = parseInt(i - 1);
					}
				});
				// 将当前选中状态移到上一个对象中
				var o_pre_indexid = site_map[pre_index];
				_click_item_do_something(o_pre_indexid);

				_site_map_del_item(o_del_indexid);

				// 将里面的内容清空
				_clear_item_content(o_del_indexid);


			});
		}

		function _clear_item_content(o_del_indexid){
			// 将标题清空
			$('input[name=title_'+o_del_indexid+']').val('');
			_title_change_trigger(o_del_indexid);

			// 将作者清空
			$('input[name=author_'+o_del_indexid+']').val('');

			// 将图片清空
			$('.j_newspublishitem_builder_'+o_del_indexid).find('.u-imgboxupload-removebtn').trigger('click');

			// 将摘要清空
			$('textarea[name=digest_'+o_del_indexid+']').val('');

			// 将内容清空
			$(document).trigger('clear_UEditor_content_'+o_del_indexid);

			// 清空原文链接
			$('input[name=from_url_'+o_del_indexid+']').val('');

		}

		function _site_map_del_item(o_del_indexid){
			current_max_item_index --;
			show_index_arr =  obj.removeArrayItem(show_index_arr , o_del_indexid);
			_change_set_post_items_indexids();
			_change_site_map_by_del_indexid(o_del_indexid);
			// 将该对象隐藏
			$('.j_sidenews_item_' + o_del_indexid).addClass('hidden');
			$('.j_newspublishitem_builder_' + o_del_indexid).addClass('hidden');
		}

		function _change_site_map_by_del_indexid(o_del_indexid){
			// 将删除的排到最后面
			obj.removeArrayItem(site_map , o_del_indexid);
			site_map.push(o_del_indexid);
			// 同时将节点也移到最后面
			var o_last_indexid  = site_map[6];
			var o_last_item     = $('.j_sidenews_item_'+o_last_indexid);
			var o_del_item      = $('.j_sidenews_item_'+o_del_indexid);
			o_del_item.insertAfter(o_last_item);
		}



		function change_site_index(down_index,up_index,o_down_indexid,o_up_indexid){
			site_map[down_index] = parseInt(o_up_indexid);
			site_map[up_index]   = parseInt(o_down_indexid);
			_change_set_post_items_indexids();
		}


		function change_site_down_up(o_down,o_up,down_index,up_index,o_down_indexid,o_up_indexid){
			
			if(current_max_item_index == 0){
				return;
			}
			if(current_max_item_index == down_index){
				return;
			}

			o_up.insertBefore(o_down);    //b插到a的前面
			change_site_index(down_index,up_index,o_down_indexid,o_up_indexid);

			if(down_index == 0){
				o_down.find('.item-first-content').addClass('hidden');
				o_down.find('.item-content').removeClass('hidden');
			}
			if(up_index == 1){
				o_up.find('.item-first-content').removeClass('hidden');
				o_up.find('.item-content').addClass('hidden');
			}
		}


		function _init_bind_title_change(item_index_id){
	        $('.j_newspublishitem_builder_'+item_index_id).find('input[name=title_'+item_index_id+']').unbind('input propertychange');
	        $('.j_newspublishitem_builder_'+item_index_id).find('input[name=title_'+item_index_id+']').bind('input propertychange',function(){
	        	if($(this).val() != ''){
	        		$('.j_sidenews_title_'+item_index_id).text($(this).val());
	        	}else{
	        		_title_change_trigger(item_index_id);
	        	}
	        });
		}

		function _title_change_trigger(item_index_id){
			var init_value = $('input[name=title_'+item_index_id+']').val();
			if(typeof init_value == 'undefined' || init_value == ''){
				init_value = '标题';
			}
			$('.j_sidenews_title_'+item_index_id).text(init_value);
		}

		function removeSideNewsHiddenClass(){
			$.each(show_index_arr,function(i,val){
				$('.j_sidenews_item_'+val).removeClass('hidden');
			});
		}

		function removeNextSideNewsHiddenClass(){
			// 获取最新的last_indexid
			var last_indexid = site_map[current_max_item_index];
			$('.j_sidenews_item_'+last_indexid).removeClass('hidden');

		}

		function onImageUpload(){
			o_document.on('cpk_imgbox_upload_callback',function(e,data,file){
				$('.j_sidenews_item.editing').find('.thumb').css('background-image','url('+data.befor_url+')');
				$('.j_sidenews_item.editing').find('.thumb').addClass('j_thumb_fileid_'+data.fileid)
			});
		}

		function onMaterialImageChoosed(){
			o_document.on('Jt_material_image_choosed_callback',function(e,imageurl,image_media_id,trigger_name){
				$('.j_sidenews_item.editing').find('.thumb').css('background-image','url('+imageurl+')');
				$('.j_sidenews_item.editing').find('.thumb').addClass('j_thumb_fileid_'+trigger_name)
			});
		}



		function onDeleteImage(){
			o_document.on('cpk_imgbox_upload_delete_callback',function(e,fileid){
				$('.j_sidenews_item').find('.j_thumb_fileid_'+fileid).css('background-image','url(/static/module/weixin/images/no_cover_pic.png)');
			});
		}

		function onMaterialImageChoosedDelete(){
			o_document.on('Jt_material_image_choosed_delete_callback',function(e,image_media_id,trigger_name){
				console.log(image_media_id);
				console.log(trigger_name);
				$('.j_sidenews_item.editing').find('.j_thumb_fileid_'+trigger_name).css('background-image','url(/static/components/builder/weixin_news_side_preview/images/no_cover_pic.png)');
			});
		}

		return obj;
	}
}
return cpk_weixin_newspublish;

});