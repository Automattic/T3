<?php
/**
 * This is the custom Tumblr theme browsing functionality.
 *
 * @package Tumblr3
 */

namespace CupcakeLabs\T3;

defined( 'ABSPATH' ) || exit;

/**
 * Class to manage Tumblr theme browsing.
 *
 * @package CupcakeLabs\T3
 */
class ThemeGarden {
	const THEME_GARDEN_ENDPOINT = 'https://www.tumblr.com/api/v2/theme_garden';

	/**
	 * This holds the currently selected category of themes.
	 *
	 * @var string
	 */
	public string $selected_category = 'featured';

	/**
	 * This holds the current search query.
	 *
	 * @var string
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

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Only checking this exists.
		if ( ! empty( $_GET['activate_tumblr_theme'] ) ) {
			add_action( 'init', array( $this, 'maybe_activate_theme' ) );
		}

		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'register_submenu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce is verified in maybe_activate_theme.
		$this->selected_category = ( isset( $_GET['category'] ) ) ? sanitize_text_field( wp_unslash( $_GET['category'] ) ) : '';

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce is verified in maybe_activate_theme.
		$this->search = ( isset( $_GET['search'] ) ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) : '';
	}

	/**
	 * Enqueue theme styles and scripts.
	 *
	 * @param string $hook The current admin page.
	 *
	 * @return void
	 */
	public function enqueue_assets( string $hook ): void {
		if ( 'appearance_page_tumblr-themes' === $hook ) {
			$deps = tumblr3_get_asset_meta( TUMBLR3_PATH . 'assets/js/build/theme-garden.asset.php' );
			$this->enqueue_admin_styles( $deps['version'] );
			$themes_and_categories = $this->get_themes_and_categories();
			wp_enqueue_script(
				'tumblr-theme-garden',
				TUMBLR3_URL . 'assets/js/build/theme-garden.js',
				$deps['dependencies'],
				$deps['version'],
				true
			);
			wp_add_inline_script(
				'tumblr-theme-garden',
				'const themeGardenData = ' . wp_json_encode(
					array(
						'logoUrl'          => TUMBLR3_URL . 'assets/images/tumblr_logo_icon.png',
						'themes'           => $themes_and_categories['themes'],
						'categories'       => $themes_and_categories['categories'],
						'selectedCategory' => $this->selected_category,
						'search'           => $this->search,
						'baseUrl'          => admin_url( 'admin.php?page=tumblr-themes' ),
					)
				),
				'before'
			);
		}

		if ( 'theme-install.php' === $hook ) {
			$deps = tumblr3_get_asset_meta( TUMBLR3_PATH . 'assets/js/build/theme-install.asset.php' );
			$this->enqueue_admin_styles( $deps['version'] );
			wp_enqueue_script(
				'tumblr-theme-install',
				TUMBLR3_URL . 'assets/js/build/theme-install.js',
				$deps['dependencies'],
				$deps['version'],
				true
			);
			wp_add_inline_script(
				'tumblr-theme-install',
				'const T3_Install = ' . wp_json_encode(
					array(
						'browseUrl'  => admin_url( 'admin.php?page=tumblr-themes' ),
						'buttonText' => __( 'Browse Tumblr themes', 'tumblr3' ),
					)
				),
				'before'
			);
		}

		if ( 'themes.php' === $hook ) {
			wp_enqueue_style(
				'tumblr3-admin',
				TUMBLR3_URL . 'assets/css/build/themes.css',
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
			TUMBLR3_URL . 'assets/css/build/admin.css',
			array(),
			$version
		);
	}

	/**
	 * Register REST routes.
	 *
	 * @return void
	 */
	public function register_rest_routes(): void {
		register_rest_route(
			'tumblr3/v1',
			'/themes',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_themes' ),
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
	public function get_themes(): \WP_REST_Response {
		$themes_and_categories = $this->get_themes_and_categories();
		return new \WP_REST_Response( $themes_and_categories['themes'], 200 );
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

		$theme_id = isset( $_GET['activate_tumblr_theme'] ) ? absint( wp_unslash( $_GET['activate_tumblr_theme'] ) ) : 0;

		// Returns early if theme id was not set.
		if ( 0 === $theme_id ) {
			return;
		}

		// During development, we don't want to be so limited by cache. So we'll send a cache invalidator to every request.
		// TODO: remove once we are confident that api response will be stable.
		$response = wp_remote_get( self::THEME_GARDEN_ENDPOINT . '/theme/' . esc_attr( $theme_id ) . '?time=' . time() );
		$status   = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $status ) {
			return;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ) );

		if ( ! isset( $body->response->theme ) ) {
			return;
		}

		// Save theme details to options.
		update_option( 'tumblr3_theme_html', $body->response->theme );

		// Save all external theme details to an option.
		$external_theme_details = array(
			'id'          => $theme_id,
			'title'       => isset( $body->response->title ) ? $body->response->title : '',
			'thumbnail'   => isset( $body->response->thumbnail ) ? $body->response->thumbnail : '',
			'author_name' => isset( $body->response->author->name ) ? $body->response->author->name : '',
			'author_url'  => isset( $body->response->author->url ) ? $body->response->author->url : '',
		);

		update_option( 'tumblr3_external_theme', $external_theme_details );
		update_option( 'tumblr3_use_theme', '1' );

		// Setup theme option defaults.
		$this->option_defaults_helper( maybe_unserialize( $body->response->default_params ) );

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
		$cached_response = get_transient( 'tumblr_themes_response_' . $this->get_api_query_string() );

		if ( false === $cached_response ) {
			$response        = wp_remote_get( self::THEME_GARDEN_ENDPOINT . $this->get_api_query_string() );
			$cached_response = wp_remote_retrieve_body( $response );
			set_transient( 'tumblr_themes_response', $cached_response, WEEK_IN_SECONDS );
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
					'admin.php?page=tumblr-themes&activate_tumblr_theme='
					. $theme['id']
					. '&_wpnonce='
					. wp_create_nonce( 'activate_tumblr_theme' )
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
		$tumblr3_mods = get_option( 'theme_mods_tumblr3', array() );

		foreach ( $default_params as $key => $value ) {
			$normal                  = tumblr3_normalize_option_name( $key );
			$tumblr3_mods[ $normal ] = $value;
		}

		update_option( 'theme_mods_tumblr3', $tumblr3_mods );
	}

	/**
	 * Registers the submenu page.
	 *
	 * @return void
	 */
	public function register_submenu(): void {
		add_submenu_page(
			'themes.php',
			__( 'Tumblr Themes', 'tumblr3' ),
			__( 'Tumblr Themes', 'tumblr3' ),
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
}
