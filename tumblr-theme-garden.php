<?php
/**
 * The Tumblr Theme Garden bootstrap file.
 *
 * @since       1.0.0
 * @version     1.0.0
 * @author      Cupcake Labs
 * @license     GPL-2.0-or-later
 * @package    TumblrThemeGarden
 *
 * @noinspection    ALL
 *
 * @wordpress-plugin
 * Plugin Name:             Tumblr Theme Garden
 * Plugin URI:              https://github.com/Automattic/tumblr-theme-garden/
 * Description:             Allows WordPress to run on Tumblr themes.
 * Version:                 0.1.15
 * Requires at least:       6.5
 * Tested up to:            6.7
 * Requires PHP:            8.2
 * Author:                  Cupcake Labs
 * Author URI:              https://www.automattic.com/
 * License:                 GPLv2 or later
 * License URI:             https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:             tumblr-theme-garden
 * Domain Path:             /languages
 **/

// The prefix CLTTG stands for Cupcake Labs Tumblr Theme Garden

defined( 'ABSPATH' ) || exit;

// Define plugin constants.
function_exists( 'get_plugin_data' ) || require_once ABSPATH . 'wp-admin/includes/plugin.php';
define( 'CLTTG_METADATA', get_plugin_data( __FILE__, false, false ) );

define( 'CLTTG_BASENAME', plugin_basename( __FILE__ ) );
define( 'CLTTG_PATH', plugin_dir_path( __FILE__ ) );
define( 'CLTTG_URL', plugin_dir_url( __FILE__ ) );

// Define REST API namespace.
define( 'CLTTG_REST_NAMESPACE', 'tumblr-theme-garden/v1' );

// Define tag and block names from Tumblr Theme language.
define( 'CLTTG_TAGS', require_once CLTTG_PATH . 'includes/tumblr-theme-language/tags.php' );
define( 'CLTTG_BLOCKS', require_once CLTTG_PATH . 'includes/tumblr-theme-language/blocks.php' );
define( 'CLTTG_OPTIONS', require_once CLTTG_PATH . 'includes/tumblr-theme-language/options.php' );
define( 'CLTTG_MODIFIERS', require_once CLTTG_PATH . 'includes/tumblr-theme-language/modifiers.php' );
define( 'CLTTG_MISSING_BLOCKS', require_once CLTTG_PATH . 'includes/tumblr-theme-language/missing-blocks.php' );
define( 'CLTTG_MISSING_TAGS', require_once CLTTG_PATH . 'includes/tumblr-theme-language/missing-tags.php' );

$lang = require_once CLTTG_PATH . 'includes/tumblr-theme-language/lang.php';
define( 'CLTTG_LANG', array_change_key_case( $lang, CASE_LOWER ) );

// Load plugin translations so they are available even for the error admin notices.
add_action(
	'init',
	static function () {
		load_plugin_textdomain(
			CLTTG_METADATA['TextDomain'],
			false,
			dirname( CLTTG_BASENAME ) . CLTTG_METADATA['DomainPath']
		);
	}
);

// Load the autoloader.
if ( ! is_file( CLTTG_PATH . '/vendor/autoload.php' ) ) {
	add_action(
		'admin_notices',
		static function () {
			$message      = __( 'It seems like <strong>Tumblr Theme Garden</strong> failed to autoload. Run composer i.', 'tumblr-theme-garden' );
			$html_message = wp_sprintf( '<div class="error notice ttgarden-error">%s</div>', wpautop( $message ) );
			echo wp_kses_post( $html_message );
		}
	);
	return;
}
require_once CLTTG_PATH . '/vendor/autoload.php';

/**
 * On activation, setup the plugin options.
 */
register_activation_hook(
	__FILE__,
	static function () {
		update_option( 'ttgarden_original_theme', '' );
		update_option( 'ttgarden_theme_html', '' );
		update_option( 'ttgarden_use_theme', '0' );
		update_option( 'ttgarden_external_theme', array() );
	}
);

/**
 * On deactivation, switch back to the orignial saved theme and delete the option.
 */
register_deactivation_hook(
	__FILE__,
	static function () {
		// Switch back to the original theme if one was saved.
		$theme = get_option( 'ttgarden_original_theme' );
		if ( $theme ) {
			switch_theme( $theme );
		}

		// Cleanup options.
		delete_option( 'ttgarden_original_theme' );
		delete_option( 'ttgarden_theme_html' );
		delete_option( 'ttgarden_use_theme' );
		delete_option( 'ttgarden_external_theme' );
	}
);

require_once CLTTG_PATH . 'functions.php';
add_action( 'plugins_loaded', array( ttgarden_get_plugin_instance(), 'initialize' ) );
