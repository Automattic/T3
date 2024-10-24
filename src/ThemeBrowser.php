<?php

namespace CupcakeLabs\T3;

defined( 'ABSPATH' ) || exit;

class ThemeBrowser {
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
		add_action('admin_menu', [$this, 'registerSubmenu']);
		add_action('admin_enqueue_scripts', [$this, 'enqueueAssets']);
	}

	public function enqueueAssets(): void {
		wp_enqueue_style( 'tumblr-theme-browser', TUMBLR3_URL . 'assets/css/build/admin.css', array(), '1.0' );
	}

	public function registerSubmenu()
	{
		add_submenu_page(
			'themes.php',
			__('Tumblr Themes', 'tumblr-theme-translator'),
			__('Tumblr Themes', 'tumblr-theme-translator'),
			'manage_options',
			'tumblr-theme',
			[$this, 'renderPage']
		);
	}

	public function renderPage(): void {
		$response = wp_remote_get(self::THEME_GARDEN_ENDPOINT);
		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		$categories = $body['response']['categories'] ?? [];
		$themes = $body['response']['themes'] ?? [];
		?>
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php echo __( 'Tumblr Themes' ); ?></h1>
			<?php $this->renderFilterBar($categories, count($themes)); ?>
			<?php $this->renderThemeList($themes); ?>
		</div>
		<?php
	}

	public function renderFilterBar(array $categories, int $theme_count): void {
		?>
		<div class="wp-filter">
			<div class="filter-count"><span class="count"><?php echo $theme_count; ?></span></div>
			<ul class="filter-links">
				<li><a class="current">Featured</a></li>
				<?php foreach ($categories as $category) : ?>
				<li><a><?php echo $category['name']; ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
	}

	public function renderThemeList($themes): void {
		if (empty($themes)) {
			return;
		} ?>
		<div class="tumblr-themes">
			<?php foreach ($themes as $theme) : ?>
			<article class="tumblr-theme">
				<header class='tumblr-theme-header'>
					<div class='tumblr-theme-title-wrapper'><span class="tumblr-theme-title"><?php echo $theme['title'] ?></span></div>
				</header>
				<div class='tumblr-theme-content'>
					<img class="tumblr-theme-thumbnail" src="<?php echo $theme['thumbnail'] ?>" />
					<ul class="tumblr-theme-buttons">
						<li><a href="#">Preview</a></li>
						<li><a href="#">Activate</a></li>
					</ul>
				</div>
			</article>
			<?php endforeach; ?>
		</div> <?php
	}
}
