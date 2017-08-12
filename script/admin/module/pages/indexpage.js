define(['jquery','app','chart'],function($,App,chart){
	var page = {
		init : function(){

			$(function(){
				App.init();  // @todo app内的toggleClass问题
				App.initHelpers('slick');
			});


			// 
			require(['./oneui_tools/oneui_dashboard'],function(BasePagesDashboard){

				BasePagesDashboard.init();
			});

		}
	}
	return page;
});