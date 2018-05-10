var searcher = require('./FileSearcher');
/**
 * 强制写入文件
 * 如果路径不存在则创建路径
 */
start_replace = function(module_name) {

	// 异步加载的components名称替换
	console.log('开始异步加载的components名称替换：'+'cpkbuild/'+module_name+'/module    '+'查找：'+'components/');
	var result_components = searcher.search('cpkbuild/'+module_name+'/module','components/');
	for(aa in result_components ){
		console.log('本次查找结果：');
		console.log(result_components[aa]);
		var file_components     = result_components[aa].from;
		console.log('file_components(组件):'+file_components);
		replace_by_rev('components',searcher,file_components,module_name);
	}

	// Page主页面中baseUrl 及 pages 的名称替换
	var result = searcher.search('cpkbuild/'+module_name+'/module','Page');
	var num = 0;
	for(aa in result ){
		console.log('=========start=========');

		console.log(result[aa]);
		var file     = result[aa].from;
		var filename = file.split('/')[3];
		var oldname  = filename.split('-')[0];
		var newname  = filename.split('.')[0];
		
		if(filename.split('.js').length==2){
			console.log("当前js文件："+file);
			console.log(filename);
			console.log(oldname);
			console.log(newname);
			console.log(file);

		   	//same md5 like taskPage-abcdefg
		   	var hasRepalceToMd5         = searcher.search(file,newname);       // 内部存在taskPage-abcdefg
		   	var hasMoreMd5need_do_again = searcher.search(file,newname + "-"); // 内部存在taskPage-abcdefg-errorddds(被多次替换,一般是认为改写引起)

		   	// 替换baseUrl
		  	var new_replaces = searcher.replace(file,/baseUrl:"\/script\//g,'baseUrl:"/cpkbuild/');
		  	console.log(new_replaces);

		  	replace_by_rev('pages',searcher,file,module_name);
	

			

		   	if(hasRepalceToMd5.length>0){
		        if(hasMoreMd5need_do_again.length>0){
			     	console.log("need replace again");
		            var replaces = searcher.replace(file,/\w+Page(-\w+)+/g,newname);
		            console.log(replaces);
		            num++;
				}else{
		            console.log("has same name");
		        }
		   	}else{
		   	    // 开始替换   
		   	    var reg      = new RegExp(remove_sub_line(oldname),"g");
		   		var replaces = searcher.replace(file,reg,newname);
		   		console.log(replaces);
				num++;
		   	}
		}else{
			console.log("当前文件不是js文件："+file);
		}
	}
	console.log('=========end=========');
	console.log(num);
}

function replace_by_rev(path_name,searcher,file,module_name){

  	// 替换内部的异步加载页面js,从cpkbuild/rev/js/rev-manifest.json中查找
  	// "car/module/pages/buycar.js": "car/module/pages/buycar-2aecd23339.js",
    

  	console.log('replace_by_rev开始：'+'path_name--['+path_name+']  file--['+file+']');

    var rev_file = 'cpkbuild/rev/js/rev-manifest.json';
    var reg      = new RegExp(module_name + "\/module\/"+path_name+"\/(\\w+)-\\w+.js","g");
    var _bbbb    = searcher.search(rev_file,reg);       // 内部存在taskPage-abcdefg
	// console.log("ppppppppppppppppppppppppppppppppp");
	for(bb in _bbbb){
		console.log('ppppppppppppppppppppppppppppppppp');

		var kk = _bbbb[bb].result;
		console.log('在rev-manifest找到的本次替换的对象:'+kk);
		var pages_name_js   = kk.split('/')[2] + "/" + kk.split('/')[3]; // 如：pages/custom-123a5b35ba.js
		
		var pages_module_name = kk.split('/')[0]; // 如：cms 

		
		var path_pages_name = pages_name_js.split('.')[0]; // 如：pages/index-06a9efdb9b

		
		var this_name_all = path_pages_name.split('/')[1]; // 如：contact-066d4c28ad

		
		var this_name = this_name_all.split('-')[0]; // contact


		// 当前模块内的 pages 或者 components
		console.log('pages_module_name与module_name是否相同:['+pages_module_name+']['+module_name+']');
		if(pages_module_name == module_name){
			console.log('相同');
		   	// 替换pages/index 为 pages/index-abcdef
		   	// 检测是否已经存在index-abcdef
		   	// pages/index-be16977f4e
		   	var hasRepalceToMd5         = searcher.search(file,path_pages_name);       // 内部存在taskPage-abcdefg
		   	var hasMoreMd5need_do_again = searcher.search(file,path_pages_name + "-"); // 内部存在taskPage-abcdefg-errorddds(被多次替换,一般是认为改写引起)
		   	if(hasRepalceToMd5.length>0){
		        if(hasMoreMd5need_do_again.length>0){
			     	console.log("need replace again");
			     	var reg_rrr      = new RegExp(path_name+"\/"+this_name+"+(-\\w+)+","g");
		            var replaces = searcher.replace(file,reg_rrr,path_pages_name);
		            console.log(replaces);
				}else{
		            console.log("has same name");
		        }
		   	}else{
		   	    // 开始替换   
		   	    console.log("开始替换:"+file+'--将'+remove_sub_line(path_pages_name)+' 替换为：'+path_pages_name);
		   	    var reg      = new RegExp(remove_sub_line(path_pages_name),"g");
			  	var new_ddd_replaces = searcher.replace(file,reg,path_pages_name);
			  	console.log("new_ddd_replaces");
			  	console.log(new_ddd_replaces);
		   	}
		}else{
			console.log('不相同');
		}

	}
	// console.log(_bbbb);
}

function remove_sub_line(name_with_line){
	var oldname  = name_with_line.split('-')[0];
	return oldname;
}
	

exports.start_replace = start_replace;