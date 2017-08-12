define(['jquery'],function($){
    console.log("jump_page");
    // 分页的跳转第几页
    $(document).on("blur","input[name=page_jump_to_value]",function(){
        var o_jump_btn = $("#j_page_jump_btn");
        var o_this     = $(this);
        var gotopage   = parseInt(o_this.val());
        var maxpage    = parseInt(o_this.attr("data-maxpage"));
        var n_href     = o_jump_btn.attr("href");
        if(typeof gotopage =="undefined" || isNaN(gotopage) || parseInt(gotopage)<=0){
            o_jump_btn.attr("href","#");
        }
        gotopage = gotopage > maxpage ? maxpage : gotopage; 
        n_href   = n_href.replace(/([\w\/]+)\s*\/([\?]+)p=([\d]+)\s*([\/\w]*)/, "$1/?p="+gotopage); 
        o_jump_btn.attr("href",n_href);
    });
});