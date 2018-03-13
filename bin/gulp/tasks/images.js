var gulp = require('gulp');
var config = require('../config');
var imagemin = require('gulp-imagemin');

gulp.task('images:min', [
    'images:min-admin',
    'images:min-web'
]);
gulp.task('images:min-web', function() {
    return imgMin(config.paths.src.images.web, config.paths.dist.images.web);
});
gulp.task('images:min-admin', function() {
    return imgMin(config.paths.dist.images.admin, config.paths.dist.images.admin);
});

gulp.task('images:watch', function() {
    gulp.watch(config.paths.src.images.admin + '**/*', ['images:min-admin']);
    gulp.watch(config.paths.src.images.web + '**/*', ['images:min-web']);
});

function imgMin(src, dest)
{
    return gulp.src(src + '*')
        .pipe(imagemin([imagemin.gifsicle(), imagemin.jpegtran(), imagemin.optipng(), imagemin.svgo()], true))
        .pipe(gulp.dest(dest));
}
