define(['jquery','uploadify'],function($,uploadify){

var cpk_uploadify = {
    createObj : function(){
        var obj = {};
        var o_document = $(document);

        var _uid = 0;
        var uploadBtn = "#file_upload";
        var uploadFileList = [];
        var imgExtList = ["jpg", "jpeg", "png", "bmp","gif"];
        var zipExtList = ["zip", "rar", "7z"];
        var docExtList = ["doc", "docx"];
        var otherExtList = ["txt", "ppt", "wps", "psd", "cdr", "swf", "xls"];
        var unknownExt = "unknown";
        var callback;

        var config = {

                'DIY_seediv_ID'  : "",
                'postFildidInput': "#file_ids",
                'postimgurlInput': "#file_url",  

                'swf'            : "/static/plugins/uploadify/uploadify.swf",
                'uploadurl'      : "/Api/file/uploadImgBase",
                'queueID'        : "",//可以用样式隐藏上传进度条
                "inputFileId"    : "#file_upload",
                "uploadType"     : "task_bid",
                'removeTimeout'  : "3600",//文件队列上传完成1秒后删除(单位秒)
                'multi'          : true,
                'width'          : 124,
                'height'         : 34,
                'progressData'   : 'speed',
                'uploadLimit'    : 5,//一次最多只允许上传5张图片
                'fileTypeExts'   : '*.gif; *.jpg; *.png;*.doc;*.docx;*.xls;*.zip;*.rar;*.7z;*.txt;*.jpeg;*.bmp;*.JPG; *.PNG;*.DOC;*.DOCX;*.XLS;*.ZIP;*.RAR;*.7Z;*.TXT;*.JPEG;*.BMP;',//限制允许上传的图片后缀
                'fileSizeLimit'  : '100000KB',//目前前端不做限制
        };


        obj.init = function(userconfig,_callback){
            callback = _callback
            config  = $.extend({}, config, userconfig);
            arrayAddRemove();
            getUid();
            uploadFileReady();
            addEvent();
            $(".flashCheckBox").html(checkInstallFlashPlay());//检查是否安装FLASH


            o_document.on("cpk_uploadify_cancel",function(){
                removeAttachmentOprate();
            });

        }
        function uploadFileReady(){
            var uploadfifyOption = _initUploadifyOption();
            $(config.inputFileId).uploadify(uploadfifyOption);
        }
        function checkInstallFlashPlay(){
            if ((!window.VBArray)){
                //alert("Not IE");
                try{
                    var swf2 = navigator.plugins['Shockwave Flash'];
                    if(swf2 == undefined){
                        //alert('没有安装Flash');
                        return "您还没有安装FLASH插件，<a href='https://get.adobe.com/flashplayer/?loc=cn' target='_blank'>立即去安装</a>";
                    }
                    else {
                        //alert('安装了Flash');
                    }
                }
                catch(e){
                    //alert('没有安装Flash');
                    return "您还没有安装FLASH插件，<a href='https://get.adobe.com/flashplayer/?loc=cn' target='_blank'>立即去安装</a>";
                }
            }else{
                try{
                    var swf1 = new ActiveXObject('ShockwaveFlash.ShockwaveFlash');
                    //alert('安装了Flash');
                    //return "您还没有安装FLASH插件，<a href='https://get.adobe.com/flashplayer/?loc=cn' target='_blank'>立即去安装</a>";
                }
                catch(e){
                    //alert('没有安装Flash');
                    return "您还没有安装FLASH插件，<a href='https://get.adobe.com/flashplayer/?loc=cn' target='_blank'>立即去安装</a>";
                }
            }
        }
        function _initUploadifyOption(){
            return {
                     'queueID'        : config.queueID,
                     'swf'            : config.swf,
                     'removeTimeout'  : config.removeTimeout,
                     'progressData'   : 'speed',
                     'uploader'       : config.uploadurl,
                     'formData'       : {'type':config.uploadType},
                     'buttonText'     : '',
                     'fileObjName'    : 'mypic',
                     'multi'          : config.multi,
                     'width'          : config.width,
                     'height'         : config.height,
                     'progressData'   : config.progressData,
                     'uploadLimit'    : config.uploadLimit,
                     'fileTypeExts'   : config.fileTypeExts,
                     'fileSizeLimit'  : config.fileSizeLimit,
                     'onUploadSuccess': onUploadSuccess,
                     'onSWFReady'     : onSwfReady,
                     'onDeleteUploadFile':removeAttachmentOprate,
                     'onUploadStart'  : onUploadStart
            };
        }
        function onUploadStart(file){
            $(config.inputFileId).uploadify("settings", "formData", { 'uid': _uid });
        }
        function getUid(){
            _uid = 0;
            // $.when(CPK_page_userdata.deferred())
            //     .done(function(uid){
            //         _uid = uid;
            //     });
        }
        function addEvent(){
            o_document.on('click','.uploadify-queue-item .cancel',function(){
                removeAttachment($(this));
                cancelUploadFile($(this));
            });
        }

        function onUploadSuccess(file, data, response) {
            var data          = $.parseJSON(data);
            if(data.code == 200){

                // 新增订阅式回调
                $(document).trigger('cpk_uploadify_callback',[data,file]);
                console.log('cpk_uploadify_callback');

                if(callback!=null){
                    callback(file.id,data.fileid,data.url);
                    return;
                }
                
                var fileId = data.fileid;
                uploadFileList.push(fileId);
                $(config.postFildidInput).val(uploadFileList.join(','));
                $(config.postimgurlInput).val(data.url);
                $("#span_"+file.id).html(getExtTypeThumbnail(data.ext,data.url));
                $('#'+file.id).attr('refileid',fileId);
                $('#'+file.id).find(".uploadify-progress div").css("width","100%");
                if(config.DIY_seediv_ID!=""){
                    $(config.DIY_seediv_ID).html("<a href='"+data.url+"' rel='group' >查看</a>").hide();
                    setTimeout(function(){
                        $(config.DIY_seediv_ID).show();
                    },1000);
                }
                if($(".j_openstore_error").length>0){
                    $(".j_openstore_error").html("");
                }
                o_document.trigger("CPK_uploadifyonUploadSuccess");
            }else{
                $.custom.alert(data.msg);
                removeAttachmentOprate();
                $('#'+file.id).remove();
            }
        }

        function getExtTypeThumbnail(ext, url){
            var extStr = "";
            var imgURL = "<img src='/static/cpk/module/task/images/{ext}.jpg'>";
            if(imgExtList.indexOf(ext)>=0){
                return "<img src='"+url+"' width='50' height='50'>";
            }else if(zipExtList.indexOf(ext)>=0){
                extStr =  "zip";
            }else if(docExtList.indexOf(ext)>=0){
                extStr =  "doc";
            }else if(otherExtList.indexOf(ext)>=0){
                extStr =  ext;
            }else {
                extStr =  unknownExt;
            }
            return imgURL.replace(/{ext}/g, extStr);
        }

        function onSwfReady(){
                setUploadfiyStatsForSuccessfulUploads(getAttachmentNum());
                initAttachmentList();
                setAttachmentValue();
        }

        function getAttachmentNum(){
            return $(".uploadify-queue-item").length;
        }

        function getUploadfiyStatus(){
            return getUploadfiySwfObject().getStats();
        }

        function setUploadfiyStatus(stats){
            getUploadfiySwfObject().setStats(stats);
        }

        function getUploadfiySwfObject(){
            return $(config.inputFileId).data('uploadify');
        }

        function removeAttachmentOprate(){
            setUploadfiyStatsForSuccessfulUploads(-1);
            if(config.DIY_seediv_ID!=""){
                $(config.DIY_seediv_ID).html("");
            }
        }


        function setUploadfiyStatsForSuccessfulUploads(num){
            var stats = getUploadfiyStatus();
            var uploadSuccessNum = stats.successful_uploads;
            if(typeof uploadSuccessNum !== 'number'){
                stats.successful_uploads = 0;
            }
            stats.successful_uploads += num;
            setUploadfiyStatus(stats);
        }
        function removeAttachment(element){
            var itemId = element.parents(".uploadify-queue-item").attr("id");
            uploadFileList.remove($('#'+ itemId).attr("refileid"));
            setAttachmentValue();
        }

        function cancelUploadFile(element){
            var itemId = element.parents(".uploadify-queue-item").attr("id");
            var getfileid = $('#'+ itemId).attr("refileid");
            uploadFileList.remove(getfileid);
            $(config.postFildidInput).val(uploadFileList.join(','));
        }

        function initAttachmentList(){
            $(".uploadify-queue-item").each(function(){
                uploadFileList.push($(this).attr("refileid"));
            });
        }

        function setAttachmentValue(){
            $(config.postFildidInput).val(uploadFileList.join(","));
        }

        function arrayAddRemove(){
            Array.prototype.indexOf = function(val) {
                for (var i = 0; i < this.length; i++) {
                    if (this[i] == val) return i;
                }
                return -1;
            };
            Array.prototype.remove = function(val) {
                var index = this.indexOf(val);
                if (index > -1) {
                    this.splice(index, 1);
                }
            };
        }
        return obj;
    }
}
return cpk_uploadify;
});