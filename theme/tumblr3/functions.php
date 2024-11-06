<?php
/**
 * Tumblr3 functions and definitions
 *
 * @package Tumblr3
 */
defined( 'ABSPATH' ) || exit;

/**
 * Enqueues the block editor assets.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  void
 */
function tumblr3_enqueue_block_editor_assets(): void {
	$deps = tumblr3_get_asset_meta( TUMBLR3_PATH . 'assets/js/build/editor.asset.php' );

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
add_action( 'enqueue_block_editor_assets', 'tumblr3_enqueue_block_editor_assets' );

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
function tumblr3_disable_post_format_ui( array $settings ): array {
	$settings['disablePostFormats'] = true;
	return $settings;
}
add_filter( 'block_editor_settings_all', 'tumblr3_disable_post_format_ui' );

/**
 * Setup theme support.
 *
 * @return void
 */
function tumblr3_theme_support(): void {
	add_theme_support( 'post-formats', array( 'image', 'gallery', 'link', 'audio', 'video', 'quote', 'chat' ) );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'custom-background' );
	add_theme_support( 'custom-header' );
	add_theme_support( 'header-text' );
	add_theme_support( 'custom-logo' );

	// Register widget area to support an edge case of Tumblr's theme.
	register_sidebar(
		array(
			'name' => esc_html__( 'Sidebar', 'tumblr3' ),
			'id'   => 'sidebar-1',
		)
	);
}
add_action( 'after_setup_theme', 'tumblr3_theme_support' );

/**
 * Enqueue theme styles and scripts.
 *
 * @return void
 */
function tumblr3_enqueue_scripts(): void {
	wp_enqueue_style(
		'tumblr3-style',
		TUMBLR3_URL . 'assets/css/build/index.css',
		array(),
		TUMBLR3_METADATA['Version']
	);
}
add_action( 'wp_enqueue_scripts', 'tumblr3_enqueue_scripts' );

/**
 * Adds a random endpoint to match Tumblr's behavior.
 *
 * @return void
 */
function tumblr3_random_endpoint_rewrite(): void {
	add_rewrite_rule(
		'^random/?$',
		'index.php?random=1',
		'top'
	);
}
add_action( 'init', 'tumblr3_random_endpoint_rewrite' );

/**
 * Add a new query variable for Tumblr search.
 *
 * @param array $vars Registered query variables.
 *
 * @return array
 */
function tumblr3_add_tumblr_search_var( $vars ): array {
	$vars[] = 'q';
	$vars[] = 'random';
	return $vars;
}
add_filter( 'query_vars', 'tumblr3_add_tumblr_search_var' );

/**
 * Redirect Tumblr search to core search.
 *
 * @return void
 */
function tumblr3_redirect_tumblr_search(): void {
	// If random is set, redirect to a random post.
	if ( get_query_var( 'random' ) ) {
		// @see https://docs.wpvip.com/databases/optimize-queries/using-post__not_in/
		$rand_post = get_posts(
			array(
				'posts_per_page' => 2,
				'orderby'        => 'rand',
				'post_type'      => 'post',
				'fields'         => 'ids',
			)
		);

		if ( ! empty( $rand_post ) ) {
			$redirect_id = ( isset( $rand_post[1] ) && get_the_ID() !== $rand_post[1] ) ? $rand_post[1] : $rand_post[0];
			wp_safe_redirect( get_permalink( $redirect_id ) );
			exit;
		}
	}

	// If 'q' is set, redirect to the core search page.
	if ( get_query_var( 'q' ) ) {
		wp_safe_redirect( home_url( '/?s=' . get_query_var( 'q' ) ) );
		exit;
	}
}
add_action( 'template_redirect', 'tumblr3_redirect_tumblr_search' );

/**
 * Custom comment markup.
 *
 * @param WP_Comment $comment The comment object.
 * @param array      $args    An array of arguments.
 *
 * @return void
 */
function tumblr3_comment_markup( $comment, $args ) {
	?>
	<li class="note">
		<a href="#" class="avatar_frame">
			<?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
		</a>

		<span class="action">
			<?php
				echo wp_kses_post(
					sprintf(
						__( '%s <span class="says">says:</span>' ),
						sprintf( '<b class="fn">%s</b>', get_comment_author_link( $comment ) )
					)
				);
				comment_text();
			?>

			<?php if ( '0' === $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.' ); ?></p>
			<?php endif; ?>

		</span>

		<div class="clear"></div>

	<?php
}
