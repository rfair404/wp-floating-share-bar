module.exports = function( grunt ) {

	'use strict';
	var banner = '/**\n * <%= pkg.homepage %>\n * Copyright (c) <%= grunt.template.today("yyyy") %>\n * This file is generated automatically. Do not edit.\n */\n';
	// Project configuration
	grunt.initConfig( {

		pkg: grunt.file.readJSON( 'package.json' ),

		addtextdomain: {
			options: {
				textdomain: 'russell-fair',
			},
			target: {
				files: {
					src: [ '*.php', '**/*.php', '!node_modules/**', '!php-tests/**', '!bin/**' ]
				}
			}
		},

		wp_readme_to_markdown: {
			your_target: {
				files: {
					'README.md': 'readme.txt'
				}
			},
		},
		uglify: {
    		my_target: {
    			files: {
        			'assets/scripts/display.min.js': ['assets/scripts/display.js']
    			}
    		}
		},
  
		concat: {
    		dist: {
    			src: ['assets/scripts/float.js', 'assets/scripts/colors.js'],
    			dest: 'assets/scripts/display.js'
    		}
		},
		
		jshint: {
    		beforeconcat: ['assets/scripts/float.js', 'assets/scripts/misc.js'],
    		afterconcat: ['assets/scripts/display.js']
		},

		sass: {                              // Task 
    		dist: {                            // Target 
    			files: {                         // Dictionary of files 
        			'assets/css/main.css': 'assets/scss/main.scss'       // 'destination': 'source' 
    			}
    		}
		},
		
		cssmin: {
			target: {
    			files: [{
			      expand: true,
			      cwd: 'assets/css',
			      src: ['*.css', '!*.min.css'],
    			  dest: 'assets/css',
			      ext: '.min.css'
			    }]
			}
		},
		
		makepot: {
			target: {
				options: {
					domainPath: '/languages',
					mainFile: 'russell-fair.php',
					potFilename: 'russell-fair.pot',
					potHeaders: {
						poedit: true,
						'x-poedit-keywordslist': true
					},
					type: 'wp-plugin',
					updateTimestamp: true
				}
			}
		},
	} );

	grunt.loadNpmTasks( 'grunt-wp-i18n' );
	grunt.loadNpmTasks( 'grunt-wp-readme-to-markdown' );
	grunt.loadNpmTasks(	'grunt-contrib-sass' );
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-contrib-concat' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );

	grunt.registerTask( 'i18n', ['addtextdomain', 'makepot'] );
	grunt.registerTask( 'readme', ['wp_readme_to_markdown'] );
	grunt.registerTask( 'default', ['sass', 'cssmin', 'concat', 'jshint', 'uglify']);
	grunt.util.linefeed = '\n';

};
