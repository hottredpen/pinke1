require.config({
    baseUrl: "/script/admin/module",//用绝对位置
    shim: {
        'validator' : ['jquery'],
        'uploadify' : ['jquery'],
        'city_picker' : ['jquery'],
        'lightgallery' : ['jquery'],
        'lg-thumbnail' : ['lightgallery'],
        'lg-zoom'      : ['lightgallery'],
        'bootstrap'    : ['jquery'],
        'jquery-slimscroll' : ['jquery'],
        'jquery-countTo'    : ['jquery'],
        'jquery-placeholder': ['jquery'],
        'jquery-appear'     : ['jquery'],
        'jquery-treetable'  : ['jquery'],
        'jquery-datepicker' : ['jquery','css!jquery-datepicker-css'],
        'nestable'          : ['jquery'],
        'layer_dialog'      : ['jquery'],
        'common_custom'     : ['layer_dialog'],
        'BMap': { 
            exports: 'BMap' 
        },
        'ueditor' : {
            deps : ['ueditor_config'],
            exports : 'ueditor'
        },
        'artdialog':{
            deps : ['jquery'],
            exports: 'dialog'
        },
        // 'app' : {
        //     deps: ['jquery','bootstrap','slick','jquery-slimscroll','jquery-scrollLock','jquery-appear','jquery-countTo','jquery-placeholder','cookie'],
        //     exports: 'App' 
        // }
    },
    paths: {
        'domReady'        : "//cdn.bootcss.com/require-domReady/2.0.1/domReady",
        'jquery'          : [
            "//cdn.bootcss.com/jquery/1.11.3/jquery.min",
            "../../../static/plugins/jquery/1.11.3/jquery.min"
        ],
        'js_md5'            : [
            "//cdn.bootcss.com/blueimp-md5/1.1.0/js/md5",
            "../../../static/plugins/md5/1.1.0/md5"
        ],
        // oneui core
        'bootstrap'         : "../../../static/plugins/bootstrap/3.3.5/bootstrap.min",
        'bootstrap_notify'  : "../../../static/plugins/bootstrap-notify/bootstrap-notify.min",
        'jquery-slimscroll' : "../../../static/plugins/jquery-slimScroll/jquery.slimscroll.min",
        'jquery-scrollLock' : "../../../static/plugins/jquery-scrollLock/jquery.scrollLock.min",
        'jquery-appear'     : "../../../static/plugins/jquery-appear/jquery.appear.min",
        'jquery-countTo'    : "../../../static/plugins/jquery-countTo/jquery.countTo.min",
        'jquery-placeholder': "../../../static/plugins/jquery-placeholder/jquery.placeholder.min",
        'cookie'            : "../../../static/plugins/cookie/js.cookie",
        'slick'             : "../../../static/plugins/slick/slick.min",
        // 'app'               : "../../../static/plugins/oneui/js/app",

        'chart'             : "../../../static/plugins/chartjs/Chart.min",

        'echarts_all'       : "../../../static/plugins/echarts/dist/echarts-all",

        // async
        'swiper'          : "../../../static/plugins/swiper/js/swiper.min",
        'uploadify'       : "../../../static/plugins/uploadify/jquery.uploadify",

        // lightgallery
        'lightgallery'    : "../../../static/plugins/lightgallery/js/lightgallery",
        'lg-thumbnail'    : "../../../static/plugins/lightgallery/js/lg-thumbnail",
        'lg-zoom'         : "../../../static/plugins/lightgallery/js/lg-zoom",
        
        // artdialog
        'artdialog'       : "../../../static/plugins/artdialog/js/dialog-min",
        'ueditor'         : "../../../static/plugins/ueditor/ueditor.all.min",
        'ueditor_config'  : "../../../static/plugins/ueditor/ueditor.config",

        // layer(此处需要重命名,不然打包不了的)
        'layer_dialog'    : "../../../static/plugins/layer/3.0.3/layer",

        'jquery-treetable': "../../../static/plugins/treetable/js/jquery.treetable",
        'jquery-datepicker' : "../../../static/plugins/datepicker/js/bootstrap-datepicker.min",
        'jquery-datepicker-css' : "../../../static/plugins/datepicker/css/bootstrap-datepicker",
        'nestable'        : "../../../static/plugins/nestable/jquery.nestable",
        'validator'       : "../../../static/plugins/validator/dist/jquery.validator",
        'city_picker'     : "../../../static/plugins/jqueryweui/js/city-picker",
        'nouislider'      : "../../../static/plugins/nouislider/nouislider",
        
        'wxjssdk'         : "http://res.wx.qq.com/open/js/jweixin-1.0.0",
        'BMap'            : "http://api.map.baidu.com/getscript?v=1.1&ak=&services=true&t=20130716024058",

    },
    map: {
        '*': {
            'css': '../../../static/plugins/css/css'
        }
    }
});
// common_sys_build  系统需打包的js块（非授权下，压缩版）
// common_sys_asy    系统异步载入的js块（非授权下，压缩版）
// common_dev_build  二次开发者需打包进入的js块
// common_dev_asy    二次开发者异步载入的js块
define(['common_sys_build','common_sys_asy','common_dev_build','common_dev_asy','bootstrap'],function(){
// define(['common','common_on_Jt','common_custom','common_asy','common_plugins','common_init','common_onbtn'],function(){

    // 初始化时用到的js(此处是实验用地，非正式，未归类的js)
    // dosomething test

        // When a submenu link is clicked
        $('[data-toggle="nav-submenu"]').on('click', function(e){
            // Get link
            var $link    = $(this);

            // Get link's parent
            var $parentLi = $link.parent('li');

            if ($parentLi.hasClass('open')) { // If submenu is open, close it..
                $parentLi.removeClass('open');
            } else { // .. else if submenu is closed, close all other (same level) submenus first before open it
                $parentLi
                    .addClass('open');
            }
            return false;
        });





});