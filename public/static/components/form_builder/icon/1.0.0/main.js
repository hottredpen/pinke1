define(['jquery'],function($){

var component = {
	getName : function(){
		return "icon@1.0.0";
	},
	getAuthKey : function(){
		return "a5bfc9e07964f8dddeb9-b6aea7af56564fd2ae29-14b148bc622828731f1a-889b5a84824021af52720295d038ffa9"; // 检测是授权组件
	},
	createObj : function(){
		var obj = {};
		var o_document = $(document);

        var config = {
                "builderDiv"       : "",
                "formItemName"     : "",
                'chooseTrigger'    : ".J_component_choose_this_icon",
                'clearTrigger'     : ".J_component_clear_this_icon",
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
				if(key == 'show_icon'){
					$(config.builderDiv + ' #j_form_item_val_'+config.formItemName).val(val);
					$(config.builderDiv + ' #j_dynamic_show_icon_'+config.formItemName).attr('class',val);
				}
			});
		}

		function _onDocumentBtn(){

			$(config.builderDiv + ' .J_component_clear_this_icon').on('click',function(){
				$(this).parent().find('input').val('');
				$(config.builderDiv + ' #j_dynamic_show_icon_'+config.formItemName).attr('class','fa fa-plus-circle'); // 默认值
			});


		    $(config.builderDiv + ' .J_component_layer_load_icon_for_choose_one').on('click',function () {

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
		}

		return obj;
	}
}

return component;

});