define(['jquery'],function($){

var component = {
    getName : function(){
        return "imgbox@1.0.0";
    },
    getAuthKey : function(){
        return "9bf31c7ff062936a96d3-af092fbcb67ffc7b96d3-b4b14711522828831f1a-1a9718a1063bccca6b2cdc2a01979712"; // 检测是授权组件
    },
	createObj : function(){
		var obj = {};
		var o_document = $(document);

        var config = {
                "builderDiv"       : "",
                "fileUploadId"     : "",
                "typename"         : "",
                "inputname"        : "",
                "autoSize"         : "",
                "width"            : "",
                "height"           : "",
        };

		obj.init = function(userconfig){
			console.log("builder imgbox");
			config       = $.extend({}, config, userconfig);
			_init_something();
			_onDocumentBtn();
		}

		function _init_something(){

            require([pinkephp.web_static_url+'static/components/tools/cpk_imgbox_upload.js'],function(CPK_imgbox_upload){
                var templateUploadImg = CPK_imgbox_upload.createObj();
                templateUploadImg.initUploadImg({
                    "fileUploadId" : config.fileUploadId,
                    "typename"     : config.typename,    //上传type
                    "inputname"    : config.inputname,   //input 可能有多附图
                    "autoSize"     : config.autoSize,
                    "width"        : config.width,
                    "height"       : config.height
                });
            });
		}

		function _onDocumentBtn(){

		}

		return obj;
	}
}

return component;

});