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
	public bool $is_tumblr3_active = false;

	/**
	 * Initializes the Hooks.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  void
	 */
	public function initialize(): void {
		// Add actions to the checkbox option that turns on the Tumblr theme.
		add_action( 'update_option_tumblr3_use_theme', array( $this, 'update_option_tumblr3_use_theme' ), 10, 2 );

		// Filter the theme root to use the Tumblr theme directory, if the option is set.
		add_filter( 'theme_root', array( $this, 'theme_root' ), 10, 2 );
		add_filter( 'stylesheet_root', array( $this, 'theme_root' ), 10, 2 );
		add_filter( 'template_root', array( $this, 'theme_root' ), 10, 2 );

		// Flush permalink rules when switching to the Tumblr theme.
		add_action( 'switch_theme', array( $this, 'switch_theme' ), 10, 3 );

		$this->is_tumblr3_active = 'tumblr3' === get_option( 'template' );

		if ( $this->is_tumblr3_active ) {
			add_filter( 'validate_current_theme', '__return_false' );
		}
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $root
	 *
	 * @return string
	 */
	public function theme_root( $root ): string {
		static $registered = null;

		// If Tumblr3 is the active theme, return the Tumblr theme directory.
		if ( $this->is_tumblr3_active ) {
			// Register the theme directory if it hasn't been registered yet.
			if ( null === $registered ) {
				$registered = register_theme_directory( TUMBLR3_PATH . 'theme' );
			}

			return TUMBLR3_PATH . 'theme';
		}

		return $root;
	}

	/**
	 * Capture updates to the Tumblr theme HTML option. Set or unset themes based on the option value.
	 *
	 * @param mixed $old_value The old option value.
	 * @param mixed $value     The new option value.
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
		if ( 'tumblr3' === $new_theme->stylesheet ) {
			flush_rewrite_rules();
		}

		if ( 'tumblr3' === $old_theme->stylesheet ) {
			update_option( 'tumblr3_original_theme', '' );
			update_option( 'tumblr3_use_theme', '' );
		}

		if ( 'tumblr3' === $new_theme->stylesheet ) {
			update_option( 'tumblr3_use_theme', '1' );
		}
	}
}
