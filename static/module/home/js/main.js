$(function(){

    var o_document = $(document);


    focus_(".banner", 2, 4000, 500);

    // 导航
    o_document.on("click","#nav li",function(){
        $("#nav li a").removeClass("hover");
        $(this).children("a").addClass("hover");
    });

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

    // pjax
    if (window.history && window.history.pushState) {
        $(window).on('popstate', function () {
            var hashLocation = location.hash;
            var hashSplit = hashLocation.split("#!/");
            var hashName = hashSplit[1];
            if (hashName !== '') {
                var hash = window.location.hash;
                if (hash === '') {
                    $(document).trigger('Jt_change_nav');
                }
            }
        });
    }

    o_document.on("Jt_change_nav",function(e){
        var _pathname = window.location.pathname;
        var _controller_arr = _pathname.split("/");
        var controller;
        for(var i = 0 ;i<_controller_arr.length;i++){
            if(_controller_arr[i] == "" || typeof(_controller_arr[i]) == "undefined"){
                _controller_arr.splice(i,1);
                i= i-1;
            }
        }
        if(typeof _controller_arr[0] == "undefined"){
            controller = "index";
        }else{
            if(_controller_arr[0] == "Home" || _controller_arr[0] == "home"){
                controller = _controller_arr[1];
            }else{
                controller = _controller_arr[0];
            }
        }
        $('.j_nav_class').removeClass('hover');
        $('.j_navname_'+ controller).addClass('hover');
    });


    NProgress.configure({
        template: '<div class="bar" role="bar" style="background: #3388ff"><div class="peg" style="box-shadow: 0 0 10px #3388ff, 0 0 5px #3388ff;"></div></div><div class="spinner" role="spinner"><div class="spinner-icon" style="border-top-color:#3388ff;border-left-color: #3388ff;"></div></div>'
    });
    if ($.support.pjax) {

        o_document.on('click', 'a[target!=_blank][target!=_self][class!="j_not_pjax"]', function(event) {
            var container = '#pjax-container';
            $.pjax.click(event, {container: container});
        })

        $.pjax.defaults.timeout = 6000;
        $.pjax.defaults.dataType = "html";

        o_document.on('pjax:send', function () {
            NProgress.start();
        });
        o_document.on('pjax:complete', function () {
            NProgress.done();
        });
        o_document.on('pjax:timeout', function (event) {
            // Prevent default timeout redirection behavior
            event.preventDefault()
        });
        o_document.on('pjax:beforeReplace', function (contents, options) {
            //处理服务器返回的json通知
            if (options['0'].data != undefined) {
                options['0'].data = '';
            }
        });
        o_document.on('submit', 'form', function (event) {
            //隐藏返回值
            $.pjax.submit(event, '#pjax-container', {push: false});
        });
        o_document.on('pjax:success', function (event, data, status, xhr) {

            o_document.trigger('Jt_change_nav');
            
            //正则匹配JSON
            if (data.match("^\{(.+:.+,*){1,}\}$")) {
                var data = JSON.parse(data);

                if (data.code == 200) {
                    toastr.success(data.msg);
                } else if (data.status == 0) {
                    toastr.error(data.msg)
                }

                if (data.data) {
                    $.pjax({
                        url: data.data,
                        container: '#pjax-container'
                    })
                }
            }
        });
    }



    $("#j_index_cases_scroll").cxScroll();

    

    // 回到顶部
    o_document.on("click","#J_goTop",function(){
        $('html,body').animate({scrollTop: '0px'}, 800);
    });


    //分页的跳转第几页
    o_document.on("blur","input[name=page_jump_to_value]",function(){
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
});