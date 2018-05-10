define(['jquery'],function($){

var component = {
	getName : function(){
		return "switch@1.0.0";
	},
	getAuthKey : function(){
		return "98f13708210194c47568-8f03ad12c6cad604d5c0-b4b147bc522828731f1a-61ad2057e9a3fa96e4c38b7da6928c49"; // 检测是授权组件
	},
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
                $(this).parent().find('.j_form_switch_real_value').val(_value);
            });
		}

		return obj;
	}
}

return component;

});