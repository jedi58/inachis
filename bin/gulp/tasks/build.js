var gulp = require('gulp');
var config = require('../config');
var exec = require('gulp-exec');

gulp.task('build', [
	'composer',
	'images:min-web',
	'images:min-admin',
	'js:compile-web',
	'js:compile-admin',
	'sass:compile-web',
	'sass:compile-admin'
]);

gulp.task('composer', function() {
  return gulp.src('')
    .pipe(exec('composer install --prefer-dist -o --no-dev', function (error, stdout) {
      console.log(stdout);
    }));
});
