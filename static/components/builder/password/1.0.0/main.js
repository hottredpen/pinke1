define(['jquery'],function($){

var component = {
	getName : function(){
		return "password@1.0.0";
	},
	getAuthKey : function(){
		return "6f4922f45568161a8cdf-7658d0d2112eb26ad6d9-b4b147bc523828731f1a-9daa6bc30102a4753a1bca06bbf44744"; // 检测是授权组件
	},
	createObj : function(){
		var obj = {};
		var o_document = $(document);

        var config = {
                "builderDiv"       : "",
                "formItemName"     : "",
        };

		obj.init = function(userconfig){
			config       = $.extend({}, config, userconfig);
			_init_something();
			_onDocumentBtn();
		}

		function _init_something(){

		}

		function _onDocumentBtn(){

		}

		return obj;
	}
}

return component;

});