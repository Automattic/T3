<?php

defined( 'ABSPATH' ) || exit;

use CupcakeLabs\T3\Plugin;

/**
 * Returns the plugin's main class instance.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  Plugin
 */
function tumblr3_get_plugin_instance(): Plugin {
	return Plugin::get_instance();
}

/**
 * Returns the plugin's slug.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  string
 */
function tumblr3_get_plugin_slug(): string {
	return sanitize_key( TUMBLR3_METADATA['TextDomain'] );
}

/**
 * We need a custom do_shortcode implementation because do_shortcodes_in_html_tags()
 * is run before running reguular shortcodes, which means that things like link hrefs
 * get populated before they even have context.
 *
 * @todo nested tags of the same type aren't rendering properly.
 *
 * @param string $content The content to parse.
 *
 * @return string The parsed content.
 */
function tumblr3_do_shortcode( $content ): string {
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
function tumblr3_get_parse_context() {
	global $tumblr3_parse_context;
	return $tumblr3_parse_context;
}

/**
 * Sets the global parse context.
 *
 * @param string $key   The key to set.
 * @param array  $value The value to set.
 *
 * @return void
 */
function tumblr3_set_parse_context( $key, $value ): void {
	global $tumblr3_parse_context;
	$tumblr3_parse_context = array( $key => $value );
}

/**
 * Normalizes a theme option name.
 *
 * @param string $name The name to normalize.
 *
 * @return string The normalized name.
 */
function tumblr3_normalize_option_name( $name ): string {
	return strtolower(
		str_replace(
			array( ' ', ':' ),
			array( '', '_' ),
			$name
		)
	);
}

// Enqueue the plugin's assets.
require TUMBLR3_PATH . 'includes/assets.php';

// Include tag and block hydration functions.
require TUMBLR3_PATH . 'includes/block-functions.php';
require TUMBLR3_PATH . 'includes/tag-functions.php';
