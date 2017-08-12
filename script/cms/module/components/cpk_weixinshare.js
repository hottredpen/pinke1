define(['jquery','wxjssdk','jquery_weui'],function($,wx){
	var cpk_weixinshare = {
		createObj : function(){
			var Obj = {};
			var o_document      = $(document);

            var config = {
                'shareApi'        : "/car/api/getshareweixinsigndata",
                'title'           : "嘟嘟二手车",
                'desc'            : "",
                'link'            : '',
                'imgUrl'          : ''
            };


			Obj.init = function(userconfig,_callback){
				config  = $.extend({}, config, userconfig);
				getShareSignCode();
			}

			function getShareSignCode(){
				var url = window.location.href;
				$.ajax({  
			        url   : config.shareApi,  
			        type  : "GET",
			        data  : {url : url},
			        dataType: "json",  
			        success: function(res){  
			          	// success_actions(); 这个没用
			          	weixin_init(res.data);
			        } 
				});
			}
			function success_actions(){
		        $.actions({
		          	title: "选择分享",
		          	onClose: function() {
		            	console.log("close");
		          	},
		          	actions: [
			            {
			              text: "分享到朋友圈",
			              className: "color-primary",
			              onClick: function() {
			                $.alert("分享到朋友圈成功");
			              }
			            },
			            {
			              text: "分享到朋友",
			              className: "color-warning",
			              onClick: function() {
			                $.alert("分享到朋友成功");
			              }
			            }
		          	]
		        });
			}
			function weixin_init(data){

	            wx.config({  
					debug: false,  
					appId: data.appId,  
					timestamp:data.timestamp,  
					nonceStr:data.nonceStr,  
					signature:data.signature,  
					jsApiList: [  
	                	'getLocation',  
	                	'onMenuShareTimeline',  
	                	'onMenuShareAppMessage' 
					]  
	          	});  


	          	wx.ready(function(){  
	             	wx.checkJsApi({  
		              	jsApiList: [  
		                	'getLocation',  
		                	'onMenuShareTimeline',  
		                	'onMenuShareAppMessage'  
		              	],  
		              	success: function (res) {  
		                	// $.alert(res.errMsg);  // 注意此处只有在微信浏览器里才会alert
		              	}  
	            	});

	             	// 分享给朋友
		            wx.onMenuShareAppMessage({  
		                title : config.title,  
		                desc  : config.desc,  
		                link  : config.link,  
		                imgUrl: config.imgUrl,  
		                trigger: function (res) {  
		                    // $.alert('用户点击发送给朋友');  
		                },  
	                  	success: function (res) {  
	                      	// $.alert('您已获得抽奖机会，赶紧去赢大奖吧～～');  
	                 	},  
		                cancel: function (res) {  
		                    // $.alert('已取消');  
		                },  
		                fail: function (res) {  
		                    $.alert(res.errMsg);  
		                }  
		            }); 
	             	
	             	// 分享到朋友圈
				    wx.onMenuShareTimeline({
		                title : config.title,  
		                desc  : config.desc,  
		                link  : config.link,  
		                imgUrl: config.imgUrl,
				      	trigger: function (res) {
				        	// $.alert('用户点击分享到朋友圈');
				      	},
				      	success: function (res) {
				        	// $.alert('已分享');
				      	},
				      	cancel: function (res) {
				        	// $.alert('已取消');
				      	},
				      	fail: function (res) {
				        	$.alert(JSON.stringify(res));
				      	}
				    });



	           	});







			}
			return Obj;
		}
	};
	return cpk_weixinshare;
});