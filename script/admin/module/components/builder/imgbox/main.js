define(['jquery'],function($){

var component = {
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