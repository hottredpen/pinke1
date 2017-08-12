var cpk_select_cate = {
	createObj : function(){
		var obj = {};
		var o_document = $(document);
		var cur_catid;
        var config = {
                "select_id"  : "#j_select_carbrand",
                "api"        : "/car/api/ajaxGetCarBrand",
                "input_id"   : "#j_input_carbrandid",
        };

		obj.init = function(userconfig,callback){
			config       = $.extend({}, config, userconfig);
			onChangeSelect();
		}


		function onChangeSelect(){

			o_document.on("change",config.select_id + " .j_select_change",function(){
				var o_this   = $(this);
				var selectid = o_this.attr('data-selectid');
				var id       = $(this).val();
				var thisdeep = $(this).attr("deep");

				if(config.select_id != selectid){
					return false; // 非该实例化对象
				}

				var deep = $(".j_select_forcate").index()+1; //获取deep的值

				var needfindpardeep = 0
				if(id == -1){
					if(thisdeep > 1){
						needfindpardeep = thisdeep - 1;
					}else{
						needfindpardeep = 1;
					}
				}else{
					cur_catid = id;
				}

				//清除 后续添加的新的元素
				$(selectid+" .j_select_forcate").each(function(){
					if($(this).attr('deep') == needfindpardeep && needfindpardeep>0){
						cur_catid = $(this).find("option:selected").val();
					}
					if($(this).attr('deep') > thisdeep){
						$(this).parent().remove();
					}
				});

				$(config.input_id).val(cur_catid);

				if(id == -1){
					return;
				}
				$.ajax({
				  	type: "get",
				  	dataType:"json",
				  	url  : config.api,
				  	data : {id:id},
				  	sync : true,//设置异步模式
				  	success: function(res){
					  	if(res.status==1){
							var option="<option value='-1'>请选择</option>";
							var list= res.data;
							var deep;
							$.each(list,function(key,valuedata){
									option += "<option value='"+list[key].id+"'>"+list[key].title+"</option>";
									deep = list[key].deep;
							});
							$('<div class="col-md-2 pl0"><select name="" deep="'+deep+'" data-selectid="'+config.select_id+'" class="j_select_change form-control input-sm  j_select_forcate">'+option+'</select></div>').appendTo($(config.select_id));
					  	}
				  	}
			   	});



			});





		}


		return obj;
	}
}