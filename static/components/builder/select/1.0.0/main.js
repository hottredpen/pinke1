define(['jquery'],function($){

var component = {
	getName : function(){
		return "select@1.0.0";
	},
	getAuthKey : function(){
		return "c9f0f895fb98ab9159f5-fad694e614a212e85c67-b4b147bc522828731f1a-104449694941f91e47c5f9c1df512e5b"; // 检测是授权组件
	},
	createObj : function(){
		var obj = {};
		var o_document = $(document);

        var config = {
                "builderDiv"       : "",
                "formItemName"     : "",
        };

		obj.init = function(userconfig){
			console.log("builder select@1.0.0");
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