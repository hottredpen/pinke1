define(['jquery'],function($){

var component = {
    getName : function(){
        return "cover_config_by_category_id@1.0.0";
    },
    getAuthKey : function(){
        return "45c48cce2e2d7fbdea1a-0a8005f5594bd670e1fa-b4b147bc622828831f11-d1fe358d5adec431996df04168c14e00"; // 检测是授权组件
    },
	createObj : function(){
		var obj = {};
		var o_document = $(document);
		var current_dialog_material_builder;

        var config = {
                "coverDivId"       : "",
                "ajaxReplaceId"      : ""
        };

		obj.init = function(userconfig){
			config       = $.extend({}, config, userconfig);
			_init_something();
		}

		function _init_something(){


            require([pinkephp.web_static_url+'static/components/tools/cpk_imgbox_upload.js','uploadify'],function(CPK_imgbox_upload){

                $(document).on('click','.J_cover_config_btn',function(){

                    var trigger_from = $(this).attr("data-triggerform");
                    var from_module  = $(this).attr("data-frommodule");
                    var catid = $('.j_form_item_'+trigger_from+ ' select[name='+trigger_from+'] option:selected' ).val();
                    $.ajax({
                        type : 'post',
                        url  : '/api/file/uploadconfig',
                        data : {catid:catid,from_module:from_module},
                        success: function(res) {
                            if(res.code==200){
                                var this_data    = {};
                                this_data.k      = 1;
                                this_data.imgurl = "";
                                this_data.subimgurl = "";
                                this_data.cover_id = 0;
                                $(config.coverDivId).html(_html_str(res.data,this_data));
                                jt_imgboxupload();

                            }else{
                                $.custom.alert(res.msg)
                            }
                        }
                    });
                });





                if($(config.ajaxReplaceId).length > 0){



                    var o_this       = $(config.ajaxReplaceId);
                    var this_data    = {};
                    this_data.k      = o_this.attr('data-k');
                    this_data.imgurl = o_this.attr('data-imgurl');
                    this_data.subimgurl = o_this.attr('data-subimgurl');
                    this_data.cover_id = o_this.attr('data-coverid');

                    var catid        = o_this.attr('data-catid');
                    var from_module  = o_this.attr('data-frommodule');


                    $.ajax({
                        type : 'post',
                        url  : '/api/file/uploadconfig',
                        data : {catid:catid,from_module:from_module},
                        success: function(res) {
                            if(res.code==200){
                                var _html = _html_str(res.data,this_data);
                                $(config.ajaxReplaceId).parent().append(_html);
                                $(config.ajaxReplaceId).remove();
                                jt_imgboxupload();
                            }else{
                                $.custom.alert(res.msg)
                            }
                        }
                    });
                }


                function jt_imgboxupload(){
                    if($('.jt_imgboxupload').length > 0){
                        // $(document).trigger('jt_imgboxupload');
                        var o_this = $('.jt_imgboxupload');
                        if(o_this.hasClass('jt_imgboxupload')){
                            var _fileUploadId = o_this.attr('data-fileUploadId');
                            var _typename     = o_this.attr('data-typename');
                            var _inputname    = o_this.attr('data-inputname');
                            var _autoSize     = o_this.attr('data-autoSize') == 1 ? true : false;
                            var _width        = o_this.attr('data-width');
                            var _height       = o_this.attr('data-height');


                            var templateUploadImg = CPK_imgbox_upload.createObj();
                            templateUploadImg.initUploadImg({
                                "fileUploadId" : _fileUploadId,
                                "typename"     : _typename,    //上传type
                                "inputname"    : _inputname,   //input 可能有多附图
                                "autoSize"     : _autoSize,
                                "width"        : _width,
                                "height"       : _height
                            });
                            o_this.removeClass('jt_imgboxupload');
                        }
                    }
                }




            });










		}

        function  _html_str(data,this_data){

            var str = '<div id="{{uploadtype}}_0Box" data-fileUploadId="#file_upload{{k}}" data-autoSize="{{autosize}}" data-inputname="{{uploadtype}}_0" data-typename="{{uploadtype}}" data-width="{{width}}" data-height="{{height}}" class="m-public-imgboxupload jt_imgboxupload j_imgboxupload" style="width: {{width}}px;height: {{height}}px;">\
                        <a class="imgbox" href="{{imgurl}}" rel="group" style="display:none;"><img src="{{subimgurl}}" ></a>\
                        <input  type="file" name="mypic" id="file_upload{{k}}" class="fileInputBox" />\
                        <div id="fileQueueHide" style="display: none;">\
                        </div>\
                        <div class="u-imgboxupload-removebtn" style="display: none">删除</div>\
                        <input type="hidden" id="j_{{uploadtype}}_0_input" name="{{uploadtype}}_0" value="{{imgurl}}">\
                        <input type="hidden" id="j_{{uploadtype}}_0_id_input" name="cover_id" value="{{cover_id}}">\
                    </div>';

            str = str.replace(/{{uploadtype}}/g,data.uploadtype);
            str = str.replace(/{{width}}/g,data.width);
            str = str.replace(/{{height}}/g,data.height);
            str = str.replace(/{{autosize}}/g,data.autosize);

            str = str.replace(/{{k}}/g,this_data.k);
            str = str.replace(/{{imgurl}}/g,this_data.imgurl);
            str = str.replace(/{{subimgurl}}/g,this_data.subimgurl);
            str = str.replace(/{{cover_id}}/g,this_data.cover_id);
            return str;
        }


		return obj;
	}
}

return component;

});