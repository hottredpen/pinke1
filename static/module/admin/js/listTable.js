/**
 * @name 列表操作(排序，修改值，状态切换，批量操作)
 */
;(function($) {
	$.fn.listTable = function(options) {
		var self = this,
			local_url = window.location.search,
			settings = {
				url: $(self).attr('data-acturi')
			}
		if(options) {
			$.extend(settings, options);
		}
		//整理排序
		var params  = local_url.substr(1).split('&');
		var sort,order;
		for(var i=0; i < params.length; i++) {
			var param = params[i];
			var arr   = param.split('=');
			if(arr[0] == 'sort') {
				sort = arr[1];
			}
			if(arr[0] == 'order') {
				order = arr[1];
			}
		}
		//全选反选
    	$(document).on("click",".J_checkall",function(){
			$('.J_checkitem').attr('checked', this.checked);
			$('.J_checkall').attr('checked', this.checked);
    	});
    	//历史排序
		$('span[data-tdtype="order_by"]', $(self)).each(function() {
			if($(this).attr('data-field') == sort) {
				if(order == 'asc') {
					$(this).attr('data-order', 'asc');
					$(this).addClass("sort_asc");
				} else if (order == 'desc') {
					$(this).attr('data-order', 'desc');
					$(this).addClass("sort_desc");
				}
			}
		}).addClass('sort_th');
		//排序
		$('span[data-tdtype="order_by"]', $(self)).on('click', function() {
			var s_name = $(this).attr('data-field'),
				s_order  = $(this).attr('data-order'),
				sort_url = (local_url.indexOf('?') < 0) ? '?' : '';
				sort_url += '&sort=' + s_name + '&order=' + (s_order =='asc' ? 'desc' : 'asc');
				local_url = local_url.replace(/&sort=(.+?)&order=(.+?)$/, '');
			location.href = local_url + sort_url;
			return false;
		});
		// ajax修改
		$('span[data-tdtype="edit"]', $(self)).on('click', function() {
			var o_this  = $(this),
			s_val   = o_this.text(),
			s_name  = o_this.attr('data-field'),
			s_id    = o_this.attr('data-id'),
			width   = o_this.width()+60;
			$('<input type="text" class="form-control" value="'+s_val+'" />').width(width).focusout(function(){
				$(this).prev('span').show().text($(this).val());
				if($(this).val() != s_val) {
		            $.ajax({
			            type : 'post', 
			            url  : settings.url, 
			            data : {id : s_id, field : s_name , val : $(this).val()},
			            success: function(res) {
			                if(res.status == 1){

			                }else{
			                	o_this.text(s_val);
			                	cpk_alert(res.msg);
			                }
			            }
		        	});
				}
				$(this).remove();
			}).insertAfter($(this)).focus().select();
			$(this).hide();
			return false;
		});
		// 切换(如状态切换)
		$('img[data-tdtype="toggle"]', $(self)).on('click', function() {
			var img    = this,
				s_val  = ($(img).attr('data-value'))== 0 ? 1 : 0,
				s_name = $(img).attr('data-field'),
				s_id   = $(img).attr('data-id'),
				s_src  = $(img).attr('src');
	            $.ajax({
		            type : 'post', // 提交方式 get/post
		            url  : settings.url, // 需要提交的 url
		            data : {id : s_id, field : s_name , val : s_val},
		            success: function(result) { // data 保存提交后返回的数据，一般为 json 数据
						if(result.status == 1) {
							if(s_src.indexOf('disabled')>-1) {
								$(img).attr({'src':s_src.replace('disabled','enabled'),'data-value':s_val});
							} else {
								$(img).attr({'src':s_src.replace('enabled','disabled'),'data-value':s_val});
							}
						}else{
							cpk_error_tip(result.msg);
						}
		            }
	        	});
			return false;
		});

		//批量操作
		$('input[data-tdtype="batch_action"]').on('click', function() {

			var btn = this;
			if($('.J_checkitem:checked').length == 0){
                cpk_alert("请选择具体项");
				return false;
            }
			var ids = '';
			$('.J_checkitem:checked').each(function(){
				ids += $(this).val() + ',';
			});
			ids = ids.substr(0, (ids.length - 1));
			var uri     = $(btn).attr('data-uri') + '/' + $(btn).attr('data-name') + '/' + ids,
				msg     = $(btn).attr('data-msg'),
				acttype = $(btn).attr('data-acttype'),
				title   = ($(btn).attr('data-title') != undefined) ? $(this).attr('data-title') : "确认";
			if(msg != undefined){
				$.dialog({
					id:'confirm',
					title:title,
					width:200,
					padding:'10px 20px',
					lock:true,
					content:msg,
					ok:function(){
						action();
					},
					cancel:function(){}
				});
			}else{
				action();
			}
			function action(){
				if(acttype == 'ajax_form'){
					var did = $(btn).attr('data-id'),
						dwidth = parseInt($(btn).attr('data-width')),
						dheight = parseInt($(btn).attr('data-height'));
					$.dialog({
						id:did,
						title:title,
						width:dwidth ? dwidth : 'auto',
						height:dheight ? dheight : 'auto',
						padding:'',
						lock:true,
						ok:function(){
							var info_form = this.dom.content.find('#info_form');
							if(info_form[0] != undefined){
								info_form.submit();
								return false;
							}
						},
						cancel:function(){}
					});

					$.getJSON(uri, function(result){
						if(result.status == 1){
							$.dialog.get(did).content(result.data);
						}
					});
				}else if(acttype == 'ajax'){

					$.getJSON(uri, function(result){

						if(result.status == 1){
							$.ftxia.tip({content:result.msg});
							window.location.reload();
						}else{
							$.ftxia.tip({content:result.msg, icon:'error'});
						}
					});
				}else{
					location.href = uri;
				}
			}
		});
	};
})(jQuery);