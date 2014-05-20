'use strict';
module.exports = function(grunt) {

grunt.initConfig({
	compass: {
		dist: {
			options: {
				config: 'config.rb',
				sassDir: 'static/src/sass',
				cssDir: 'public/css',
				environment: 'production'
			}
		}
	},

	autoprefixer: {
		options: {
			 browsers: ['last 3 version', 'ie >= 8']
		},
		global: {
			src: 'public/css/*.css'
		},
	},

	csso: {
		dynamic_mappings: {
			expand: true,
			cwd: 'public/css/',
			src: ['*.css', '!*.min.css'],
			dest: 'public/css/',
			ext: '.min.css'
		}
	},

	includereplace: {
		dist: {
			files: [
				{src: '*.html', dest: 'static/', expand: true, cwd: 'static/src/'},
			]
		}
	},
	clean: {
		html: ["static/_*.html"],
	},


	watch: {
		scss: {
			files: 'static/src/sass/**/*.scss',
			tasks: ['compass', 'autoprefixer'],
		},
		html: {
			files: 'static/src/*.html',
			tasks: ['includereplace', 'clean:html'],
		},
	},
});
	grunt.loadNpmTasks('grunt-contrib-compass');
	grunt.loadNpmTasks('grunt-autoprefixer');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-csso');
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-include-replace');

	grunt.registerTask( 'default', ['watch']);
	grunt.registerTask( 'release', ['compass', 'autoprefixer', 'csso', 'includereplace', 'clean:html']);

};