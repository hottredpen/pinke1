    function cpk_tip(msg){
        var d = dialog({
            content: '<span class="text-success"><i class="fa  fa-check-circle"></i>'+msg+'</span>'
        });
        d.show();
        setTimeout(function () {
            d.close().remove();
        }, 2000);
    }
    function cpk_error_tip(msg){
        var d = dialog({
            content: '<span class="text-danger"><i class="fa  fa-remove"></i>'+msg+'</span>'
        });
        d.show();
        setTimeout(function () {
            d.close().remove();
        }, 2000);
    }
    function cpk_dialog_remove(dialogid){
    	if(typeof dialogid == "undefined"){
    		dialogid = "j_artdialog_form";
    	}
        var f= dialog.get(dialogid);
        if(typeof f != "undefined"){
            f.close().remove();
        }
    }
    function cpk_alert(msg){
    	var d = dialog({
    		id   : "j_artdialog_alert",
    	    title: '提示',
    	    content: msg,
    	    fixed : false,
    	    okValue : "确定",
    	    ok: function () {},
    	    statusbar: '<label><input type="checkbox">不再提醒</label>'
    	});
    	d.show();
    }
    $(function(){
    	$('.J_tablelist').listTable();

        $("a[rel=group]").fancybox();
    });



    $(document).on("click",".J_showdialog",function(){
        var o_this = $(this),
        dtitle     = o_this.attr('data-title'),
        duri       = o_this.attr('data-uri'),
        dwidth     = o_this.attr('data-width'),
        dheight    = o_this.attr('data-height'),
        dcallback  = o_this.attr('data-callback');

        $.getJSON(duri, function(result){
            if(result.status == 1){
                var d = dialog({
                	id : "j_artdialog_form",
                    title: dtitle,
                    content: result.data,
                    width  : dwidth,
                    height : dheight,
                    okValue: '确定',
                    zIndex : 100,
                    ok: function () {
                        $("#j_artdialog_form").submit();
                        return false;
                    },
                    cancelValue: '取消',
                    cancel: function () {},
                });
                d.showModal();
            }else{
                cpk_alert(result.msg);
            }
        });
        return false;
    });

    $(document).on("click",".J_showdialog_no_btn",function(){
        var o_this = $(this),
        dtitle     = o_this.attr('data-title'),
        duri       = o_this.attr('data-uri'),
        dwidth     = o_this.attr('data-width'),
        dheight    = o_this.attr('data-height'),
        dcallback  = o_this.attr('data-callback');

        $.getJSON(duri, function(result){
            if(result.status == 1){
                var d = dialog({
                    id : "j_artdialog_form",
                    title: dtitle,
                    content: result.data,
                    width  : dwidth,
                    height : dheight,
                    okValue: '确定',
                    zIndex : 100
                });
                d.showModal();
            }else{
                cpk_alert(result.msg);
            }
        });
        return false;
    });

    $(document).on("click",".J_confirmurl",function(){
    	var o_this = $(this),
    	id         = o_this.attr('data-id')
    	uri        = o_this.attr('data-uri'),
    	dtitle     = o_this.attr('data-title'),
    	msg        = o_this.attr('data-msg'),
    	callback   = o_this.attr('data-callback');
        var ids    = "";

        // 批量操作
        var target_form = o_this.attr('data-target-from');
        if( typeof target_form != "undefined"){

            var ids_arr = [];
            $('input.ids').each(function(i,ele){
                if($(this).is(':checked')){
                    console.log($(this).val());
                    ids_arr.push($(this).val());
                }
            });
            ids = ids_arr.join(",");

            console.log(ids);
        }


        var d = dialog({
        	id : "j_artdialog_confirm",
            title: dtitle,
            content: msg,
            fixed  : true,
            okValue: '确定',
            ok: function () {
    		    $.ajax({
    		        type : 'post',
    		        url  : uri,
    		        data : {id:id,ids:ids},
    		        success: function(res) {
    		            if(res.status==1){
    		                cpk_tip(res.msg);
    						if(callback != undefined){
    							eval(callback+'(self)');
    						}else{
    							window.location.reload();
    						}
    		            }else{
    		                cpk_alert(res.msg)
    		            }
    		        }
    		    });
    		    this.remove();
                return false;
            },
            cancelValue: '取消',
            cancel: function () {},
        });
        d.showModal();
    });

    $(document).on("click",".J_batch_dialog",function(){
        var o_this = $(this),
        id         = o_this.attr('data-id')
        uri        = o_this.attr('data-uri'),
        dtitle     = o_this.attr('data-title'),
        msg        = o_this.attr('data-msg'),
        callback   = o_this.attr('data-callback');
        var ids    = "";

        // 批量操作
        var target_form = o_this.attr('data-target-from');
        if( typeof target_form != "undefined"){

            var ids_arr = [];
            $('input.ids').each(function(i,ele){
                if($(this).is(':checked')){
                    console.log($(this).val());
                    ids_arr.push($(this).val());
                }
            });
            ids = ids_arr.join(",");

            console.log(ids);
        }


        $.getJSON(uri, {ids:ids},function(result){
            if(result.status == 1){
                var d = dialog({
                    id : "j_artdialog_form",
                    title: dtitle,
                    content: result.data,
                    width  : 500,
                    height : 'auto',
                    okValue: '确定',
                    zIndex : 100
                });
                d.showModal();
            }else{
                cpk_alert(result.msg);
            }
        });



        // console.log(uri);
        // var d = dialog({
        //     id : "j_artdialog_batch",
        //     title: dtitle,
        //     content: msg,
        //     fixed  : true,
        //     okValue: '确定',
        //     ok: function () {
        //         $.ajax({
        //             type : 'get',
        //             url  : uri,
        //             data : {id:id,ids:ids},
        //             success: function(res) {
        //                 if(res.status==1){
        //                     cpk_tip(res.msg);
        //                     if(callback != undefined){
        //                         eval(callback+'(self)');
        //                     }else{
        //                         window.location.reload();
        //                     }
        //                 }else{
        //                     cpk_alert(res.msg)
        //                 }
        //             }
        //         });
        //         this.remove();
        //         return false;
        //     },
        //     cancelValue: '取消',
        //     cancel: function () {},
        // });
        // d.showModal();
    });

    $(document).on('jt_imgboxupload',function(){
        // console.log('jt_imgboxupload');
        $('.j_imgboxupload').each(function(){
            var o_this = $(this);
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
                $(this).removeClass('jt_imgboxupload');
            }
        });
    });

    if($('.jt_imgboxupload').length > 0){
        $(document).trigger('jt_imgboxupload');
    }


    //分页的跳转第几页
    $(document).on("blur","input[name=page_jump_to_value]",function(){
        var gotopage = parseInt($(this).val());
        var maxpage  = parseInt($(this).attr("data-maxpage"));
        var o_btn    = $("#j_page_jump_btn");
        var n_href   = o_btn.attr("href");
        if(typeof gotopage =="undefined" || isNaN(gotopage) || parseInt(gotopage)<=0){
            $("#j_page_jump_btn").attr("href","#");
        }
        gotopage = gotopage > maxpage ? maxpage : gotopage; 
        n_href = n_href.replace(/([\w\/]+)\s*\/([\?]+)p=([\d]+)\s*([\/\w]*)/, "$1/?p="+gotopage); 
        $("#j_page_jump_btn").attr("href",n_href);
    });



    //全选/反选/单选的实现
    $('body').delegate('.check-all', 'click', function() {
        $(".ids").prop("checked", this.checked);
    });

    $('body').delegate('.ids', 'click', function() {
        var option = $(".ids");
        option.each(function() {
            if (!this.checked) {
                $(".check-all").prop("checked", false);
                return false;
            } else {
                $(".check-all").prop("checked", true);
            }
        });
    });