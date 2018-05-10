define(['jquery','layer_dialog'],function($,layer){

var weixin_keywords_str = {
	getName : function(){
		return "weixin_keywords_str@1.0.0";
	},
	getAuthKey : function(){
		return "4e732ced3463d06de0ca-2c62105ee18ecd5deec3-b4b147bc522828731f1a-4ac779a6b847dfb96707938562cfd4d4"; // 检测是授权组件
	},
	createObj : function(){
		var obj = {};
		var o_document = $(document);
		var keyword_li_index = 0;

        var config = {
                "uploadType"       : "",
        };


		obj.init = function(userconfig){
			console.log("builder weixin_keywords_str");
			config       = $.extend({}, config, userconfig);

			init_keyword_li_index();
			onDocumentBtn();
		}
		function init_keyword_li_index(){
			keyword_li_index = $("#j_keywords_list li").length;
		}

		function onDocumentBtn(){

			o_document.on('click','.J_keyword_edit',function(){
			    var keyword = $(this).parent().parent().find('.j_keyword_val').attr('data-content');
			    var indexid = $(this).parent().parent().attr('data-index');
			    show_keyword_dialog_form(keyword,indexid);

			});

			o_document.on('click','.J_keyword_del',function(){
			    $(this).parent().parent().remove();
			});

			$(document).off('Jt_add_keyword_in_page');
			$(document).on('Jt_add_keyword_in_page',function(e,data){
				console.log(" get Jt_add_keyword_in_page");
			    var keyword = data.keywords;
			    keyword_li_index ++;
			    $("#j_keywords_list").append(_html_keyword_li(keyword,keyword_li_index));

			    layer.closeAll();
			});
		}


		function _html_keyword_li(keyword,indexid){
		    var _str = '<li class="j_indexid_'+indexid+'" data-index="'+indexid+'">\
		                    <div class="desc">\
		                        <strong class="title j_keyword_val" data-content="'+keyword+'">'+keyword+'</strong>\
		                        <input type="hidden" name="keywords[]" value="'+keyword+'">\
		                    </div>\
		                    <div class="opr">\
		                        <a href="javascript:;" data-id="0" class="icon14_common del_gray J_keyword_del">删除</a>\
		                    </div>\
		                </li>';
		    return _str;
		}
		function edit_keyword_in_page(indexid){
		    var keyword = $("#j_keywords_val").val();
		    $(".j_indexid_"+indexid).html(_html_keyword_li(keyword,indexid));
		    cpk_dialog_remove("j_artdialog_keyword_form");
		}
		return obj;
	}
}
return weixin_keywords_str;
});