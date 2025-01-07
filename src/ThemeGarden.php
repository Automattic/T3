<?php
/**
 * This is the custom Tumblr theme browsing functionality.
 *
 * @package TumblrThemeGarden
 */

namespace CupcakeLabs\TumblrThemeGarden;

defined( 'ABSPATH' ) || exit;

/**
 * Class to manage Tumblr theme browsing.
 *
 * @package CupcakeLabs\TumblrThemeGarden
 */
class ThemeGarden {
	const THEME_GARDEN_ENDPOINT = 'https://www.tumblr.com/api/v2/theme_garden';
	const ADMIN_MENU_SLUG       = 'tumblr-themes';

	/**
	 * The `category` param in the current URL. If present, we'll search Tumblr's API for the given category.
	 * Defaults to featured if no param is present.
	 *
	 * @var string $selected_category
	 */
	public string $selected_category = 'featured';

	/**
	 * The `theme` param in the current URL. If present, we'll render a theme details overlay.
	 *
	 * @var string $selected_theme_id
	 */
	public string $selected_theme_id = '';

	/**
	 * The `search` param in the current URL. If present, we'll search Tumblr's API for the given query.
	 *
	 * @var string $search
	 */
	public string $search = '';

	/**
	 * Initializes the class.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  void
	 */
	public function initialize(): void {
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
		add_action( 'admin_bar_menu', array( $this, 'admin_bar_render' ) );

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Only checking this exists.
		if ( ! empty( $_GET['activate_tumblr_theme'] ) ) {
			add_action( 'init', array( $this, 'maybe_activate_theme' ) );
		}

		add_action( 'admin_menu', array( $this, 'register_submenu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce is verified in maybe_activate_theme.
		$this->selected_category = ( isset( $_GET['category'] ) ) ? sanitize_text_field( wp_unslash( $_GET['category'] ) ) : '';

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce is verified in maybe_activate_theme.
		$this->search = ( isset( $_GET['search'] ) ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) : '';

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce is verified in maybe_activate_theme.
		$this->selected_theme_id = ( isset( $_GET['theme'] ) ) ? sanitize_text_field( wp_unslash( $_GET['theme'] ) ) : '';
	}

	/**
	 * Enqueue theme styles and scripts.
	 *
	 * @param string $hook The current admin page.
	 *
	 * @return void
	 */
	public function enqueue_assets( string $hook ): void {
		if ( 'appearance_page_' . self::ADMIN_MENU_SLUG === $hook ) {
			$deps = ttgarden_get_asset_meta( TTGARDEN_PATH . 'assets/js/build/theme-garden.asset.php' );

			$this->enqueue_admin_styles( $deps['version'] );

			$themes_and_categories = $this->get_themes_and_categories();
			$theme_details         = $this->selected_theme_id ? $this->get_theme( $this->selected_theme_id ) : null;
			$is_using_tumblr_theme = get_option( 'ttgarden_use_theme' );
			$active_theme          = $is_using_tumblr_theme ? get_option( 'ttgarden_external_theme' ) : null;

			wp_enqueue_script(
				'tumblr-theme-garden',
				TTGARDEN_URL . 'assets/js/build/theme-garden.js',
				$deps['dependencies'],
				$deps['version'],
				true
			);

			wp_add_inline_script(
				'tumblr-theme-garden',
				'const themeGardenData = ' . wp_json_encode(
					array(
						'logoUrl'          => TTGARDEN_URL . 'assets/images/tumblr_logo_icon.png',
						'customizeUrl'     => wp_customize_url(),
						'themes'           => $themes_and_categories['themes'],
						'categories'       => $themes_and_categories['categories'],
						'selectedCategory' => $this->selected_category,
						'search'           => $this->search,
						'baseUrl'          => admin_url( 'admin.php?page=' . self::ADMIN_MENU_SLUG ),
						'selectedThemeId'  => $this->selected_theme_id,
						'themeDetails'     => $theme_details,
						'activeTheme'      => $active_theme,
					)
				),
				'before'
			);
		}

		if ( 'theme-install.php' === $hook ) {
			$deps = ttgarden_get_asset_meta( TTGARDEN_PATH . 'assets/js/build/theme-install.asset.php' );

			$this->enqueue_admin_styles( $deps['version'] );

			wp_enqueue_script(
				'tumblr-theme-install',
				TTGARDEN_URL . 'assets/js/build/theme-install.js',
				$deps['dependencies'],
				$deps['version'],
				true
			);

			wp_add_inline_script(
				'tumblr-theme-install',
				'const TumblrThemeGarden_Install = ' . wp_json_encode(
					array(
						'browseUrl'  => admin_url( 'admin.php?page=' . self::ADMIN_MENU_SLUG ),
						'buttonText' => __( 'Browse Tumblr themes', 'tumblr-theme-garden' ),
					)
				),
				'before'
			);
		}

		if ( 'themes.php' === $hook ) {
			wp_enqueue_style(
				'tumblr-theme-garden-admin',
				TTGARDEN_URL . 'assets/css/build/themes.css',
				array(),
				time()
			);
		}
	}

	/**
	 * Enqueues admin CSS.
	 *
	 * @param string $version Plugin version.
	 *
	 * @return void
	 */
	public function enqueue_admin_styles( $version ): void {
		wp_enqueue_style(
			'tumblr-theme-garden',
			TTGARDEN_URL . 'assets/css/build/admin.css',
			array(),
			$version
		);
	}

	/**
	 * Fetches and theme object from Tumblr's API.
	 *
	 * @param string $theme_id The theme id to send to Tumblr's API.
	 *
	 * @return \WP_Error | object
	 */
	public function get_theme( string $theme_id ) {
		$response = wp_remote_get( self::THEME_GARDEN_ENDPOINT . '/theme/' . esc_attr( $theme_id ) . '?time=' . time() );
		$status   = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $status ) {
			return new \WP_Error();
		}

		$body = json_decode( wp_remote_retrieve_body( $response ) );

		if ( ! isset( $body->response->theme ) ) {
			return new \WP_Error();
		}

		return $body->response;
	}

	/**
	 * Register REST routes.
	 *
	 * @return void
	 */
	public function register_rest_routes(): void {
		register_rest_route(
			TTGARDEN_REST_NAMESPACE,
			'/themes',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'rest_api_get_themes' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			TTGARDEN_REST_NAMESPACE,
			'/theme',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'rest_api_get_theme' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);
	}

	/**
	 * Gets themes for an ajax request.
	 *
	 * @return \WP_REST_Response The settings for the queue.
	 */
	public function rest_api_get_themes(): \WP_REST_Response {
		$themes_and_categories = $this->get_themes_and_categories();
		return new \WP_REST_Response( $themes_and_categories['themes'], 200 );
	}

	/**
	 * Gets theme details for an ajax request.
	 *
	 * @return \WP_REST_Response The settings for the queue.
	 */
	public function rest_api_get_theme(): \WP_REST_Response {
		$theme = $this->get_theme( $this->selected_theme_id );
		return new \WP_REST_Response( $theme, 200 );
	}

	/**
	 * Checks URL query for a tumblr theme id to activate.
	 *
	 * @return void
	 */
	public function maybe_activate_theme(): void {
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'activate_tumblr_theme' ) ) {
			return;
		}

