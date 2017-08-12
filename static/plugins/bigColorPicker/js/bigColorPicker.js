(function($){
	var bigColorPicker = new function(){
		this.sideLength = 6;//Áù¸öÇøÓòµÄÃ¿¸ö±ß³¤
		this.targetEl = {};
		var allColorArray = [];//Õû¸öÑÕÉ«ÇøÓò·ÖÎªÇ°Á½ÁÐ£¬ºóÃæËùÓÐÁÐ·ÖÎª6¸ö¿é£¬¼ÆËã³ö¸÷¸öÇøÓòµÄÊý×éºó·ÅÈëÒ»¸ö×ÜµÄ¶þÎ¬Êý×éÀï
		var sideLength = 6;
		this.init = function(){
			sideLength = this.sideLength;
			allColorArray = new Array(sideLength*3);
			//¼ÆËãµÚÒ»ÁÐµÄÊý×é
			var mixColorArray = [];
			var blackWhiteGradientArray = gradientColor(new RGB(0,0,0),new RGB(255,255,255));
			for(var i=0;i<blackWhiteGradientArray.length;i++){
				mixColorArray[i] = blackWhiteGradientArray[i];
			}
			var baseArray = [new RGB(255,0,0),new RGB(0,255,0),new RGB(0,0,255),new RGB(255,255,0),new RGB(0,255,255),new RGB(255,0,255),new RGB(204,255,0),new RGB(153,0,255),new RGB(102,255,255),new RGB(51,0,0)];
			mixColorArray = mixColorArray.concat(baseArray.slice(0, sideLength))
			allColorArray[0] = mixColorArray;
			
			//¼ÆËãµÚ¶þÁÐµÄÊý×é
			var blackArray = new Array(sideLength*2);
			for(var i=0;i<blackArray.length;i++){
				blackArray[i] = new RGB(0,0,0);
			}
			allColorArray[1] = blackArray;
			
			//¼ÆËãÁù¸öÇøÓòµÄÊý×é
			var cornerColorArray = new Array();//Áù¸öÔªËØ£¬Ã¿¸öÔªËØ·Å6¸öÇøÓòµÄËÄ½ÇÑÕÉ«¶þÎ¬Êý×é
			cornerColorArray.push(generateBlockcornerColor(0),generateBlockcornerColor(51),generateBlockcornerColor(102),generateBlockcornerColor(153),generateBlockcornerColor(204),generateBlockcornerColor(255));
			var count = 0;
			var halfOfAllArray1 = [];
			var halfOfAllArray2 = [];
			for(var i=0;i<cornerColorArray.length;i++){
				var startArray = gradientColor(cornerColorArray[i][0][0],cornerColorArray[i][0][1])
				var endArray = gradientColor(cornerColorArray[i][1][0],cornerColorArray[i][1][1])
				for(var j=0;j<sideLength;j++){
					if(i < 3){
						halfOfAllArray1[count] = gradientColor(startArray[j],endArray[j]);
					}else{
						halfOfAllArray2[count - sideLength*3] = gradientColor(startArray[j],endArray[j]);
						
					}
					count++;
				}
			}
			for(var i=0;i<halfOfAllArray1.length;i++){
				allColorArray[i + 2] = halfOfAllArray1[i].concat(halfOfAllArray2[i]);
			}
			
			//½«Êý×éÀïËùÓÐµÄRGBÑÕÉ«×ª»»³ÉHexÐÎÊ½
			for(var i=0;i<allColorArray.length;i++){
				for(var j=0;j<allColorArray[i].length;j++){
					allColorArray[i][j] = RGBToHex(allColorArray[i][j]);
				}
			}
		}
		/*
		 * callback:¿ÉÒÔÊÇundefined£¨²»´«²ÎÊýÊ±£©¡¢×Ö·û´®¡¢º¯Êý
		 * 		undefined£º°ÑÑ¡ÔñµÄÑÕÉ«ÉèÖÃµ½°ó¶¨bigColorpickerµÄÔªËØµÄvalueÉÏ¡£
		 * 		×Ö·û´®£º°ÑÑ¡ÔñµÄÑÕÉ«ÉèÖÃµ½idÎª"×Ö·û´®"µÄÔªËØµÄvalueÉÏ¡£
		 * 		º¯Êý£ºÖ´ÐÐ´«ÈëµÄº¯ÊýÒÔÊµÏÖ×Ô¶¨ÒåµÄ»ñÈ¡ÑÕÉ«ºóµÄ¶¯×÷¡£
		 * engine:¿ÉÒÔÊÇundefined¡¢p(»òP)¡¢l(»òL)£¬
		 * 		ÔÚbigColorpickerÕ¹ÏÖÑÕÉ«Ñ¡ÔñÇøÓòÐ¡¸ñÊ±ÓÐÁ½ÖÖÊµÏÖ·½Ê½£º
		 *     Ò»¡¢ÊÇÒ»ÕÅ±³¾°Í¼Æ¬£¬²ÉÓÃ¹â±ê¶¨Î»µÄ·½Ê½»ñÈ¡ÑÕÉ«¡£p(»òP)
		 *     ¶þ¡¢Ã¿¸öÐ¡¸ñ¾ÍÊÇÒ»¸öli£¬ÉèÖÃliµÄ±³¾°ÑÕÉ«¡£l(»òL)
		 *     ÊµÏÖ·½Ê½µÄ²»Í¬£¬Ð§ÂÊÓÐËù²îÒì£¬¿ÉÒÔ×Ô¼ºÔÚÊ¹ÓÃÊ±Ñ¡Ôñ£¬Ä¬ÈÏÊÇp(»òP)¡£
		 *     undefined£¨²»´«²ÎÊýÊ±£©£ºÊ¹ÓÃÄ¬ÈÏp(»òP)
		 * sideLength:ÉèÖÃÑÕÉ«ÇøÓòµÄ´óÐ¡È¡Öµ·¶Î§Îª2¡«10£¬Ä¬ÈÏÎª6,Ö»ÓÐengineÎªLÊ±²ÅÉúÐ§
		 */
		this.showPicker = function(callback,engine,sideLength_){
			var sl_ = parseInt(sideLength_,10);
			if(engine == "L" && !isNaN(sl_) && sl_ >=2 && sl_ <= 10){
				bigColorPicker.sideLength = sl_;
			}else{
				bigColorPicker.sideLength = 6;
			}
			bigColorPicker.init();
			bigColorPicker.targetEl = this;
			$(bigColorPicker.targetEl).data("bigpickerCallback",callback);
			$(bigColorPicker.targetEl).data("bigpickerId","bigpicker");
			
			if($("#bigpicker").length <=0){
				$(document.body).append('<div id="bigpicker" class="bigpicker"><ul class="bigpicker-bgview-text" ><li><div id="bigBgshowDiv"></div></li><li><input id="bigHexColorText" size="7" maxlength="7" value="#000000" /></li></ul><div id="bigSections" class="bigpicker-sections-color"></div><div id="bigLayout" class="biglayout" ></div></div>');
				
			}
			$("#bigLayout").unbind("hover").unbind("click").hover(function(){
				$(this).show();
			},function(){
				$(this).hide();
			}).click(function(){
				$("#bigpicker").hide();
			});
			
			//ÑÕÉ«Ê°È¡Æ÷textÊäÈë¿ò×¢²áÊÂ¼þ
			$("#bigHexColorText").unbind("keypress").unbind("keyup").unbind("focus").keypress(function(){
				var text = $.trim($(this).val());
				$(this).val(text.replace(/[^A-Fa-f0-9#]/g,''));
				if(text.length <= 0)return;
				text = text.charAt(0)=='#'?text:"#" + text;
				var countChar = 7 - text.length;
				if(countChar < 0){
					text = text.substring(0,7);
				}else if(countChar > 0){
					for(var i=0;i<countChar;i++){
						text += "0";
					};
				}
				$("#bigBgshowDiv").css("backgroundColor",text);
			}).keyup(function(){
				var text = $.trim($(this).val());
				$(this).val(text.replace(/[^A-Fa-f0-9#]/g,''));	
			}).focus(function(){
				this.select();
			})		
			
			
			//¸ø´«ÈëµÄÊ°È¡ÑÕÉ«µÄÔªËØ°ó¶¨clickÊÂ¼þ£¬ÏÔÊ¾ÑÕÉ«Ê°È¡Æ÷
			$(this).unbind("click").bind("click",bigpickerShow);
			function bigpickerShow(event){
				bigColorPicker.targetEl = event.currentTarget;
				var $this = $(bigColorPicker.targetEl);
				$("#bigBgshowDiv").css("backgroundColor","#000000");
				$("#bigHexColorText").val("#000000");
				
				var pos = calculatePosition ($this);
				$("#bigpicker").css({"left":pos.left + "px","top":pos.top + "px"}).fadeIn(300);
				
				var bigSectionsP = $("#bigSections").position();
				$("#bigLayout").css({"left":bigSectionsP.left,"top":bigSectionsP.top}).show();
			}
			
			//µ¥»÷Ò³ÃæÑ¡ÔñÆ÷ÒÔÍâµÄµØ·½Ê±Ñ¡ÔñÆ÷Òþ²Ø
			$(document).bind('mousedown',function(event){
				if($("#bigpicker").length>0){
					if(!($(event.target).parents().andSelf().is('#bigpicker'))){
						$("#bigpicker").hide();
					}
				}
			})
			//监听键盘esc -hottredpen 2016.4.23
			$(document).unbind("keydown.spgl_sp_esc").bind("keydown.spgl_sp_esc",function(event){
				if($("#bigpicker").length>0){
					if(!($(event.target).parents().andSelf().is('#bigpicker'))){
						$("#bigpicker").hide();
					}
				}
			})

			if(engine != undefined){
				try{
					engine = engine.toUpperCase();
				}catch(e){
					engine = "P";
				}
			}
			
			if(engine == "L" ){
				$("#bigSections").unbind("mousemove").unbind("mouseout").unbind("click").removeClass("bigpicker-bgimage");;
				generateBgImage();
			}else{
				//Ô¤¼ÓÔØÍ¼Æ¬
			     var bgmage = new Image();
			     bgmage.src = "../images/big_bgcolor.jpg";
			     
			     //³õÊ¼»¯ÎªÄ¬ÈÏÑùÊ½
				$("#bigSections").height(132).width(220).addClass("bigpicker-bgimage").empty();
				$("#bigpicker").width(227).height(163);
				//Êó±êÒÆ¶¯Ê±¼ÆËã¹â±êµÄÇø¼ä
				var xSections = getSections(sideLength*3 + 2);
				var ySections = getSections(sideLength*3);
				$("#bigSections").unbind("mousemove").unbind("mouseout").unbind("click").mousemove(function(event){
					var $this = $(this);
					var cursorXPos = event.pageX - $this.offset().left;
					var cursorYPos = event.pageY - $this.offset().top;
					var xi = 0;
					var yi = 0;
					for(var i=0;i<(sideLength*3+2);i++){
						if(cursorXPos >= xSections[i][0] && cursorXPos <= xSections[i][1]){
							xi = i;
							break;
						}
					}
					for(var i=0;i<(sideLength*3);i++){
						if(cursorYPos >= ySections[i][0] && cursorYPos <= ySections[i][1]){
							yi = i;
							break;
						}
					}
					$("#bigLayout").css({"left":$this.position().left + xi*11,"top":$this.position().top + yi*11}).show();
					var hex = allColorArray[xi][yi];
					$("#bigBgshowDiv").css("backgroundColor",hex);
					$("#bigHexColorText").val(hex);
					
					invokeCallBack(hex);
					
				}).mouseout(function(){
					$("#bigLayout").hide();
				});
			}
			
			/*
			*/
		}
		
		this.hidePicker = function(){
			var id = $(bigColorPicker.targetEl).data("bigpickerId");
			$("#" + id).hide();
		}
		
		this.movePicker = function(){
			var $this = $(bigColorPicker.targetEl);
			var pos = calculatePosition ($this);
			$("#bigpicker").css({"left":pos.left + "px","top":pos.top + "px"});
			$("#bigLayout").hide();
		}
		
		//¼ÆËã³öÊ°È¡Æ÷²ãµÄleft,top×ø±ê
		function calculatePosition ($el) {
			var offset = $el.offset();
			var compatMode = document.compatMode == 'CSS1Compat';
			var w = compatMode ? document.documentElement.clientWidth : document.body.clientWidth;
			var h = compatMode ? document.documentElement.clientHeight : document.body.clientHeight;
			var pos = {left:offset.left,top:offset.top + $el.height() + 7};
			if(offset.left + 227 > w){
				pos.left = offset.left - 227 - 7;
				if(pos.left < 0){
					pos.left = 0;
				}						
			}
			if(offset.top + $el.height() + 7 + 163 > h){
				pos.top = offset.top - 163 - 7;
				if(pos.top < 0){
					pos.top = 0;
				}
			}
			return pos;
		}		
		
		function invokeCallBack(hex){
			var callback_ = $(bigColorPicker.targetEl).data("bigpickerCallback");
			if($.isFunction(callback_)){
				callback_(bigColorPicker.targetEl,hex);
			}else if(callback_ == undefined || callback_ == ""){
				$(bigColorPicker.targetEl).val(hex);
			}else{
				if(callback_.charAt(0) != "#"){
					callback_ = "#" + callback_;
				}
				$(callback_).val(hex);
			}			
		}
		
		//´´½¨Ð¡ÇøÓòµÄËÄ¸ö½ÇµÄÑÕÉ«¶þÎ¬Êý×é
		function generateBlockcornerColor(r){
			var a = new Array(2);
			a[0] = [new RGB(r,0,0),new RGB(r,255,0)];
			a[1] = [new RGB(r,0,255),new RGB(r,255,255)];			
			return a;
		}

		//Á½¸öÑÕÉ«µÄ½¥±äÑÕÉ«Êý×é
		function gradientColor(startColor,endColor){
			var gradientArray = [];
			gradientArray[0] = startColor;
			gradientArray[sideLength-1] = endColor;
			var averageR = Math.round((endColor.r - startColor.r)/sideLength);
			var averageG = Math.round((endColor.g - startColor.g)/sideLength);
			var averageB = Math.round((endColor.b - startColor.b)/sideLength);
			for(var i=1;i<sideLength-1;i++){
				gradientArray[i] =  new RGB(startColor.r + i*averageR,startColor.g + i*averageG,startColor.b + i*averageB);
			}			
			return gradientArray;
		}
		/*»ñÈ¡Ò»¸ö¶þÎ¬Çø¼äÊý×é±ÈÈç[0,11],[12,23],[24,37]..
		 * sl:Çø¼äµÄ³¤¶È
		*/
		function getSections(sl){
			var sections = new Array(sl);
			var initData = 0; 
			for(var i=0;i<sl;i++){
				var temp = initData + 1;
				initData += 11;
				sections[i] = [temp,initData];
			}			
			return sections;
		}
		
		function RGBToHex(rgb){
			var hex = "#";
			for(c in rgb){
				var h = rgb[c].toString(16).toUpperCase();
				if(h.length == 1){
					hex += "0" + h;
				}else{
					hex += h;
				}
			}
			return hex;
		}
		
		//RGB¶ÔÏóº¯Êý£¬ÓÃÓÚ´´½¨Ò»¸öRGBÑÕÉ«¶ÔÏó
		function RGB(r,g,b){
			this.r = Math.max(Math.min(r,255),0);
			this.g = Math.max(Math.min(g,255),0);
			this.b = Math.max(Math.min(b,255),0);
		}
		
		
		//Éú³É±³¾°ÑÕÉ«
		function generateBgImage(){
			var ulArray = new Array();
			for(var i=0;i<sideLength*3 + 2;i++){
				ulArray.push("<ul>");
				for(var j=0;j<sideLength*2;j++){
					ulArray.push("<li data-color='" + allColorArray[i][j] + "' style='background-color: " + allColorArray[i][j] + ";' ></li>");
				}
				ulArray.push("</ul>");
			}
			$("#bigSections").html(ulArray.join(""));
			var minBigpickerHeight = 90;
			var minBigpickerWidth = 129;
			var minSectionsHeight = minBigpickerHeight - 29;
			var minSectionsWidth = minBigpickerWidth - 5;
			
			var defaultSectionsWidth = (sideLength*3 + 2)*11;
			
			if(defaultSectionsWidth < minSectionsWidth){
				$("#bigSections li,#bigLayout").width(minSectionsWidth/(sideLength*3 + 2) - 1)
					    			.height(minSectionsHeight/(sideLength*2) - 1);
				$("#bigpicker").height(minBigpickerHeight).width(minBigpickerWidth);
				$("#bigSections").height(minSectionsHeight).width(minSectionsWidth);

			}else{
				$("#bigSections").width(defaultSectionsWidth)
				                 .height(sideLength*2*11);
				$("#bigpicker").width(defaultSectionsWidth + 5)
				               .height(sideLength*2*11 + 29);
				
			}			
			$("#bigSections li").hover(function(){
				var $this = $(this);
				$("#bigLayout").css({"left":$this.position().left,"top":$this.position().top}).show();
				
				var cor = $this.attr("data-color");
				$("#bigBgshowDiv").css("backgroundColor",cor);
				$("#bigHexColorText").val(cor);
				invokeCallBack(cor);				
			},function(){
				$("#bigLayout").hide();
			});
		}
		
	};
	
	$.fn.bigColorpicker = bigColorPicker.showPicker;
	$.fn.bigColorpickerMove = bigColorPicker.movePicker; //Ê¹ÓÃÔÚÍÏ×§µÄ¸¡²ãÉÏ£¬ÔÚÍÏ×§Ê±Ê°È¡Æ÷Ëæ¸¡²ãÒÆ¶¯Î»ÖÃ
	$.fn.bigColorpickerHide = bigColorPicker.hidePicker; //¶ÔÓ¦Ò»Ð©ÌØ¶¨µÄÓ¦ÓÃÐèÒªÊÖ¶¯Òþ²ØÊ°È¡Æ÷Ê±Ê¹ÓÃ¡£±ÈÈçÍÏ×§¸¡²ãÊ±Òþ²ØÊ°È¡Æ÷
})(jQuery)