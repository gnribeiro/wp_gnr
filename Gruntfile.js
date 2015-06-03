module.exports = function(grunt){
    "use strict";
    require("matchdep").filterDev("grunt-*").forEach(grunt.loadNpmTasks);


    grunt.initConfig({

        
        files_js: {
            all:[
                //'bower_components/bootstrap/dist/js/bootstrap.min.js',
                //'bower_components/jquery/jquery.min.js',
                'assets/js/index.js'
            ]
        },

        files_css: {
            all:[
                //'bower_components/teste.css',
                'assets/css/index.css'
            ]
        },

        pkg: grunt.file.readJSON('package.json'),

         uglify: {
            dev: {
                options: {
                   beautify: true
                },
                files: {
                    'assets/js/index.min.js': [
                        '<%= files_js.all %>'
                    ]
                }
            },

            prod: {
                options: {
                 
                },
                files: {
                    'assets/js/index.min.js': [
                        '<%= files_js.all %>'
                    ]
                }
            }
        },

         less: {
          dev: {
            options: {
              paths: ["assets/css"],
              compress: false,
              cleancss: true
            },
            files: {
              "assets/css/index.css": "assets/less/index.less"
            }
          },

          prod: {
            options: {
              paths: ["assets/css"],
              compress: true,
              cleancss: true,
            },
            files: {
             "assets/css/index.css": "assets/less/index.less"
            }
          }
        },

        cssmin: {
            options: {
                shorthandCompacting: false,
                roundingPrecision: -1,
            },
            target: {
                files: {
                  'assets/css/index-min.css': [
                     '<%= files_css.all %>'
                ]
              }
            }
        },

        notify: {
            less: {
                options: {
                    title: 'Grunt, grunt!',
                    message: 'less is all gravy'
                }
            },
            js: {
                options: {
                    title: 'Grunt, grunt!',
                    message: 'JS is all good'
                }
            }
        },

     

         watch: {
          
            js: {
              files: [
                    '<%= files_js.all %>'
                ],
                tasks: [
                  'uglify:dev',
                  'notify:js'
                ]
            },

            less: {
                files: ['assets/less/*.less'],
                tasks: [
                  'less:dev',
                  'notify:less'
                ]
            }
        },
    });

    grunt.registerTask('default', [
    'dev'
    ]);
    
    grunt.registerTask('dev', [
        'less:dev',
        'uglify:dev',
        'cssmin', 
        'notify'
    ]);
    
    grunt.registerTask('prod', [
        'less:prod',                        
        'uglify:prod', 
        'cssmin', 
        'notify'
    ]);
}