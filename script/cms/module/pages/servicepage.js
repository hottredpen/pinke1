define([],function(){
	var page = {
		init : function(){

			console.log("service");


	        var form = $('#j_service_post');
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
	                    form.append( form_status.html('<p><i class="fa fa-spinner fa-spin"></i> 表单正在提交中...</p>').fadeIn() );
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




		}
	}
	return page;
});