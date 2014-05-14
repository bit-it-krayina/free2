'use strict';
module.exports = function(grunt) {

grunt.initConfig({
	compass: {
		dist: {
			options: {
				config: 'public/config.rb',
				sassDir: 'public/sass',
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

	watch: {
		scss: {
			files: 'public/sass/**/*.scss',
			tasks: ['compass', 'autoprefixer'],
		},
	},
});
	grunt.loadNpmTasks('grunt-contrib-compass');
	grunt.loadNpmTasks('grunt-autoprefixer');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-csso');

	grunt.registerTask( 'default', ['watch']);
	grunt.registerTask( 'release', ['compass', 'autoprefixer', 'csso']);

};