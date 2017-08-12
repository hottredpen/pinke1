define(['jquery','layer_dialog'],function($,layer){

    var custom_obj = {};

    custom_obj.alert = function(msg){
        layer.alert(msg,{
            icon:0
        });
    }

    return custom_obj;


});