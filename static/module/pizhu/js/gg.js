/**
 * Created by Richard on 4/20/14.
 */

var globle_comment_blur;
var current_selected_txt;
var selected_txt_parent_card;
var yellowBgApplier;
var isFirefox = typeof InstallTrigger !== 'undefined';

jQuery(document).ready(function () {
    jQuery('input, textarea').placeholder();

    // Rangy（选中文本插件）初始化
    if(typeof(rangy)!='undefined'){
		rangy.init();
        var classApplierModule = rangy.modules.ClassApplier || rangy.modules.CssClassApplier;
        if (rangy.supported && classApplierModule && classApplierModule.supported) {
            yellowBgApplier = rangy.createCssClassApplier("yellowBgApplier", {
                tagNames: ["span", "a", "b"]
            });
        }
	}
    try {
        document.execCommand("MultipleSelection", true, true);
    } catch (ex) {}
    update_progress_btn();
    jQuery.ajaxSetup({
        'timeout': 70000
    });
    //开小差 全局设置
	 
    jQuery(document).ajaxError(function (event, request, settings) {
        message_box("麦格开小差了<br>再试一次吧~", "error");
        jQuery('#ajax-retry').click(function () {
            jQuery.modal.close();
            jQuery.ajax(settings);

        });
    });
 

    //返回顶部
    jQuery(".go-to-top").click(function () {
        jQuery("html, body").animate({ scrollTop: 0 });
        return false;
    });


    jQuery(window).scroll(function () {
        if (jQuery(this).scrollTop()) {
            jQuery('.go-to-top:hidden').stop(true, true).fadeIn();
        } else {
            jQuery('.go-to-top').stop(true, true).fadeOut();
        }
    });


    //帮助按钮
    jQuery(".help-btn").click(function () {
        jQuery("#help-dialog").modal({onShow: function () {
            setTimeout(function () {
                jQuery("#guestbook").blur();
            }, 50);
        },

            containerCss: {

                width: "740px",
                height: "357px"
            },

            Width: 840, Height: 430});
    });

    // 正文区，选中文本抬起鼠标后
    var downLock=false,moveLock=false,downXY={},upXY={};
    jQuery('body').on('mousedown', '.full-article-ready', function (event) {
        downLock=true;
        moveLock=false;
        downXY.x=event.pageX;
        downXY.y=event.pageY;
    });
    jQuery('body').on('mousemove', '.full-article-ready', function (event) {
        if(!downLock){
            return;
        }
        moveLock=true;
    });
    jQuery('body').on('mouseleave', '.full-article-ready', function (event) {
        downLock=false;
        moveLock=false;
    });
    jQuery('body').on('mouseup', '.full-article-ready', function (event) {
        if(!downLock || !moveLock){
            return;
        }
        downLock=false;
        moveLock=false;
        upXY.x=event.pageX;
        upXY.y=event.pageY;
        var source_id = jQuery(this).attr('source_id');
        selected_txt_parent_card = jQuery(this).parents(".card");

        //确定正文区
        var dom = jQuery(this).find(".allow-pick");
        //获得选中文本
        var sel_txt = getSelectedTextWithin();
        current_selected_txt = sel_txt;
        if (current_selected_txt != "") {
            savedSel = saveSelection();
            show_picked(downXY,upXY);
            jQuery('#pick-note-dialog').attr('data-sourcid', source_id);
			//兼容评论
			jQuery('#pick-note-dialog').attr('data-msgid', jQuery(this).parents(".card").attr("msgid"));
			jQuery('#pick-note-dialog').attr('data-type', jQuery(this).parents(".card").attr("type"));
			jQuery('#pick-note-dialog').attr('data-srctype', jQuery(this).parents(".card").attr("srctype"));
			jQuery('#pick-note-dialog').attr('data-fid', jQuery(this).parents(".card").attr("fid"));
			jQuery('#pick-note-dialog').attr('data-srcid', jQuery(this).parents(".card").attr("srcid"));
			jQuery('#pick-note-dialog').attr('data-isreal', jQuery(this).parents(".card").attr("isreal"));
			jQuery('#pick-note-dialog').attr('data-srcnull', jQuery(this).parents(".card").attr("srcnull"));
            jQuery('#pick-note-dialog').attr('data-fuid', jQuery(this).parents(".card").attr("fuid"));
            jQuery('#pick-note-dialog').attr('data-funame', jQuery(this).parents(".card").attr("funame"));
        }
    });
});

//摘抄时只选择正文区文本（过滤掉非区域文本）
function getSelectedTextWithin() {
    if(ie){
        return document.selection.createRange().text
    }else{
        return rangy.getSelection().getRangeAt(0).toString();
    }
}
//显示摘抄框
function show_picked(down,up) {
//    var startPos = rangy.getSelection().getStartDocumentPos();
//    var endPos = rangy.getSelection().getEndDocumentPos();
    var topPos,leftPos;
    if(down.y>up.y){
        topPos=down.y;
        leftPos=down.x;
    }else{
        topPos=up.y;
        leftPos=down.x;
    }
    zcBoxUi()
    $("#pick-note-dialog").css({position: "absolute", top: topPos+20,left: leftPos,width: "270px"}).show();

}