		$theme_id_to_activate = sanitize_text_field( wp_unslash( isset( $_GET['activate_tumblr_theme'] ) ? $_GET['activate_tumblr_theme'] : '' ) );
		$theme                = $this->get_theme( $theme_id_to_activate );

		if ( is_wp_error( $theme ) ) {
			return;
		}
		// Save theme details to options.
		update_option( 'ttgarden_theme_html', $theme->theme );

		// Save all external theme details to an option.
		$external_theme_details = array(
			'id'          => $theme_id_to_activate,
			'title'       => isset( $theme->title ) ? $theme->title : '',
			'thumbnail'   => isset( $theme->thumbnail ) ? $theme->thumbnail : '',
			'author_name' => isset( $theme->author->name ) ? $theme->author->name : '',
			'author_url'  => isset( $theme->author->url ) ? $theme->author->url : '',
		);

		update_option( 'ttgarden_external_theme', $external_theme_details );
		update_option( 'ttgarden_use_theme', '1' );

		// Setup theme option defaults.
		$this->option_defaults_helper( maybe_unserialize( $theme->default_params ) );

		// Finally, redirect to the customizer with the new theme active.
		wp_safe_redirect( wp_customize_url() );
		exit;
	}

	/**
	 * Checks transients for a cached value of themes and categories. If cache is empty, hits the Tumblr API.
	 * Before output, themes are formatted for use in javascript.
	 *
	 * @return array
	 */
	public function get_themes_and_categories(): array {
		$cached_response = get_transient( 'ttgarden_tumblr_themes_response_' . $this->get_api_query_string() );

		if ( false === $cached_response ) {
			$response        = wp_remote_get( self::THEME_GARDEN_ENDPOINT . $this->get_api_query_string() );
			$cached_response = wp_remote_retrieve_body( $response );
			set_transient( 'ttgarden_tumblr_themes_response', $cached_response, DAY_IN_SECONDS );
		}

		$body = json_decode( $cached_response, true );

		$themes = $body['response']['themes'];
		if ( ! empty( $this->selected_category ) && 'featured' !== $this->selected_category ) {
			// Todo: API is returning themes ordered from oldest to newest. Needs to be fixed on Tumblr side.
			$themes = array_reverse( $themes );
		}

		$formatted_themes = array_map(
			function ( $theme ) {
				$theme['activate_url'] = admin_url(
					sprintf(
						'admin.php?page=%s&activate_tumblr_theme=%s&_wpnonce=%s',
						self::ADMIN_MENU_SLUG,
						$theme['id'],
						wp_create_nonce( 'activate_tumblr_theme' )
					)
				);

				return $theme;
			},
			$themes
		);

		return array_merge( $body['response'], array( 'themes' => $formatted_themes ) );
	}

	/**
	 * On Tumblr theme activation, sets up the default options provided by the theme.
	 *
	 * @param array $default_params Default option values from the theme.
	 *
	 * @return void
	 */
	public function option_defaults_helper( $default_params ): void {
		$ttgarden_mods = get_option( 'theme_mods_tumblr-theme-garden', array() );

		if ( ! is_array( $ttgarden_mods ) ) {
			$ttgarden_mods = array();
		}

		foreach ( $default_params as $key => $value ) {
			$normal                   = ttgarden_normalize_option_name( $key );
			$ttgarden_mods[ $normal ] = ( str_starts_with( $key, 'color:' ) ) ? sanitize_hex_color( $value ) : sanitize_text_field( $value );
		}

		update_option( 'theme_mods_tumblr-theme-garden', $ttgarden_mods );
	}

	/**
	 * Registers the submenu page.
	 *
	 * @return void
	 */
	public function register_submenu(): void {
		add_submenu_page(
			'themes.php',
			__( 'Tumblr Themes', 'tumblr-theme-garden' ),
			__( 'Tumblr Themes', 'tumblr-theme-garden' ),
			'manage_options',
			'tumblr-themes',
			array( $this, 'render_theme_garden' )
		);
	}

	/**
	 * Makes a target <div> allowing React to render the theme garden.
	 *
	 * @return void
	 */
	public function render_theme_garden(): void {
		?>
		<div id="tumblr-theme-garden"></div>
		<?php
	}

	/**
	 * Checks for relevant params in the current URL's query, which will be sent to Tumblr API.
	 *
	 * @return string A query string to send to Tumblr API.
	 */
	public function get_api_query_string(): string {
		if ( ! empty( $this->search ) ) {
			return '?search=' . $this->search;
		}

		if ( ! empty( $this->selected_category ) && 'featured' !== $this->selected_category ) {
			return '?category=' . $this->selected_category;
		}

		return '';
	}

	/**
	 * The admin bar on the front end has a site-name drop down menu, which includes a link to
	 * install themes. Let's also include a link to install Tumblr themes.
	 *
	 * @param WP_Admin_Bar $admin_bar The admin bar object.
	 *
	 * @return void
	 */
	public function admin_bar_render( $admin_bar ): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$admin_bar->add_menu(
			array(
				'parent' => 'themes',
				'id'     => 'tumblr_themes',
				'title'  => __( 'Tumblr Themes', 'tumblr-theme-garden' ),
				'href'   => admin_url( 'admin.php?page=' . self::ADMIN_MENU_SLUG ),
				'meta'   => false,
			)
		);
	}
}
