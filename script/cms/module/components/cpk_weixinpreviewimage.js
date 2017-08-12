define(['jquery','wxjssdk'],function($,wx){
	var cpk_weixinpreviewimage = {
		createObj : function(){
			var Obj = {};
			var o_document      = $(document);

            var config = {
                'title'           : "嘟嘟二手车",
            };


			Obj.init = function(userconfig,_callback){
				config  = $.extend({}, config, userconfig);
				weixinpreviewimage();
			}

			function weixinpreviewimage(){
				$(".J_to_weixin_previewimage").on('click',function(){
					var _current_url ;
					var _urls = [];
					$(this).find(".j_weixin_preview").each(function(index,val){
						var _this_src = $(this).attr('src');
						if(index == 0){
							_current_url = "http://www.lankuwangluo.com" + _this_src;
						}
						_urls.push("http://www.lankuwangluo.com" + _this_src);
					});
				    wx.previewImage({
				      	current: _current_url,
				      	urls: _urls
				    });
				});
			}
			return Obj;
		}
	};
	return cpk_weixinpreviewimage;
});