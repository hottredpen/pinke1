define(['jquery'],function($){
    // 分页的跳转第几页
    $(document).on("blur","input[name=page_jump_to_value]",function(){
        var o_jump_btn = $("#j_page_jump_btn");
        var o_this     = $(this);
        var to_page   = parseInt(o_this.val());
        var maxpage    = parseInt(o_this.attr("data-maxpage"));
        var n_href     = o_jump_btn.attr("href");
        if(typeof to_page =="undefined" || isNaN(to_page) || parseInt(to_page)<=0){
            o_jump_btn.attr("href","#");
        }
        to_page = to_page > maxpage ? maxpage : to_page; 
        n_href   = n_href.replace(/([\w\/]+)\s*\/([\?]+)p=([\d]+)\s*([\/\w]*)/, "$1/?p="+to_page); 
        o_jump_btn.attr("href",n_href);
    });
});