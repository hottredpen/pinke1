define(['jquery'],function($){

var cpk_pictures = {
    getName : function(){
        return "cpk_pictures@1.0.0";
    },
    getAuthKey : function(){
        return "d3d9446802a44259755d-ea20a043c08f51687450-b4b147bc522828731f1a-2fa7529d6d1f2fae8ea3754f919cb4d6"; // 检测是授权组件
    },
	createObj : function(){
		var obj = {};
		var o_document = $(document);
		var current_dialog_material_builder;

        var config = {
                "uploadType"       : "",
                "uploadInitInput"  : "",
                "uploadPostInput"  : "",
                "mainDivId"        : "",
                "mainDivUl"        : "",
                "builderFieldName" : "",


        };

		obj.init = function(userconfig){
			console.log("builder cpk_pictures");
			config       = $.extend({}, config, userconfig);

			_init_something();
		}

		function _init_something(){

			require([pinkephp.web_static_url+'static/components/tools/cpk_uploadify.js','nestable'],function(cpk_uploadify){
                var _uploadtype        = config.uploadType;
                var _upload_init_input = config.uploadInitInput;
                var _uploadify         = cpk_uploadify.createObj();
                _uploadify.init({"queueID":"imguploadhidden","uploadType": _uploadtype,"inputFileId":"#"+_upload_init_input,"uploadLimit":10},function(uploadifyid,fileid,fileurl){
                        $(config.mainDivUl).append('<li class="dd-item">\
                                                            <div class="dd-handle"><img src="'+fileurl+'"></div>\
                                                            <div class="dd-del"><a class="J_del_'+config.builderFieldName+'_imgbox" href="javascript:;">删除（id:'+fileid+'）</a></div>\
                                                            <input type="hidden" name="'+config.uploadPostInput+'" value="'+fileid+'" >\
                                                        </li>');
                });

                $(config.mainDivId).nestable();

	            $('.J_del_'+config.builderFieldName+'_imgbox').on('click',function(){
	                $(this).parent().parent().remove();
	            });

			});
		}
		return obj;
	}
}

return cpk_pictures;

});