define(['jquery'],function($){
    //console.log("commonTool");
    var o_document = $(document);

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




    }
);