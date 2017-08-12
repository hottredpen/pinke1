define(['jquery','./cpk_plugins/lazyload'],function($){
    $(function(){
        $("img.lazyloadImg").lazyload({effect: "fadeIn"});
    });
});