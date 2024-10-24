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
 * Gets the current parse context.
 * Used for informing data tags of their context.
 * Also used for storing data to pass between tags.
 *
 * @return array|null|string The current parse context.
 */
function tumblr3_get_parse_context() {
	$plugin = tumblr3_get_plugin_instance();
	return $plugin->parser->get_parse_context();
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
	$plugin = tumblr3_get_plugin_instance();
	$plugin->parser->set_parse_context( $key, $value );
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
			array( ' ', ':', '{', '}' ),
			array( '', '_', '', '' ),
			$name
		)
	);
}

/**
 * Removes the core 'Menus' panel from the Customizer.
 * This has to happen early, before the Customizer is initialized.
 *
 * @param array $components Core Customizer components list.
 *
 * @return array (Maybe) modified components list.
 */
function tumblr3_remove_nav_menus_panel( $components ) {
	$i = array_search( 'nav_menus', $components, true );
	if ( false !== $i ) {
		unset( $components[ $i ] );
	}
	return $components;
}
add_filter( 'customize_loaded_components', 'tumblr3_remove_nav_menus_panel' );

// Enqueue the plugin's assets.
require TUMBLR3_PATH . 'includes/assets.php';
