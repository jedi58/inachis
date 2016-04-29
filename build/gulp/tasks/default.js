var gulp = require('gulp');
gulp.task('default', [
	//'clean',
	'js:compile',
	'sass:compile',
	'images:min'
]);

gulp.task('watchall', [
	'images:watch',
	'js:watch',
	'sass:watch'
]);