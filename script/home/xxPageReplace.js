var searcher = require('./FileSearcher');

var result = searcher.search('../../cpkbuild/home/module','Page');

var num = 0;
for(aa in result ){
console.log('=========start=========');

//console.log(result[aa]);
var file     = result[aa].from;
var filename = file.split('/')[5];
var oldname  = filename.split('-')[0];
var newname  = filename.split('.')[0];
console.log(file);
console.log(filename);
if(filename.split('.js').length==2){
   console.log('yes');
   console.log(filename);
   console.log(oldname);
   console.log(newname);
   //same md5 like taskPage-abcdefg
   var hasSameMd5 = searcher.search(file,newname);
   var hasMoreMd5needDel = searcher.search(file,newname + "-");
   if(hasSameMd5.length>0){
        if(hasMoreMd5needDel.length>0){
	     console.log("need del");
             var replaces = searcher.replace(file,/\w+Page(-\w+)+/g,newname);
             console.log(replaces);
             num++;
	}else{
             console.log("has same name");
        }
   }else{   
   	var replaces = searcher.replace(file,oldname,newname);
   	console.log(replaces);
	num++;
   }
}else{
   console.log('no');
}
console.log('=========end=========');

}

console.log(num);
