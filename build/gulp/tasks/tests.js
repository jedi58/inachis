var gulp = require('gulp');
var exec = require('gulp-exec');
 
gulp.task('test', [
	'phplint',
	'psr-2',
	'phpunit'
]);

gulp.task('phpunit', function() {
	return gulp.src('')
		.pipe(exec('phpunit -d date.timezone=\'Europe/London\'', function(error, stdout) {
        console.log(stdout);
    }));
});

gulp.task('phplint', function() {
  return gulp.src('')
    .pipe(
      exec('find -L . -name \'*.php\' -not -path \'*/{vendor,node_modules}/*\' -print0 | xargs -0 -n 1 -P 4 php -l | grep \'Parse\'',
        function(error, stdout) {
          console.log(stdout);
        }
      )
    );
});

gulp.task('psr-2', [
  'psr-2-app',
  'psr-2-src',
  'psr-2-tests',
  'psr-2-web'
]);

gulp.task('psr-2-app', function() {
  return gulp.src('')
    .pipe(exec('phpcs --standard=PSR2 app', function (error, stdout) {
      console.log(stdout);
    }));
});

gulp.task('psr-2-src', function() {
  return gulp.src('')
    .pipe(exec('phpcs --standard=PSR2 src', function (error, stdout) {
      console.log(stdout);
    }));
});

gulp.task('psr-2-tests', function() {
  return gulp.src('')
    .pipe(exec('phpcs --standard=PSR2 tests', function (error, stdout) {
      console.log(stdout);
    }));
});

gulp.task('psr-2-web', function() {
  return gulp.src('')
    .pipe(exec('phpcs --standard=PSR2 web', function (error, stdout) {
      console.log(stdout);
    }));
});
