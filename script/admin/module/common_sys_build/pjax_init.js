define(['jquery','../cpk_plugins/nprogress','../cpk_plugins/pjax','./jquery_extend'],function($,NProgress){
// 2017.5.23 hottredpen@126.com 根据 a中data-pjax-container选择需要替换掉的内容块
// 2017.7.15 hottredpen@126.com 添加了cur_click_href 为了Jt_page_change更新改变相应的导航便签
    console.log('pjax_init');

    var o_document            = $(document);
    var cur_click_href        = "";
    var config  = {};
    config.pjax_container_default = "#pjax-container";
    config.pjax_container_click   = "#pjax-container";

    // pjax
    if (window.history && window.history.pushState) {
        $(window).on('popstate', function () {
            var hashLocation = location.hash;
            var hashSplit = hashLocation.split("#!/");
            var hashName = hashSplit[1];
            if (hashName !== '') {
                var hash = window.location.hash;
                console.log(window.history.state.url);
                if (hash === '') {
                    o_document.trigger('Jt_page_change',[window.history.state.url]);
                }
            }
        });
    }

    NProgress.configure({
        template: '<div class="bar" role="bar" style="background: #3388ff"><div class="peg" style="box-shadow: 0 0 10px #3388ff, 0 0 5px #3388ff;"></div></div><div class="spinner" role="spinner"><div class="spinner-icon" style="border-top-color:#3388ff;border-left-color: #3388ff;"></div></div>'
    });
    if ($.support.pjax) {

        o_document.on('click', 'a[target!=_blank][target!=_self][class!="j_not_pjax"]', function(event) {
            console.log('pjax');
            var _pjax_container = $(this).attr('data-pjax-container');

            cur_click_href = $(this).attr('href');
            if(typeof _pjax_container != "undefined"){

                if($(_pjax_container).length > 0){
                    config.pjax_container_click = _pjax_container;
                }else{
                    config.pjax_container_click = config.pjax_container_default;
                }
            }else{
                config.pjax_container_click = config.pjax_container_default;
            }
            //console.log("config.pjax_container_click");
            // console.log(config.pjax_container_click);
            $.pjax.click(event, {container: config.pjax_container_click});
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
        // o_document.on('submit', 'form', function (event) {
        //     //隐藏返回值
        //     $.pjax.submit(event, '#pjax-container', {push: false});
        // });
        o_document.on('pjax:success', function (event, data, status, xhr) {

            o_document.trigger('Jt_page_change',[cur_click_href]);

            //正则匹配JSON
            if (data.match("^\{(.+:.+,*){1,}\}$")) {
                var data = JSON.parse(data);

                if (data.status == 0) {
                    // pjax跳转错误时
                    $.custom.notify(data.msg);
                    window.history.go(-1);
                    return;
                }

                if(data.status == 1){
                    $.custom.success_tip(data.msg);
                    if(typeof data.data.backurl != "undefined"){
                        $.pjax({
                            url: data.data.backurl,
                            container: config.pjax_container_click
                        });
                    }
                }
            }
        });
    }


});