require.config({
	paths: {
		jquery: 'boot3/jquery'
	}
});

require(['jquery'], function($) {
	alert($().jquery);
});

require(['jquery'], function($) {
	alert("sdfsadfsfd");
});