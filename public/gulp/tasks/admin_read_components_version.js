var gulp = require('gulp');
var fs = require('fs'),
path = require('path');



var app_path = path.resolve(__dirname, '../../../');

// 1、读取文件readFile函数

//readFile(filename,[options],callback);

/**
 * filename, 必选参数，文件名
 * [options],可选参数，可指定flag（文件操作选项，如r+ 读写；w+ 读写，文件不存在则创建）及encoding属性
 * callback 读取文件后的回调函数，参数默认第一个err,第二个data 数据
 */
console.log(app_path);
var module_name_upper = "Admin";
var module_name_lower = "admin";
var module_form_builder_dir = '/Application/'+module_name_upper + '/Builder/FormBuilder.class.php';
var wirte_file_name = "/public/script/" + module_name_lower + '/module/components/components_form_builder.js';
var components_arr = [];


gulp.task('admin_read_components_version', function(){

	console.log('admin_read_components_version start');


	fs.readFile(app_path + module_form_builder_dir, {flag: 'r+', encoding: 'utf8'}, function (err, data) {
	    if(err) {
	        console.error(err);
	        return;
	    }
	    //console.log(data);

	    var regexp = /\'([\w]+)\'\s+=>\s+'(.*)'/g;

	    
	    while ((rs = regexp.exec(data)) != null){

	    	rs[1] = rs[1].replace('switch','pk_switch'); // r.js build时switch 不用过，需要别名
	        components_arr.push({'name':rs[1],'path':rs[2].replace(/([@~])/g, "\/")});
	    }

	    // console.log(r);

	    // 2、写文件

	    // fs.writeFile(filename,data,[options],callback);
	    // var w_data = '这是一段通过fs.writeFile函数写入的内容；\r\n';

	    var w_data = "define([ \r\n\ ";

	        for(var i in components_arr){
	            if(i< components_arr.length - 1){
	                w_data += "    '../components/form_builder/"+components_arr[i].path+"/main',\r\n ";
	            }else{
	                w_data += "    '../components/form_builder/"+components_arr[i].path+"/main'],\r\n ";
	            }
	        }

	        w_data += "\rfunction(\r\n\ ";

	        for(var i in components_arr){
	            if(i< components_arr.length - 1){
	                w_data += " "+components_arr[i].name+",\r\n\ ";
	            }else{
	                w_data += " "+components_arr[i].name;
	            }
	            
	        }

	        w_data += "\r){\r\
	        \rconsole.log(' test components');\r\
	        \rvar components_arr = [];\r\n\ ";

	        for(var i in components_arr){
	            w_data += "\rcomponents_arr.push({'name' : "+components_arr[i].name+".getName() , 'main' : "+components_arr[i].name+"});\r\n\ ";
	        }
	        
	        w_data += "\rconsole.log(components_arr);\r\n\
	        \rreturn components_arr;\r\n\
	        \r});";



	    var w_data = new Buffer(w_data);

	    /**
	     * filename, 必选参数，文件名
	     * data, 写入的数据，可以字符或一个Buffer对象
	     * [options],flag,mode(权限),encoding
	     * callback 读取文件后的回调函数，参数默认第一个err,第二个data 数据
	     */

	    fs.writeFile(app_path + wirte_file_name, w_data, {flag: 'w'}, function (err) {
	        if(err) {
	            console.error(err);
	        } else {
	            console.log('写入成功');
	        }
	    });

	});






});

