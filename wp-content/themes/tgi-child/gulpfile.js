'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var uglify = require('gulp-uglify');
var pump = require('pump');
var runSequence = require('run-sequence');
var del = require('del');
var postcss = require('gulp-postcss');
var autoprefixer = require('autoprefixer');

var path = {
	source: './assets',
	build: './dist'
};

gulp.task('clean', function () {
	return del(path.build);
});

gulp.task('styles', function () {
	return gulp.src(path.source + '/styles/main.scss')
		.pipe(sourcemaps.init())
		.pipe(sass().on('error', sass.logError))
		.pipe(postcss([autoprefixer()]))
		.pipe(sourcemaps.write())
		.pipe(gulp.dest(path.build + '/styles'));
});

gulp.task('scripts', function (cb) {
	pump([
			gulp.src(path.source + '/scripts/*.js'),
			uglify(),
			gulp.dest(path.build + '/scripts')
		],
	);
});
gulp.task('copy-vendor-scripts', function () {
	return gulp.src(path.source + '/scripts/vendor/*.js')
		.pipe(gulp.dest(path.build + '/scripts/vendor'));
});

gulp.task('copy-fonts', function () {
	return gulp.src(path.source + '/fonts/**/*')
		.pipe(gulp.dest(path.build + '/fonts'));
});

gulp.task('copy-images', function () {
	return gulp.src(path.source + '/images/**/*')
		.pipe(gulp.dest(path.build + '/images'));
});

gulp.task('watch', function () {
	gulp.watch(path.source + '/styles/**/*.scss', ['styles']);
	gulp.watch(path.source + '/scripts/*.js', ['scripts']);
	gulp.watch(path.source + '/fonts/**/*', ['copy-fonts']);
	gulp.watch(path.source + '/images/**/*', ['copy-images']);
});

gulp.task('build', ['clean'], function () {
	runSequence('styles',
		'scripts',
		'copy-vendor-scripts',
		'copy-fonts',
		'copy-images');
});

gulp.task('default', function () {
	gulp.start('build');
})