//消息框方法
function message_box(content, type) {
    jQuery.modal.close();
    jQuery("#message-" + type + " span").html(content);
    if (type=='noteok')
    {
        jQuery("#message-" + type).modal({onShow: function () {
            jQuery(".modalCloseImg").hide();
            jQuery("#simplemodal-container").css("height","auto");
        },opacity: 0, focus: false, Width: 160, minHeight: 58, overlayClose: true,
            containerCss: {

                width: "160px"
            }
        });
        jQuery("footer").delay(2000).fadeIn(0,function(){
            jQuery.modal.close();
        });
    }
    else
    {
        jQuery("#message-" + type).modal({close: false});
    }

}

//字数检测
function check_words(content, limit) {
    if (content.length == 0) return "请输入内容";
    var count = limit - content.length;
    if (count >= 0) {
        return "pass";
    }
    else {
        return "已超出" + count * (-1) + "字";
    }
}

// IE版本检测
var ie = (function () {
    var undef,
        v = 3,
        div = document.createElement('div'),
        all = div.getElementsByTagName('i');
    while (
        div.innerHTML = '<!--[if gt IE ' + (++v) + ']><i></i><![endif]-->',
            all[0]
        );
    return v > 4 ? v : undef;
}());
/**
 * 更新进度条按钮
 */
function update_progress_btn()
{
    jQuery(".progress-btn").each(function(){
        var txt=jQuery(this).html();
        var pro=100-jQuery(this).data("progress")*1;

        var xwidth=jQuery(this).outerWidth();
        var xheight=jQuery(this).outerHeight();
        var b_width=xwidth*pro/100;
        jQuery(this).html("<b style='height:"+xheight+"px;width:"+b_width.toFixed(0) +"px'></b><span>"+txt+"</span>");
    });
}

