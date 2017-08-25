define(['jquery'],function($){

var component = {
	getName : function(){
		return "text@1.0.0~hottredpen";
	},
	getAuthKey : function(){
		return "abcdefghijklmnopqrst-bcdefghijklmnopqrstt-cdefghijklmnopqrst0t"; // 检测是授权组件
	}, 
	createObj : function(){
		var obj = {};
		var o_document = $(document);

        var config = {
                "builderDiv"       : "",
                "formItemName"     : "",
        };

		obj.init = function(userconfig){
			console.log("component text@1.0.0~hottredpen");
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