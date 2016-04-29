var gulp = require('gulp');
var autoprefixer = require('gulp-autoprefixer');
var config = require('../config');
var cssnano = require('gulp-cssnano');
var rename = require('gulp-rename');
var sass = require('gulp-ruby-sass');

gulp.task('sass:compile', [
    'sass:compile-admin',
    'sass:compile-web'
]);
gulp.task('sass:compile-web', function() {
  return sass(config.paths.src.sass.web + '**/*.scss', { style: 'expanded' })
    .pipe(autoprefixer('last 2 version'))
    .pipe(cssnano())
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest(config.paths.dist.sass.web));
});
gulp.task('sass:compile-admin', function() {
  return sass(config.paths.src.sass.web + '**/*.scss', { style: 'expanded' })
    .pipe(autoprefixer('last 2 version'))
    .pipe(cssnano())
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest(config.paths.dist.sass.admin));
});

gulp.task('sass:watch', function() {
    gulp.watch(config.paths.src.sass.admin + '**/*.scss', ['sass:compile-admin']);
    gulp.watch(config.paths.src.sass.web + '**/*.scss', ['sass:compile-web']);
});
