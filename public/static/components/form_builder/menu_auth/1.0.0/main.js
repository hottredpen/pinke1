define(['jquery'],function($){

var component = {
	getName : function(){
		return "menu_auth@1.0.0";
	},
	getAuthKey : function(){
		return "c74d97b01eae257e44aa-73f7634ab3f381fe1099-b4b147bc522828732f1a-8546a6ea19f4026cb83d06730f8f0c8f"; // 检测是授权组件
	},
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