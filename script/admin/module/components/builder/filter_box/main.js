define(['jquery'],function($){

var pinkephp_component = {
	createObj : function(){
		var obj = {};
		var o_document = $(document);
		var current_dialog_material_builder;

        var config = {
                "uploadType"       : "",
        };

		obj.init = function(userconfig){
			config       = $.extend({}, config, userconfig);
			_init_something();
		}

		function _init_something(){
            // 查找要筛选的字段
            var $searchItems = $('.filter-field-list > div');
            var $searchValue = '';
            var reg;
            $('.js-field-search').on('keyup', function(){
                $searchValue = $(this).val().toLowerCase();

                if ($searchValue.length >= 1) {
                    $searchItems.hide().removeClass('field-show');

                    $($searchItems).each(function(){
                        reg = new RegExp($searchValue, 'i');
                        if ($(this).text().match(reg)) {
                            $(this).show().addClass('field-show');
                        }
                    });
                } else if ($searchValue.length === 0) {
                    $searchItems.show().removeClass('field-show');
                }
            });
		}
		return obj;
	}
}

return pinkephp_component;

});