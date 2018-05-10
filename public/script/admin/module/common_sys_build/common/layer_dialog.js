define(['jquery','layer_dialog','./jquery_custom'],function($,layer){

    // 弹出框显示页面
    $(document).on('click','.J_layer_dialog',function () {
        var $url    = $(this).attr('data-url');
        var $title  = $(this).attr('data-title');
        var $width  = $(this).attr('data-width');
        var $height = $(this).attr('data-height');
        if(typeof $width == 'undefined'){
            $width = "80%";
        }
        if(typeof $height == 'undefined'){
            var area_arr = [$width,"auto"];
        }else{
            var area_arr = [$width,$height];
        }

        // console.log('J_layer_dialog click');

        $.ajax({
            type : 'get',
            url  : $url,
            success: function(res) {
                if(res.code == 200){
                    layer.open({
                        type: 1,
                        fixed :false,
                        offset : '50px',
                        anim: 1,      // 0-6的动画形式，-1不开启
                        title: $title,
                        area: area_arr,
                        content: res.data
                    });
                    // layer.autoArea(index);
                    // console.log('Jt_builder_form_init trigger');
                    $(document).trigger('Jt_builder_form_init');
                }else{
                    $.custom.alert(res.msg)
                }
            }
        });
        return false;
    });

    // 弹出框显示页面
    $(document).on('click','.J_layer_iframe',function () {
        var $url   = $(this).attr('data-url');
        var $title = $(this).attr('title') || $(this).data('original-title');
        var $width = $(this).attr('data-width');
        var $height = $(this).attr('data-height');
        if(typeof $width == 'undefined'){
            $width = "80%";

        }
        if(typeof $height == 'undefined'){
            var area_arr = [$width,"auto"];
        }else{
            var area_arr = [$width,$height];
        }

        layer.open({
            type: 2,
            fixed :false,
            offset : '50px',
            anim: 1,      // 0-6的动画形式，-1不开启
            title: dtitle,
            area: area_arr,
            content: $url
        });

        return false;
    });
    
    $(document).on("click",".J_ajax_post_url",function(){
        var o_this = $(this),
        url        = o_this.attr('data-url');
        var _data  = o_this.data();
        $.ajax({
            type : 'post',
            url  : url,
            data : _data,
            success: function(res) {
                if(res.code==200){
                    layer.msg(res.msg,{time: 500, icon:6});
                    if(typeof res.data.backurl != 'undefined' && res.data.backurl != ''){
                        // window.location.href=res.data.backurl;
                        $.custom.redirect(res.data.backurl);
                    }

                }else{
                    $.custom.alert(res.msg)
                }
            }
        });
    });

    $(document).on("click",".J_confirmurl",function(){
        var o_this = $(this),
        id         = o_this.attr('data-id')
        uri        = o_this.attr('data-uri'),
        dtitle     = o_this.attr('data-title'),
        msg        = o_this.attr('data-msg');

        var ids    = "";
        // 批量操作
        var target_form = o_this.attr('data-target-from');
        if( typeof target_form != "undefined"){
            var ids_arr = [];
            $('input.ids').each(function(i,ele){
                if($(this).is(':checked')){
                    ids_arr.push($(this).val());
                }
            });
            ids = ids_arr.join(",");
        }

        layer.open({
            title : dtitle,
            icon:0,
            content: msg,
            yes: function(index, layero){
                $.ajax({
                    type : 'post',
                    url  : uri,
                    data : {id:id,ids:ids},
                    success: function(res) {
                        layer.close(index);
                        if(res.code==200){
                            layer.msg(res.msg,{time: 500, icon:6},function(){
                                $.custom.reload();
                                // window.location.reload();
                            });
                        }else{
                            $.custom.alert(res.msg)
                        }
                    }
                });
            }
        });
    });

    $(document).on("click",".J_batch_layer_dialog",function(){
        var o_this = $(this),
        id         = o_this.attr('data-id')
        uri        = o_this.attr('data-uri'),
        width      = o_this.attr('data-width'),
        height     = o_this.attr('data-height'),
        dtitle     = o_this.attr('data-title'),
        msg        = o_this.attr('data-msg');
        var ids    = "";

        // 批量操作
        var target_form = o_this.attr('data-target-from');
        if( typeof target_form != "undefined"){

            var ids_arr = [];
            $('input.ids').each(function(i,ele){
                if($(this).is(':checked')){
                    ids_arr.push($(this).val());
                }
            });
            ids = ids_arr.join(",");
        }
        // console.log('J_batch_layer_dialog click');
        $.getJSON(uri, {ids:ids},function(result){
            if(result.code == 200){

                layer.open({
                    type: 1,
                    fixed :false,
                    offset : '50px',
                    anim: 1,      // 0-6的动画形式，-1不开启
                    title: dtitle,
                    area: [width,height],
                    content: result.data
                });
                // console.log('Jt_builder_form_init  trigger');
                $(document).trigger('Jt_builder_form_init');

            }else{
                $.custom.alert(result.msg);
            }
        });
    });


});