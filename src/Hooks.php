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
}
