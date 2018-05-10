var gulp = require('gulp');
var gulpsync = require('gulp-sync')(gulp);

//gulp.task('default', gulpsync.sync(['clean', ['less', 'images', 'js'],'rev','watch']));
gulp.task('module_admin', gulpsync.sync([ ['less', 'images', 'js'],'rev','admin_page_rename']));

//gulp.task('deploy', gulpsync.sync(['clean', ['less-deploy', 'imagemin'],'rev']));
gulp.task('deploy', gulpsync.sync([ ['less-deploy', 'imagemin'],'rev']));
