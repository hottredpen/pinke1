define(['jquery','uploadify'],function($,uploadify){

// 早期写的图片上传，有些无法直视先将就用吧 ，以后有时间改成百度的webuploader


var CPK_getNewImgSize = {
    createObj : function(){
        var obj = {};
        var _$img;
        var zImage;
        var _loadWidth,_loadHeight;
        var maxwidth,maxheight;
        var deferred = $.Deferred();
        var callback;
        obj.imgLoadingOkCallback =function(_imgurl,_config,_callback){
            if(_imgurl==""){return}
            maxwidth  = _config.maxwidth;
            maxheight = _config.maxheight;
            callback  = _callback;
            var img = new Image();
            img.src = _imgurl;
            $(img).load(function(){
                zImage = this;
                deferred.resolve();
            })
            ListenImgLoadOk();
        }
        function ListenImgLoadOk(){
            $.when(deferred).done(function(){
                _loadWidth  = zImage.width;
                _loadHeight = zImage.height;
                calculateAndCallback();
            });
        }
        function calculateAndCallback(){
            var imgshowwidth,imgshowheight;
            if(_loadWidth>maxwidth && _loadHeight>maxheight){
                if(_loadWidth>_loadHeight){
                    //双边超范围，超宽，设宽为固定，等比缩放
                    imgshowwidth  = maxwidth;
                    imgshowheight = _loadHeight*(maxwidth/_loadWidth);
                }else{
                    //双边超范围，超高，设高为固定，等比缩放
                    imgshowwidth  = _loadWidth*(maxheight/_loadHeight);
                    imgshowheight = maxheight;
                }
            }else if(_loadWidth>=maxwidth){
                    //单一超范围，宽，设宽为固定，等比缩放
                    imgshowwidth  = maxwidth;
                    imgshowheight = _loadHeight*(maxwidth/_loadWidth);
            }else if(_loadHeight>=maxheight){
                    //单一超范围，高，设高为固定，等比缩放
                    imgshowwidth  = _loadWidth*(maxheight/_loadHeight);
                    imgshowheight = maxheight;
            }else{
                    //非超范围
                    imgshowwidth  = _loadWidth;
                    imgshowheight = _loadHeight;
            }
            callback(imgshowwidth,imgshowheight);
        }
        return obj;
    }
}


var CPK_imgbox_upload = {
    createObj : function(){
        var obj = {};
        var _imgurl,_imgurlWithDomain;
        var _fileId;
        var _uid = 0;
        var _callback;
        var uploadImgBox;
        var showimg;
        var DOMAIN;
        var config = {
                "domain"         : getDomain(),
                "typename"       : "",
                "inputname"      : "",
                "changeEle"      : "",
                "queueID"        : "fileQueueHide",
                "fileUploadId"   : "#file_upload",
                "removeBtnClass" : "u-imgboxupload-removebtn",
                "startUploadBtn" : "u-imgboxupload-startbtn",
                "width"          : 480,
                "height"         : 48,
                "autoSize"       : false
        };

        obj.initUploadImg  =  function(userconfig,callback){
            _callback    = callback;
            config       = $.extend({}, config, userconfig);
            DOMAIN       = config.domain;
            uploadImgBox = $("#"+config.inputname+"Box");
            showimg      = $("#"+config.inputname+"Box"+" img");
            getUid();
            uploadFileReady();
            onHasImg();
            removeBtnBindClick();
            listenDoSomething();
        };
        function getUid(){
            // $.when(CPK_page_userdata.deferred())
            //     .done(function(uid){
            //         _uid = uid;
            //     });
            return _uid = 1;
        }

        function onHasImg(){
            _imgurl = showimg.attr('src');
            if(_imgurl==''){return};
            imgToShow();
            removeBtnShow();
            changeImgBoxSizeNotWithAnimate();
            _fileId = $('#j_'+config.inputname+'_id_input').val();
            removeBtnBindClick();
        }

        function uploadFileReady(){
            var uploadfifyOption = _initUploadifyOption();
            $(config.fileUploadId).uploadify(uploadfifyOption);
        }

        function _initUploadifyOption(){
            return {
                     'queueID'        : config.queueID,
                     'width'          : config.width,
                     'height'         : config.height,
                     'swf'            : '/static/plugins/uploadify/uploadify.swf',
                     'progressData'   : 'speed',
                     'uploader'       : "/api/file/uploadImgBase",
                     'formData'       : {'type':config.typename},
                     'buttonText'     : '开始上传',
                     'buttonClass'    : config.startUploadBtn,
                     'fileObjName'    : 'mypic',
                     'multi'          : false,
                     'progressData'   : 'speed',
                     'removeTimeout'  : 3600,
                     'uploadLimit'    : 10,
                     'fileTypeExts'   : '*.gif; *.jpg; *.png;*.jpeg;',
                     'fileSizeLimit'  : '100000KB',//前端目前不限制
                     'onUploadSuccess': onUploadSuccess,
                     'onDeleteUploadFile':onDeleteUploadFile,
                     'onUploadStart'  : onUploadStart,
                     'onUploadError' : function(file,errorCode,errorMsg,errorString,swfuploadifyQueue) {
                        alert(errorMsg);
                     }
            };
        }
        function onUploadStart(file){
            $(config.fileUploadId).uploadify("settings", "formData", { 'uid': _uid });
        }
        function onUploadSuccess(file, data, response) {
            var data          = $.parseJSON(data);
            if(data.code==200){
                // 新增订阅式回调
                $(document).trigger('cpk_imgbox_upload_callback',[data,file]);
                console.log('cpk_imgbox_upload_callback');
                _imgurl           = data.url;
                _imgurlWithDomain = DOMAIN+_imgurl;
                _fileId           = data.fileid;
                setImgsrc();
                imgToShow();
                imgurlInputset();
                if(config.autoSize){
                    changeImgBoxSizeWithAnimate();
                }else{
                    changeImgBoxSizeNotWithAnimate
                }
                removeBtnShow();
                changeElement();
                if(_callback!=null){
                    _callback.apply(_empty_callback,[_fileId]);
                }
            }else{
                $.custom.alert(data.msg);
            }
        }
        function _empty_callback(fileid){

        }
        function listenDoSomething(){
            listenBgimgrepeat();
        }

        function listenBgimgrepeat(){
            $(".J_bodyimgrepeat").on("change",function(){
                obj.triggerBgimgrepeat();
            });
        }

        function onDeleteUploadFile(){
            // 新增订阅式回调
            $(document).trigger('cpk_imgbox_upload_delete_callback',[_fileId]);
            // console.log('cpk_imgbox_upload_delete_callback'+_fileId);
            _imgurl = "";
            _fileId = "";
            imgBoxChangeSizeWithAnimate(config.width,config.height);
            imgToDelect();
            imgurlInputset();
            removeBtnHide();
            changeElement();
        }

        function imgToDelect(){
            showimg.attr("src",'');
            showimg.parent().attr("href",'').hide();
            imgToHide();
        }

        function setImgsrc(){
            showimg.attr("src",_imgurl);
            showimg.parent().attr("href",_imgurl);
        }

        function imgToShow(){
            showimg.parent().show();
        }

        function imgToHide(){
            showimg.parent().hide();
        }

        function imgurlInputset(){
            $('#j_'+config.inputname+'_input').val(_imgurl);
            $('#j_'+config.inputname+'_id_input').val(_fileId);
        }

        function changeImgBoxSizeWithAnimate(){
            var _loadimg = CPK_getNewImgSize.createObj();
            var _maxwidth  = config.autoSize == true ? 150 : config.width;
            var _maxheight = config.autoSize == true ? 150 : config.height;
            _loadimg.imgLoadingOkCallback(_imgurl,{"maxwidth":_maxwidth,"maxheight":_maxheight},imgBoxChangeSizeWithAnimate);
        }

        function changeImgBoxSizeNotWithAnimate(){
            var _loadimg = CPK_getNewImgSize.createObj();
            var _maxwidth  = config.autoSize == true ? 150 : config.width;
            var _maxheight = config.autoSize == true ? 150 : config.height;
            _loadimg.imgLoadingOkCallback(_imgurl,{"maxwidth":_maxwidth,"maxheight":_maxheight},imgBoxChangeSizeNotWithAnimate);
        }

        function removeBtnShow(){
            uploadImgBox.find(" ."+config.removeBtnClass).show();
            uploadImgBox.find("."+config.startUploadBtn).hide();
        }

        function removeBtnHide(){
            uploadImgBox.find(" ."+config.removeBtnClass).hide();
            uploadImgBox.find("."+config.startUploadBtn).show();
        }

        function removeBtnBindClick(){
            uploadImgBox.find(" ."+config.removeBtnClass).on('click',function(){
                $('#j_'+config.inputname+'_input').val(_imgurl);
                showimg = $(this).parent().find(".imgbox img");
                onDeleteUploadFile();
            });
        }

        function imgBoxChangeSizeWithAnimate(width,height){
            changeFileInputSize(width,height);
            changeTirggerUploadBoxSize(width,height,1);
            changeShowimgSize(width,height,1);
        }

        function imgBoxChangeSizeNotWithAnimate(width,height){
            changeFileInputSize(width,height);
            changeTirggerUploadBoxSize(width,height,0);
            changeShowimgSize(width,height,0);
        }

        function changeFileInputSize(width,height){
            $(config.fileUploadId).css({width:width,height:height});
            $(config.fileUploadId).find(".swfupload").css({width:width,height:height});
            $(config.fileUploadId).find(".uploadify-button").css({'width':width,'height':height,'line_height':height});
        }

        function changeTirggerUploadBoxSize(width,height,is_animate){
            if(is_animate){
                uploadImgBox.animate({width:width,height:height});
            }else{
                uploadImgBox.css({width:width,height:height});
            }
        }
        function changeShowimgSize(width,height,is_animate){
            if(is_animate){
                showimg.animate({width:width,height:height});
            }else{
                showimg.css({width:width,height:height});
            }
        }
        function getDomain(){
            var curWwwPath    = window.document.location.href;  
            var pathName      = window.document.location.pathname;  
            var pos           = curWwwPath.indexOf(pathName); //获取主机地址，如： http://localhost:8080  
            var DOMAIN        = curWwwPath.substring(0, pos); //获取带"/"的项目名，如：/cis  
            return DOMAIN;
        }
        function changeElement(){
            if(config.changeEle=="bodybg"){
                $('body').css('background',"transparent url("+_imgurl+")");
                obj.triggerBgimgrepeat();
            }
        }

        obj.triggerBgimgrepeat = function(){
            var $body = $("body");
            var bodyimgrepeat = $('input:radio[name=bodyimgrepeat]:checked').val();
            switch (bodyimgrepeat){
                case "0":
                    $body.css("background-position","center top");
                    $body.css("background-repeat","no-repeat");
                    $body.css("background-attachment","");
                    break;
                case "1":
                    $body.css("background-position","");
                    $body.css("background-repeat","repeat-x");
                    $body.css("background-attachment","");
                    break;
                case "2":
                    $body.css("background-position","center top");
                    $body.css("background-repeat","repeat-y");
                    $body.css("background-attachment","");
                    break;
                case "3":
                    $body.css("background-position","");
                    $body.css("background-repeat","repeat");
                    $body.css("background-attachment","");
                    break;
                case "4":
                    $body.css("background-position","center top");
                    $body.css("background-repeat","no-repeat");
                    $body.css("background-attachment","fixed");
                    break;
            }
        }

        return obj;
    }
}


return CPK_imgbox_upload;
});