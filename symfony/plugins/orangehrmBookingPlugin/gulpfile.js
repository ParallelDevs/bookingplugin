'use strict';

var gulp = require('gulp');
var plugins = require('gulp-load-plugins')();

var config = {
  assetsDir: 'src',  
  scssPattern: 'scss/**/*.scss',  
  jsPattern: 'js/**/*.js',
  prod: !!plugins.util.env.prod,
  sourceMaps: !!plugins.util.env.prod
};

var app = {};

app.compileSass = function (paths, dest) {
  gulp.src(paths)
          .pipe(plugins.plumber())
          .pipe(plugins.if(config.sourceMaps, plugins.sourcemaps.init()))
          .pipe(plugins.sass())
          .pipe(plugins.autoprefixer({browsers: ['last 3 versions']}))
          .pipe(plugins.minifyCss())
          .pipe(plugins.rename({suffix: '.min'}))
          .pipe(config.prod ? plugins.cleanCss() : plugins.util.noop())
          .pipe(plugins.if(config.sourceMaps, plugins.sourcemaps.write('.')))
          .pipe(gulp.dest(dest));
};

app.compileJs = function (paths, dest) {
  gulp.src(paths)
          .pipe(plugins.ignore('_*.js'))
          .pipe(plugins.plumber())
          .pipe(plugins.include())
          .pipe(plugins.uglify())
          .pipe(plugins.rename({suffix: '.min'}))
          .pipe(gulp.dest(dest));
};

gulp.task('styles', function () {
  app.compileSass([
    config.assetsDir + '/' + config.scssPattern
  ], 'web/css');
});

gulp.task('scripts', function () {
  app.compileJs([
    config.assetsDir + '/' + config.jsPattern
  ], 'web/js');
});


gulp.task('watch', function () {
  gulp.watch(config.assetsDir + '/' + config.scssPattern, ['styles']);
  gulp.watch(config.assetsDir + '/' + config.jsPattern, ['scripts']);
});

gulp.task('default', ['styles', 'scripts', 'watch']);
