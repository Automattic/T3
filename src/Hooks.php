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
	 * The Tumblr3 active status.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     bool
	 */
	private $is_tumblr3_active;

	/**
	 * Initializes the Hooks.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param boolean $is_tumblr3_active The Tumblr3 active status.
	 *
	 * @return  void
	 */
	public function initialize( $is_tumblr3_active ): void {
		$this->is_tumblr3_active = $is_tumblr3_active;

		// Add actions to the checkbox option that turns on the Tumblr theme.
		add_action( 'update_option_tumblr3_use_theme', array( $this, 'update_option_tumblr3_use_theme' ), 10, 2 );

		// Filter the theme root to use the Tumblr theme directory, if the option is set.
		add_filter( 'theme_root', array( $this, 'theme_root' ), 10, 2 );
		add_filter( 'stylesheet_root', array( $this, 'theme_root' ), 10, 2 );
		add_filter( 'template_root', array( $this, 'theme_root' ), 10, 2 );

		// Flush permalink rules when switching to the Tumblr theme.
		add_action( 'switch_theme', array( $this, 'switch_theme' ), 10, 3 );

		// Only run these if the Tumblr3 theme is active.
		if ( $this->is_tumblr3_active ) {
			add_filter( 'validate_current_theme', '__return_false' );
			add_filter( 'wp_prepare_themes_for_js', array( $this, 'prepare_themes_for_js' ) );
		}
	}

	/**
	 * Modify the themes JavaScript object to include the Tumblr3 theme data.
	 *
	 * @param array $themes Array of installed themedata.
	 *
	 * @return array
	 */
	public function prepare_themes_for_js( $themes ): array {
		if ( isset( $themes['tumblr3'] ) ) {
			$themes['tumblr3']['screenshot'][0] = get_option( 'tumblr3_external_theme_thumbnail' );
			$themes['tumblr3']['author']        = get_option( 'tumblr3_external_theme_author' );
			$themes['tumblr3']['name']          = get_option( 'tumblr3_external_theme_title' );
			$themes['tumblr3']['authorAndUri']  = $themes['tumblr3']['author'];
		}

		return $themes;
	}

	/**
	 * Switches theme roots to support the tumblr3 theme in this plugin.
	 *
	 * @param string $root Current WP theme root.
	 *
	 * @return string
	 */
	public function theme_root( $root ): string {
		global $pagenow;
		static $registered = null;

		// If the user is switching themes, return the default theme directory.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- $_GET value is never consumed.
		if ( current_user_can( 'switch_themes' ) && 'themes.php' === $pagenow && isset( $_GET['action'] ) && 'activate' === $_GET['action'] ) {
			return $root;
		}

		// If Tumblr3 is the active theme, return the Tumblr theme directory.
		if ( $this->is_tumblr3_active ) {
			// Register the theme directory if it hasn't been registered yet.
			if ( null === $registered || false === $registered ) {
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
	 * Flush rewrite rules when switching to the Tumblr theme.
	 *
	 * @param string $new_name  The new theme name.
	 * @param object $new_theme The new theme object.
	 * @param object $old_theme The old theme object.
	 *
	 * @return void
	 */
	public function switch_theme( $new_name, $new_theme, $old_theme ): void {
		if ( 'tumblr3' === $new_theme->stylesheet ) {
			flush_rewrite_rules();
		}

		if ( 'tumblr3' === $old_theme->stylesheet ) {
			update_option( 'tumblr3_original_theme', '' );
			update_option( 'tumblr3_use_theme', '' );
		}
	}
}
