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
class CLTTG_Hooks {
	/**
	 * The TumblrThemeGarden active status.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     bool
	 */
	private $is_clttg_active;

	/**
	 * Initializes the CLTTG_Hooks.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param boolean $is_clttg_active The TumblrThemeGarden active status.
	 *
	 * @return  void
	 */
	public function initialize( $is_clttg_active ): void {
		$this->is_clttg_active = $is_clttg_active;

		// Add actions to the checkbox option that turns on the Tumblr theme.
		add_action( 'update_option_clttg_use_theme', array( $this, 'update_option_clttg_use_theme' ), 10, 2 );

		// Filter the theme root to use the Tumblr theme directory, if the option is set.
		add_filter( 'theme_root', array( $this, 'theme_root' ), 10, 2 );
		add_filter( 'stylesheet_root', array( $this, 'theme_root' ), 10, 2 );
		add_filter( 'template_root', array( $this, 'theme_root' ), 10, 2 );

		// Flush permalink rules when switching to the Tumblr theme.
		add_action( 'switch_theme', array( $this, 'switch_theme' ), 10, 3 );

		add_action( 'after_plugin_row_meta', array( $this, 'plugin_row_meta' ), 10 );

		// Only run these if the TumblrThemeGarden theme is active.
		if ( $this->is_clttg_active ) {
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
		if ( isset( $themes['tumblr-theme-garden'] ) ) {
			$theme_details = get_option( 'clttg_external_theme' );

			// Prepare the Tumblr theme screenshot.
			if ( isset( $theme_details['thumbnail'] ) && ! empty( $theme_details['thumbnail'] ) ) {
				$themes['tumblr-theme-garden']['screenshot'][0] = $theme_details['thumbnail'];
			}

			// Prepare the Tumblr theme author.
			if ( isset( $theme_details['author_name'] ) && ! empty( $theme_details['author_name'] ) ) {
				$themes['tumblr-theme-garden']['author'] = $theme_details['author_name'];

				if ( isset( $theme_details['author_url'] ) && ! empty( $theme_details['author_url'] ) ) {
					$themes['tumblr-theme-garden']['authorAndUri'] = '<a href="' . $theme_details['author_url'] . '">' . $theme_details['author_name'] . '</a>';
				} else {
					$themes['tumblr-theme-garden']['authorAndUri'] = $theme_details['author_name'];
				}
			}

			// Prepare the Tumblr theme name.
			if ( isset( $theme_details['title'] ) && ! empty( $theme_details['title'] ) ) {
				$themes['tumblr-theme-garden']['name'] = $theme_details['title'];
			}
		}

		return $themes;
	}

	/**
	 * Switches theme roots to support the tumblr-theme-garden theme in this plugin.
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
		if ( $this->is_clttg_active ) {
			// Register the theme directory if it hasn't been registered yet.
			if ( null === $registered || false === $registered ) {
				$registered = register_theme_directory( CLTTG_PATH . 'theme' );
			}

			return CLTTG_PATH . 'theme';
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
	public function update_option_clttg_use_theme( $old_value, $value ): void {
		if ( '1' === $value ) {
			update_option( 'clttg_original_theme', get_option( 'template' ) );
			switch_theme( 'tumblr-theme-garden' );
		} else {
			switch_theme( get_option( 'clttg_original_theme' ) );
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
		if ( 'tumblr-theme-garden' === $new_theme->stylesheet ) {
			flush_rewrite_rules();
		}

		if ( 'tumblr-theme-garden' === $old_theme->stylesheet ) {
			update_option( 'clttg_original_theme', '' );
			update_option( 'clttg_use_theme', '' );
			update_option( 'clttg_theme_html', '' );
		}
	}

	/**
	 * Fires after plugin row meta.
	 *
	 * @since 6.5.0
	 *
	 * @param string $plugin_file Refer to {@see 'plugin_row_meta'} filter.
	 */
	public function plugin_row_meta( $plugin_file ): void {
		// Only show the message on the TumblrThemeGarden plugin.
		if ( 'tumblr-theme-garden/tumblr-theme-garden.php' !== $plugin_file ) {
			return;
		}

		$features = new CLTTG_FeatureSniffer();

		// If there are no unsupported features, return early.
		if ( empty( $features->get_unsupported_features( 'plugins' ) ) ) {
			return;
		}

		printf(
			'<div class="requires"><p><strong>%s:</strong></p>%s</div>',
			esc_html__( 'The active Tumblr Theme recommends the following additional plugins', 'tumblr-theme-garden' ),
			wp_kses_post( $features->get_unsupported_features_html( true ) )
		);
	}
}
