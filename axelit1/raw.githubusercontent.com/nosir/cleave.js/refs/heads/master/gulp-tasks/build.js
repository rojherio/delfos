var gulp = require('gulp');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var path = require('path');
var header = require('gulp-header');
var gulpsync = require('gulp-sync')(gulp);
var fs = require('fs');
var del = require('del');

var getLicense = function () {
    return '' + fs.readFileSync('src/build/license.html');
};

var packageInfo = JSON.parse(fs.readFileSync('package.json', 'utf8'));
var date = new Date();

var paths = {
    tmp:       './tmp',
    src:       './src',
    build:     'build',
    utils:     'utils',
    common:    'common',
    shortcuts: 'shortcuts',
    dist:      './dist'
};

gulp.task('min-mangle', function () {
    return gulp.src([
            path.join(paths.dist, 'cleave.html'),
            path.join(paths.dist, 'cleave-react-node.html'),
            path.join(paths.dist, 'cleave-react.html')
        ])
        .pipe(uglify({mangle: true}))
        .pipe(header(getLicense(), {
            year:    date.getFullYear(),
            version: packageInfo.version,
            build:   date.toUTCString()
        }))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(path.join(paths.dist)));
});

gulp.task('min-no-mangle', function () {
    return gulp.src([
            path.join(paths.dist, 'cleave-angular.html')
        ])
        .pipe(uglify({mangle: false}))
        .pipe(header(getLicense(), {
            year:    date.getFullYear(),
            version: packageInfo.version,
            build:   date.toUTCString()
        }))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(path.join(paths.dist)));
});

gulp.task('clean', function () {
    del([paths.tmp])
});

gulp.task('build', gulpsync.sync([
    // sync
    'js:vanilla',
    'js:react',
    'js:react-node',
    'js:angular-merge',
    'js:angular',
    [
        'js:esm',
        'js:esm-min'
    ],
    [
        // async
        'min-mangle',
        'min-no-mangle'
    ],
    'clean'
]));
