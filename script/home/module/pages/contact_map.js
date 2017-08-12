define(['jquery'],function($){
	var page = {
		init : function(){

	    	require(['BMap','domReady'],function(BMap){

							function initMap(){
								createMap();
								setMapEvent();
								addMapControl();
								addMarker();
							}
							
							
							function createMap(){
								var map = new BMap.Map("dituContent");
								var point = new BMap.Point(120.252594,29.277712);
								map.centerAndZoom(point,15);
								window.map = map;
							}
							
							
							function setMapEvent(){
								map.enableDragging();
								map.enableScrollWheelZoom();
								map.enableDoubleClickZoom();
								map.enableKeyboard();
							}
							
							
							function addMapControl(){
								
								var ctrl_nav = new BMap.NavigationControl({anchor:BMAP_ANCHOR_TOP_LEFT,type:BMAP_NAVIGATION_CONTROL_LARGE});
								map.addControl(ctrl_nav);
								
								var ctrl_ove = new BMap.OverviewMapControl({anchor:BMAP_ANCHOR_BOTTOM_RIGHT,isOpen:1});
								map.addControl(ctrl_ove);
								
								var ctrl_sca = new BMap.ScaleControl({anchor:BMAP_ANCHOR_BOTTOM_LEFT});
								map.addControl(ctrl_sca);
							}
							
							
							var markerArr = [{title:"浙江蓝酷网络科技有限公司",content:"浙江省东阳市吴宁街道汉宁东路166号3楼",point:"120.252594|29.277712",isOpen:1,icon:{w:23,h:25,l:46,t:21,x:9,lb:12}}
								 ];
							//´´½¨marker
							function addMarker(){
								for(var i=0;i<markerArr.length;i++){
									var json = markerArr[i];
									var p0 = json.point.split("|")[0];
									var p1 = json.point.split("|")[1];
									var point = new BMap.Point(p0,p1);
									var iconImg = createIcon(json.icon);
									var marker = new BMap.Marker(point,{icon:iconImg});
									var iw = createInfoWindow(i);
									var label = new BMap.Label(json.title,{"offset":new BMap.Size(json.icon.lb-json.icon.x+10,-20)});
									marker.setLabel(label);
									map.addOverlay(marker);
									label.setStyle({
												borderColor:"#808080",
												color:"#333",
												cursor:"pointer"
									});
									
									(function(){
										var index = i;
										var _iw = createInfoWindow(i);
										var _marker = marker;
										_marker.addEventListener("click",function(){
											this.openInfoWindow(_iw);
										});
										_iw.addEventListener("open",function(){
											_marker.getLabel().hide();
										})
										_iw.addEventListener("close",function(){
											_marker.getLabel().show();
										})
										label.addEventListener("click",function(){
											_marker.openInfoWindow(_iw);
										})
										if(!!json.isOpen){
											label.hide();
											_marker.openInfoWindow(_iw);
										}
									})()
								}
							}
							//´´½¨InfoWindow
							function createInfoWindow(i){
								var json = markerArr[i];
								var iw = new BMap.InfoWindow("<b class='iw_poi_title' title='" + json.title + "'>" + json.title + "</b><div class='iw_poi_content'>"+json.content+"</div>");
								return iw;
							}
							//´´½¨Ò»¸öIcon
							function createIcon(json){
	                            var icon = new BMap.Icon("http://api.map.baidu.com/lbsapi/creatmap/images/us_cursor.gif", new BMap.Size(json.w,json.h),{imageOffset: new BMap.Size(-json.l,-json.t),infoWindowOffset:new BMap.Size(json.lb+5,1),offset:new BMap.Size(json.x,json.h)})
	                            return icon;
							}
							initMap();//´´½¨ºÍ³õÊ¼»¯µØÍ¼
	
			});
		}
	}
	return page;
});