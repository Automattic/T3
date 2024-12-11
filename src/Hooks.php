<?php
/**
 * TumblrThemeGarden theme hooks.
 *
 * @package TumblrThemeGarden
 */

namespace CupcakeLabs\TumblrThemeGarden;

defined( 'ABSPATH' ) || exit;

/**
 * Logical node for all integration functionalities.
 *
 * @since   1.0.0
 * @version 1.0.0
 */
class Hooks {
	/**
	 * The TumblrThemeGarden active status.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     bool
	 */
	private $is_ttgarden_active;

	/**
	 * Initializes the Hooks.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param boolean $is_ttgarden_active The TumblrThemeGarden active status.
	 *
	 * @return  void
	 */
	public function initialize( $is_ttgarden_active ): void {
		$this->is_ttgarden_active = $is_ttgarden_active;

		// Add actions to the checkbox option that turns on the Tumblr theme.
		add_action( 'update_option_ttgarden_use_theme', array( $this, 'update_option_ttgarden_use_theme' ), 10, 2 );

		// Filter the theme root to use the Tumblr theme directory, if the option is set.
		add_filter( 'theme_root', array( $this, 'theme_root' ), 10, 2 );
		add_filter( 'stylesheet_root', array( $this, 'theme_root' ), 10, 2 );
		add_filter( 'template_root', array( $this, 'theme_root' ), 10, 2 );

		// Flush permalink rules when switching to the Tumblr theme.
		add_action( 'switch_theme', array( $this, 'switch_theme' ), 10, 3 );

		// Only run these if the TumblrThemeGarden theme is active.
		if ( $this->is_ttgarden_active ) {
			add_filter( 'validate_current_theme', '__return_false' );
			add_filter( 'wp_prepare_themes_for_js', array( $this, 'prepare_themes_for_js' ) );
		}
	}

	/**
	 * Modify the themes JavaScript object to include the TumblrThemeGarden theme data.
	 *
	 * @param array $themes Array of installed themedata.
	 *
	 * @return array
	 */
	public function prepare_themes_for_js( $themes ): array {
		if ( isset( $themes['ttgarden'] ) ) {
			$theme_details = get_option( 'ttgarden_external_theme' );

			if ( isset( $theme_details['thumbnail'] ) && ! empty( $theme_details['thumbnail'] ) ) {
				$themes['ttgarden']['screenshot'][0] = $theme_details['thumbnail'];
			}

			if ( isset( $theme_details['author_name'] ) && ! empty( $theme_details['author_name'] ) ) {
				$themes['ttgarden']['author'] = $theme_details['author_name'];

				if ( isset( $theme_details['author_url'] ) && ! empty( $theme_details['author_url'] ) ) {
					$themes['ttgarden']['authorAndUri'] = '<a href="' . $theme_details['author_url'] . '">' . $theme_details['author_name'] . '</a>';
				} else {
					$themes['ttgarden']['authorAndUri'] = $theme_details['author_name'];
				}
			}

			if ( isset( $theme_details['title'] ) && ! empty( $theme_details['title'] ) ) {
				$themes['ttgarden']['name'] = $theme_details['title'];
			}
		}

		return $themes;
	}

	/**
	 * Switches theme roots to support the ttgarden theme in this plugin.
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

		// If TumblrThemeGarden is the active theme, return the Tumblr theme directory.
		if ( $this->is_ttgarden_active ) {
			// Register the theme directory if it hasn't been registered yet.
			if ( null === $registered || false === $registered ) {
				$registered = register_theme_directory( TTGARDEN_PATH . 'theme' );
			}

			return TTGARDEN_PATH . 'theme';
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
	public function update_option_ttgarden_use_theme( $old_value, $value ): void {
		if ( '1' === $value ) {
			update_option( 'ttgarden_original_theme', get_option( 'template' ) );
			switch_theme( 'ttgarden' );
		} else {
			switch_theme( get_option( 'ttgarden_original_theme' ) );
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
		if ( 'ttgarden' === $new_theme->stylesheet ) {
			flush_rewrite_rules();
		}

		if ( 'ttgarden' === $old_theme->stylesheet ) {
			update_option( 'ttgarden_original_theme', '' );
			update_option( 'ttgarden_use_theme', '' );
		}
	}
}
