define(['jquery'],function($){
    // 全选
    var o_document = $(document);
    
    o_document.on('click','.js-table-checkable thead input:checkbox',function(e){
        var $checkedStatus = $(this).prop('checked');
        $('.js-table-checkable tbody input:checkbox').each(function() {
            var $checkbox = $(this);
            $checkbox.prop('checked', $checkedStatus);
            uiHelperTableToolscheckRow($checkbox, $checkedStatus);
        });
    });
    var uiHelperTableToolscheckRow = function($checkbox, $checkedStatus) {
        if ($checkedStatus) {
            $checkbox
                .closest('tr')
                .addClass('active');
        } else {
            $checkbox
                .closest('tr')
                .removeClass('active');
        }
    };
});