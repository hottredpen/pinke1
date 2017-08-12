define(['jquery'],function($){

var component = {
	createObj : function(){
		var obj = {};
		var o_document = $(document);

        var config = {
                "builderDiv"       : "",
                "formItemName"     : "",
                "tellInputId"      : "",
        };

		obj.init = function(userconfig){
			console.log("builder checkbox");
			config       = $.extend({}, config, userconfig);
			_init_something();
			_onDocumentBtn();
		}

		function _init_something(){
            // 对已经选中的进行一次触发
            _foreach_value_for_trigger();
		}

		function _onDocumentBtn(){
            // 监听按钮
            $(config.builderDiv + ' .J_form_item_checkbox').on('click',function(){
                _foreach_value_for_trigger();
            });
		}

        function _foreach_value_for_trigger(){
            // 遍历所有本组件内的checkbox值
            var all_values_arr = [];
            var all_values_str = "";
            $(config.builderDiv + ' .J_form_item_checkbox').each(function(i,ele){
                if($(ele).is(":checked")){
                    all_values_arr.push($(ele).val());
                }
            });
            // 排序，组合
            all_values_arr.sort();
            all_values_str = all_values_arr.join(",");
            // console.log(all_values_str);
            $(config.tellInputId).val(all_values_str);
            $(document).trigger('Jt_builder_form_class_trigger_item_'+config.formItemName,[all_values_str]);
            console.log("触发"+'Jt_builder_form_class_trigger_item_'+config.formItemName+':'+all_values_str);
        }

		return obj;
	}
}

return component;

});