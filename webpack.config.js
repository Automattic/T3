const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

module.exports = {
	...defaultConfig,
	entry: {
		'theme-garden': './assets/js/src/theme-garden.js',
		'editor': './assets/js/src/editor.js',
		'theme-install': './assets/js/src/theme-install.js'
	},
	output: {
		filename: '[name].js',
		path: __dirname + '/assets/js/build',
	},
};
