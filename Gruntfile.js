module.exports = function (grunt) {
  grunt.initConfig({
    // Watch task config
    watch: {
        styles: {
            files: "scss/*.scss",
            tasks: ['sass', 'postcss'],
        },
        javascript: {
            files: ["js/*.js", "!js/*.min.js"],
            tasks: ['uglify'],
        },
    },
    sass: {
        dist: {
            options: {
                style: 'compressed'
            },
            files: {
                "css/backend.min.css" : "scss/backend.scss",
                "css/chosen.min.css" : "scss/chosen.scss",
                "css/main.min.css" : "scss/main.scss",
            }
        }
    },
    postcss: {
        options: {
            map: {
                inline: false,
                annotation: 'css/',
            },

            processors: [
                require('pixrem')(), // add fallbacks for rem units
                require('autoprefixer')({browsers: 'last 2 versions'}), // add vendor prefixes
                require('cssnano')() // minify the result
            ]
        },
        dist: {
            src: 'css/main.min.css',
        }
    },
    uglify: {
        custom: {
            files: {
                'js/chosen.jquery.min.js': ['js/chosen.jquery.js'],
                'js/csr.min.js': ['js/csr.js'],
                'js/grad-offering.min.js': ['js/grad-offering.js'],
                'js/theme.min.js': ['js/theme.js'],
                'js/video-res.min.js': ['js/video-res.js'],
            },
        },
    },
    browserSync: {
        dev: {
            bsFiles: {
                src : ['**/*.css', '**/*.php', '**/*.js', '!node_modules'],
            },
            options: {
                watchTask: true,
                proxy: "https://dev.abc.dev",
                https: {
                    key: "/Users/andrew/github/dotfiles/local-dev.key",
                    cert: "/Users/andrew/github/dotfiles/local-dev.crt",
                }
            },
        },
    },
  });

    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-postcss');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-browser-sync');
    grunt.registerTask('default', [
        'browserSync',
        'watch',
    ]);
};
