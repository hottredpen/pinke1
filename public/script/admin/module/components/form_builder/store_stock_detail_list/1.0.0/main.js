define(['jquery'],function($){

var component = {
	getName : function(){
		return "store_stock_detail_list@1.0.0";
	},
	getAuthKey : function(){
		return "c16a5320fa475530d958-28fd0fbd336517d0d958-b4b1471c522828731f1a-d6b8c610dce8d92bf35be03826fcfffe"; // 检测是授权组件
	},
	createObj : function(){
		var obj = {};
		var o_document = $(document);


        var config = {
                "builderDiv"       : "",
                "formItemName"     : "",
                "stockMainType"  : 1 // 1为入库，0为出库      
        };

		obj.init = function(userconfig){
			config       = $.extend({}, config, userconfig);
			_init_something();
			_onDocumentBtn();
		}

		function _init_something(){

		}

		function _onDocumentBtn(){

			$(document).on('click',config.builderDiv + ' .J_remove_product_item',function(){
				var _id = $(this).attr('data-id');
				$('.j_product_item_id_'+_id).remove();
			});


		    // 弹出框显示页面
		    $(config.builderDiv + ' .J_component_layer_load_list_for_choose_ids').on('click',function () {


		    	if(config.stockMainType == 1){
			    	var supplier_id = $('input[name=supplier_id]').val();
			    	if(typeof supplier_id == 'undefined' || parseInt(supplier_id) == 0 || supplier_id == ''){
			    		$.custom.alert('请选择供应商');
			    		return;
			    	}
		    	}else{
			    	var customer_id = $('input[name=customer_id]').val();
			    	if(typeof customer_id == 'undefined' || parseInt(customer_id) == 0 || customer_id == ''){
			    		$.custom.alert('请选择客户');
			    		return;
			    	}
		    	}


		    	// 选择仓库
		    	var store_id = $('input[name=store_id]').val();
		    	if(typeof store_id == 'undefined' || parseInt(store_id) == 0 || store_id == ''){
		    		$.custom.alert('请选择仓库');
		    		return;
		    	}
		    	var company_id = parseInt($('input[name=company_id]').val());

				var $url         = $(this).attr('data-url');
				var $product_url = $(this).attr('data-product-url');
				var $title       = $(this).attr('data-title');
				var $width       = $(this).attr('data-width');
				var $height      = $(this).attr('data-height');
		        if(typeof $width == 'undefined'){
		            $width = "80%";
		        }
		        if(typeof $height == 'undefined'){
		            var area_arr = [$width,"auto"];
		        }else{
		            var area_arr = [$width,$height];
		        }

		    	if(config.stockMainType == 1){
			    	var url_params = "?&supplier_id=" + supplier_id + "&company_id="+company_id+"&_without_layout=1";
		    	}else{
			    	var url_params = "?&store_id=" + store_id + "&company_id="+company_id+"&_without_layout=1";
		    	}

			    layer.open({
			      	type: 2,
			      	title: $title,
			      	shadeClose: true,
			      	shade: false,
			      	maxmin: true, //开启最大化最小化按钮
			      	area: area_arr,
			      	content: $url+ url_params,
				  	btn: ['确认', '取消'], //只是为了演示
				    yes:function(index,layero){
        				var that_document = $(layero).find("iframe")[0].contentWindow.document;
			            var _ids_arr = [];
			            $(that_document).find('input.ids').each(function(i,ele){
			                if($(this).is(':checked')){
			                    _ids_arr.push($(this).val());
			                }
			            });
			            var _ids = _ids_arr.join(",");
			            showChooseToHtmlList($product_url,_ids);
			            layer.close(index);
     				}
			    });
		        return false;
		    });


		}
		function showChooseToHtmlList(product_url,ids){
			if(typeof ids == 'undefined' || ids == ''){
				return;
			}
			console.log(ids)
			if(typeof product_url == 'undefined' || product_url == ''){
				return;
			}
	        $.ajax({
	            type : 'get',
	            url  : product_url,
	            data : {ids:ids},
	            success: function(res) {
	                if(res.code==200){
	                    
	                	res.data.map(function(_val){
	                		if(config.stockMainType == 1){
	                			enter_html_append(_val);
	                		}else{
	                			outer_html_append(_val);
	                		}
	                	});

	                }else{
	                    $.custom.alert(res.msg)
	                }
	            }
	        });
		}
		function enter_html_append(_val){
    		$(".j_append_choose_product").append("<tr class='j_product_item_id j_product_item_id_"+_val.id+"'>\
    			<td class='text-center'><input name='product_id[]' value='"+_val.id+"' type='hidden'>"+_val.id+"</td>\
    			<td class='text-center'><input name='product_no[]' value='"+_val.num+"' type='hidden'>"+_val.num+"</td>\
    			<td class='text-center'>"+_val.name+"</td>\
    			<td class='text-center'>"+_val.size+"</td>\
    			<td class='text-center'><input name='enter_num_of_piece[]' value='"+_val.box_quantity+"'></td>\
    			<td class='text-center'><input name='enter_num[]'></td>\
    			<td class='text-center'><input name='product_piece[]'></td>\
    			<td class='text-center'><input name='product_price[]'></td>\
    			<td class='text-center'><input name='product_date[]'></td>\
    			<td class='text-center'><a class='J_remove_product_item' data-id='"+_val.id+"'>移除</a></td>\
    		</tr>");
		}
		function outer_html_append(_val){
    		$(".j_append_choose_product").append("<tr class='j_product_item_id j_product_item_id_"+_val.id+"'>\
    			<td class='text-center'><input name='product_id[]' value='"+_val.product_id+"' type='hidden'>"+_val.product_id+"</td>\
    			<td class='text-center'><input name='product_no[]' value='"+_val.product_no+"' type='hidden'>"+_val.product_no+"</td>\
    			<td class='text-center'>"+_val.product_name+"</td>\
    			<td class='text-center'>"+_val.remain_num+"</td>\
    			<td class='text-center'>"+_val.enter_num_of_piece+"</td>\
    			<td class='text-center'><input name='outer_num[]'></td>\
    			<td class='text-center'><input name='product_piece[]'></td>\
    			<td class='text-center'><input name='product_price[]'></td>\
    			<td class='text-center'>"+_val.product_date+"</td>\
    			<td class='text-center'><a class='J_remove_product_item' data-id='"+_val.id+"'>移除</a></td>\
    		</tr>");
		}
		return obj;
	}
}

return component;

});