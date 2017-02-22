'use strict';

var gulp = require('gulp');
var plugins = require('gulp-load-plugins')();

var config = {
    assetsDir: 'src',
    cssDir: 'css',
    scssPattern: 'scss/**/*.scss',
    cssPattern: 'css/**/*.css',
    prod: !!plugins.util.env.prod,
    sourceMaps: !plugins.util.env.prod
};

var app = {};

app.compileSass = function (paths, dest) {
    gulp.src(paths)
            .pipe(plugins.plumber())
            .pipe(plugins.if(config.sourceMaps, plugins.sourcemaps.init()))
            .pipe(plugins.sass())
            .pipe(config.prod ? plugins.cleanCss() : plugins.util.noop())
            .pipe(plugins.if(config.sourceMaps, plugins.sourcemaps.write('.')))
            .pipe(gulp.dest(dest));
};

app.contactCss = function (paths, dest) {
    gulp.src(paths)
            .pipe(plugins.plumber())
            .pipe(plugins.if(config.sourceMaps, plugins.sourcemaps.init()))
            .pipe(plugins.concat('orangeBookingPlugin.min.css'))
            .pipe(plugins.minifyCss())
            .pipe(config.prod ? plugins.cleanCss() : plugins.util.noop())
            .pipe(plugins.if(config.sourceMaps, plugins.sourcemaps.write('.')))
            .pipe(gulp.dest(dest));
};

gulp.task('styles', function () {
    app.compileSass([
        config.assetsDir + '/' + config.scssPattern
    ], config.assetsDir + '/' + config.cssDir);

    app.contactCss([
        config.assetsDir + '/' + config.cssPattern
    ], 'web/css');
});


gulp.task('watch', function () {
    gulp.watch(config.assetsDir + '/' + config.scssPattern, ['styles']);
    gulp.watch(config.assetsDir + '/' + config.cssPattern, ['styles']);
});

gulp.task('default', ['styles', 'watch']);
