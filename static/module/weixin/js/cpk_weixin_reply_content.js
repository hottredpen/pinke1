var cpk_weixin_reply_content = {
	createObj : function(){
		var obj = {};
		var o_document = $(document);
		var reply_content_li_index = 0;
		obj.init = function(){
			init_reply_content_li_index();
			onDocumentBtn();
		}
		function init_reply_content_li_index(){
			reply_content_li_index = $("#j_content_list li").length;
		}

		function onDocumentBtn(){

			// o_document.on('click','.J_keyword_edit',function(){
			//     var keyword = $(this).parent().parent().find('.j_keyword_val').attr('data-content');
			//     var indexid = $(this).parent().parent().attr('data-index');
			//     show_keyword_dialog_form(keyword,indexid);

			// });

			o_document.on('click','.J_reply_del',function(){
			    $(this).parent().parent().remove();
			});


			// o_document.on('click','.J_show_keyword_dialog',function(){
			//     show_keyword_dialog_form();
			// });

		}

		function show_keyword_dialog_form(keyword,indexid){
		    var _content = _html_keyword_form(keyword,indexid);
		    var _title   = parseInt(indexid) > 0 ? '修改关键字' : '添加关键字';

		    var d = dialog({
		        id : "j_artdialog_keyword_form",
		        title: _title,
		        content: _content,
		        width  : 600,
		        okValue: '确定',
		        zIndex : 100,
		        ok: function () {
		            parseInt(indexid) > 0 ? edit_keyword_in_page(indexid) : add_keyword_in_page();
		            return false;
		        },
		        cancelValue: '取消',
		        cancel: function () {},
		    });
		    d.showModal();
		}


		function _html_keyword_li(keyword,indexid){
		    var _str = '<li class="j_indexid_'+indexid+'" data-index="'+indexid+'">\
		                    <div class="desc">\
		                        <strong class="title j_keyword_val" data-content="'+keyword+'">'+keyword+'</strong>\
		                        <input type="hidden" name="keywords[]" value="'+keyword+'">\
		                    </div>\
		                    <div class="opr">\
		                        <a href="javascript:;" class="icon14_common edit_gray J_keyword_edit">编辑</a>\
		                        <a href="javascript:;" data-id="0" class="icon14_common del_gray J_keyword_del">删除</a>\
		                    </div>\
		                </li>';
		    return _str;
		}

		function _html_keyword_form(keyword){
		    if(typeof keyword == "undefined"){
		        keyword = "";
		    }
		    var _str = '<div class="dialog_content">\
		                    <form id="j_artdialog_keyword_form" action="" method="post">\
		                    <table width="100%" class="table_form">\
		                        <input id="j_keywords_val" type="text" name="keyword" value="'+keyword+'">\
		                    </table>\
		                    </form>\
		                </div>';
		    return _str;
		}

		function add_keyword_in_page(){
		    var keyword = $("#j_keywords_val").val();
		    reply_content_li_index ++;
		    $("#j_keywords_list").append(_html_keyword_li(keyword,reply_content_li_index));
		    cpk_dialog_remove("j_artdialog_keyword_form");
		}

		function edit_keyword_in_page(indexid){
		    var keyword = $("#j_keywords_val").val();
		    $(".j_indexid_"+indexid).html(_html_keyword_li(keyword,indexid));
		    cpk_dialog_remove("j_artdialog_keyword_form");
		}


		return obj;
	}
}