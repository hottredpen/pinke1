define(['jquery','layer_dialog'],function($,layer){
    // 切换(如状态切换)
    // $('img[data-tdtype="toggle"]').on('click', function() {
    $(document).on('click','img[data-tdtype="toggle"]',function(){
        var img    = this,
            s_val  = ($(img).attr('data-value'))== 0 ? 1 : 0,
            s_name = $(img).attr('data-field'),
            s_id   = $(img).attr('data-id'),
            s_src  = $(img).attr('src'),
            posturl = $('.j_tablelist').attr('data-acturi');
            if(posturl != "#"){
                $.ajax({
                    type : 'post', // 提交方式 get/post
                    url  : $('.j_tablelist').attr('data-acturi'), // 需要提交的 url
                    data : {id : s_id, field : s_name , val : s_val},
                    success: function(result) { // data 保存提交后返回的数据，一般为 json 数据
                        if(result.code == 200) {
                            if(s_src.indexOf('disabled')>-1) {
                                $(img).attr({'src':s_src.replace('disabled','enabled'),'data-value':s_val});
                            } else {
                                $(img).attr({'src':s_src.replace('enabled','disabled'),'data-value':s_val});
                            }
                            layer.msg(result.msg,{time:500});
                        }else{
                            layer.msg(result.msg);
                        }
                    }
                });
            }
        return false;
    });

    // ajax修改
    // $('span[data-tdtype="edit"]').on('click', function() {
    $(document).on('click','span[data-tdtype="edit"]',function(){
        var o_this  = $(this),
        s_val   = o_this.text(),
        s_name  = o_this.attr('data-field'),
        s_id    = o_this.attr('data-id'),
        width   = o_this.width()+60;
        $('<input type="text" class="form-control" value="'+s_val+'" />').width(width).focusout(function(){
            $(this).prev('span').show().text($(this).val());
            if($(this).val() != s_val) {
                $.ajax({
                    type : 'post', 
                    url  : $('.j_tablelist').attr('data-acturi'), // 需要提交的 url
                    data : {id : s_id, field : s_name , val : $(this).val()},
                    success: function(res) {
                        if(res.code == 200){
                            layer.msg(res.msg);
                        }else{
                            o_this.text(s_val);
                            $.custom.alert(res.msg);
                        }
                    }
                });
            }
            $(this).remove();
        }).insertAfter($(this)).focus().select();
        $(this).hide();
        return false;
    });



    // 筛选
    $(document).on('click','.builder-table .J_field_filter_form',function () {
        var self             = $(this),
            $filter          = self.attr('data-filter'), // 要筛选的字段
            $title           = typeof self.attr('data-title') != "undefined" ? self.attr('data-title') : "筛选";

            var $method      = self.attr('data-method');      // 本字段的筛选方法 function 或者 options
            var $data        = self.attr('data-data');        // 方法的参数
            var $filter_vals = self.attr('data-filter-vals'); // 本字段的当前值
            var $backurl     = self.attr('data-backurl');     // 本页面
            var $map         = self.attr('data-map');
            var $search_field= self.attr('data-search-field');
            var $keyword     = self.attr('data-keyword');

            $.ajax({
                type : 'get',
                url  : pinkephp.get_filter_list,
                data : {method:$method,data:$data,filter_vals:$filter_vals,backurl:$backurl,filter:$filter,map:$map,search_field:$search_field,keyword:$keyword},
                success: function(res) {
                    if(res.code==200){
                        layer.open({
                            type: 1,
                            fixed :false,
                            offset : '50px',
                            anim: 1,      // 0-6的动画形式，-1不开启
                            title: $title,
                            area:  ['600px', '600px'],
                            content: res.data
                        });
                        $(document).trigger('Jt_builder_form_init');
                    }else{
                        $.custom.alert(res.msg)
                    }
                }
            });
    });

    // 时间选择
    if(('#j_list_filter_time').length > 0){
        require(['jquery_datepicker'],function(){
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

    // 搜索
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

    // jquery_treetable
    if( $('.j_builder_treetable').length > 0){
        require(['jquery_treetable'],function(){
            $('.j_builder_treetable').each(function(i,ele){
                if($(this).attr('data-is-treetable-init') == "false"){
                    $(this).treetable({ expandable: true ,initialState:'expanded'});
                    $(this).attr('data-is-treetable-init','true');
                }
            });
        });
    }

});