define(['jquery','../components/qrcode','lightgallery'],function($,QRCode){
	var page = {
		init : function(){

			console.log("detail");

			$(".j_product_qrcode_btn").on("mouseover",function(){
				$('.j_qrcode_div').show();
				$(".j_product_qrcode").hide();
				var _type = $(this).attr("data-type");
				$("#j_qrcode_replace_"+ _type).show();
			});

			$(".j_product_qrcode_btn").on("mouseleave",function(){
				$('.j_qrcode_div').hide();
				$("#j_product_qrcode").hide();
			});


	        var taobao_url = $("#j_qrcode_replace_taobao").attr("data-url");
	        taobao_url = taobao_url.replace(/(^\s*)|(\s*$)/g, "");

	        var qrcode_taobap = new QRCode(document.getElementById("j_qrcode_replace_taobao"), {
	            width : 190,//设置宽高
	            height : 190
	        });

	        qrcode_taobap.makeCode(taobao_url);

	        var weixin_url = $("#j_qrcode_replace_weixin").attr("data-url");
	        weixin_url = weixin_url.replace(/(^\s*)|(\s*$)/g, "");

	        var qrcode_taobap = new QRCode(document.getElementById("j_qrcode_replace_weixin"), {
	            width : 190,//设置宽高
	            height : 190
	        });

	        qrcode_taobap.makeCode(weixin_url);



            // var taobao_url = $("#j_qrcode_replace_taobao").attr("data-url");
            // $('#j_qrcode_replace_taobao').qrcode({
            //     width : 190,
            //     height : 190,
            //     render : "table",
            //     text : taobao_url
            // }); 

            // var weixin_url = $("#j_qrcode_replace_weixin").attr("data-url");
            // $('#j_qrcode_replace_weixin').qrcode({
            //     width : 190,
            //     height : 190,
            //     render : "table",
            //     text : weixin_url
            // }); 


			//产品详情页标准模式
			if($('.met-showproduct.pagetype1').length){
				$("body").addClass("met-white-lightGallery");//画廊皮肤

			    require(["lg-thumbnail","lg-zoom"], function(){
			        
			    	var _pic_max_index = parseInt($('#lightgallery li').length - 1 );
			    	var _current_index = 0;


					$("#lightgallery").lightGallery({
						thumbnail:true,
					}); 

					$(document).on('click',"#gallery .ad-image img",function(event){
						var i = parseInt($(".j_min_image_list_pic.ad-active").parent().index());
						$(".j_min_image_list_pic_"+i).trigger('click');
					});


 					$('.ad-next-image').on('click',function(){
 						if( _current_index < _pic_max_index){
 							_current_index ++;
 						}else{
 							_current_index = 0;
 						}
 						$(".j_min_image_list_pic_"+_current_index+".J_min_image_list_pic").trigger('click');
 					});

 					$('.ad-prev-image').on('click',function(){
 						if( _current_index > 0 ){
 							_current_index --;
 						}else{
 							_current_index = _pic_max_index;
 						}
 						$(".j_min_image_list_pic_"+_current_index+".J_min_image_list_pic").trigger('click');
 					});


 					$(".J_min_image_list_pic").on("click",function(){
 						var img_url = $(this).find('img').attr('src');
 						_current_index = parseInt($(this).attr('data-index'));
 						$('.j_min_image_list_pic').removeClass('ad-active');
 						$(this).addClass('ad-active');
 						$('.j_product_main_show img').attr("src",img_url);
 					});
			    });
			}



		}
	}
	return page;
});