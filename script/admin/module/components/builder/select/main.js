define(['jquery'],function($){

var component = {
	getName : function(){
		return "select";
	},
	createObj : function(){
		var obj = {};
		var o_document = $(document);

        var config = {
                "builderDiv"       : "",
                "formItemName"     : "",
        };

		obj.init = function(userconfig){
			console.log("builder select default");
			config       = $.extend({}, config, userconfig);
			_init_something();
			_onDocumentBtn();
		}

		function _init_something(){

		}

		function _onDocumentBtn(){
            $(config.builderDiv + " select[name="+config.formItemName+"]").on('change',function(){
                $(document).trigger('Jt_builder_form_class_trigger_item_'+config.formItemName,[$(this).val()]);
            });
		}

		return obj;
	}
}

return component;

});