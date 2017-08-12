define(['jquery'],function($){

var weixin_material_image_dialog = {
	getName : function(){
		return "weixin_material_image_dialog@1.0.0";
	},
	getAuthKey : function(){
		return "02e74f10e0327ad868d1-3994f23bfb2b89986bd1-b4b147bc523828731f1a-4f36fcf6d63360c53372629d7e2b5135"; // 检测是授权组件
	},
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