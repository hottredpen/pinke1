define(['jquery','artdialog','bootstrap_notify'],function($,dialog){

    var custom_cpk = {};

    custom_cpk.alert = function(msg){
    	var d = dialog({
    		id   : "j_artdialog_alert",
    	    title: '提示',
    	    content: msg,
    	    fixed : false,
    	    okValue : "确定",
    	    ok: function () {},
    	    statusbar: '<label><input type="checkbox">不再提醒</label>'
    	});
    	d.show();
    }

    custom_cpk.success_tip = function(msg){
        var d = dialog({
            content: '<span class="text-success"><i class="fa  fa-check-circle"></i>'+msg+'</span>'
        });
        d.show();
        setTimeout(function () {
            d.close().remove();
        }, 2000);
    }

    custom_cpk.error_tip = function(msg){
        var d = dialog({
            content: '<span class="text-danger"><i class="fa  fa-remove"></i>'+msg+'</span>'
        });
        d.show();
        setTimeout(function () {
            d.close().remove();
        }, 2000);
    }

    custom_cpk.dialog_remove = function(dialogid){
        if(typeof dialogid == "undefined"){
            dialogid = "j_artdialog_form";
        }
        var f= dialog.get(dialogid);
        if(typeof f != "undefined"){
            f.close().remove();
        }
    }


    custom_cpk.notify = function ($msg, $type, $icon, $from, $align) {
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


    return custom_cpk;


});