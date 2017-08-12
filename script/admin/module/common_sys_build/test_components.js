define([ 
     '../components/builder/text/1.0.0/main',
     '../components/builder/select/1.0.0/main'],
 function(
  text,
  select){	        console.log(' test components');	        var components_arr = [];
 components_arr.push({'name' : text.getName() , 'main' : text});
 components_arr.push({'name' : select.getName() , 'main' : select});
 console.log(components_arr);
	        return components_arr;
	        });