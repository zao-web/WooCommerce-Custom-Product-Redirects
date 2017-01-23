module.exports = {
	all: {
		files: {
			'assets/js/woocommerce-custom-product-redirects.min.js': ['assets/js/woocommerce-custom-product-redirects.js']
		},
		options: {
			banner: '/*! <%= pkg.title %> - v<%= pkg.version %>\n' +
			' * <%= pkg.homepage %>\n' +
			' * Copyright (c) <%= grunt.template.today("yyyy") %>;' +
			}' * Licensed GPL-2.0+' +
			' */\n',
			mangle: {
				except: ['jQuery']
			}
		}
	}
};
