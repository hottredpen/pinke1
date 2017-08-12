// 判断打开网站的平台，跳转到相应网站
var system = {
	win : false,
	mac : false,
	xll : false
};
//检测平台
var p = navigator.platform;
system.win = p.indexOf("Win") == 0;
system.mac = p.indexOf("Mac") == 0;
system.x11 = (p == "X11") || (p.indexOf("Linux") == 0);
//var href = window.location.href;
//var wenjian = href.substr(href.lastIndexOf("/"), href.length);


//var url = href.substr(0, href.lastIndexOf("/"));
var url;
function init(lin) {url = lin;}
function detectingPlatform() {
	if(system.win||system.mac||system.xll) {//电脑版
		
	} else {//移动版
		if(document.body.clientWidth < 990) {
			//window.location.href = url + "/wap" + wenjian;
			window.location.href = url + "wap";
		} else {
			//window.location.href = url
		}
	}
}

//浏览器窗口大小变化时，重新获取参数
$(window).resize(function() {
	detectingPlatform();
});