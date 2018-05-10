var fs = require('fs');

/**
 * 读取文件
 */
var readFile = function(path) {
	return fs.readFileSync(path, 'utf-8');
}

/**
 * 强制写入文件
 * 如果路径不存在则创建路径
 */
writeTextToFile = function(path, text) {
	if(fs.existsSync(path)){
		//已经存在，直接写入
		fs.writeFileSync(path, text, 'utf-8');
	}else{
		var items = path.split('/');
		var aPath = '.';
		//目录不存在，那么创建之
		for(var i=0; i<items.length - 1; i++){
			aPath += '/' + items[i];
			if(!fs.existsSync(aPath)){
				fs.mkdirSync(aPath);
			}
		}
		fs.writeFileSync(path, text, 'utf-8');
	}
	
}

/**
 * 递归深度处理文件目录
 * @param {String} path
 * @param {Function} 文件处理函数，非异步调用
 */
var readDeeply = function(dir, handler) {
	var status = fs.statSync(dir);
	if(status.isFile()) {
		var result = handler && handler(dir, readFile(dir));
		if (result) {
			//有返回数据，表示出现了文本替换
			writeTextToFile(dir, result);
		};
	} else {
		//递归遍历目录
		var files = fs.readdirSync(dir);
		for(var i=0; i<files.length; i++){
			readDeeply(dir + '/' + files[i], handler);
		}
	}
	
}

/**
 * 匹配文件中的符合条件的内容并替换
 * @param {String} 搜索的目录或者文件
 * @param {String | RegExp} 匹配规则，可以是单一的字符串，也可以是正则表达式
 * @param {Funcction | String} 替换规则
 * @param {Object} 匹配和替换结果
 */
var replace = function(dir, condition, replacer) {
	var result = [];
	readDeeply(dir, function(file, text) {
		return text.replace(condition, function(word) {
			var res = null;
			if (typeof replacer == 'string') {
				res = replacer;
			} else if(typeof replacer == 'function'){
				res = replacer.apply(this, this.arguments);
			} else {
			};
			result.push({
				from: file,
				source: word,
				result: res
			});
			return res;
		});
	});
	return result;
}

/**
 * 匹配文件中的符合条件的内容并返回结果
 * @param {String} 搜索的目录或者文件
 * @param {String | RegExp} 匹配规则，可以是单一的字符串，也可以是正则表达式
 * @return {Object} 匹配结果
 */
var search = function(dir, condition) {

	var result = [];
	readDeeply(dir, function(file, text) {
		var temp = text.match(condition);
		if (!temp) {
			return;
		};
		for(var i=0; i<temp.length; i++) {
			result.push({
				from: file,
				result: temp[i]
			});
		}
	});
	return result;
}

exports.search = search;
exports.replace = replace;
exports.tools = {
	writeTextToFile: writeTextToFile
};