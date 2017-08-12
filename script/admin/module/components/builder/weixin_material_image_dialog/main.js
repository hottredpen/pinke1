define(['jquery'],function($){

var weixin_material_image_dialog = {
	createObj : function(){
		var obj = {};
		var o_document = $(document);

        var config = {
                "materialTypeInput"      : "",
        };

		obj.init = function(userconfig){
			config       = $.extend({}, config, userconfig);
			_init_something();
			onSomeThingBtnEvent();
		}

		function _init_something(){

		}

		function onSomeThingBtnEvent(){

		}


		return obj;
	}
}

return weixin_material_image_dialog;

});