define(['jquery','swiper'],function($,Swiper){
	var page = {
		init : function(){




	        var form = $('#j_contact_us');
	        form.submit(function(event){
	            event.preventDefault();
	            var form_status = $('<div class="form_status clearfix"></div>');
	            //var fd = new FormData(document.getElementById("main-contact-form"));
	            //console.log(fd);
	            $.ajax({
	                url: $(this).attr('action'),
	                type : $(this).attr('method'),
	                dataType:'json',
	                data: form.serialize(),
	                beforeSend: function(){
	                    form.prepend( form_status.html('<p><i class="fa fa-spinner fa-spin"></i> 表单正在提交中...</p>').fadeIn() );
	                }
	            }).done(function(data){
	                if(data.status==1){
	                    form_status.html('<p class="text-success"><i class="fa fa-check"></i>' + data.msg + '</p>').delay(3000).fadeOut();
	                	setTimeout(function(){
	                		form[0].reset();
	                	},1000);

	                	
	                }else{
	                    form_status.html('<p class="text-danger"><i class="fa fa-close"></i>' + data.msg + '</p>').delay(3000).fadeOut();
	                }
	                
	            });
	        });


			console.log('index swiper');

        	var window_width = $(window).width();
        	var doc_width    = $(document).width();
        	var window_height = $(window).height();
        	var doc_height    = $(document).height();

        	console.log("window_width:"+window_width);
        	console.log("doc_width:"+doc_width);
        	console.log("window_height:"+window_height);
        	console.log("doc_height:"+doc_height);

			if(doc_width < 450){
				console.log("手机");
				$(".window-back").each(function(i,el){
					var bg_img = $(this).attr('data-background');
					if( bg_img != "" && typeof bg_img != "undefined"){
						console.log("bg");
						$(this).css({'background-image':'url('+bg_img+')','display': 'block'});
					}
				});

			}else{
				document.body.style.overflow="hidden";//隐藏页面水平和垂直滚动条 
				new Swiper('.window-box', {
					wrapperClass: 'window-cut',
					lazyLoading: true,
					lazyLoadingOnTransitionStart: true,
					slideClass: 'window-bin',
					direction: 'vertical',
					speed: 700,
					hashnav: true,
					roundLengths: true, 
					keyboardControl: true,
					slidesPerView: 1,
					mousewheelControl: true,
					pagination: '.area-box',
					bulletClass: 'area-cut',
					bulletActiveClass: 'active',
					paginationClickable: true,
				});
			}


			var banner_slide = new Swiper('.banner-box', {
				wrapperClass: 'banner-cut',
				slideClass: 'banner-bin',
				speed: 500,
				loop: true,
				autoplay: 4500,
				autoplayDisableOnInteraction: true,
				grabCursor: true,
				lazyLoading: true,
				keyboardControl: true,
				lazyLoadingOnTransitionStart: true,
				slidesPerView: 1,
				pagination: '.banner-pager',
				paginationClickable :true
			});
			$('.banner-ctrl .ctrl-left').click(function(){
				banner_slide.slidePrev();
			});
			$('.banner-ctrl .ctrl-right').click(function(){
				banner_slide.slideNext();
			});



		}
	}
	return page;
});