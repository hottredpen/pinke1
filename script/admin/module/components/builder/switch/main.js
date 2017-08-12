define(['jquery'],function($){

var component = {
	createObj : function(){
		var obj = {};
		var o_document = $(document);

        var config = {
                "builderDiv"       : "",
                "formItemName"     : "",
        };

		obj.init = function(userconfig){
			console.log("builder switch");
			config       = $.extend({}, config, userconfig);
			_init_something();
			_onDocumentBtn();
		}

		function _init_something(){

		}

		function _onDocumentBtn(){
            // 监听按钮
            $(config.builderDiv + ' .J_form_item_switch').on('click',function(){
                var _value = $(this).val() == 1 ? 0 : 1;
                $(this).val(_value);
            });
		}

		return obj;
	}
}

return component;

});