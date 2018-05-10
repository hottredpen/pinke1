var gulp = require('gulp');
var searcher = require('./FileSearcher');
var cpk_replace_for_module = require('./cpk_replace_for_module');

gulp.task('admin_page_rename', function(){
  cpk_replace_for_module.start_replace('admin');
});