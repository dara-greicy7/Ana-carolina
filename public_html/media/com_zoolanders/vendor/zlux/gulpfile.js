/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

var 
pkg     = require('./package.json'),
date    = require('date-utils'),
del     = require('del'),
gulp    = require('gulp'),
less    = require('gulp-less'),
minify  = require('gulp-minify-css'),
rename  = require('gulp-rename'),
replace = require('gulp-replace'),
concat  = require('gulp-concat'),
util    = require('gulp-util'),
uglify  = require('gulp-uglify'),
header  = require('gulp-header'),
jshint  = require('gulp-jshint'),
merge   = require('merge-stream'),
runSeq  = require('run-sequence'),

banner = ['/**',
          ' * @package     '+pkg.name,
          ' * @version     '+pkg.version,
          ' * @author      '+pkg.authors[0].name+' - '+pkg.authors[0].homepage,
          ' * @license     '+pkg.license,
          ' */',
          ''].join('\n').replace(/\n$/g, ''),

output = util.env.output || util.env.o || 'dist';


/** TASKS **/

gulp.task('lint', function() {
  return gulp.src('src/js/**').pipe(jshint()).pipe(jshint.reporter('default'));
});


/* dist */

gulp.task('default', ['dist'], function(cb) {
    cb();
});

gulp.task('build', function(cb) {
    runSeq('dist-clean', 'dist-copy', 'dist-compile', 'dist-concat', 'dist-minify', 'dist-headers', cb);
});

gulp.task('dist-copy', ['dist-clean'], function() {
    return merge(
        gulp.src(['src/js/components/**']).pipe(gulp.dest(output+'/js/components')),
        gulp.src(['src/svg/**']).pipe(gulp.dest(output+'/svg')),
        gulp.src(['src/fonts/**']).pipe(gulp.dest(output+'/fonts')),
        gulp.src(['CHANGELOG.md']).pipe(gulp.dest(output))
    );
});

gulp.task('dist-compile', function() {
    return gulp.src(['src/less/zlux.less', 'src/less/zlux-uikit.less']).pipe(less()).pipe(gulp.dest(output+'/css'));
});

gulp.task('dist-concat', function() {
    return gulp.src(['src/js/core/core.js', 'src/js/core/extensions.js', 'src/js/core/*.js']).pipe(concat('zlux.js')).pipe(gulp.dest(output+'/js'));
});

gulp.task('dist-minify', function() {
    return merge(
        gulp.src(['dist/css/**/*.css']).pipe(rename({ suffix: '.min' })).pipe(minify()).pipe(gulp.dest('dist/css')),
        gulp.src(['dist/js/**/*.js']).pipe(rename({ suffix: '.min' })).pipe(uglify()).pipe(gulp.dest('dist/js'))  
    );
});

gulp.task('dist-headers', function() {
    return gulp.src(['dist/**/*.*(js|css)', '!dist/vendor/**']).pipe(header(banner+'\n\n')).pipe(gulp.dest('dist'));
});

gulp.task('dist-clean', function(cb) {
    del(output, cb);
});


/* helper */

function tagHelper(stream) {
    return stream.pipe(replace('{{VERSION}}',      pkg.version))
                 .pipe(replace('{{NAME}}',         pkg.name))
                 .pipe(replace('{{DESCRIPTION}}',  pkg.description))
                 .pipe(replace('{{DATE}}',         Date.today().toFormat('MMMM YYYY')))
                 .pipe(replace('{{COPYRIGHT}}',    pkg.copyright))
                 .pipe(replace('{{LICENSE}}',      pkg.license))
                 .pipe(replace('{{AUTHOR}}',       pkg.authors[0].name))
                 .pipe(replace('{{AUTHOREMAIL}}',  pkg.authors[0].email))
                 .pipe(replace('{{AUTHORURL}}',    pkg.authors[0].homepage));
}
