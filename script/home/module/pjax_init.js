define(['nprogress','pjax'],function(NProgress){

	var o_document = $(document);

    // pjax
    if (window.history && window.history.pushState) {
        $(window).on('popstate', function () {
            var hashLocation = location.hash;
            var hashSplit = hashLocation.split("#!/");
            var hashName = hashSplit[1];
            if (hashName !== '') {
                var hash = window.location.hash;
                if (hash === '') {
                    o_document.trigger('Jt_page_change');
                }
            }
        });
    }

    o_document.on("Jt_page_change",function(e){
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
        // todo controller问题
        $('.j_nav_class').removeClass('hover');
        // console.log("nav");
        $('.j_navname_'+ controller).addClass('hover');
        // console.log('.j_navname_'+ controller);
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
        o_document.on('pjax:success', function (event, data, status, xhr) {

            o_document.trigger('Jt_page_change');
            
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


});