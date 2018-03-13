var gulp = require('gulp');
var config = require('../config');

var clean = require('gulp-clean');

gulp.task('clean', function() {
	return gulp.src(config.paths.dist.images.web)
		.pipe(clean())
		.pipe(gulp.src(config.paths.dist.js.web))
		.pipe(clean())
		.pipe(gulp.src(config.paths.dist.sass.web))
		.pipe(clean());
});