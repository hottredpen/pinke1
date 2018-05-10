define(['jquery','layer_dialog','bootstrap_notify'],function($,layer){
    // 系统的统一友好提示输出
    $.custom = $.custom || {version : "v1.0.0",name : "pk"}

    $.extend($.custom,{});

    $.custom.alert = function(msg){
        layer.alert(msg);
    }

    $.custom.msg = function(msgs){
        layer.msg(msgs,{time: 500, icon:6});
    }

    $.custom.error_msg = function(msg){
        layer.msg(msg,{time: 500, icon:5});
    }

    $.custom.notify = function(msg){
        custom_notify(msg);
    }

    $.custom.error_notify = function(msg){
        custom_notify(msg,'danger');
    }

    $.custom.reload = function(){
        $('#j_layout_click_a_to_url').trigger('click');
        layer.closeAll('page');
    }

    $.custom.redirect = function(backurl){
        if(backurl != ""){
            $('#j_layout_click_a_to_url').attr('href',backurl).trigger('click');
            layer.closeAll('page');
        }
    }

    var custom_notify = function ($msg, $type, $icon, $from, $align) {
        $type  = $type || 'info';
        $from  = $from || 'top';
        $align = $align || 'center';
        $enter = $type === 'success' ? 'animated fadeInUp' : 'animated shake';

        $.notify({
            icon: $icon,
            message: $msg
        },
        {
            element: 'body',
            type: $type,
            allow_dismiss: true,
            newest_on_top: true,
            showProgressbar: false,
            placement: {
                from: $from,
                align: $align
            },
            offset: 20,
            spacing: 10,
            z_index: 10800,
            delay: 3000,
            timer: 1000,
            animate: {
                enter: $enter,
                exit: 'animated fadeOutDown'
            }
        });
    };
});