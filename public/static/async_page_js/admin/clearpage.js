define(['jquery'],function($){

var page_trigger = {
	createObj : function(){
		var obj = {};
		var o_document = $(document);

        var config = {
                "builderDiv"       : "",
        };

		obj.init = function(userconfig){
			config       = $.extend({}, config, userconfig);
			// _init_something();
			_onDocumentBtn();
		}

		function _onDocumentBtn(){
		    $(document).on("click","#J_clear_cache",function(){
		        $('input[name="type"]:checked').each(function(){
		            var type   = $(this).val();
		            var uri    = $(this).attr('data-uri');
		            var o_info = $('#j_'+type+'_actioninfo');
		            o_info.html("等待");
		            $.getJSON(uri, '', function(result){
		                o_info.html("<i class='fa fa-check'></i>清理完成");
		            });
		        });
		    });
		}

		return obj;
	}
}

return page_trigger;
});