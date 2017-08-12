define(['jquery','js_md5','./test_components','validator'],function($,md5,components_arr){


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

    $(document).on('Jt_builder_form_post_init',function(){
        if($('.jt_builder_form_post_init').length > 0){

            $('.jt_builder_form_post_init').each(function(i,ele){

                var jt_obj             = $(ele);
                var form_id            = jt_obj.attr('data-form-id');
                var trigger_click_a_id = jt_obj.attr('data-trigger-click-a-id');
                var post_bottom        = jt_obj.attr('data-post-buttom');

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
                            if(res.status==1){

                                if( typeof parent.layer != "undefined" ){
                                    var index   = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                                    parent.layer.close(index); //再执行关闭   
                                }
                                
                                
                                if( typeof res.data.backurl != 'undefined'){
                                    layer.msg(res.msg,{time: 1000, icon:6});
                                    window.location = res.data.backurl;
                                }else if( typeof res.data.click_a_to_url != 'undefined'){
                                    layer.msg(res.msg,{time: 1000, icon:6});
                                    $(trigger_click_a_id).attr('href',res.data.click_a_to_url).trigger('click');
                                    closeLayer($(trigger_click_a_id));

                                }else if( typeof res.data.not_reload != 'undefined'){
                                    if( typeof res.data.trigger_name != 'undefined'){
                                        $(document).trigger(res.data.trigger_name,[res.data]);
                                    }
                                    onSubmitClick();
                                }else{
                                    layer.msg(res.msg,{time: 1000, icon:6});
                                    window.location.reload();
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
    $(document).on('Jt_builder_form_init',function(){

        $(document).trigger('Jt_builder_form_class_trigger_init');
        // console.log('Jt_builder_form_init');
        $(document).trigger('Jt_builder_form_post_init');


        // 
        if( $(".J_choose_this_text").length > 0 ||
            $(".J_choose_this_image").length > 0 || 
            $(".J_choose_this_news").length > 0 ||
            $(".J_choose_this_redpack").length > 0 ||
            $(".J_choose_this_voice").length > 0 ||
            $(".J_choose_this_video").length > 0 ||
            $(".J_choose_this_card").length > 0 

            ){
            require(['./components/weixin_material_dialog_box'],function(weixin_material_dialog_box){
                var _material_dialog_box = weixin_material_dialog_box.createObj();
                _material_dialog_box.init();
            });
        }

        // 这个是js核心
        eval(function(p,a,c,k,e,r){e=function(c){return(c<62?'':e(parseInt(c/62)))+((c=c%62)>35?String.fromCharCode(c+29):c.toString(36))};if('0'.replace(0,e)==0){while(c--)r[e(c)]=k[c];k=[function(e){return r[e]||e}];e=function(){return'([6-9a-hj-zA-Z]|1\\w)'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('8($(\'.O\').t>0){$(\'.O\').P(m(i,u){6 e=$(u);6 v=e.w();8(Q e.C(\'w-D-n\')=="undefined"||e.C(\'w-D-n\')=="x"){6 J=x;8(y.components_build=="o"){$.P(components_arr,m(i,u){8(u.name==v.R){J=o;e.S();8(!T()){j.k(\'你的站点未授权无法使用组件z打包功能,希望您能理解开发人员的不易\');E}6 F=u.U.V();F.n(v);e.C("w-D-n","o")}})}8(!J){6 d=v.R;d=d.W("@","/");d=d.W("~","/");require([y.web_static_url+\'static/p/builder/\'+d+\'/U.z\'],m(p){e.S();8(Q(p.K)=="m"){6 X=p.K();6 Y=p.getName();8(!Z(X,Y)){j.k(\'组件:\'+d+\'的authkey 错误,组件z初始化失败(如果改组件不需要z支持则无所谓)\');E}6 F=p.V();F.n(v);e.C("w-D-n","o");j.k(\'组件:\'+d+\'，z初始化成功\')}q{j.k(\'组件:\'+d+\'必须具有K的方法\')}})}}})}m T(){6 G=y.10;6 l=o;6 b=G.L("-");6 7=[];r(6 i=0;i<b.t;i++){6 s=[];r(f=0;f<b[i].t;f++){s.9(b[i].11(f))}7.9({\'12\':i,\'a\':s,\'c\':b[i]})}6 g=[];6 h=[];r(6 i=0;i<20;i++){8(7[0].a[i]==7[1].a[i]){g.9(1)}q{g.9(0)}8(7[1].a[i]==7[2].a[i]){h.9(1)}q{h.9(0)}}6 M=A(g.B(""),2);6 _turnover_id=A(h.B(""),2);6 H=14(y.website_url+7[0].c+"-"+7[1].c+"-"+7[2].c);8(7[3].c!=H){j.k("网站授权检测失败");l=x}E l}m Z(15,16){6 G=15+"-"+y.10;6 l=o;6 b=G.L("-");6 7=[];r(6 i=0;i<b.t;i++){6 s=[];r(f=0;f<b[i].t;f++){s.9(b[i].11(f))}7.9({\'12\':i,\'a\':s,\'c\':b[i]})}6 g=[];6 h=[];6 I=[];r(6 i=0;i<20;i++){8(7[0].a[i]==7[1].a[i]){g.9(1)}q{g.9(0)}8(7[1].a[i]==7[2].a[i]){h.9(1)}q{h.9(0)}8(7[4].a[i]==7[5].a[i]){I.9(1)}q{I.9(0)}}6 _component_id=A(g.B(""),2);6 N=A(h.B(""),2);6 M=A(I.B(""),2);8(N>0){8(M!=N){j.k("服务商id检测失败");l=x}}6 17=16.L(\'@\');6 H=14(17[0]+7[0].c+"-"+7[1].c+"-"+7[2].c);8(7[3].c!=H){j.k("组件授权检测失败");l=x}E l}',[],70,'||||||var|_obj_arr|if|push|array|_arr|str|component_dir|jt_obj|ii|_first_str_arr|_second_str_arr||console|log|_all_is_ok|function|init|true|components|else|for|_tmp|length|ele|_config|data|false|pinkephp|js|parseInt|join|attr|is|return|_aa|_all_key|_md5_value|_pinke_openid_str_arr|_is_find|getAuthKey|split|_bussiness_id|_local_bussiness_id|jt_form_builder_component|each|typeof|componentName|show|check_web_auth|main|createObj|replace|_components_key|_components_name|check_component_auth|web_openid|charAt|index||md5|_key|_name|_main_components_version_with_name_arr'.split('|'),0,{}));

        if( $('.j_builder_treetable').length > 0){
            require(['jquery-treetable'],function(){
                $('.j_builder_treetable').each(function(i,ele){
                    if($(this).attr('data-is-treetable-init') == "false"){
                        $(this).treetable({ expandable: true ,initialState:'expanded'});
                        $(this).attr('data-is-treetable-init','true');
                    }
                });
            });
        }

        if( $('#j_builder_search_field_input').length > 0 ){
            // search初始化
            var search_field = $('#j_builder_search_field_input').val();
            $('.j_search_field_btn').each(function () {
                var self = $(this);
                if (self.attr('data-field') == search_field) {
                    $('.j_search_field_name').text(self.text());
                }
            })

            $('.J_search_field_btn').on('click',function(){
                var field_name  = $(this).attr('data-field-name');
                var field_value = $(this).attr('data-field');
                 $('.j_search_field_name').text(field_name);
                $('#j_builder_search_field_input').val(field_value);
            });
        }


        if(('#j_list_filter_time').length > 0){
            require(['jquery-datepicker'],function(){
                $("#j_list_filter_time_from").datepicker({
                    autoclose: true,
                    format: "yyyy-mm-dd"
                });
                $("#j_list_filter_time_to").datepicker({
                    autoclose: true,
                    format: "yyyy-mm-dd"
                });
            });
        }


    });

    $(document).trigger('Jt_builder_form_init');

});