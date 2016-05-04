var gulp = require('gulp');
var exec = require('gulp-exec');

gulp.task('composer-autoload', function() {
	return gulp.src('')
		.pipe(exec('composer dump-autoload --optimize', function(error, stdout) {
        console.log(stdout);
    }));
});
