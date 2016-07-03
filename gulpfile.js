/**
 *  Modified by asasher
 *  ----------------------------------
 *
 *  Web Starter Kit
 *  Copyright 2014 Google Inc. All rights reserved.
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      https://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License
 *
 */

'use strict';

var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var browserSync = require('browser-sync');
var reload = browserSync.reload;
var addr = 'localhost:8000';

var AUTOPREFIXER_BROWSERS = [
  'ie >= 10',
  'ie_mob >= 10',
  'ff >= 30',
  'chrome >= 34',
  'safari >= 7',
  'opera >= 23',
  'ios >= 7',
  'android >= 4.4',
  'bb >= 10'
];

gulp.task('styles', function () {
  return gulp.src([
    'app/css/*.scss',
    ])
    .pipe($.plumber())
    .pipe($.sass({
      precision: 10
    }))
    .pipe($.autoprefixer({browsers: AUTOPREFIXER_BROWSERS}))
    .pipe(gulp.dest('app/css'));
});

gulp.task('default',['styles'] ,function () { });

gulp.task('run',['default'] ,function () { 
  $.run('cd ./app; php -S ' + addr).exec()
    .pipe(gulp.dest('output'));
});

gulp.task('serve', function () {
  browserSync({
    files: ['app/**/*.php',           
          'app/css/**/*.css',
          'app/js/**/*.js',
          'app/images/**/*'],
    notify: false,
    logPrefix: '-_-',
    proxy: addr
  });

  gulp.watch('app/css/**/*.scss',['styles']);
});
