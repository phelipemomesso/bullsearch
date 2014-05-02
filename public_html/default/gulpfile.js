// Aqui nós carregamos o gulp e os plugins através da função `require` do nodejs
var gulp = require('gulp');
var jshint = require('gulp-jshint');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var rename = require('gulp-rename');
var imagemin = require('gulp-imagemin');
 
// Definimos o diretorio dos arquivos para evitar repetição futuramente
var files = "./js/**/*";
var filesimage = "./imagens/**/*";
 

gulp.task('images', function() {
 return gulp.src(filesimage)
    // Pass in options to the task
    .pipe(imagemin({optimizationLevel: 5}))
    .pipe(gulp.dest('./imagens/'));
});
 
//Criamos outra tarefa com o nome 'dist'
gulp.task('dist', function() {
 
// Carregamos os arquivos novamente
// E rodamos uma tarefa para concatenação
// Renomeamos o arquivo que sera minificado e logo depois o minificamos com o `uglify`
// E pra terminar usamos o `gulp.dest` para colocar os arquivos concatenados e minificados na pasta build/
gulp.src(files)
.pipe(concat('./js'))
.pipe(rename('bullsearch.min.js'))
.pipe(uglify())
.pipe(gulp.dest('./js'));
});

// Rerun the task when a file changes
gulp.task('watch', function () {
  gulp.watch(files, ['dist']);
  gulp.watch(filesimage, ['images']);
});
 
gulp.task('default', ['dist', 'images', 'watch']);