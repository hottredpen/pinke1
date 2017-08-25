define(['jquery'],function($){

var component = {
	getName : function(){
		return "daterangepicker@1.0.0";
	},
	getAuthKey : function(){
		return "c20ad4d76fe97759aa27-d5490f048dc3b17aaa7e-b4b147bc522828831f1a-173618d11bc246214dd7e80ab5593d50"; // 检测是授权组件
	},
	createObj : function(){
		var obj = {};
		var o_document = $(document);

        var config = {
                "inputId"       : "",
        };

		obj.init = function(userconfig){
			console.log("builder cpk_pictures");
			config       = $.extend({}, config, userconfig);

			_init_something();
		}

		function _init_something(){
			require(['jquery_datepicker'],function(){
	            $(config.inputId).datepicker({
	                autoclose: true,
	                format: "yyyy-mm-dd"
	            }).on("changeDate",function(){
	                $(document).trigger("Jt_datepicker_change",[config.inputId]);
	            });
	        });  
		}
		return obj;
	}
}

return component;

});