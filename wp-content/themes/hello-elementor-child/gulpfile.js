const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const rename = require('gulp-rename');

gulp.task('sass', function () {
    return gulp
        .src('src/sass/theme.scss') // ✅ Corrected path
        .pipe(sass().on('error', sass.logError))
        .pipe(rename('theme.css'))
        .pipe(gulp.dest('assets/css'));
});

gulp.task('watch', function () {
    gulp.watch('src/sass/**/*.scss', gulp.series('sass')); // ✅ Corrected watch path
});

gulp.task('default', gulp.series('sass', 'watch'));
