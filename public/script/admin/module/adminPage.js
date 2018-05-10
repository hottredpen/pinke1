require.config({
    baseUrl: "/script/admin/module",//用绝对位置
    shim: {
        'validator'    : ['jquery'],
        'bootstrap'    : ['jquery'],
        'layer_dialog' : ['jquery','css!layer_css'],
        'webuploader'  : ['css!webuploader_css'],
        // 'fancybox'     : ['css!fancybox_css'],
        'pjax': { 
            deps       : ['jquery'],
            exports    : 'pjax'
        },
        'nprogress':{
            deps : ['css!nprogress_css'],
            exports: 'nprogress'
        },
        // 以下非核心
        'jquery_datepicker' : ['jquery','css!jquery_datepicker_css'],
        'jquery_fancybox'   : ['jquery','css!jquery_fancybox_css'],
        'jquery_treetable'  : ['jquery'],
    },
    paths: {
        // domReady
        'domReady'        : "../../../bower_components/domReady/domReady",
        // jquery
        'jquery'          : "../../../bower_components/jquery/dist/jquery.min",
        // md5(加密)
        'js_md5'          : "../../../bower_components/blueimp-md5/js/md5.min",
        // bootstrap(ui基础)
        'bootstrap'       : "../../../bower_components/bootstrap/dist/js/bootstrap.min",
        // cookie
        'cookie'          : "../../../bower_components/js-cookie/src/js.cookie",
        // layer(此处需要重命名,不然打包不了的)
        'layer_dialog'    : "../../../bower_components/layer/build/layer",
        'layer_css'       : "../../../bower_components/layer/build/skin/default/layer",
        // validator(表单提交验证)
        'validator'       : "../../../bower_components/nice-validator/dist/jquery.validator",
        // pjax
        'pjax'            : "../../../bower_components/jquery-pjax/jquery.pjax",
        // nprogress
        'nprogress'       : "../../../bower_components/nprogress/nprogress",
        'nprogress_css'   : "../../../bower_components/nprogress/nprogress",
        // webuploader
        'webuploader'     : "../../../bower_components/fex-webuploader/dist/webuploader.min",
        'webuploader_css' : "../../../bower_components/fex-webuploader/dist/webuploader",
        // fancybox
        // 'fancybox'        : "../../../bower_components/fancybox/dist/jquery.fancybox.min",
        // 'fancybox_css'    : "../../../bower_components/fancybox/dist/jquery.fancybox",

        // 以下非核心
        'artdialog'             : "../../../bower_components/artDialog/dist/dialog-min",
        'jquery_datepicker'     : "../../../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min",
        'jquery_datepicker_css' : "../../../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker",
        'bootstrap_notify'      : "../../../bower_components/remarkable-bootstrap-notify/dist/bootstrap-notify.min",        
        'jquery_treetable'      : "../../../bower_components/jquery-treetable/jquery.treetable",
        'jquery_fancybox'       : "../../../bower_components/fancybox/dist/jquery.fancybox.min",
        'jquery_fancybox_css'   : "../../../bower_components/fancybox/dist/jquery.fancybox",

    },
    map: {
        '*': {
            'css': '../../../bower_components/require-css/css'
        }
    }
});
// common_sys_build 为系统内部调用
// common_dev_build 第三方开发者的js入口
define(['common_sys_build','common_dev_build','bootstrap'],function(){
    // 初始化时用到的js(此处是实验用地，非正式，未归类的js)
    $(function(){
    // $.fancybox({
    //     afterLoad: function () {
    //         $(".fancybox-opened,.fancybox-overlay").css("z-index", 9999);
    //     }
    // });
    });

});