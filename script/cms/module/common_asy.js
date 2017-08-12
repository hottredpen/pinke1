define(['jquery'],function($){
	var o_document = $(document);

    // 导航的关联class
    var nav_base_class     = ".j_nav_class";
    var nav_cur_pre_class  = ".j_navname_";
    var nav_on_class_name  = "active";


    // 页面发生切换
    o_document.on('Jt_page_change',function(){

        document.body.style.overflow="auto";//隐藏页面水平和垂直滚动条 


        $(nav_base_class).removeClass(nav_on_class_name);
        // 为了md5后不被类似名称的替换掉，在尾部加了后缀page
        var asyload_arr = [
        "pages/indexpage",
        "pages/productpage",
        "pages/custompage",
        "pages/newspage",
        "pages/aboutpage",
        "pages/contactpage",
        "pages/cmappage",
        "pages/product_detailpage",
        "pages/servicepage"
        ];
        $.each(asyload_arr,function(i,asyload){
            var _pagename = asyload.replace(/pages\//g,"");
            var _reg      = /pages\/(\w+)page/g;
            var _res_arr  = _reg.exec(asyload);
            var _name     = _res_arr[1];
            var _nav_name = _name.split("_")[0];
            if($("#jt_page_"+ _name).length > 0){
                $(nav_cur_pre_class+_nav_name).addClass(nav_on_class_name);
                require(['./pages/'+_pagename],function(thispage){
                    thispage.init();
                });
            }
        });
        // 页面中存在表单验证提交的
        if($('#j_artdialog_form').length > 0){
            require(['validator'],function(){
                $('#j_artdialog_form').validator(function(form){
                    $.ajax({
                        type : 'post',
                        url  : $(form).attr("action"),
                        data : $(form).serialize(),
                        success: function(res) {
                            if(res.status==1){
                                if(typeof res.data != 'undefined' && res.data != null){
                                    var r_data = res.data;
                                    if(typeof r_data.redirect_url != 'undefined'){
                                        window.location.href = r_data.redirect_url;
                                    }
                                }else{
                                    $.toast(res.msg, function() {
                                        window.location.href = "/car/me/";
                                    }); 
                                }
                            }else{
                                $.alert(res.msg);
                            }
                        }
                    });
                });
            });
        }
    });

    // 第一次初始化
    o_document.trigger('Jt_page_change');
});