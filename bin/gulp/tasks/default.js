var gulp = require('gulp');
gulp.task('default', [
	//'clean',
	'js:compile',
	'sass:compile',
	'images:min'
]);

gulp.task('watchall', [
	'sass:watch',
	'js:watch',
	'images:watch'
]);