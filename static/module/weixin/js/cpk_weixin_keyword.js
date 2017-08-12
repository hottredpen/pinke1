var cpk_weixin_keyword = {
	createObj : function(){
		var obj = {};
		var o_document = $(document);
		var keyword_li_index = 0;
		obj.init = function(){
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

			$(document).on('Jt_add_keyword_in_page',function(e,data){
			    var keyword = data.keywords;
			    keyword_li_index ++;
			    $("#j_keywords_list").append(_html_keyword_li(keyword,keyword_li_index));
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