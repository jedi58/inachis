var gulp = require('gulp');
var config = require('../config');
var crypto = require('crypto');
var jeditor = require("gulp-json-editor");
var path = require('path');

gulp.task('encryption:generate-key', function()
{
    return gulp.src(config.paths.config.system)
            .pipe(jeditor(function(json) {
                var replaceKey = checkKey(json)
                if (replaceKey) {
                    json.security.encryptionKey = getKey(48);   
                }
                return json;
            },
            {
                'end_with_newline': true,
                'indent_char': ' ',
                'indent_size': 4
            }))
            .pipe(gulp.dest(path.dirname(config.paths.config.system)));
});

function checkKey(json)
{
    if (json.security.encryptionKey) {
        console.log('An encryption key already defined, changing this will break existing data.');
        // @todo prompt to see if this should be overwritten
        return false;
    }
    return true;
}

function getKey(length)
{
    return crypto.randomBytes(length || 48).toString('hex')
}
