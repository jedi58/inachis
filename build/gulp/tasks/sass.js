var gulp = require('gulp');
var autoprefixer = require('gulp-autoprefixer');
var config = require('../config');
var cssnano = require('gulp-cssnano');
var rename = require('gulp-rename');
var runSequence = require('gulp-run-sequence');
var sass = require('gulp-ruby-sass');

gulp.task('sass:compile', [
    'sass:compile-admin',
    'sass:compile-web'
]);
gulp.task('sass:compile-web', function() {
  return sass('resources/assets/scss/web/*.scss', { style: 'expanded' })
    .pipe(autoprefixer('last 2 version'))
    .pipe(gulp.dest('web/assets/css'))
    .pipe(rename({suffix: '.min'}))
    .pipe(cssnano())
    .pipe(gulp.dest('web/assets/css'));
});
gulp.task('sass:compile-admin', function() {
  return sass('resources/assets/scss/inadmin/*.scss', { style: 'expanded' })
    .pipe(autoprefixer('last 2 version'))
    .pipe(gulp.dest('web/inadmin/assets/css'))
    .pipe(rename({suffix: '.min'}))
    .pipe(cssnano())
    .pipe(gulp.dest('web/indamin/assets/css'));
});

gulp.task('sass:watch', function() {
    gulp.watch(config.paths.src.sass.admin + '**/*', ['sass:compile']);
    gulp.watch(config.paths.src.sass.web + '**/*', ['sass:compile']);
});
