<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package TumblrThemeGarden
 */

defined( 'ABSPATH' ) || exit;

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<ol class="notes">
	<?php
	wp_list_comments(
		array(
			'style'       => 'ol',
			'short_ping'  => true,
			'avatar_size' => 24,
			'callback'    => 'clttg_comment_markup',
			'max_depth'   => 0,
		)
	);
	?>
</ol><!-- .comment-list -->

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav class="navigation comment-navigation" role="navigation">
		<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'tumblr-theme-garden' ) ); ?></div>
		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'tumblr-theme-garden' ) ); ?></div>
	</nav><!-- .comment-navigation -->
<?php endif; // Check for comment navigation ?>

<?php if ( ! comments_open() && get_comments_number() ) : ?>
	<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'tumblr-theme-garden' ); ?></p>
<?php endif; ?>

<?php if ( comments_open() ) : ?>

<ol class="notes">
	<li>
		<?php comment_form(); ?>
	</li>
</ol><!-- .comment-list -->

	<?php
endif;
