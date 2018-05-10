define(['jquery'],function($){

    // common_asy_sys_nav
    // 每次pjax后需要更新的nav
    // 此js需要配合<BeforeTemplate:page_init>

    var o_document = $(document);

    // 导航的关联class
    var nav_base_class     = ".j_nav_class";
    var nav_cur_pre_class  = ".j_navname_";
    var nav_on_class_name  = "active";

    // 页面发生切换
    o_document.on('Jt_page_change',function(e,cur_click_href){

        var _site_url = pinkephp.website_url.substring(0, pinkephp.website_url.lastIndexOf('/'));       
        if(typeof cur_click_href != "undefined" && cur_click_href.indexOf('http://') == -1){
            cur_click_href = _site_url + cur_click_href;
            // 只保留前几位
            // 例如http://store.com/Admin/Store/stock_out/?search_field=&keyword=&_filter=inout_type_id&_filter_content=6
            // 上面的链接只保留http://store.com/Admin/Store/stock_out
            var _href_arr = cur_click_href.split("/",6);
            cur_click_href = _href_arr.join("/");
        }

        document.body.style.overflow="auto";//隐藏页面水平和垂直滚动条 

        $(nav_base_class).removeClass(nav_on_class_name);

        $('.jt_page_change').each(function(i,ele){

            var jt_obj    = $(ele);
            var _config   = jt_obj.data();
            var _top_tab_name = jt_obj.attr('data-top-tab-name');

            var _cur_url = jt_obj.attr('data-cur-url');
            if(_cur_url.indexOf('http://') == -1){
                _cur_url = _site_url + _cur_url;
            }
            var _cur_url_arr = _cur_url.split("/");
            if( $.inArray('getfilterlist', _cur_url_arr) > -1){
                return ; // 有一定概率会出现getfilterlist，暂时未深入为什么会出现
            }
            if(typeof cur_click_href != "undefined"){
                var cur_click_href_lower = cur_click_href.toLowerCase();
            }else{
                var cur_click_href_lower = "";
            }

            if( typeof jt_obj.attr('data-is-init') == "undefined" || jt_obj.attr('data-is-init') == "false" || _cur_url == cur_click_href_lower ){
                if(_top_tab_name != ''){
                    $('.j_left_menu_top_tab_name').hide();
                    $('.j_left_menu_top_tab_name_'+_top_tab_name).show();

                    $('.j_top_menu_top_tab_name').removeClass('active');
                    $('.j_top_menu_top_tab_name_'+_top_tab_name).addClass('active');
                    // console.log('j_menu_top_tab_name_'+_top_tab_name);
                    $('.j_left_menu_mca').removeClass('active');
                    $('.j_left_menu_mca_admin_'+_config.controller+'_'+_config.action).addClass('active');
                }

                if( typeof _config.loadJs != "undefined" && _config.loadJs != ""){
                    require([pinkephp.web_static_url+'static/async_page_js/'+_config.module+'/'+_config.loadJs+".js"],function(thispage){
                        jt_obj.show(); // css加载完毕 显示
                        var _aa = thispage.createObj();
                        _aa.init(_config);
                    });
                }
                if( typeof _config.loadVue != "undefined" && _config.loadVue != ""){
                    require([pinkephp.web_static_url+'static/async_page_js/'+_config.module+'/'+_config.loadVue+".js"],function(thispage){
                        var _aa = thispage.createObj();
                        _aa.init(_config);
                        console.log("loadVue");
                    });
                }
                jt_obj.attr("data-is-init","true");
            }
        });  
        o_document.trigger('Jt_builder_form_init');
    });
    // 第一次初始化
    o_document.trigger('Jt_page_change');





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

    $('[data-toggle="layout"]').on('click', function(){
        var $btn = $(this);

        var $lPage  = $('#page-container');
        var $windowW = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

        if ($windowW > 991) {
            if ($lPage.hasClass('sidebar-mini')) {
                $lPage.removeClass('sidebar-mini');
                // Cookies.remove('sidebarMini');
            } else {
                $lPage.addClass('sidebar-mini');
                // Cookies.set('sidebarMini', 'true', { expires: 7 });
            }
        }
    });

});