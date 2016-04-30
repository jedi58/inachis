var gulp = require('gulp');
var config = require('../config');
var imagemin = require('gulp-imagemin');

gulp.task('images:min', [
    'images:min-admin',
    'images:min-web'
]);
gulp.task('images:min-web', function() {
    return gulp.src(config.paths.src.images.web)
        .pipe(imagemin())
        .pipe(gulp.dest(config.paths.dist.images.web));
});
gulp.task('images:min-admin', function() {
    return gulp.src(config.paths.dist.images.admin)
        .pipe(imagemin())
        .pipe(gulp.dest(config.paths.dist.images.admin));
});

gulp.task('images:watch', function() {
    gulp.watch(config.paths.src.images.admin + '**/*', ['images:min-admin']);
    gulp.watch(config.paths.src.images.web + '**/*', ['images:min-web']);
});
