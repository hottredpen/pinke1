define(['jquery'],function($){

var page_trigger = {
	createObj : function(){
		var obj = {};
		var o_document = $(document);

        var config = {
                "builderDiv"        : "",
        };

		obj.init = function(userconfig){
			config       = $.extend({}, config, userconfig);
			_init_something();
			_onDocumentBtn();
		}

		function _init_something(){
			if($('#j_check_version_url').length > 0){

		        $.ajax({
		            url: $('#j_check_version_url').attr('data-url'),
		            type: 'GET',
		        }).done(function(data) {
		            if (data.code == 200) {
		            	if(data.data.is_authorized){
		            		$('#j_check_version_url a').text(data.msg).removeClass('label-danger').addClass('label-success');
		            	}
		                //if (data.data.version) {
		                    $('#j_product_current_version').text("最新版本"+data.data.version);
		                // }
		            }
		        });
			}
		}

		function _onDocumentBtn(){



		}

		return obj;
	}
}

return page_trigger;
});