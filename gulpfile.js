var gulp = require("gulp"),
	sourcemaps = require("gulp-sourcemaps"),
	rename = require("gulp-rename"),
	uglify = require("gulp-uglify"),
	concat = require("gulp-concat");

gulp.task("uglify", function() {
	var files = [
		"./node_modules/class.extend/lib/class.js",
		"./web/scripts/searchbar.js"
	];


	return gulp.src(files)
		.pipe(sourcemaps.init())
		.pipe(concat("searchbar.js"))
		.pipe(uglify())
		.pipe(rename({ "suffix": ".min" }))
		.pipe(sourcemaps.write())
		.pipe(gulp.dest("./web/scripts/"));
});

gulp.task("watch", function() {
	gulp.watch("./web/scripts/searchbar.js", ["uglify"]);
});

gulp.task("default", ["watch"]);
