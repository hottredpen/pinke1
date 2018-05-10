define(['jquery'],function($){

var component = {
	getName : function(){
		return "text_dialog_with_id@1.0.0";
	},
	getAuthKey : function(){
		return "6364d3f0f495b6ab9dcf-9ac05befca7d64a9e3ab-b4b147bc522828731f1a-cd4ae1a6727d92eb369853c554387642"; // 检测是授权组件
	},
	createObj : function(){
		var obj = {};
		var o_document = $(document);

        var config = {
        		"parentBuilderId"  : "",
                "builderDiv"       : "",
                "formItemName"     : "",
                "showAttr"         : "name",
                "hiddenAttr"       : "id",
                'chooseTrigger'    : ".J_component_choose_this_supplier"
        };

		obj.init = function(userconfig){
			config       = $.extend({}, config, userconfig);
			_init_something();
			_onDocumentBtn();
		}

		function _init_something(){

		}

		function getValuesToText(attr_data){
			$.each(attr_data,function(key,val){
				// 将本次 选中的值广播出去 todo 目前需求好像不需要
				
				// console.log(config.formItemName+"."+key+":"+val);
				// console.log(config.parentBuilderId+"发送");
				// $(config.parentBuilderId).trigger(config.formItemName+"."+key,val);

				if(key == config.showAttr){
					$(config.builderDiv + ' #j_form_item_text_for_show_'+config.formItemName).val(val);
				}
				if(key == config.hiddenAttr){
					$(config.builderDiv + ' #j_form_item_val_'+config.formItemName).val(val);
				}
			});
		}

		function _onDocumentBtn(){

		    $(config.builderDiv + ' .J_component_layer_load_list_for_choose_one').on('click',function () {

				var $url         = $(this).attr('data-url');
				var $title       = $(this).attr('data-title');
				var $width       = $(this).attr('data-width');
				var $height      = $(this).attr('data-height');
		        if(typeof $width == 'undefined'){
		            $width = "80%";
		        }
		        if(typeof $height == 'undefined'){
		            var area_arr = [$width,"80%"];
		        }else{
		            var area_arr = [$width,$height];
		        }

			    var url_params = "?&_without_layout=1";

			    layer.open({
			      	type: 2,
			      	title: $title,
			      	shadeClose: true,
			      	shade: false,
			      	maxmin: true, //开启最大化最小化按钮
			      	area: area_arr,
			      	content: $url+ url_params,
				  	success: function(layero, index){
					　　	var that_document = $(layero).find("iframe")[0].contentWindow.document;
        				$(that_document).on('click',config.chooseTrigger,function(){
        					getValuesToText($(this).data());
        					layer.close(index);
        				});
					}
			    });
		        return false;
		    	
		    });

		    // J_component_choose_this_supplier

		}

		return obj;
	}
}

return component;

});