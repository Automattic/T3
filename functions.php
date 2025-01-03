<?php
/**
 * TumblrThemeGarden theme functions.
 *
 * @package TumblrThemeGarden
 */

defined( 'ABSPATH' ) || exit;

use CupcakeLabs\TumblrThemeGarden\CLTTG_Plugin;

/**
 * Returns the plugin's main class instance.
 *
 * @return  CLTTG_Plugin
 *@version 1.0.0
 *
 * @since   1.0.0
 */
function ttgarden_get_plugin_instance(): CLTTG_Plugin
{
	return CLTTG_Plugin::get_instance();
}

/**
 * Returns the plugin's slug.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  string
 */
function ttgarden_get_plugin_slug(): string {
	return sanitize_key( TTGARDEN_METADATA['TextDomain'] );
}

/**
 * Returns an array with meta information for a given asset path. First, it checks for an .asset.php file in the same directory
 * as the given asset file whose contents are returns if it exists. If not, it returns an array with the file's last modified
 * time as the version and the main stylesheet + any extra dependencies passed in as the dependencies.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @param   string     $asset_path         The path to the asset file.
 * @param   array|null $extra_dependencies Any extra dependencies to include in the returned meta.
 *
 * @return  array|null
 */
function ttgarden_get_asset_meta( string $asset_path, ?array $extra_dependencies = null ): ?array {
	if ( ! file_exists( $asset_path ) || ! str_starts_with( $asset_path, TTGARDEN_PATH ) ) {
		return null;
	}

	$asset_path_info = pathinfo( $asset_path );
	if ( file_exists( $asset_path_info['dirname'] . '/' . $asset_path_info['filename'] . '.php' ) ) {
		$asset_meta  = require $asset_path_info['dirname'] . '/' . $asset_path_info['filename'] . '.php';
		$asset_meta += array( 'dependencies' => array() ); // Ensure 'dependencies' key exists.
	} else {
		$asset_meta = array(
			'dependencies' => array(),
			'version'      => filemtime( $asset_path ),
		);
		if ( false === $asset_meta['version'] ) { // Safeguard against filemtime() returning false.
			$asset_meta['version'] = TTGARDEN_METADATA['Version'];
		}
	}

	if ( is_array( $extra_dependencies ) ) {
		$asset_meta['dependencies'] = array_merge( $asset_meta['dependencies'], $extra_dependencies );
		$asset_meta['dependencies'] = array_unique( $asset_meta['dependencies'] );
	}

	return $asset_meta;
}

/**
 * We need a custom do_shortcode implementation because do_shortcodes_in_html_tags()
 * is run before running reguular shortcodes, which means that things like link hrefs
 * get populated before they even have context.
 *
 * @param string $content The content to parse.
 *
 * @return string The parsed content.
 */
function ttgarden_do_shortcode( $content ): string {
	global $shortcode_tags;
	static $pattern = null;

	// Avoid generating this multiple times.
	if ( null === $pattern ) {
		$pattern = get_shortcode_regex( array_keys( $shortcode_tags ) );
	}

	$content = preg_replace_callback( "/$pattern/", 'do_shortcode_tag', $content );

	// Always restore square braces so we don't break things like <!--[if IE ]>.
	$content = unescape_invalid_shortcodes( $content );

	return $content;
}

/**
 * Gets the current parse context.
 * Used for informing data tags of their context.
 * Also used for storing data to pass between tags.
 *
 * @return array|null|string The current parse context.
 */
function ttgarden_get_parse_context() {
	global $ttgarden_parse_context;
	return $ttgarden_parse_context;
}

/**
 * Sets the global parse context.
 *
 * @param string $key   The key to set.
 * @param mixed  $value The value to set.
 *
 * @return void
 */
function ttgarden_set_parse_context( $key, $value ): void {
	global $ttgarden_parse_context;
	$ttgarden_parse_context = array( $key => $value );
}

/**
 * Normalizes a theme option name.
 *
 * @param string $name The name to normalize.
 *
 * @return string The normalized name.
 */
function ttgarden_normalize_option_name( $name ): string {
	return strtolower(
		str_replace(
			array_merge(
				array( ' ' ),
				TTGARDEN_OPTIONS
			),
			'',
			$name
		)
	);
}

/**
 * Gets the Tumblr Theme Garden regex.
 *
 * @return string The Tumblr Theme Garden regex.
 */
function ttgarden_get_tumblr_regex(): string {
	return '/\{([a-zA-Z0-9][a-zA-Z0-9\\-\/=" ]*|font\:[a-zA-Z0-9 ]+|text\:[a-zA-Z0-9 ]+|select\:[a-zA-Z0-9 ]+|image\:[a-zA-Z0-9 ]+|color\:[a-zA-Z0-9 ]+|RGBcolor\:[a-zA-Z0-9 ]+|lang\:[a-zA-Z0-9- ]+|[\/]?block\:[a-zA-Z0-9]+( [a-zA-Z0-9=" ]+)*)\}/i';
}

// Include tag and block hydration functions for each Tumblr Theme tag|block.
require TTGARDEN_PATH . 'includes/block-functions.php';
require TTGARDEN_PATH . 'includes/tag-functions.php';
