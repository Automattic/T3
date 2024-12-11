<?php
/**
 * The Tumblr Theme Translator bootstrap file.
 *
 * @since       1.0.0
 * @version     1.0.0
 * @author      Cupcake Labs
 * @license     GPL-3.0-or-later
 * @package    Tumblr3
 *
 * @noinspection    ALL
 *
 * @wordpress-plugin
 * Plugin Name:             Tumblr Theme Translator
 * Plugin URI:              https://github.com/Automattic/T3/
 * Description:             Allows WordPress to run on Tumblr themes.
 * Version:                 0.1.12
 * Requires at least:       6.5
 * Tested up to:            6.5
 * Requires PHP:            8.2
 * Author:                  Cupcake Labs
 * Author URI:              https://www.automattic.com/
 * License:                 GPL v3 or later
 * License URI:             https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:             tumblr3
 * Domain Path:             /languages
 **/

defined( 'ABSPATH' ) || exit;

// Define plugin constants.
function_exists( 'get_plugin_data' ) || require_once ABSPATH . 'wp-admin/includes/plugin.php';
define( 'TUMBLR3_METADATA', get_plugin_data( __FILE__, false, false ) );

define( 'TUMBLR3_BASENAME', plugin_basename( __FILE__ ) );
define( 'TUMBLR3_PATH', plugin_dir_path( __FILE__ ) );
define( 'TUMBLR3_URL', plugin_dir_url( __FILE__ ) );

// Define tag and block names from Tumblr Theme language.
define( 'TUMBLR3_TAGS', require_once TUMBLR3_PATH . 'includes/tumblr-theme-language/tags.php' );
define( 'TUMBLR3_BLOCKS', require_once TUMBLR3_PATH . 'includes/tumblr-theme-language/blocks.php' );
define( 'TUMBLR3_OPTIONS', require_once TUMBLR3_PATH . 'includes/tumblr-theme-language/options.php' );
define( 'TUMBLR3_MODIFIERS', require_once TUMBLR3_PATH . 'includes/tumblr-theme-language/modifiers.php' );
define( 'TUMBLR3_MISSING_BLOCKS', require_once TUMBLR3_PATH . 'includes/tumblr-theme-language/missing-blocks.php' );
define( 'TUMBLR3_MISSING_TAGS', require_once TUMBLR3_PATH . 'includes/tumblr-theme-language/missing-tags.php' );

$lang = require_once TUMBLR3_PATH . 'includes/tumblr-theme-language/lang.php';
define( 'TUMBLR3_LANG', array_change_key_case( $lang, CASE_LOWER ) );

// Load plugin translations so they are available even for the error admin notices.
add_action(
	'init',
	static function () {
		load_plugin_textdomain(
			TUMBLR3_METADATA['TextDomain'],
			false,
			dirname( TUMBLR3_BASENAME ) . TUMBLR3_METADATA['DomainPath']
		);
	}
);

// Load the autoloader.
if ( ! is_file( TUMBLR3_PATH . '/vendor/autoload.php' ) ) {
	add_action(
		'admin_notices',
		static function () {
			$message      = __( 'It seems like <strong>Tumblr Theme Translator</strong> failed to autoload. Run composer i.', 'tumblr3' );
			$html_message = wp_sprintf( '<div class="error notice tumblr3-error">%s</div>', wpautop( $message ) );
			echo wp_kses_post( $html_message );
		}
	);
	return;
}
require_once TUMBLR3_PATH . '/vendor/autoload.php';

/**
 * On activation, setup the plugin options.
 */
register_activation_hook(
	__FILE__,
	static function () {
		update_option( 'tumblr3_original_theme', '' );
		update_option( 'tumblr3_theme_html', '' );
		update_option( 'tumblr3_use_theme', '0' );
		update_option( 'tumblr3_external_theme', array() );
	}
);

/**
 * On deactivation, switch back to the orignial saved theme and delete the option.
 */
register_deactivation_hook(
	__FILE__,
	static function () {
		// Switch back to the original theme if one was saved.
		$theme = get_option( 'tumblr3_original_theme' );
		if ( $theme ) {
			switch_theme( $theme );
		}

		// Cleanup options.
		delete_option( 'tumblr3_original_theme' );
		delete_option( 'tumblr3_theme_html' );
		delete_option( 'tumblr3_use_theme' );
		delete_option( 'tumblr3_external_theme' );
	}
);

require_once TUMBLR3_PATH . 'functions.php';
add_action( 'plugins_loaded', array( tumblr3_get_plugin_instance(), 'initialize' ) );
