module.exports = {
	main: {
		options: {
			mode: 'zip',
			archive: './release/wooproduct_redirects.<%= pkg.version %>.zip'
		},
		expand: true,
		cwd: 'release/<%= pkg.version %>/',
		src: ['**/*'],
		dest: 'wooproduct_redirects/'
	}
};
