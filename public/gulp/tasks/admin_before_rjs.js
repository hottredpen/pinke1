var gulp = require('gulp');
var gulpsync = require('gulp-sync')(gulp);


gulp.task('admin_before_rjs', gulpsync.sync([ ['admin_read_components_version'],'admin_copy_components_to_script' ]));
