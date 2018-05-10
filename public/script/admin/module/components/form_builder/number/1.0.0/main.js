define(['jquery'],function($){

var component = {
	getName : function(){
		return "number@1.0.0";
	},
	getAuthKey : function(){
		return "70efdf2ec9b086079795-138cccd4fda172471f25-b4b147bc52282873211a-559eeef0b05823808f926399faae8d1b"; // 检测是授权组件
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