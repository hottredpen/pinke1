define(['jquery','ueditor'],function($,ueditor){

// 所有已实例化的ueditor 对象数组
var g_ueditor_arr = window.g_ueditor_arr || [];

var ueditor = {
	createObj : function(){
		var obj = {};
		var o_document = $(document);
		var current_dialog_material_builder;

        var config = {
                "contentId"      : "",
                "name"           : ""
        };

		obj.init = function(userconfig){
			config       = $.extend({}, config, userconfig);
			_init_something();
			onSomeThingBtnEvent();
		}

		function _init_something(){
			$(function(){
				// console.log(config.contentId);

	            var _ueditor = new UE.ui.Editor();
	            _ueditor.render(config.contentId);

	            var _ueditor_obj = {
	            	"ueditor_obj" : _ueditor
	            }
	            g_ueditor_arr.push(_ueditor_obj);
	            window.g_ueditor_arr = g_ueditor_arr;
	            // console.log(g_ueditor_arr);


	            $(document).on("clear_UEditor_"+config.name,function(){
	                _ueditor.setContent('');
	            });

			});
		}

		function onSomeThingBtnEvent(){

		}

		return obj;
	}
}

return ueditor;

});