define(["Vue", "vue!component", "vue!component.html", "vue!using_alias"], function(Vue){

var page_trigger = {
	createObj : function(){
		var obj = {};
	
        var config = {
                "builderDiv"       : "",
        };

		obj.init = function(userconfig){
			// console.log("talk_one_by_one init");
			config       = $.extend({}, config, userconfig);
			_init_something();
			_onDocumentBtn();
		}

		function _init_something(){
		    new Vue({
		        el: "#app"
		    });
		}

		function _onDocumentBtn(){

		}

		return obj;
	}
}

return page_trigger;


});