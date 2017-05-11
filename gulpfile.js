var gulp = require("gulp"),
	sourcemaps = require("gulp-sourcemaps"),
	rename = require("gulp-rename"),
	uglify = require("gulp-uglify");

gulp.task("uglify", function() {
	return gulp.src("./web/scripts/searchbar.js")
		.pipe(sourcemaps.init())
		.pipe(uglify())
		.pipe(rename({ "suffix": ".min" }))
		.pipe(sourcemaps.write())
		.pipe(gulp.dest("./web/scripts/"));
});

gulp.task("watch", function() {
	gulp.watch("./web/scripts/searchbar.js", ["uglify"]);
});

gulp.task("default", ["watch"]);
