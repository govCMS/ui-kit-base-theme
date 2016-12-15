// Define requirements
var importOnce = require('node-sass-import-once'),
  gulp         = require('gulp'),
  $            = require('gulp-load-plugins')(),
  browserSync  = require('browser-sync').create(),
  // gulp-load-plugins will report "undefined" error unless you load gulp-sass manually.
  sass         = require('gulp-sass'),
  rename       = require('gulp-rename'),
  path         = require('path'),
  imagemin     = require('gulp-imagemin'),
  autoprefixer = require('gulp-autoprefixer'),
  handlebars   = require('gulp-compile-handlebars'),
  uglify       = require('gulp-uglify'),
  sourcemaps   = require('gulp-sourcemaps'),
  webpack      = require('webpack-stream'),
  svg2png      = require('gulp-svg2png');
  // Add later
  //del         = require('del'),
  //kss         = require('kss');

// Options
var paths = {};
var rootPath = {};
var options = {};

rootPath = {
  theme           : __dirname + '/',
  themeBase       : __dirname + '../../../uikit_base/',
  protoSrc        : __dirname + '/build/src/',
  protoDist       : __dirname + '/build/dist/',
  uikit           : '../uikit_base/ui-kit/assets/',
  styleGuide      : __dirname + '/build/styleguide/'
}

paths = {
  proto: {
    imagesSrc: rootPath.protoSrc + 'img',
    sassSrc: rootPath.protoSrc + 'sass',
    jsSrc: rootPath.protoSrc + 'js',
    pagesSrc: rootPath.protoSrc,

    imagesDist: rootPath.protoDist + 'img',
    cssDist: rootPath.protoDist + 'css',
    jsDist: rootPath.protoDist + 'js',
    pagesDist: rootPath.protoDist
  },
  theme: {
    images: rootPath.theme + 'images',
    sass: rootPath.theme + 'sass',
    css: rootPath.theme + 'css',
    js: rootPath.theme + 'js'
  },
  uikit: {
    imagesSrc: rootPath.uikit + 'img',
    jsSrc: rootPath.uikit + 'js',
    sassSrc: rootPath.uikit + 'sass'
  }
}

options = {
  autoprefixer: {
      browsers: ['last 2 versions', 'ie 7-10', 'iOS >= 4']
  },
  imagemin: {
    optimizationLevel: 3,
    progressive: true,
    interlaced: true
  },
  handlebars: {
    batch : [paths.proto.pagesSrc + 'partials']
  },
  sass: {
    importer: importOnce,
    errLogToConsole: true,
    sourcemap: true
  },
  webpack: {
    output: {
      filename: 'scripts.js'
    }
  }
}

// #########################
// High-level tasks
// #########################

gulp.task('default', ['build']);
gulp.task('build', ['build:proto','build:theme']);
gulp.task('build:proto', ['html', 'images', 'styles:proto', 'js:proto']);
gulp.task('build:theme', ['styles:theme']);

// #########################
// Prototype only tasks
// #########################

gulp.task('html', function () {
	return gulp.src(paths.proto.pagesSrc + '*.hbs')
    .pipe(handlebars({}, options.handlebars))
    .pipe(rename({
      extname: '.html'
    }))
    .pipe(gulp.dest(paths.proto.pagesDist))
    .pipe(browserSync.reload({stream: true}));
});

// Spin up a server and live reload anytime a file changes
gulp.task("watch", ['html'], function() {
  browserSync.init({
      server: {
          baseDir: paths.proto.pagesDist
      }
  });
	gulp.watch(paths.proto.imagesSrc, ['images:proto']);
	gulp.watch(paths.proto.sassSrc + '/**/*.scss', ['styles:proto']);
  gulp.watch(paths.proto.jsSrc + '/**/*.js', ['js:proto']);
	gulp.watch(paths.proto.pagesSrc + '**/*.hbs', ['html']);
	gulp.watch(paths.proto.pagesDist + '*.html').on('change', browserSync.reload);
});

// #########################
// UI Kit tasks
// #########################

// Sass/CSS
gulp.task('styles', ['styles:proto', 'styles:theme']);

gulp.task('styles:proto', function() {
  return gulp.src(paths.proto.sassSrc + '/styles.scss')
    .pipe(sass(options.sass).on('error', sass.logError))
    .pipe($.autoprefixer(options.autoprefixer))
    .pipe(gulp.dest(paths.proto.cssDist))
    .pipe(browserSync.reload({stream: true}));
});

gulp.task('styles:theme', function() {
  return gulp.src(paths.theme.sass + '/styles.scss')
    .pipe(sass(options.sass).on('error', sass.logError))
    .pipe($.autoprefixer(options.autoprefixer))
    .pipe(gulp.dest(paths.theme.css));
});
// Still to add compilation and copy of IE6,7,8 SCSS


// JS
gulp.task('js', ['js:proto','js:theme']);

gulp.task('js:proto', function () {
  return gulp.src([paths.uikit.jsSrc + '/ui-kit.js', paths.proto.jsSrc + '/scripts.js'])
    .pipe(webpack(options.webpack))
    //.pipe(uglify())
    //.pipe(rename({
    //    suffix: '.min'
    //}))
    .pipe(gulp.dest(paths.proto.jsDist));
});

//gulp.task('js:theme', function () {
//  return gulp.src(paths.uikit.jsSrc + '/ui-kit.js')
    .pipe(webpack(options.webpack))
    //.pipe(uglify())
    //.pipe(rename({
    //    suffix: '.min'
    //}))
//    .pipe(gulp.dest(paths.theme.js));
//});

// Images
gulp.task('images', ['images:proto', 'images:ui-kit']);

gulp.task('images:proto', function () {
  return gulp.src(paths.proto.imagesSrc + '/**/*')
    .pipe(imagemin())
    .pipe(gulp.dest(paths.proto.imagesDist));
});

// Copy ui-kit images from base to sub-theme due to pathing constraints on localhost
gulp.task('images:ui-kit', function () {
  return gulp.src(paths.uikit.imagesSrc + '/**/*')
    .pipe(imagemin())
    .pipe(gulp.dest(paths.proto.imagesDist));
});

//ui-kit images and not copied for Drupal as they are access via the base theme and pathed within SCSS
