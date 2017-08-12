define(['jquery'],function($){

var component = {
	getName : function(){
		return "weixin_card_color_picker@1.0.0";
	},
	getAuthKey : function(){
		return "1ff1de774005f8da13f4-de5d40209344c96a1a0a-b4b157bc522828732f11-ccd094e53013ac9f90036fd89156b57f"; // 检测是授权组件
	},
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