/**
 * 成功提示
 * @param text 内容
 * @param title 标题
 */
function op_success(text, title) {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-center",
        "onclick": null,
        "showDuration": "1000",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    toastr.success(text, title);
}
/**
 * 失败提示
 * @param text 内容
 * @param title 标题
 */
function op_error(text, title) {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-center",
        "onclick": null,
        "showDuration": "1000",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    toastr.error(text, title);
}
/**
 * form提交提示
 * @param text 内容
 * @param title 标题
 */
$(document).ajaxStart(function(){
		$("button:submit").addClass("log-in").attr("disabled", true);
	})
	.ajaxStop(function(){
		$("button:submit").removeClass("log-in").attr("disabled", false);
	});
	
$("form").submit(function(){
	var self = $(this);
	$.post(self.attr("action"), self.serialize(), success, "json");
	return false;

	function success(data){
		if(data.status){
			op_success(data.info);
			window.location.href = data.url;
		} else {
			op_error(data.info);
			//刷新验证码，如果是有验证码
			$(".reloadverify").click();
		}
	}
});