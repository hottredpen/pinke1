define(['jquery','jquery-datepicker'],function($){

var component = {
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

            $(config.inputId).datepicker({
                autoclose: true,
                format: "yyyy-mm-dd"
            }).on("changeDate",function(){
                $(document).trigger("Jt_datepicker_change",[config.inputId]);
            });
               
		}
		return obj;
	}
}

return component;

});