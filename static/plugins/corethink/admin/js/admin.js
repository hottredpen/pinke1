/* 扩展OpenCMF对象 */
(function($){
    /**
     * 获取OpenCMF基础配置
     * @type {object}
     */
    var OpenCMF = window.OpenCMF;

    /* 基础对象检测 */
    OpenCMF || $.error("OpenCMF基础配置没有正确加载！");

    /**
     * 解析URL
     * @param  {string} url 被解析的URL
     * @return {object}     解析后的数据
     */
    OpenCMF.parse_url = function(url){
        var parse = url.match(/^(?:([a-z]+):\/\/)?([\w-]+(?:\.[\w-]+)+)?(?::(\d+))?([\w-\/]+)?(?:\?((?:\w+=[^#&=\/]*)?(?:&\w+=[^#&=\/]*)*))?(?:#([\w-]+))?$/i);
        parse || $.error("url格式不正确！");
        return {
            "scheme"   : parse[1],
            "host"     : parse[2],
            "port"     : parse[3],
            "path"     : parse[4],
            "query"    : parse[5],
            "fragment" : parse[6]
        };
    }

    OpenCMF.parse_str = function(str){
        var value = str.split("&"), vars = {}, param;
        for(val in value){
            param = value[val].split("=");
            vars[param[0]] = param[1];
        }
        return vars;
    }

    OpenCMF.parse_name = function(name, type){
        if(type){
            /* 下划线转驼峰 */
            name.replace(/_([a-z])/g, function($0, $1){
                return $1.toUpperCase();
            });

            /* 首字母大写 */
            name.replace(/[a-z]/, function($0){
                return $0.toUpperCase();
            });
        } else {
            /* 大写字母转小写 */
            name = name.replace(/[A-Z]/g, function($0){
                return "_" + $0.toLowerCase();
            });

            /* 去掉首字符的下划线 */
            if(0 === name.indexOf("_")){
                name = name.substr(1);
            }
        }
        return name;
    }

    //scheme://host:port/path?query#fragment
    OpenCMF.U = function(url, vars, suffix){
        var info = this.parse_url(url), path = [], param = {}, reg;

        /* 验证info */
        info.path || $.error("url格式错误！");
        url = info.path;

        /* 组装URL */
        if(0 === url.indexOf("/")){ //路由模式
            this.MODEL[0] == 0 && $.error("该URL模式不支持使用路由!(" + url + ")");

            /* 去掉右侧分割符 */
            if("/" == url.substr(-1)){
                url = url.substr(0, url.length -1)
            }
            url = ("/" == this.DEEP) ? url.substr(1) : url.substr(1).replace(/\//g, this.DEEP);
            url = "/" + url;
        } else { //非路由模式
            /* 解析URL */
            path = url.split("/");
            path = [path.pop(), path.pop(), path.pop()].reverse();
            path[1] || $.error("OpenCMF.U(" + url + ")没有指定控制器");

            if(path[0]){
                param[this.VAR[0]] = this.MODEL[1] ? path[0].toLowerCase() : path[0];
            }

            param[this.VAR[1]] = this.MODEL[1] ? this.parse_name(path[1]) : path[1];
            param[this.VAR[2]] = path[2].toLowerCase();

            url = "?" + $.param(param);
        }

        /* 解析参数 */
        if(typeof vars === "string"){
            vars = this.parse_str(vars);
        } else if(!$.isPlainObject(vars)){
            vars = {};
        }

        /* 解析URL自带的参数 */
        info.query && $.extend(vars, this.parse_str(info.query));

        if(vars){
            url += "&" + $.param(vars);
        }

        if(0 != this.MODEL[0]){
            url = url.replace("?" + (path[0] ? this.VAR[0] : this.VAR[1]) + "=", "/")
                     .replace("&" + this.VAR[1] + "=", this.DEEP)
                     .replace("&" + this.VAR[2] + "=", this.DEEP)
                     .replace(/(\w+=&)|(&?\w+=$)/g, "")
                     .replace(/[&=]/g, this.DEEP);

            /* 添加伪静态后缀 */
            if(false !== suffix){
                suffix = suffix || this.MODEL[2].split("|")[0];
                if(suffix){
                    url += "." + suffix;
                }
            }
        }

        url = this.APP + url;
        return url;
    }

    /* 设置表单的值 */
    OpenCMF.setValue = function(name, value){
        var first = name.substr(0,1), input, i = 0, val;
        if(value === "") return;
        if("#" === first || "." === first){
            input = $(name);
        } else {
            input = $("[name='" + name + "']");
        }

        if(input.eq(0).is(":radio")) { //单选按钮
            input.filter("[value='" + value + "']").each(function(){this.checked = true});
        } else if(input.eq(0).is(":checkbox")) { //复选框
            if(!$.isArray(value)){
                val = new Array();
                val[0] = value;
            } else {
                val = value;
            }
            for(i = 0, len = val.length; i < len; i++){
                input.filter("[value='" + val[i] + "']").each(function(){this.checked = true});
            }
        } else {  //其他表单选项直接设置值
            input.val(value);
        }
    }
})(jQuery);


$(function(){
    // 一次性初始化所有弹出框
    $('[data-toggle="popover"]').popover();

    // 图片lazyload
    $('img.lazy').lazyload({
        effect         : 'fadeIn',
        data_attribute : 'src',
        placeholder    : $('#corethink_home_img').val()+'/default/default.gif'
    });

    // 刷新验证码
    $(".reload-verify").on('click', function() {
        var verifyimg = $(this).attr("src");
        if (verifyimg.indexOf('?') > 0) {
            $(this).attr("src", verifyimg + '&random=' + Math.random());
        } else {
            $(this).attr("src", verifyimg.replace(/\?.*$/, '') + '?' + Math.random());
        }
    });

    //全选/反选/单选的实现
    $('body').delegate('.check-all', 'click', function() {
        $(".ids").prop("checked", this.checked);
    });

    $('body').delegate('.ids', 'click', function() {
        var option = $(".ids");
        option.each(function() {
            if (!this.checked) {
                $(".check-all").prop("checked", false);
                return false;
            } else {
                $(".check-all").prop("checked", true);
            }
        });
    });

    //搜索功能
    $('body').delegate('.search-btn', 'click', function() {
        var url = $(this).closest('form').attr('action');
        var query = $(this).closest('form').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g, '');
        query = query.replace(/(^&)|(\+)/g, '');
        if (url.indexOf('?') > 0) {
            url += '&' + query;
        } else {
            url += '?' + query;
        }
        window.location.href = url;
        return false;
    });

    //回车搜索
    $('body').delegate('.search-input', 'keydown', function(e) {
        if (e.keyCode === 13) {
            $(this).closest('form').find('.search-btn').click();
            return false;
        }
    });


    // 设置ct-tab的宽度
    $('.ct-tab').width($(window).width()-373);

    // 打开新Tab
    $('body').delegate('#sidebar .open-tab', 'click', function() {
        var tab_url   = $(this).attr('href');
        var tab_name  = $(this).attr('tab-name');
        var is_open   = $('.ct-tab-content #' + tab_name).length;
        if(is_open !== 0){
            $('.ct-tab a[href=#' + tab_name + ']').tab('show');
        } else {
            var tab  = '<li class="new-add" style="position: relative;float:left;display: inline-block;"><a href="#'
                     + tab_name
                     + '" role="tab" data-toggle="tab">'
                     + $(this).html()
                     + '<button type="button" class="close" aria-label="Close">'
                     + '<span aria-hidden="true">&times;</span></button></a></li>';
            var tab_content = '<div role="tabpanel" class="new-add tab-pane fade" id="'
                            + tab_name
                            + '"><iframe name="#'
                            + tab_name
                            + '" class="iframe" src="'
                            + tab_url
                            +'"></iframe></div>';
            $('.ct-tab').width($('.ct-tab').width() + 60);
            $('.ct-tab').append(tab);
            $('.ct-tab-content').append(tab_content);
            $('.ct-tab a:last').tab('show');
        }
        return false;
    });

    // 关闭标签时自动取消左侧导航的active状态
    $('body').delegate('.nav-close .close', 'click', function() {
        var id  = $(this).closest('a[data-toggle="tab"]').attr('href');
        var tab = id.split('#');
        $('a[tab-name=' + tab[1] + ']').closest('li').removeClass('active');
    });

    // 关闭所有标签
    $('body').delegate('.close-all', 'click', function() {
        $('.new-add').remove();
        $('.ct-tab a:first').tab('show');
    });

    // 双击刷新标签
    $('body').delegate('.ct-tab a', 'dblclick', function() {
        var id = $(this).attr('href');
        $(id+' .iframe').attr('src', $(id+' .iframe').attr('src'));
    });

    // TAB向左滚动
    $('body').delegate('#tab-left', 'click', function() {
        var left = $('.ct-tab').position().left;
        if (left < 0) {
            $('.ct-tab').animate({left:(left+480+'px')});
        }
    });

    // TAB向右滚动
    $('body').delegate('#tab-right', 'click', function() {
        var left = $('.ct-tab').position().left;
        if(($(window).width()-373)-(left+$('.ct-tab').width()) < 0){
            $('.ct-tab').animate({left:(left-480+'px')});
        }
    });

    // 检测更新
    if ($('.ct-update').length != 0) {
        // 检测更新
        $.ajax({
            url: $('input[name="check_version_url"]').val(),
            type: 'GET',
        }).done(function(data) {console.log();
            if (data.status == 1) {
                $('.version').html(data.info);
                if (data.sn_info) {
                    $('.sn_info').html(data.sn_info);
                }
            } else {
                $.alertMessager(data.info, 'danger');
            }
        });
    }
});
