require.config({
    baseUrl: "/script/home/module",//用绝对位置
    shim: {
        'pjax' : ['jquery'],
        'validator' : ['jquery'],
        'jquery_weui' : ['jquery'],
        'city_picker' : ['jquery'],
        'cxscroll'    : ['jquery'],        
        'BMap': { 
            exports: 'BMap' 
        } 
    },
    paths: {
        'domReady'        : "//cdn.bootcss.com/require-domReady/2.0.1/domReady",
        'jquery'          : "../../../static/plugins/jquery/1.11.3/jquery.min",
        'fastclick'       : "../../../static/plugins/fastclick/fastclick",
        'swiper'          : "../../../static/plugins/swiper/js/swiper.min",
        'nprogress'       : "../../../static/plugins/nprogress/nprogress",
        'pjax'            : "../../../static/plugins/pjax/jquery.pjax",
        'validator'       : "../../../static/plugins/validator/dist/jquery.validator",
        'jquery_weui'     : "../../../static/plugins/jqueryweui/js/jquery-weui",
        'city_picker'     : "../../../static/plugins/jqueryweui/js/city-picker",
        'cxscroll'        : "../../../static/plugins/cxscroll/cxscroll",
        'BMap'            : "http://api.map.baidu.com/getscript?v=1.1&ak=&services=true&t=20130716024058",
    },
    map: {
        '*': {
            'css': '../../../static/cpk/public/js/css'
        }
    }

});
define(['swiper','fastclick','common','common_init','pjax_init','validator'],function(Swiper,FastClick){

    var o_document = $(document);

    o_document.ready(function(){
        FastClick.attach(document.body);
    });

    // 回到顶部
    o_document.on("click","#J_goTop",function(){
        $('html,body').animate({scrollTop: '0px'}, 800);
    });

    o_document.on('Jt_page_change',function(){
        if($("#jt_page_index").length > 0){
            // indexpage.init();
            require(['./pages/index'],function(indexpage){
                indexpage.init();
            });

        }
        if($("#jt_page_contact_map").length > 0){
            require(['./pages/contact_map'],function(contact_map){
                contact_map.init();
            });
        }
        $('#j_artdialog_form').validator(function(form){
            $.ajax({
                type : 'post',
                url  : $(form).attr("action"),
                data : $(form).serialize(),
                success: function(res) {
                    if(res.status==1){
                        $.toast(res.msg, function() {
                            console.log('close');
                        });
                        window.location.href = "/car/me/";
                    }else{
                        $.alert(res.msg);
                    }
                }
            });
        });

    });

    // 第一次初始化
    o_document.trigger('Jt_page_change');

    // 收藏本站
    o_document.on("click",".J_add_favorite",function(){
        var url = $(this).attr("data-url");
        var title = $(this).attr("data-title");
        try {
            window.external.addFavorite(url, title);
        }catch (e) {
            try {
                window.sidebar.addPanel(title, url, "");
            }
            catch (e) {
                alert("抱歉，您所使用的浏览器无法完成此操作。\n\n加入收藏失败，请使用Ctrl+D进行添加");
            }
        }
    });


    // 幻灯片
    var mySwiper = new Swiper ('.swiper-container', {
        // 如果需要分页器
        pagination: '.swiper-pagination',
        // paginationType: 'fraction', // 数字形式
        // 如果需要前进后退按钮
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        paginationClickable: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
    });

});