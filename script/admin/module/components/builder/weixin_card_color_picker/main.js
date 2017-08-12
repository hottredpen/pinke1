define(['jquery'],function($){

var component = {
	createObj : function(){
		var obj              = {};
		var o_document       = $(document);

		obj.init = function(){
			init_something();
			onDocumentBtn();
		}

		function init_something(){

		}

		function onDocumentBtn(){

			$('#j_weixin_card_color_picker_select').on('click',function(){
				$(".j_DropdownList").show();
			});

			$(document).on('click',function(e){
             	if($(e.target).closest("#j_weixin_card_color_picker_select").length == 0 ){
                 	$(".j_DropdownList").hide();
         		}
			});

			$('.J_choose_this_card_color').on('click',function(){
				var color    = $(this).attr('data-value');
				var input_id = $(this).attr('data-input-id');
				$(".j_BtLabel").css('background-color',color);
				$(input_id).val(color);
				console.log(' trigger Jt_weixin_card_color ');
				$(document).trigger('Jt_weixin_card_color',[color]);

			});



		}
		return obj;
	}
}
return component;

});