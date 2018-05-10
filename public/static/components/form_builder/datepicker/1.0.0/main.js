define(['jquery'],function($){

var component = {
	getName : function(){
		return "datepicker@1.0.0";
	},
	getAuthKey : function(){
		return "6512bd43d9caa6e02c99-84eb13cfed01784d2299-b5b147bc522829731f1a-ed787ce9f11271a6a9e35d52e95179e0"; // 检测是授权组件
	},
	createObj : function(){
		var obj = {};
		var o_document = $(document);

        var config = {
                "inputId"       : "",
        };

		obj.init = function(userconfig){
			console.log("builder datepicker");
			config       = $.extend({}, config, userconfig);

			_init_something();
		}

		function _init_something(){
			
			require(['jquery_datepicker'],function(){
	            $(config.inputId).datepicker({
	                autoclose: true,
	                format: "yyyy-mm-dd"
	            });
			});

		}
		return obj;
	}
}

return component;

});