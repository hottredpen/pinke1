var map = new BMap.Map("allmap",{mapType: BMAP_HYBRID_MAP}); 
var mapType = new BMap.MapTypeControl({mapTypes: [BMAP_NORMAL_MAP,BMAP_HYBRID_MAP]});
map.addControl(mapType); //2D图，卫星图
var marker=[];
var infoWindow=[];
map.centerAndZoom(new BMap.Point(120.126003,30.222425), 8);
var opts = {
	width : 250,     // 信息窗口宽度
	height: 80,     // 信息窗口高度
	title : "" , // 信息窗口标题
	enableMessage:true//设置允许信息窗发送短息
};	


for(var i=0;i<data_info.length;i++){
	marker[i] = new BMap.Marker(new BMap.Point(data_info[i][0],data_info[i][1]));  // 创建标注
	content[i] = data_info[i][2];
	map.addOverlay(marker[i]);               // 将标注添加到地图中
	infoWindow[i] = new BMap.InfoWindow(content[i],opts);  // 创建信息窗口对象 
	addMoveOverHandler(content[i],marker[i],i);
	
}
function addMoveOverHandler(content,marker,i){
	marker.addEventListener("mouseover",function(e){
		openInfo(i);
	});

}
function openInfo(i){
	marker[i].openInfoWindow(infoWindow[i],marker[i].getPosition()); //开启信息窗口
}

var ind = 0;
var timer = null;


function autoOpenInfo(id){  
	clearOverlays();
	marker[id].openInfoWindow(infoWindow[id]);
	map.panTo(marker[id].getPosition().lng, marker[id].getPosition().lat);
}
function clearOverlays() {
  if (infoWindow) {
	for (var i=0;i<infoWindow.length;i++) {
		if(typeof(infoWindow[i]) != "undefined") {
		infoWindow[i].close();
	  }
	}
  }
}

function startshow(){  
	timer = setInterval(
		 function(){    
			if(ind == infoWindow.length){     
				 ind = 0;     
			}    
			//console.log(ind);
			autoOpenInfo(ind);

			var ishas=$("#ishas_"+ind).val();
			if(ishas==0){
					// 创建地址解析器实例
					var myGeo = new BMap.Geocoder();
					var address=$("#zz_"+ind).attr("title");
					address=sjaddress(address);
					var ii=ind;
					// 将地址解析结果显示在地图上,并调整地图视野
					myGeo.getPoint(address, function(point){
						if (point) {
							$("#zyy_"+ii).html(point.lng+'-'+point.lat);
							var lng=point.lng;
							var lat=point.lat;
							var regionid=$("#zcounty_"+ii).attr("title");
									 $.ajax({
										url : '/map/addLngLat',
										type : "POST",
										dataType : "json",
										data : {lng:lng,lat:lat,regionid:regionid},
										success : function(res){
										   if(res.status == 1){
										   		$("#ishas_"+ind).val(1);
												//console.log(res);
											} else {
											  
										   }
										}
									 });
						}else{
							alert("您选择地址没有解析到结果!");
						}
					}, "北京市");
			}
			ind++;  
		},
	5000);
}
function myFun(result){
	var cityName = result.name;
	//map.setCenter(cityName);
	alert("当前定位城市:"+cityName);
	openInfo(data_info.length-1);
    startshow();
}
var myCity = new BMap.LocalCity();
myCity.get(myFun);


function sjaddress(address){
	return address.replace("北京北京", "北京");
}
