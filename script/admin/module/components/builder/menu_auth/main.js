define(['jquery'],function($){

var component = {
	createObj : function(){
		var obj = {};
		var o_document = $(document);

        var config = {
                "builderDiv"       : "",
                "formItemName"     : "",
                "tellInputId"      : "",
        };

		obj.init = function(userconfig){
			console.log("builder menu_auth");
			config       = $.extend({}, config, userconfig);
			_init_something();
			_onDocumentBtn();
		}

		function _init_something(){
            $(config.builderDiv + ' .auth input[type="checkbox"]').on('change',function(){
                $('.'+$(this).attr('data-module-name')+' .auth'+$(this).val()).find('input').prop('checked',this.checked);
            });
		}

		function _onDocumentBtn(){

		}


		return obj;
	}
}

return component;

});