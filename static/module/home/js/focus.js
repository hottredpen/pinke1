// JavaScript Document
//框架、类型(1:放大、2:左滑动、3:渐变)、每次变化的间隔时间、每次变化的执行时间
function focus_(demo, type, interval, execution_time) {
	var sWidth = $(demo).width(); //获取焦点图的宽度（显示面积）
	var len = $(demo + " ul li").length; //获取焦点图个数
	$(demo + " ul li").css("width", sWidth);
	//本例为左右滚动，即所有li元素都是在同一排向左浮动，所以这里需要计算出外围ul元素的宽度
	$(demo + " ul").css("width", sWidth*len);
	var index = 0;
	var picTimer;
	var btn = "";
	
	//添加上一页下一页按钮
	btn += "<div class='pre_box pre'></div><div class='pre_box next'></div>";
	//添加数字按钮
	btn += "<div class='btn'>";
	if(len > 1) {
		for(var i=0; i < len; i++) {
			if(i==0){
				btn += "<span style='background:#fa7623;'> </span>";
			}else{
				btn += "<span> </span>";
			}
		}
		btn += "</div>";
	}
	$(demo).append(btn);
	
	//上一页、下一页按钮样式和添加鼠标事件
	$(demo + " .pre").click(function() {
		index -= 1;
		if(index == -1) {index = len - 1;}
		showPics(index);
	});
	$(demo + " .next").click(function() {
		index += 1;
		if(index == len) {index = 0;}
		showPics(index);
	});
	
	//为小按钮添加鼠标事件
	$(demo + " .btn span").mouseenter(function() {
		index = $(demo + " .btn span").index(this);
		showPics(index);
	});
	
	//鼠标滑上焦点图时停止自动播放，滑出时开始自动播放、并触发mouseenter(鼠标滑过)事件
	$(demo).hover(function() {
		clearTimeout(picTimer);
	}, function() {
		pic_time_start();
	});
	
	
	function pic_time_start(){
		picTimer = setTimeout(function(){
			index++;
			if(index == len) {index = 0;}
			showPics(index);
			pic_time_start();
		},interval);
	}

	// 初始化运行
	pic_time_start();

	//显示图片函数，根据接收的index值显示相应的内容
	function showPics(index) {
		//$(demo + " .btn span").stop(true, false).animate({"opacity":"0.4"}, 300).eq(index).stop(true,false).animate({"opacity":"1"}, 300); //为当前的按钮切换到选中的效果
		$(demo + " .btn span").css({"background":"#bbb"}).eq(index).css({"background":"#fa7623"}); //为当前的按钮切换到选中的效果
		var nowLeft = -index*sWidth; //根据index值计算ul元素的left值
		if(type == 1) {//渐变放大
			$(demo + " ul li").css("opacity", 0.1).find("img").css({"width":"90%", "height":"90%"});
			$(demo + " ul").stop(true, false).css({"margin-left":nowLeft+"px"}); //通过animate()调整ul元素滚动到计算出的position
			$(demo + " ul li").eq(index).animate({"opacity":"1"}, execution_time).find("img").animate({"width":"100%", "height":"100%"}, execution_time);
			//$(demo + " ul li").css("opacity", 0.1).eq(index).animate({"opacity":"1"}, 1500);
		} else if(type == 2) {//左滑动
			$(demo + " ul").stop(true, false).animate({"margin-left":nowLeft+"px"}, execution_time); //通过animate()调整ul元素滚动到计算出的position
		} else if(type == 3) {//渐变
			$(demo + " ul li").stop(true, false).eq(index).animate({"opacity":1}, execution_time).siblings("li").animate({"opacity":0.01}, execution_time, function() {
				//$(this).css("display","none");
			});
		}
	}
	
	//浏览器窗口大小变化时，重新获取参数
	$(window).resize(function() {
		sWidth = $(demo).width();
		$(demo + " ul").css("width", sWidth*len);
		$(demo + " ul li").css("width", sWidth);
	});
}