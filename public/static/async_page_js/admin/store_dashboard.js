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

			    // 获取入库出库数量
			    $.ajax({
			        type : 'get',
			        url  : '/store/index/api_test',
			        success: function(res) {
			        	console.log(res);
			        	//start_print_map(res.data);
			        	start_print_pie(res.data);
			       		console.log(res.data);

			        }
			    });
				fetchPieceNumHistoryData();
			});
		}




		function _onDocumentBtn(){
			$('#J_select_month_value').on('change',function(){
				fetchPieceNumHistoryData();
			});
		}
		
		
		function inout_nul_line_picture(draw_data_xAxis,draw_data_series_enter,draw_data_series_outer,draw_data_series_store){

			var myChart = echarts.init(document.getElementById('j_inout_num_line'));
			var option = {
			    title : {
			        text: '出入库情况',
			        subtext: '以个数为单位'
			    },
			    tooltip : {
			        trigger: 'axis'
			    },
			    legend: {
			        data:['出库','入库']
			    },
			    toolbox: {
			        show : true,
			        feature : {
			            mark : {show: true},
			            dataView : {show: true, readOnly: false},
			            magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
			            restore : {show: true},
			            saveAsImage : {show: true}
			        }
			    },
			    calculable : true,
			    xAxis : [
			        {
			            type : 'category',
			            boundaryGap : false,
			            data : draw_data_xAxis
			        }
			    ],
			    yAxis : [
			        {
			            type : 'value'
			        }
			    ],
			    series : [
			        {
			            name:'出库',
			            type:'line',
			            smooth:true,
			            itemStyle: {normal: {areaStyle: {type: 'default'}}},
			            data:draw_data_series_outer
			        },
			        {
			            name:'入库',
			            type:'line',
			            smooth:true,
			            itemStyle: {normal: {areaStyle: {type: 'default'}}},
			            data:draw_data_series_enter
			        }
			    ]
			};
        	// 使用上面的配置项作为参数，传给echart来显示
        	myChart.setOption(option);
		}

		function start_print_pie(res_data){

	        var myPie = echarts.init(document.getElementById('j_charts_pie'));
	        var option = {
        		title   : {
			        text: '出入库类型比例图',
			        subtext: '仓库管理系统',
			        x:'center'
		            },
	            tooltip : {
	                trigger: 'item',
	                formatter: "{a} <br/>{b} : {c} ({d}%)"
	            },
	            legend  : {
	                orient: 'vertical',
	                x: 'left',
	                data: ['采购入库','转仓入库','进口入库','销售退货','其他原因（入库）','销售出库','转仓出库','采购退货','其他原因（出库）']
	            },
	            series  : [
	                {
	                    name: '出入库类型',
	                    type: 'pie',
	                    radius: ['30%', '45%'],
	                    center: ['60%', '60%'],
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
	                        {value:res_data.ruku.num, name:'采购入库'},
	                        {value:res_data.zcrk.num, name:'转仓入库'},
	                        {value:res_data.jkrk.num, name:'进口入库'},
	                        {value:res_data.xsth.num, name:'销售退货'},
	                        {value:res_data.yyrk.num, name:'其他原因（入库）'},		                       
	                        {value:res_data.chuku.num, name:'销售出库'},
	                        {value:res_data.zcck.num, name:'转仓出库'},
	                        {value:res_data.cgth.num, name:'采购退货'},
	                        {value:res_data.yyck.num, name:'其他原因（出库）'},
	                        
	                    ],
	                    itemStyle: {
	                        emphasis: {
	                            shadowBlur: 10,
	                            shadowOffsetX: 0,
	                            shadowColor: 'rgba(0, 0, 0, 0.5)'
	                        }
	                    }
	                }
	            ]
			};
		    // 使用上面的配置项作为参数，传给echart来显示
		    myPie.setOption(option);
		}
		function start_print_map(res_data){
	        var myChart = echarts.init(document.getElementById('j_charts_map'));
	        var option = {
				title : {
		        text: '出入库比例图',
		        subtext: '仓库管理系统',
		        x:'center'
		            },
		            tooltip : {
		                trigger: 'item',
		                formatter: "{a} <br/>{b} : {c} ({d}%)"
		            },
		            legend: {
		                orient: 'vertical',
		                x: 'left',
		                data: ['入库','出库']
		            },
		            series : [
		                {
		                    name: '入库出库',
		                    type: 'pie',
		                    radius : '55%',
		                    center: ['50%', '60%'],
		                    data:[
		                        {value:res_data.ruku.num, name:'入库'},
		                        {value:res_data.chuku.num, name:'出库'},
		                        
		                    ],
		                    itemStyle: {
		                        emphasis: {
		                            shadowBlur: 10,
		                            shadowOffsetX: 0,
		                            shadowColor: 'rgba(0, 0, 0, 0.5)'
		                        }
		                    }
		                }
		            ]
		        };
	        // 使用上面的配置项作为参数，传给echart来显示
	        myChart.setOption(option);
		}
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

		function fetchPieceNumHistoryData(){
			var month = $('select[name=month]').val();

		    $.ajax({
		    	type : 'get',
		    	url  : "/store/index/getPieceNumHistory",
		    	data : {
		    		month:month
		    	},
		    	success :function(res){
		    		var draw_data = {};
		    		// 待优化了，此处就简单粗暴点定义了
		    		var draw_data_xAxis= [];
		    		var draw_data_series_enter = [];
		    		var draw_data_series_outer = [];
		    		var draw_data_series_store = [];
		    		$.each(res.data,function(i,d_obj){
		    			draw_data_xAxis.push(d_obj.event_date);
						draw_data_series_enter.push(d_obj.today_enter_num);
						draw_data_series_outer.push(d_obj.today_outer_num);
						draw_data_series_store.push(d_obj.all_this_store_product_num);
		    		});
		    		inout_nul_line_picture(draw_data_xAxis,draw_data_series_enter,draw_data_series_outer,draw_data_series_store);
		    	}

		    });
		}



		return obj;
	}
}

return page_trigger;
});


