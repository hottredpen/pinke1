define(['jquery','layer_dialog','js_md5','../../components/components_form_builder','validator','../common'],function($,layer,md5,components_form_builder){
	// 表单内的值变化触发的class变化
    $(document).on('Jt_builder_form_class_trigger_init',function(){
        if($('.jt_builder_form_class_trigger_init').length > 0 ){
            $('.jt_builder_form_class_trigger_init').each(function(i,ele){
                var o_this             = $(this);
                var on_field           = o_this.attr('data-on-field');
                var on_values          = o_this.attr('data-on-values');
                var trigger_items      = o_this.attr('data-trigger-items');
                var is_init            = o_this.attr('data-is-init');
                if(is_init == "true"){
                    return ;
                }else{
                    o_this.attr('data-is-init',"true");
                }
                var on_values_arr = on_values.split("||");

                if(trigger_items != ""){
                    var trigger_item_arr = trigger_items.split("|");
                }else{
                    var trigger_item_arr = [];
                }


                $(document).on("Jt_builder_form_class_trigger_item_"+on_field,function(e,t_val){

                    if($.inArray(t_val,on_values_arr) > -1){

                        $.each(trigger_item_arr,function(ii,vval){
                            // console.log(vval);
                            var trigger_action = vval.split("&");

                            var trigger_fields = trigger_action[0].toString();
                            var add_class      = trigger_action[1].toString();
                            var remove_class   = trigger_action[2].toString();
                            if(typeof trigger_action[3] != "undefined"){
                                var trigger_other  = trigger_action[3].toString();
                            }                            

                            var trigger_fields_arr = trigger_fields.split(",");
                            $.each(trigger_fields_arr,function(i,item){
                                // 如果item是.class 或者是 #idname则直接对使用改对象
                                if(item.indexOf(".") > -1 || item.indexOf("#") > -1  ){
                                    $(item).addClass(add_class);
                                    $(item).removeClass(remove_class);
                                }else{
                                    $('.j_form_item_'+item).addClass(add_class);
                                    $('.j_form_item_'+item).removeClass(remove_class);
                                }
                                if( typeof trigger_other != "undefined"){
                                    if(trigger_other.indexOf("[this]") > -1){
                                        $(document).trigger(trigger_other,['.j_form_item_'+item]);
                                    }else{
                                        $(document).trigger(trigger_other);
                                    }
                                }
                            });
                        });
                    }
                });
            });
        }
    });

    // 表单提交
    $(document).on('Jt_builder_form_post_init',function(){
        if($('.jt_builder_form_post_init').length > 0){
            $('.jt_builder_form_post_init').each(function(i,ele){

                var jt_obj             = $(ele);
                var form_id            = jt_obj.attr('data-form-id');
                var trigger_click_a_id = jt_obj.attr('data-trigger-click-a-id');
                var post_bottom        = jt_obj.attr('data-post-buttom');
                var form_backurl       = jt_obj.attr('data-backurl');

                function onSubmitClick(){
                    $(post_bottom).off('click'); // 关闭可能之前存在的重复监听
                    $(post_bottom).on('click',function(){
                        $(form_id).submit();
                        $(this).attr('disabled',"true"); // 添加disabled属性
                    });
                }

                onSubmitClick();

                $(form_id).validator(function(form){
                    $.ajax({
                        type : 'post',
                        url  : $(form).attr("action"),
                        data : $(form).serialize(),
                        success: function(res) {
                            $(post_bottom).removeAttr("disabled"); // 移除disabled属性 
                            if(res.code==200){
                            	// @todo 以下部分待优化
                                if( typeof parent.layer != "undefined" ){
                                    var index   = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                                    parent.layer.close(index); //再执行关闭   
                                }
                                if( typeof res.data.backurl != 'undefined' && res.data.backurl != ''){
                                    $.custom.msg(res.msg);
                                    $.custom.redirect(res.data.backurl);
                                }else if( typeof res.data.click_a_to_url != 'undefined'){
                                    $.custom.msg(res.msg);
                                    $(trigger_click_a_id).attr('href',res.data.click_a_to_url).trigger('click');
                                    closeLayer($(trigger_click_a_id));
                                }else if( typeof res.data.not_reload != 'undefined'){
                                    if( typeof res.data.trigger_name != 'undefined'){
                                        $(document).trigger(res.data.trigger_name,[res.data]);
                                    }
                                    onSubmitClick();
                                }else if(form_backurl != ''){
                                    $.custom.msg(res.msg);
                                    $(trigger_click_a_id).attr('href',form_backurl).trigger('click');
                                    closeLayer($(trigger_click_a_id));
                                }else{
                                    $.custom.msg(res.msg);
                                    $.custom.reload();
                                }
                            }else{
                                $.custom.alert(res.msg);
                                onSubmitClick();
                            }
                        },
                        error :function(){
                            $(post_bottom).removeAttr("disabled"); // 移除disabled属性 
                            onSubmitClick();
                        }
                    });
                });
            });
        }
    });
    function closeLayer(o_this){
        var this_layer_index = o_this.closest('.layui-layer-page').attr('times');
        layer.close(this_layer_index);
    }


    // 默认页面会第一次触发本事件，之后的弹窗都会触发一次
    // 此处做了组件授权的验证
    eval(function(p,a,c,k,e,r){e=function(c){return(c<62?'':e(parseInt(c/62)))+((c=c%62)>35?String.fromCharCode(c+29):c.toString(36))};if('0'.replace(0,e)==0){while(c--)r[e(c)]=k[c];k=[function(e){return r[e]||e}];e=function(){return'([6-9a-hj-zA-Z]|1\\w)'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('$(A).on(\'Jt_builder_form_init\',e(){$(A).L(\'Jt_builder_form_class_trigger_init\');$(A).L(\'Jt_builder_form_post_init\');7($(\'.M\').B>0){$(\'.M\').N(e(i,l){6 a=$(l);6 f=a.m();7(O a.q(\'m-r-g\')=="undefined"||a.q(\'m-r-g\')=="s"){6 C=s;7(n.components_build=="c"){$.N(components_form_builder,e(i,l){7(l.name==f.D){C=c;a.P();6 t=l.Q.R();t.g(f);a.q("m-r-g","c")}})}7(!C){7(n.S=="c"){h.j("组件"+f.D+" 为T 模式  ")}6 9=f.D;9=9.U("@","/");9=9.U("~","/");T([n.web_static_url+\'static/k/form_builder/\'+9+\'/Q.E\'],e(k){a.P();7(O(k.F)=="e"){6 V=k.F();6 W=k.getName();7(!X(V,W)){h.j(\'组件:\'+9+\'的authkey 错误,组件E初始化失败\');Y}6 t=k.R();t.g(f);a.q("m-r-g","c");7(n.S=="c"){h.j(\'组件:\'+9+\'，E初始化成功\')}}u{h.j(\'组件:\'+9+\'必须具有F的方法\')}})}}})}e X(Z,10){6 11=Z+"-"+n.web_openid;6 v=c;6 o=11.12("-");6 8=[];G(6 i=0;i<o.B;i++){6 H=[];G(w=0;w<o[i].B;w++){H.b(o[i].charAt(w))}8.b({\'index\':i,\'d\':H,\'p\':o[i]})}6 x=[];6 y=[];6 z=[];G(6 i=0;i<20;i++){7(8[0].d[i]==8[1].d[i]){x.b(1)}u{x.b(0)}7(8[1].d[i]==8[2].d[i]){y.b(1)}u{y.b(0)}7(8[4].d[i]==8[5].d[i]){z.b(1)}u{z.b(0)}}6 _component_id=I(x.J(""),2);6 K=I(y.J(""),2);6 13=I(z.J(""),2);7(K>0){7(13!=K){h.j("该组件为收费组件,你的站点未被授予使用,"+\'获取该组件使用限制，请访问http://pinke.jk-kj.com\');v=s}}6 14=10.12(\'@\');6 15=md5(14[0]+8[0].p+"-"+8[1].p+"-"+8[2].p);7(8[3].p!=15){h.j("组件授权检测失败");v=s}Y v}});',[],68,'||||||var|if|_obj_arr|component_dir|jt_obj|push|true|array|function|_config|init|console||log|components|ele|data|pinkephp|_arr|str|attr|is|false|_aa|else|_all_is_ok|ii|_first_str_arr|_second_str_arr|_pinke_openid_str_arr|document|length|_is_find|componentName|js|getAuthKey|for|_tmp|parseInt|join|_local_bussiness_id|trigger|jt_form_builder_component|each|typeof|show|main|createObj|debug|require|replace|_components_key|_components_name|check_component_auth|return|_key|_name|_all_key|split|_bussiness_id|_main_components_version_with_name_arr|_md5_value'.split('|'),0,{}));

    $(document).trigger('Jt_builder_form_init');

});