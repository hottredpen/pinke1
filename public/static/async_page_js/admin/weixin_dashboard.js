define(['jquery','echarts_all'],function($){

var page_trigger = {
	createObj : function(){
		var obj = {};
		var o_document = $(document);

        var config = {
                "builderDiv"        : "",
        };

		obj.init = function(userconfig){
			config       = $.extend({}, config, userconfig);
			_init_something();
			_onDocumentBtn();
		}

		function _init_something(){

			$(function(){
			    // var obj = [
			    //     {b: '3', c: 'c'},
			    //     {b: '1', c: 'a'},
			    //     {b: '2', c: 'b'}
			    // ];

			    // 1、数字排序
			    // 复制代码 代码如下:
			    // obj.sort(sortBy('b', false, parseInt));
			    // console.log(obj);

			    // 2、字符串排序
			    // 复制代码 代码如下:
			    // obj.sort(sortBy('b', false, String));
			    // console.log(obj);
			    var sortBy = function (filed, rev, primer) {
			        rev = (rev) ? -1 : 1;
			        return function (a, b) {
			            a = a[filed];
			            b = b[filed];
			            if (typeof (primer) != 'undefined') {
			                a = primer(a);
			                b = primer(b);
			            }
			            if (a < b) { return rev * -1; }
			            if (a > b) { return rev * 1; }
			            return 1;
			        }
			    };

			    // 获取区域男女人数
			    $.ajax({
			        type : 'get',
			        url  : '/admin/weixin/api_wechat_user_sex_data',
			        success: function(res) {
			            start_print_map(res.data);
			            // 地区总人数排名
			            area_ranking(res.data.all);
			        }
			    });

			    // 获取新增跑路粉丝人数
			    $.ajax({
			        type : 'get',
			        url  : '/admin/weixin/api_wechat_user_join_data',
			        success: function(res) {
			            start_print_line(res.data);
			        }
			    });




			    function area_ranking(data){
			        data.sort(sortBy('value', false, parseInt));
			        data.reverse(data);
			        var str = "";
			        $.each(data,function(i,obj){
			            if(i<10){
			                str += "<tr>";
			                str += "<td>\
			                            "+parseInt(i+1)+"\
			                        </td>\
			                        <td >\
			                            "+obj.name+"\
			                        </td>\
			                        <td >\
			                            "+obj.value+"\
			                        </td></tr>";
			            }
			        });
			        $('#j_area_data_body').html(str);
			    }
			    function start_print_line(res_data){

			        var myLine = echarts.init(document.getElementById('j_charts_line'));
			        var line_option = {

			        tooltip : {
			            trigger: 'axis'
			        },
			        legend: {
			            data:['新增粉丝','跑路粉丝','净增粉丝']
			        },
			        toolbox: {
			            show : true,
			            feature : {
			                // mark : {show: true},
			                dataView : {show: true, readOnly: false},
			                magicType : {show: true, type: ['line', 'bar', 'tiled']},
			                restore : {show: true},
			                saveAsImage : {show: true}
			            }
			        },
			        calculable : true,
			        xAxis : [
			            {
			                type : 'category',
			                boundaryGap : false,
			                data : res_data.ref_date
			            }
			        ],
			        yAxis : [
			            {
			                type : 'value'
			            }
			        ],
			        series : [
			            {
			                name:'新增粉丝',
			                type:'line',
			                smooth:true,
			                symbol:'circle',
			                data:res_data.new_user
			            },
			            {
			                name:'跑路粉丝',
			                type:'line',
			                smooth:true,
			                symbol:'circle',
			                data:res_data.cancel_user
			            },
			            {
			                name:'净增粉丝',
			                type:'line',
			                smooth:true,
			                symbol:'circle',
			                data:res_data.add_user
			            }
			        ]
			                        

			        }
			        myLine.setOption(line_option);
			    }


			    function start_print_map(res_data){

			        var myChart = echarts.init(document.getElementById('j_charts_map'));
			        var option = {

			            title : {
			                text: '微信粉丝分布',
			                subtext: '男女分布',
			                x:'center'
			            },
			            tooltip : {
			                trigger: 'item'
			            },
			            legend: {
			                orient: 'vertical',
			                x:'left',
			                data:['女','男','未知']
			            },
			            dataRange: {
			                x: 'left',
			                y: 'bottom',
			                splitList: [
			                    {start: 300},
			                    {start: 250, end: 300},
			                    {start: 150, end: 200},
			                    {start: 100, end: 150},
			                    {start: 10, end: 100},
			                    {start: 5, end: 10},
			                    {start: 1, end: 5},
			                    {end: 1}
			                ],
			                color: ['#0533fc', '#2a76d7', '#fff']
			            },


			            toolbox: {
			                show: true,
			                orient : 'vertical',
			                x: 'right',
			                y: 'center',
			                feature : {
			                    // mark : {show: true},
			                    dataView : {show: true, readOnly: false},
			                    restore : {show: true},
			                    saveAsImage : {show: true}
			                }
			            },
			            roamController: {
			                show: true,
			                x: 'right',
			                mapTypeControl: {
			                    'china': true
			                }
			            },
			            series : [
			                {
			                    name: '男',
			                    type: 'map',
			                    mapType: 'china',
			                    roam: false,
			                    itemStyle:{
			                        normal:{
			                            label:{show:true},
			                            borderColor:'#dfdfdf'//省份的边框颜色
			                        },
			                        emphasis:{label:{show:true}}
			                    },
			                    data:res_data.man
			                },
			                {
			                    name: '女',
			                    type: 'map',
			                    mapType: 'china',
			                    itemStyle:{
			                        normal:{
			                            label:{show:true},
			                            borderColor:'#dfdfdf'//省份的边框颜色
			                        },
			                        emphasis:{label:{show:true}}
			                    },
			                    data:res_data.woman
			                },
			                {
			                    name: '未知',
			                    type: 'map',
			                    mapType: 'china',
			                    itemStyle:{
			                        normal:{
			                            label:{show:true},
			                            borderColor:'#dfdfdf'//省份的边框颜色
			                        },
			                        emphasis:{label:{show:true}}
			                    },
			                    data:res_data.unknown
			                }
			            ]

			        }
			        myChart.setOption(option);


			        var myPie = echarts.init(document.getElementById('j_charts_pie'));
			        var option_pie =  {
			            tooltip: {
			                trigger: 'item',
			                formatter: "{a} <br/>{b}: {c} ({d}%)"
			            },
			            legend: {
			                orient: 'vertical',
			                x: 'left',
			                data:['女性','男性','未知']
			            },
			            series: [
			                {
			                    name:'微信性别',
			                    type:'pie',
			                    radius: ['50%', '70%'],
			                    avoidLabelOverlap: false,
			                    label: {
			                        normal: {
			                            show: false,
			                            position: 'center'
			                        },
			                        emphasis: {
			                            show: true,
			                            textStyle: {
			                                fontSize: '30',
			                                fontWeight: 'bold'
			                            }
			                        }
			                    },
			                    labelLine: {
			                        normal: {
			                            show: false
			                        }
			                    },
			                    data:[
			                        {value:res_data.num.woman, name:'女性'},
			                        {value:res_data.num.man, name:'男性'},
			                        {value:res_data.num.unknown, name:'未知'}
			                    ]
			                }
			            ]
			        };

			        myPie.setOption(option_pie);

			    }

			});
		}

		function _onDocumentBtn(){



		}

		return obj;
	}
}

return page_trigger;
});


