$(function(){
	var MTPL='static/css/admin/css/';
	
	//换肤yskj_skin
	var yskj_skin='<div class="yskj_dh yskj_skin dn"><ul>';
	yskj_skin+='<li><a href="javascript:void(0);" id="skin_01"><img src="'+MTPL+'images/login/yskj_skin01.gif" width="170" height="120" /><h2><input type="radio" class="radio1" name="radio2" checked="checked"/>默认</h2></a></li>';
	yskj_skin+='<li><a href="javascript:void(0);" id="skin_02"><img src="'+MTPL+'images/login/yskj_skin02.gif" width="170" height="120" /><h2><input type="radio" class="radio1" name="radio2" />诗意时光</h2></a></li>'
	yskj_skin+='<li><a href="javascript:void(0);" id="skin_03"><img src="'+MTPL+'images/login/yskj_skin03.gif" width="170" height="120" /><h2><input type="radio" class="radio1" name="radio2" />伟岸雄风</h2></a></li>';
	yskj_skin+='<li><a href="javascript:void(0);" id="skin_04"><img src="'+MTPL+'images/login/yskj_skin04.gif" width="170" height="120" /><h2><input type="radio" class="radio1" name="radio2" />海天一色</h2></a></li>';
	yskj_skin+='<li><a href="javascript:void(0);" id="skin_05"><img src="'+MTPL+'images/login/yskj_skin05.gif" width="170" height="120" /><h2><input type="radio" class="radio1" name="radio2" />如画中国</h2></a></li>';
	yskj_skin+='</ul></div>';
	$('body').append(yskj_skin);
	
	//透明度yskj_tm
	var yskj_tm='<div class="yskj_dh yskj_tm dn"><ul>';
	yskj_tm+='<li><a href="javascript:void(0);" id="opcity01"><img src="'+MTPL+'images/login/yskj_qh_1.gif" width="200" height="138" /><h2><input type="radio" class="radio1" name="radio1" checked="checked"/>默认</h2></a></li>';
	yskj_tm+='<li><a href="javascript:void(0);" id="opcity02"><img src="'+MTPL+'images/login/yskj_qh_2.gif" width="200" height="138" /><h2><input type="radio" class="radio1" name="radio1" />透明</h2></a></li>';
	yskj_tm+='</ul></div>';
	$('body').append(yskj_tm);
	
	$('.run_skin').click(function(event){
		$('.yskj_dh').hide();
		$('.yskj_skin').show();	
		event.stopPropagation();
	});
	$('.run_tm').click(function(event){
		$('.yskj_dh').hide();
		$('.yskj_tm').show();
		event.stopPropagation();	
	});
	$('html,body').click(function(){
		$('.yskj_dh').hide();
	});
	$('.yskj_dh').click(function(event){
		event.stopPropagation();	
	});
	//获取焦点，取消焦点
	$('.txt').bind({
	focus:function(){
	if (this.value == this.defaultValue){
	this.value="";
	}
	},
	blur:function(){
	if (this.value == ""){
	this.value = this.defaultValue;
	}
	}
	});
	//换肤  
    var myskin=$.cookie('myskin');
	//console.log(myskin);
    if(myskin){
        switchskin(myskin);    
    }
    var skini=$('.yskj_skin a');
    skini.click(function(){
        switchskin(this.id);  
    });
	function switchskin(skin){
		$('.yskj_skin a').removeClass('on');
		$('#'+skin).addClass('on');
		$('#'+skin).find('input').attr('checked','checked');
		$('#skin').attr('href',MTPL+skin+'.css');
		$.cookie('myskin',skin,{path:'/',expires:365})    
    }  
	//透明度
	function switchtm(opcity){		
		$('.main_bg').removeClass('opcity01');
		$('.main_bg').removeClass('opcity02');
		$('.main_bg').addClass(opcity);
		$('#'+opcity).find('input').attr('checked','checked');
                $.cookie('opcity',opcity,{path:'/',expires:365}) 	
	}
	var opcity=$.cookie('opcity');
	if(opcity){
		switchtm(opcity);	
	}
	var tmi=$('.yskj_tm a');
	tmi.click(function(){
		switchtm(this.id);	
	});
})