// 保存与恢复文本选择
var saveSelection, restoreSelection;
var savedSel = null;
if (window.getSelection) {
    // IE 9 and non-IE
    saveSelection = function() {
        var sel = window.getSelection(), ranges = [];
        if (sel.rangeCount) {
            for (var i = 0, len = sel.rangeCount; i < len; ++i) {
                ranges.push(sel.getRangeAt(i));
            }
        }
        return ranges;
    };

    restoreSelection = function(savedSelection) {
        var sel = window.getSelection();
        sel.removeAllRanges();
        for (var i = 0, len = savedSelection.length; i < len; ++i) {
            sel.addRange(savedSelection[i]);
        }
    };
} else if (document.selection && document.selection.createRange) {
    // IE <= 8
    saveSelection = function() {
        var sel = document.selection;
        return (sel.type != "None") ? sel.createRange() : null;
    };

    restoreSelection = function(savedSelection) {
        if (savedSelection) {
            savedSelection.select();
        }
    };
}
function zcBoxUi(){
    $("body").append('<div id="pick-note-dialog" title="" class="fancybox fw-dialog hidden collapsed  callout border-callout" data-sourcid="" style="width:270px;"></div>')
    $("#pick-note-dialog").html('<div class="inner">' +
        '<textarea name="pick-note-content" id="pick-note-content" cols="30" rows="2" placeholder="摘抄理由"></textarea>' +
        '<div class="clearfix infobar">' +
        '<div class="fl">' +
        '<div class="active-it">说点什么</div>' +
        '<div class="word-count font12" id="wordCount" style="display:none;">摘抄<span>1</span>字<div class="pick-note-error-message font12"><i id="pick-note-msg"><br>字数过多，超过300字，请重新选择</i></div></div>' +
        ' </div><div class="fr">' +
        '<a class="submit btn88 btn  post-pick-note-cancle" href="#" id="close_zc" onclick="return false;">取消</a>' +
        '<a class="submit btn88 btn  post-pick-note" href="#" onclick="return false">摘抄下来</a></div></div> </div>' +
        '<div class="bottom" style="display:none;"></div><b class="border-notch notch"></b><b class="notch"></b>');
    var word_count = current_selected_txt.length;
    if (word_count > 300) {
        jQuery('#pick-note-dialog .word-count span').html(word_count);
        jQuery('#pick-note-msg').html("超出"+(word_count-300)+"字");
        jQuery('#wordCount').show();
        jQuery('#pick-note-dialog .bottom').show();
        jQuery(".submit.post-pick-note").addClass("note_no_bt");
    }
    else {
        jQuery('#wordCount').hide();
        jQuery('#pick-note-dialog .word-count span').html(word_count);
        jQuery('#pick-note-dialog .bottom').hide();
        jQuery(".submit.post-pick-note").removeClass("note_no_bt");

    }
//    摘抄浮层点击时，选中文本焦点时去前，瞬间保存选中区域
//    jQuery("#pick-note-dialog").mousedown(function(){
//        savedSel = saveSelection();
//    });
//    瞬间再恢复选中区域
//    jQuery("#pick-note-dialog").mousedown(function(e){
//        // 如果点击非 textarea，恢复选中区域，否则，释放焦点并允许textarea输入
//        if (jQuery(e.target).closest('#pick-note-content').length == 0) {
//            restoreSelection(savedSel);
//        }
//    });
    $("#close_zc").on("click",function(){
       $('#pick-note-dialog').remove();
        deleteSel();
    });
    $(document).off("mousedown").on("mousedown",function(evevt){
        if($(evevt.target).parents().hasClass("fancybox")){
            return;
        }
        deleteSel();
        $("#pick-note-dialog").remove();

    });
    // 激活展开输入摘抄理由
    jQuery("#pick-note-dialog .active-it").mousedown(function(){
        var word_count = current_selected_txt.length;
        if (word_count > 300){
            return;
        }
        yellowBgApplier.applyToSelection();
        jQuery(this).hide();
        jQuery("#pick-note-dialog").removeClass("collapsed");
        jQuery("#pick-note-content").focus();
        $("#pick-note-dialog").css({"width":430});
        return false;
    });
    jQuery('#pick-note-content').on('keyup', function () {
        var reason = jQuery('#pick-note-content').val();
        if (reason.length > 300) {
            jQuery('#pick-note-msg').html("摘抄理由最多300字！").css("display","in-line");
            jQuery('#wordCount').show();
            jQuery(".submit.post-pick-note").addClass("note_no_bt");
        }else{
            jQuery('#wordCount').hide();
            jQuery(".submit.post-pick-note").removeClass("note_no_bt");
        }

    });
    //摘抄按钮提交
    jQuery('.post-pick-note').on('mouseup', function () {
        deleteSel();
        if($(this).hasClass('note_no_bt')){
            return false;
        }
        var source_id = jQuery('#pick-note-dialog').data('sourcid');
        var msgid = jQuery('#pick-note-dialog').attr('data-msgid');
        var type = jQuery('#pick-note-dialog').attr('data-type');
        var srctype = jQuery('#pick-note-dialog').attr('data-srctype');
        var fid = jQuery('#pick-note-dialog').attr('data-fid');
        var srcid = jQuery('#pick-note-dialog').attr('data-srcid');
        var isreal = jQuery('#pick-note-dialog').attr('data-isreal');
        var srcnull =jQuery('#pick-note-dialog').attr('data-srcnull');
        var fuid = jQuery('#pick-note-dialog').attr('data-fuid');
        var funame = jQuery('#pick-note-dialog').attr('data-funame');
        var reason = jQuery('#pick-note-content').val();
        if (reason.length > 300) {
            jQuery(".submit.post-pick-note").addClass("note_no_bt");
        } else {
            jQuery.ajax({
                data: {
                    srcId:source_id,
                    content:reason,
                    srcContent:current_selected_txt,
                    msgid:msgid,
                    type:type,
                    srctype:srctype,
                    fid:fid,
                    isreal:isreal,
                    srcnull:srcnull,
                    fuid:fuid,
                    source_id:source_id,
                    funame:funame

                },
                url: "/excerpt/replyfresh",
                type: "POST",
                dataType: 'json',
                beforeSend:function(){
                    message_box("加载中...",'loading');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    jQuery.modal.close();
                },
                success: function (res) {
                    if (res.code == 0) {
                        jQuery.modal.close();
                        message_box('摘抄成功！～', 'noteok');
                        $('#pick-note-dialog').remove();
                        var data=res.data.feed;
                        if($("#card_list").length>0){
                            var $sec=$("<div class='card pie'></div>");
                            $("#card_list").prepend($sec);
                            $sec.attr({"msgid":data.id,"type":'4',"fuid":data.uid,"srctype":data.type,"srcid":data.fid,"fid":data.id,"funame":data.uname,"isreal":"","srcnull":data.srcId}).css("opacity",0);
                            var myTemplate  = $("#yczc_mb").html();
                            var out = Handlebars.compile(myTemplate);
                            $sec.html(out(res));
                            $sec.animate({opacity: 1},"fast");
                        }
                        var $sour=$('<li class="last-comment"></li>');
                        $("div[srcid='"+srcid+"'].card").find(".comments").append($sour);
                        $sour.attr({"data-id":res.data.comment.id,"id":'comment_content_'+res.data.comment.id}).css("opacity",0);
                        var myTemp  = $("#zfzc_mb").html();
                        var outTemp = Handlebars.compile(myTemp);
                        $sour.html(outTemp(res));
                        $sour.animate({opacity: 1},"fast");
                    }else{
                        message_box('摘抄失败！～', 'noteok');
                        $('#pick-note-dialog').remove();
                    }
                }
            });
        }

    });
}
function deleteSel(){
    $(".full-article-ready").find("span.yellowBgApplier").each(function(){
        var text=$(this).html();
        $(this).replaceWith(text);
    });
}