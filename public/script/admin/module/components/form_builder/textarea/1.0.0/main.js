define(['jquery'],function($){

var component = {
	getName : function(){
		return "textarea@1.0.0";
	},
	getAuthKey : function(){
		return "3c59dc048e8850243be8-f8032feb3513d5049b58-b4b147bc522828731f1a-bd9ac4b38426d19fe181908ae96462b3"; // 检测是授权组件
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