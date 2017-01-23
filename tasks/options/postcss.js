module.exports = {
	dist: {
		options: {
			processors: [
				require('autoprefixer')({browsers: 'last 2 versions'})
			]
		},
		files: {
			'assets/css/woocommerce-custom-product-redirects.css': [ 'assets/css/src/woocommerce-custom-product-redirects.css' ]
		}
	}
};
