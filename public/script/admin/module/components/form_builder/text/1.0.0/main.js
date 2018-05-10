define(['jquery'],function($){

var component = {
	getName : function(){
		return "text@1.0.0";
	},
	getAuthKey : function(){
		return "c4ca4238a0b923820dcc-96a3be5cf272e017246c-b4b147b1532828731f1a-ccedf137957c72d0f54c7b7c98a94e4b"; // 检测是授权组件
	},
	createObj : function(){
		var obj = {};
		var o_document = $(document);

        var config = {
        		"parentBuilderId"  : "",
                "builderDiv"       : "",
                "formItemName"     : ""

        };

		obj.init = function(userconfig){
			config       = $.extend({}, config, userconfig);
			_init_something();
			_onDocumentBtn();
		}

		function _init_something(){

		}

		function _onDocumentBtn(){
			$(config.parentBuilderId).on('store_id.store_uid_name',function(e,val){
				console.log("get val");
				console.log(val);

			});
		}

		return obj;
	}
}

return component;

});