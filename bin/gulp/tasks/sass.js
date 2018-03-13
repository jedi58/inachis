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
  return sassCompile(config.paths.src.sass.web , config.paths.dist.sass.web);
});
gulp.task('sass:compile-admin', function() {
  return sassCompile(config.paths.src.sass.admin, config.paths.dist.sass.admin);
});

gulp.task('sass:watch', function() {
    gulp.watch(config.paths.src.sass.all + '**/*.scss', [
        'sass:compile-web',
        'sass:compile-admin'
    ]);
});

function sassCompile(src, dest)
{
    return sass(src + '**/*.scss', { style: 'expanded' })
        .pipe(autoprefixer('last 2 version'))
        .pipe(cssnano())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(dest));
}
