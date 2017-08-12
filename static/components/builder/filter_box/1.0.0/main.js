define(['jquery'],function($){

var pinkephp_component = {
    getName : function(){
        return "filter_box@1.0.0";
    },
    getAuthKey : function(){
        return "aab3238922bcc25a6f60-0e51011a4c4891e56f6c-b4b247bc52292873111a-bc728570985ab4449aff97615b3a0773"; // 检测是授权组件
    },
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