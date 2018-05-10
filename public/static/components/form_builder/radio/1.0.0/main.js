define(['jquery'],function($){

var component = {
    getName : function(){
        return "radio@1.0.0";
    },
    getAuthKey : function(){
        return "1f0e3dad99908345f743-9c3ce93643ed68553a43-b4b147bc522829731f1a-386f95b9c16e55ad21c2687fa8db607e"; // 检测是授权组件
    },
	createObj : function(){
		var obj = {};
		var o_document = $(document);

        var config = {
                "builderDiv"       : "",
                "formItemName"     : "",
                "tellInputId"      : "",
        };

		obj.init = function(userconfig){
			console.log("builder radio");
			config       = $.extend({}, config, userconfig);
			_init_something();
			_onDocumentBtn();
		}

		function _init_something(){
            // 对已经选中的进行一次触发
            $(config.builderDiv + ' .J_form_item_radio').each(function(i,ele){
                if($(ele).is(":checked")){
                    $(document).trigger('Jt_builder_form_class_trigger_item_'+config.formItemName,[$(ele).val()]);
                }
            });
		}

		function _onDocumentBtn(){
            // 监听按钮
            $(config.builderDiv + ' .J_form_item_radio').on('click',function(){
                $(config.tellInputId).val($(this).val());
                if($(this).is(":checked")){
                    $(document).trigger('Jt_builder_form_class_trigger_item_'+config.formItemName,[$(this).val()]);
                    console.log("触发"+'Jt_builder_form_class_trigger_item_'+config.formItemName+':'+$(this).val());
                }
            });

            // 监听变量事件
            $(document).on('Jt_each_radio_checked_trigger_event_[this]',function(e,div_obj_name){
                if(config.builderDiv == div_obj_name){
                    // console.log('监听到'+'Jt_each_radio_checked_trigger_event_'+config.builderDiv);
                    _init_something();
                }
            });
		}

		return obj;
	}
}

return component;

});