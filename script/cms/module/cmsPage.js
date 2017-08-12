require.config({
    baseUrl: "/script/cms/module",//用绝对位置
    shim: {
        'validator' : ['jquery'],
        'city_picker' : ['jquery'],
        'lightgallery' : ['jquery'],
        'qrcode'       : ['jquery'],
        'lg-thumbnail' : ['lightgallery'],
        'lg-zoom'      : ['lightgallery'],
        'bootstrap'    : ['jquery'],
        'BMap': { 
            exports: 'BMap' 
        }
    },
    paths: {
        'domReady'        : "//cdn.bootcss.com/require-domReady/2.0.1/domReady",
        'jquery'          : [
            "//cdn.bootcss.com/jquery/1.11.3/jquery.min",
            "../../../static/plugins/jquery/1.11.3/jquery.min"
        ],
        // common

        // async
        'swiper'          : "../../../static/plugins/swiper/js/swiper.min",
        'qrcode'          : "../../../static/plugins/qrcode/jquery.qrcode.min",

        // lightgallery
        'lightgallery'    : "../../../static/plugins/lightgallery/js/lightgallery",
        'lg-thumbnail'    : "../../../static/plugins/lightgallery/js/lg-thumbnail",
        'lg-zoom'         : "../../../static/plugins/lightgallery/js/lg-zoom",
        'bootstrap'       : "../../../static/plugins/bootstrap/3.3.5/bootstrap.min",

        'validator'       : "../../../static/plugins/validator/dist/jquery.validator",
        'city_picker'     : "../../../static/plugins/jqueryweui/js/city-picker",
        'nouislider'      : "../../../static/plugins/nouislider/nouislider",
        'cookie'          : "../../../static/plugins/cookie/js.cookie",
        'wxjssdk'         : "http://res.wx.qq.com/open/js/jweixin-1.0.0",
        'BMap'            : "http://api.map.baidu.com/getscript?v=1.1&ak=&services=true&t=20130716024058",
    },
    map: {
        '*': {
            'css': '../../../static/cpk/public/js/css'
        }
    }
});
define(['common','common_asy','common_plugins','common_init','bootstrap'],function(){

    // 本页常用的
    var o_document = $(document);



    o_document.on("mouseover",".dropdown",function(){
        $(this).addClass("open");  
    });
    o_document.on("mouseleave",".dropdown",function(){
        $(this).removeClass("open");  
    });
    
    // 回到顶部
    o_document.on("click","#J_goto_pagetop",function(){
        $('html,body').animate({scrollTop: '0px'}, 800);
    });


    var window_width = $(window).width();
    var doc_width    = $(document).width();
    var window_height = $(window).height();
    var doc_height    = $(document).height();

    if(doc_width < 450){
        // 手机
    }else{
        // logo 偏移 总长度的 2/12
        var log_offset = parseInt(doc_width*2/12);
        var ul_offset  = parseInt(doc_width - log_offset - 100 - 670);
        $('.j_navbar_logo').animate({left:''+log_offset+'px'});
        $('.j_navbar_ul').animate({'margin-right':''+ul_offset+'px'});
        console.log('j_navbar_logo');
    }



});