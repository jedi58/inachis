var gulp = require('gulp');
var config = require('../config');

var concat = require('gulp-concat');
var minify = require('gulp-babel-minify');
var pump = require('pump');
var rename = require('gulp-rename');

gulp.task('js:compile', [
    'js:compile-admin',
    'js:compile-web'
]);
gulp.task('js:compile-web', function(callback) {
    jsCompile(config.paths.src.js.web, config.paths.dist.js.web, callback);
});
gulp.task('js:compile-admin', function(callback) {
    jsCompile(config.paths.src.js.admin, config.paths.dist.js.admin, callback);
});

gulp.task('js:watch', function() {
    gulp.watch(config.paths.src.js.all + '**/*.js', [
        'js:compile-admin',
        'js:compile-web'
    ]);
});

function jsCompile(src, dest, callback)
{
    pump(
        [
            gulp.src('{' + config.paths.src.js.shared + ',' + src + '}**/*.js'),
            concat('scripts.js'),
            minify({
                mangle: {
                    keepClassName: true
                }
            }),
            rename({ suffix: '.min' }),
            gulp.dest(dest)
        ],
        callback
    );
}
