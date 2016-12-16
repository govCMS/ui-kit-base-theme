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
  svg2png      = require('gulp-svg2png'),
  sassGlob     = require('gulp-sass-glob');
  // Add later
  //del         = require('del'),
  //kss         = require('kss');

// Options
var paths = {};
var rootPath = {};
var options = {};

rootPath = {
  theme       : __dirname + '/',
  protoSrc    : __dirname + '/build/src/',
  protoDist   : __dirname + '/build/dist/',
  styleGuide  : __dirname + '/build/styleguide/'
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
    js: rootPath.theme + 'js',
    uikit: rootPath.theme + 'ui-kit'
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
      filename: 'ui-kit.js'
    },
  }
}

// #########################
// High-level tasks
// #########################

gulp.task('default', ['build']);
gulp.task('build', ['build:proto','build:theme']);
gulp.task('build:proto', ['html', 'ui-kit.styles:proto', 'ui-kit.js:proto', 'ui-kit.images:proto']);
gulp.task('build:theme', ['ui-kit.styles:theme', 'ui-kit.js:theme', 'ui-kit.images:theme']);

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
gulp.task("watch", function() {
  browserSync.init({
      server: {
          baseDir: paths.proto.pagesDist
      }
  });
	gulp.watch(paths.proto.imagesSrc, ['ui-kit.images:proto']);
	gulp.watch(paths.proto.sassSrc + '/**/*.scss', ['ui-kit.styles:proto']);
  gulp.watch(paths.proto.jsSrc + '/**/*.js', ['ui-kit.js:proto']);
	gulp.watch(paths.proto.pagesSrc + '**/*.hbs', ['html']);
	gulp.watch(paths.proto.pagesDist + '*.html').on('change', browserSync.reload);
});

// #########################
// UI Kit tasks
// #########################

// Sass/CSS
gulp.task('ui-kit.styles', ['ui-kit.styles:proto', 'ui-kit.styles:theme']);

gulp.task('ui-kit.styles:proto', function() {
  return gulp.src(paths.proto.sassSrc + '/ui-kit.scss')
    .pipe(sass(options.sass).on('error', sass.logError))
    .pipe($.autoprefixer(options.autoprefixer))
    .pipe(gulp.dest(paths.proto.cssDist))
    .pipe(browserSync.reload({stream: true}));
});

gulp.task('ui-kit.styles:theme', function() {
  return gulp.src(paths.theme.sass + '/ui-kit.scss')
    .pipe(sassGlob())
    .pipe(sass(options.sass).on('error', sass.logError))
    .pipe($.autoprefixer(options.autoprefixer))
    .pipe(gulp.dest(paths.theme.css));
});
// Still to add compilation and copy of IE6,7,8 SCSS


// JS
gulp.task('ui-kit.js', ['ui-kit.js:proto','ui-kit.js:theme']);

gulp.task('ui-kit.js:proto', function () {
  return gulp.src(paths.theme.uikit + '/assets/js/ui-kit.js')
    .pipe(webpack(options.webpack))
    //.pipe(uglify())
    //.pipe(rename({
    //    suffix: '.min'
    //}))
    .pipe(gulp.dest(paths.proto.jsDist));
});

gulp.task('ui-kit.js:theme', function () {
  return gulp.src(paths.theme.uikit + '/assets/js/ui-kit.js')
    .pipe(webpack(options.webpack))
    .pipe(uglify())
    .pipe(rename({
        suffix: '.min'
    }))
    .pipe(gulp.dest(paths.theme.js));
});

// Images
gulp.task('ui-kit.images', ['ui-kit.images:proto','ui-kit.images:theme']);

gulp.task('ui-kit.images:proto', function () {
  return gulp.src(paths.theme.uikit + '/assets/img/**/*')
    // SUB-THEME - add inclusion of theme images
    .pipe(imagemin())
    .pipe(gulp.dest(paths.proto.imagesDist));
});

gulp.task('ui-kit.images:theme', function () {
  return gulp.src(paths.theme.uikit + '/assets/img/**/*')
    // SUB-THEME - add inclusion of theme images
    .pipe(imagemin())
    .pipe(gulp.dest(paths.theme.images));
});

// Copy the UI kit from mode_modules to workable locations
var DIR_NPM = path.join(__dirname, 'node_modules');

// Copy UI kit to selected locations
gulp.task('ui-kit.install', function() {
  return gulp.src(path.join(DIR_NPM, 'gov-au-ui-kit/**/*'))
    .pipe(gulp.dest(paths.theme.uikit));
});
