define(['jquery','./cpk_plugins/fastclick','./cpk_plugins/nprogress'],function($,FastClick){
    $(document).ready(function(){
        FastClick.attach(document.body);
    });
});