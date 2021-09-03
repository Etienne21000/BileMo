const gulp = require('gulp');
const sass = require('gulp-sass');
const rename = require('gulp-rename');
const clean = require('gulp-clean');
const runSequence = require('gulp4-run-sequence');

sass.compiler = require('node-sass');

gulp.task('build', function() {
    return gulp.src('public/sass/main.scss')
        .pipe(sass({outputStyle: 'compressed'}))
        .pipe(rename('main.min.css'))
        .pipe(gulp.dest('public/style/'));
});

gulp.task('back', function(){
    return gulp.src('public/sass/custom.scss')
        .pipe(sass({outputStyle: 'compressed'}))
        .pipe(rename('custom.min.css'))
        .pipe(gulp.dest('public/style/'));
});

gulp.task('clean-css', function () {
    return gulp.src('public/style/', {read: false})
        .pipe(clean());
});

gulp.task('sass', async function(callback) {
    runSequence(
        ['clean-css'],
        ['build'],
        ['back'],
        callback);
});