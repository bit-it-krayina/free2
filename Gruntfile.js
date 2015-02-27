'use strict';
module.exports = function(grunt) {

grunt.initConfig({
	sass: {
		dist: {
			files: {
				'public/css/screen.css' : 'public/static/src/sass/screen.scss'
			},
			options: {
				// style: 'expanded',
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

	csscomb: {
		options: {
            config: 'public/static/src/sass/scss-config.json'
        },
        dynamic_mappings: {
            expand: true,
            cwd: 'public/static/src/sass/screen/',
            src: ['*.scss'],
            dest: 'public/static/src/sass/screen/',
            //ext: '*.scss'
        }
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
				{src: '*.html', dest: 'public/static/', expand: true, cwd: 'public/static/src/'},
			]
		}
	},

	clean: {
		html: ["public/static/_*.html"],
	},


	watch: {
		scss: {
			files: 'public/static/src/sass/**/*.scss',
			tasks: ['sass', 'csscomb', 'autoprefixer'],
		},
		html: {
			files: 'public/static/src/*.html',
			tasks: ['includereplace', 'clean:html'],
		},
	},
});
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-csscomb');
	grunt.loadNpmTasks('grunt-autoprefixer');
	grunt.loadNpmTasks('grunt-csso');
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-include-replace');
	grunt.loadNpmTasks('grunt-contrib-watch');

	grunt.registerTask( 'default', ['watch']);
	grunt.registerTask( 'release', ['sass', 'autoprefixer', 'csso', 'includereplace', 'clean:html']);

 };