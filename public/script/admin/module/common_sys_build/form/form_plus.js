define(['jquery','layer_dialog'],function($,layer){
    // 弹出层内的取消关闭layui-layer-page
    $(document).on('click','.J_builder_form_cancel',function(){
        var this_layer_index = $(this).closest('.layui-layer-page').attr('times');
        layer.close(this_layer_index);
        // 以下是为多种取消情况做的拓展
        $(document).trigger('J_builder_form_cancel_click',[this]);
    });

    // item_tab
    $(document).on('click','[data-toggle="tabs"] li',function(){
        $(this).find('a').tab('show');
    });


    
});