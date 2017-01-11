'use strict';

var gulp = require('gulp');
var plugins = require('gulp-load-plugins')();

var config = {
    assetsDir: '.',
    scssPattern: 'scss/**/*.scss',
    prod: !!plugins.util.env.prod,
    sourceMaps: !plugins.util.env.prod
};

var app = {};

app.addStyles = function (paths, dest) {
    gulp.src(paths)
            .pipe(plugins.plumber())
            .pipe(plugins.if(config.sourceMaps, plugins.sourcemaps.init()))
            .pipe(plugins.sass())
            .pipe(config.prod ? plugins.cleanCss() : plugins.util.noop())
            .pipe(plugins.if(config.sourceMaps, plugins.sourcemaps.write('.')))
            .pipe(gulp.dest(dest));
};

gulp.task('styles', function () {
    app.addStyles([
        config.assetsDir + '/' + config.scssPattern
    ], 'web/css');
});


gulp.task('watch', function () {
    gulp.watch(config.assetsDir + '/' + config.sassPattern, ['styles']);
});

gulp.task('default', ['styles', 'watch']);
