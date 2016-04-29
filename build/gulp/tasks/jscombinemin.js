var gulp = require('gulp');
var config = require('../config');

var concat = require('gulp-concat');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');

gulp.task('js:compile', [
    'js:compile-admin',
    'js:compile-web'
]);
gulp.task('js:compile-web', function() {
  return gulp.src(config.paths.src.js.web + '**/*.js')
    .pipe(concat('scripts.js'))
    .pipe(uglify())
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest(config.paths.dist.js.web));
});
gulp.task('js:compile-admin', function() {
  return gulp.src(config.paths.src.js.admin + '**/*.js')
    .pipe(concat('scripts.js'))
    .pipe(uglify())
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest(config.paths.dist.js.admin));
});

gulp.task('js:watch', function() {
    gulp.watch(config.paths.src.js.admin + '**/*.js', ['js:compile-admin']);
    gulp.watch(config.paths.src.js.web + '**/*.js', ['js:compile-web']);
});
