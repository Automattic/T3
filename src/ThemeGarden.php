<?php

namespace CupcakeLabs\T3;

defined( 'ABSPATH' ) || exit;

/**
 * Class ThemeGarden
 *
 * @package CupcakeLabs\T3
 */
class ThemeGarden {
	const THEME_GARDEN_ENDPOINT = 'https://roccotrip.dca.tumblr.net/v2/theme_garden';
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
	}

	/**
	 * Enqueue theme styles and scripts.
	 *
	 * @return void
	 */
	public function enqueue_assets(): void {
		wp_enqueue_style( 'tumblr-theme-browser', TUMBLR3_URL . 'assets/css/build/admin.css', array(), '1.0' );
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
	 * Renders the theme garden page.
	 *
	 * @return void
	 */
	public function render_page(): void {
		$response   = wp_remote_get( self::THEME_GARDEN_ENDPOINT );
		$body       = json_decode( wp_remote_retrieve_body( $response ), true );
		$categories = $body['response']['categories'] ?? array();
		$themes     = $body['response']['themes'] ?? array();
		?>
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php esc_html_e( 'Tumblr Themes', 'tumblr3' ); ?></h1>
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
			<label for="t3-categories">Categories</label>
			<select id="t3-categories">
				<option>Featured</option>
				<?php foreach ( $categories as $category ) : ?>
					<option><?php echo esc_html( $category['name'] ); ?></option>
				<?php endforeach; ?>
			</select>
			<form class="search-form">
				<p class="search-box">
					<label for="wp-filter-search-input">Search Themes</label>
					<input type="search" aria-describedby="live-search-desc" id="wp-filter-search-input" class="wp-filter-search">
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
		?>
		<div class="tumblr-themes">
			<?php foreach ( $themes as $theme ) : ?>
			<article class="tumblr-theme">
				<header class='tumblr-theme-header'>
					<div class='tumblr-theme-title-wrapper'><span class="tumblr-theme-title"><?php echo esc_html( $theme['title'] ); ?></span></div>
				</header>
				<div class='tumblr-theme-content'>
					<img class="tumblr-theme-thumbnail" src="<?php echo esc_url( $theme['thumbnail'] ); ?>" />
					<ul class="tumblr-theme-buttons">
						<li><a href="#">Preview</a></li>
						<li><a href="#">Activate</a></li>
					</ul>
				</div>
			</article>
			<?php endforeach; ?>
		</div> 
		<?php
	}
}
