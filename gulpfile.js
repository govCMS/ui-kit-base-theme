// Define requirements
var importOnce = require('node-sass-import-once');
var path = require('path');

// Gulp and related plugins
var gulp      = require('gulp'),
  $           = require('gulp-load-plugins')(),
  browserSync = require('browser-sync').create(),
  del         = require('del'),
  // gulp-load-plugins will report "undefined" error unless you load gulp-sass manually.
  sass        = require('gulp-sass'),
  kss         = require('kss');
  rename      = require('gulp-rename');

var imagemin = require('gulp-imagemin');
var autoprefixer = require('gulp-autoprefixer');
var handlebars = require('gulp-compile-handlebars');

// Options
var options = {};

options.rootPath = {
	theme   	  : __dirname + '/',	
	proto   : __dirname + '/build/',
	styleGuide  : __dirname + '/build/styleguide/'
};

options.theme = {
  root  : options.rootPath.theme,
  css   : options.rootPath.theme + 'css/',
  js    : options.rootPath.theme + 'js/',
  images: options.rootPath.theme + 'images/'
};

options.proto = {
  root  : options.rootPath.proto,
  css   : options.rootPath.proto + 'css/',  
  sass  : options.rootPath.proto + 'sass/',
  js    : options.rootPath.proto + 'js/',
  images: options.rootPath.proto + 'images/',
  hbs   : options.rootPath.proto + 'hbs/'
};

options.autoprefixer = {
  browsers: ['last 2 versions', 'ie 7-10', 'iOS >= 4']
};

options.handlebars = {
  batch : [options.proto.hbs + 'partials']
};

options.sass = {
	importer: importOnce,
  errLogToConsole: true
  //sourcemap: false,
  //includePaths: options.proto.sass + 'style.scss'
};

options.imagemin = {
  optimizationLevel: 3,
  progressive: true,
  interlaced: true
};

options.eslint = {
  files  : [options.proto.js + '**/*.js']
};

// #########################
// Tasks
// #########################

// #########################
// Build the prototype
// #########################
gulp.task("default", ["build"]);

gulp.task("build", ["html", "styles"]);
//gulp.task("build", ["html", "styles", "images", "js"]);

gulp.task('html', function () {
	return gulp.src(options.proto.hbs + '*.hbs')
    .pipe(handlebars({}, options.handlebars))
    .pipe(rename({
      extname: '.html'
    }))
    .pipe(gulp.dest(options.proto.root))
    .pipe(browserSync.reload({stream: true}));
});

var sassFiles = [options.proto.sass + 'styles.scss'];

gulp.task("styles", function() {
	return gulp.src(sassFiles)
    .pipe($.sourcemaps.init())
    .pipe(sass(options.sass).on('error', sass.logError))
    .pipe($.autoprefixer(options.autoprefixer))
    .pipe($.size({showFiles: true}))
    .pipe($.sourcemaps.write(options.proto.css))
    .pipe(gulp.dest(options.proto.css))
    //.pipe(gulp.dest(options.theme.css))
    .pipe(browserSync.reload({stream: true}));
});

gulp.task("images", function () {
	return gulp.src(options.proto.images + '**/*')
    .pipe(imagemin(options.imagemin))
    .pipe(gulp.dest(options.theme.images))
});

gulp.task("js", function () {
	// No task currently
});

// #########################
// Lint Sass and JavaScript.
// #########################
gulp.task('lint', ['lint:sass', 'lint:js']);

// Lint JavaScript.
gulp.task('lint:js', function() {
  return gulp.src(options.eslint.files)
    .pipe($.eslint())
    .pipe($.eslint.format());
});

// Lint Sass.
gulp.task('lint:sass', function() {
  return gulp.src(options.proto.sass + '**/*.scss')
    .pipe($.sassLint())
    .pipe($.sassLint.format());
});

// Spin up a server and live reload anytime a file changes
gulp.task("watch", function() {
  browserSync.init({
      server: {
          baseDir: options.proto.root
      }
  });
	gulp.watch(options.proto.js + "*");
	gulp.watch(options.proto.sass + "**/*.scss", ["styles"]);
	gulp.watch(options.proto.hbs + "**/*.hbs", ["html"]);
	gulp.watch(options.proto.root + "*.html").on("change", browserSync.reload);
});