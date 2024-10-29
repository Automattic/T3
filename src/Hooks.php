<?php

namespace CupcakeLabs\T3;

defined( 'ABSPATH' ) || exit;

/**
 * Logical node for all integration functionalities.
 *
 * @since   1.0.0
 * @version 1.0.0
 */
final class Hooks {

	/**
	 * Initializes the Hooks.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  void
	 */
	public function initialize(): void {
		// Enqueue Block Editor Assets.
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );

		// Disable the default post format UI in favor of our custom one.
		add_filter( 'block_editor_settings_all', array( $this, 'block_editor_settings_all' ) );

		// Add actions to the checkbox option that turns on the Tumblr theme.
		add_action( 'update_option_tumblr3_use_theme', array( $this, 'update_option_tumblr3_use_theme' ), 10, 2 );

		// Filter the theme root to use the Tumblr theme directory, if the option is set.
		add_filter( 'theme_root', array( $this, 'theme_root' ), 10, 2 );
		add_filter( 'stylesheet_root', array( $this, 'theme_root' ), 10, 2 );
		add_filter( 'template_root', array( $this, 'theme_root' ), 10, 2 );

		add_action( 'switch_theme', array( $this, 'switch_theme' ), 10, 3 );
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $root
	 * @return string
	 */
	public function theme_root( $root ): string {
		static $registered = null;

		// If the option is set to use a Tumblr theme, return the Tumblr theme directory.
		if ( get_option( 'tumblr3_use_theme' ) ) {
			// Register the theme directory if it hasn't been registered yet.
			if ( null === $registered ) {
				$registered = register_theme_directory( TUMBLR3_PATH . 'theme' );
			}

			return TUMBLR3_PATH . 'theme';
		}

		return $root;
	}

	/**
	 * Enqueues the block editor assets.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  void
	 */
	public function enqueue_block_editor_assets(): void {
		$deps = tumblr3_get_asset_meta( plugin_dir_path( __DIR__ ) . 'assets/js/build/editor.asset.php' );

		wp_enqueue_script(
			'cupcakelabs-t3',
			TUMBLR3_URL . 'assets/js/build/editor.js',
			$deps['dependencies'],
			$deps['version'],
			true
		);

		wp_enqueue_style(
			'cupcakelabs-t3',
			TUMBLR3_URL . 'assets/js/build/editor.css',
			array(),
			$deps['version']
		);
	}

	/**
	 * Filters the block editor settings.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 * @see https://developer.wordpress.org/block-editor/reference-guides/filters/editor-filters/
	 *
	 * @param   array $settings The block editor settings.
	 *
	 * @return  array
	 */
	public function block_editor_settings_all( array $settings ): array {
		$settings['disablePostFormats'] = true;
		return $settings;
	}

	/**
	 * Capture updates to the Tumblr theme HTML option. Set or unset themes based on the option value.
	 *
	 * @param mixed  $old_value The old option value.
	 * @param mixed  $value     The new option value.
	 *
	 * @return void
	 */
	public function update_option_tumblr3_use_theme( $old_value, $value ): void {
		if ( '1' === $value ) {
			update_option( 'tumblr3_original_theme', get_option( 'template' ) );
			switch_theme( 'tumblr3' );
		} else {
			switch_theme( get_option( 'tumblr3_original_theme' ) );
		}
	}

	/**
	 * @todo This isn't working currently, switching to another theme doesn't work smoothly.
	 */
	public function switch_theme( $new_name, $new_theme, $old_theme ): void {
		if ( 'tumblr3' === $old_theme->stylesheet ) {
			update_option( 'tumblr3_original_theme', '' );
			update_option( 'tumblr3_use_theme', '' );
		}

		if ( 'tumblr3' === $new_name ) {
			update_option( 'tumblr3_use_theme', '1' );
		}
	}
}
