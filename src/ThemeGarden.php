<?php

namespace CupcakeLabs\T3;

use function cli\err;

defined( 'ABSPATH' ) || exit;

/**
 * Class ThemeGarden
 *
 * // Todo: Make translatable.
 *
 * @package CupcakeLabs\T3
 */
class ThemeGarden {
	const THEME_GARDEN_ENDPOINT      = 'https://www.tumblr.com/api/v2/theme_garden';
	public string $selected_category = 'featured';
	public string $search            = '';
	public string $activation_error  = '';

	/**
	 * Initializes the class.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  void
	 */
	public function initialize(): void {
		add_action( 'admin_menu', array( $this, 'register_submenu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'init', array( $this, 'maybe_activate_theme' ) );
		add_action( 'admin_notices', array( $this, 'maybe_show_notice' ) );

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce is verified in maybe_activate_theme.
		$this->selected_category = ( isset( $_GET['category'] ) ) ? sanitize_text_field( $_GET['category'] ) : '';

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce is verified in maybe_activate_theme.
		$this->search = ( isset( $_GET['search'] ) ) ? sanitize_text_field( $_GET['search'] ) : '';
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

			wp_enqueue_style(
				'tumblr-theme-garden',
				TUMBLR3_URL . 'assets/css/build/admin.css',
				array(),
				$deps['version']
			);

			wp_enqueue_script(
				'tumblr-theme-garden',
				TUMBLR3_URL . 'assets/js/build/theme-garden.js',
				$deps['dependencies'],
				$deps['version'],
				true
			);
		}
	}

	/**
	 * Displays an error message if something went wrong during theme activations.
	 *
	 * @return void
	 */
	public function maybe_show_notice(): void {
		if ( empty( $this->activation_error ) ) {
			return;
		}
		?>
		<div class="notice notice-error is-dismissible">
			<p><?php echo esc_html( $this->activation_error ); ?></p>
		</div>
		<?php
	}

	/**
	 * Checks URL query for a tumblr theme id to activate.
	 *
	 * @return void
	 */
	public function maybe_activate_theme(): void {
		if ( empty( $_GET['activate_tumblr_theme'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'activate_tumblr_theme' ) ) {
			return;
		}

		$response = wp_remote_get( self::THEME_GARDEN_ENDPOINT . '/theme/' . esc_attr( $_GET['activate_tumblr_theme'] ) );
		$status   = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $status ) {
			$this->activation_error = 'Error activating theme. Please try again later.';
			return;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ) );

		if ( ! isset( $body->response->theme ) ) {
			$this->activation_error = 'Error activating theme. Please try again later.';
			return;
		}

		// Save theme details to options.
		update_option( 'tumblr3_use_theme', '1' );
		update_option( 'tumblr3_theme_html', $body->response->theme );
		update_option( 'tumblr3_external_theme_id', $_GET['activate_tumblr_theme'] );
		update_option( 'tumblr3_external_theme_title', $body->response->title );
		update_option( 'tumblr3_external_theme_thumbnail', $body->response->thumbnail );
		update_option( 'tumblr3_external_theme_author', $body->response->author );

		// Setup theme option defaults.
		$this->option_defaults_helper( maybe_unserialize( $body->response->default_params ) );

		// Finally, redirect to the customizer with the new theme active.
		wp_safe_redirect( wp_customize_url() );
		exit;
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
			array( $this, 'render_page' )
		);
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
	 * Renders the theme garden page.
	 *
	 * @return void
	 */
	public function render_page(): void {
		$tumblr_logo = TUMBLR3_PATH . 'assets/images/tumblr_logo_icon.png';
		$cached_response = get_transient( 'tumblr_themes_response_' . $this->get_api_query_string() );

		if ( false === $cached_response ) {
			$response        = wp_remote_get( self::THEME_GARDEN_ENDPOINT . $this->get_api_query_string() );
			$cached_response = wp_remote_retrieve_body( $response );
			set_transient( 'tumblr_themes_response', $cached_response, WEEK_IN_SECONDS );
		}

		$body       = json_decode( $cached_response, true );
		$categories = isset( $body['response']['categories'] ) ? $body['response']['categories'] : array();
		$themes     = isset( $body['response']['themes'] ) ? $body['response']['themes'] : array();
		?>

		<div class="wrap">
			<h1 class="wp-heading-inline" id="theme-garden-heading">
				<img class="tumblr-logo-icon" src="<?php echo esc_url($tumblr_logo) ?>" alt="" />
				<span><?php esc_html_e( 'Tumblr Themes', 'tumblr3' ); ?></span>
			</h1>
			<?php $this->render_filter_bar( $categories, count( $themes ) ); ?>
			<?php $this->render_theme_list( $themes ); ?>
		</div>

		<?php
	}

	/**
	 * Renders the filter bar.
	 *
	 * @param array   $categories  The categories to render.
	 * @param integer $theme_count The number of themes.
	 *
	 * @return void
	 */
	public function render_filter_bar( array $categories, int $theme_count ): void {
		?>

		<div class="wp-filter">

			<div class="filter-count"><span class="count"><?php echo esc_html( $theme_count ); ?></span></div>

			<form method="get" id="t3-category-select-form">
				<input type="hidden" name="page" value="tumblr-themes">
				<label for="t3-categories">Categories</label>
				<select id="t3-categories" name="category">
					<option value="featured">Featured</option>
					<?php foreach ( $categories as $category ) : ?>
						<?php $selected = ( $this->selected_category === $category['text_key'] ) ? 'selected' : ''; ?>
						<option value="<?php echo esc_attr( $category['text_key'] ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $category['name'] ); ?></option>
					<?php endforeach; ?>
				</select>
			</form>

			<form method="get" class="search-form">
				<input type="hidden" name="page" value="tumblr-themes">
				<p class="search-box">
					<label for="wp-filter-search-input">Search Themes</label>
					<input type="search" aria-describedby="live-search-desc" id="wp-filter-search-input" class="wp-filter-search" name="search" value="<?php echo esc_attr( $this->search ); ?>">
				</p>
			</form>

		</div>

		<?php
	}

	/**
	 * Renders the theme list.
	 *
	 * @param array $themes The themes to render.
	 *
	 * @return void
	 */
	public function render_theme_list( $themes ): void {
		if ( empty( $themes ) ) {
			return;
		}

		if ( ! empty( $this->selected_category ) && 'featured' !== $this->selected_category ) {
			// Todo: API is returning themes ordered from oldest to newest. Needs to be fixed on Tumblr side.
			$themes = array_reverse( $themes );
		}
		?>
		<div class="tumblr-themes">
			<?php foreach ( $themes as $theme ) : ?>
				<?php
					$url          = add_query_arg(
						array(
							'activate_tumblr_theme' => $theme['id'],
						),
						admin_url( 'admin.php?page=tumblr-themes' )
					);
					$activate_url = wp_nonce_url( $url, 'activate_tumblr_theme' );
				?>

			<article class="tumblr-theme">

				<header class='tumblr-theme-header'>
					<div class='tumblr-theme-title-wrapper'><span class="tumblr-theme-title"><?php echo esc_html( $theme['title'] ); ?></span></div>
				</header>

				<div class='tumblr-theme-content'>
					<img class="tumblr-theme-thumbnail" src="<?php echo esc_url( $theme['thumbnail'] ); ?>" />
					<ul class="tumblr-theme-buttons">
						<li><a href="#">Preview</a></li>
						<li><a href="<?php echo esc_url( $activate_url ); ?>">Activate</a></li>
					</ul>
				</div>

			</article>

			<?php endforeach; ?>
		</div>
		<?php
	}
}
