<?php
/**
 * Block Functions
 *
 * @package Tumblr3
 */

defined( 'ABSPATH' ) || exit;

/**
 * This should not load on front-end views.
 * Effectively, this shortcode strips unwanted HTML.
 * This is the desired outcome, so not marking as a missing block.
 *
 * @return string Nothing, this is intentionally blank on the front-end.
 */
add_shortcode( 'block_options', '__return_empty_string' );
add_shortcode( 'block_hidden', '__return_empty_string' );

/**
 * Returns parsed content if the blog has more than one post author.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_groupmembers( $atts, $content = '' ): string {
	// Get all users who have published posts
	$authors = get_users(
		array(
			'has_published_posts' => array( 'post' ),
		)
	);
	$output  = '';

	// Check if there is more than one author
	if ( count( $authors ) > 1 ) {
		tumblr3_set_parse_context( 'groupmembers', $authors );
		$output = tumblr3_do_shortcode( $content );
		tumblr3_set_parse_context( 'theme', true );
	}

	return $output;
}
add_shortcode( 'block_groupmembers', 'tumblr3_block_groupmembers' );

/**
 * Loops over all group members and parses shortcodes within the block.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_groupmember( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();
	$output  = '';

	if ( isset( $context['groupmembers'] ) ) {
		$authors = $context['groupmembers'];

		// Loop over each blog author.
		foreach ( $authors as $author ) {
			tumblr3_set_parse_context( 'groupmember', $author );
			$output .= tumblr3_do_shortcode( $content );
		}

		tumblr3_set_parse_context( 'theme', true );
	}

	return $output;
}
add_shortcode( 'block_groupmember', 'tumblr3_block_groupmember' );

/**
 * Outputs content if the twitter username theme set is not empty.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_twitter( $atts, $content = '' ): string {
	return ( '' !== get_theme_mod( 'twitter_username', '' ) ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_twitter', 'tumblr3_block_twitter' );

/**
 * Boolean check for theme options.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_if_theme_option( $atts, $content = '' ): string {
	// Parse shortcode attributes.
	$atts = shortcode_atts(
		array(
			'name' => '',
		),
		$atts,
		'block_if_theme_option'
	);

	// Don't render if the name attribute is empty.
	if ( '' === $atts['name'] ) {
		return '';
	}

	return ( get_theme_mod( $atts['name'] ) ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_if_theme_option', 'tumblr3_block_if_theme_option' );

/**
 * Boolean check for theme options.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_ifnot_theme_option( $atts, $content = '' ): string {
	// Parse shortcode attributes.
	$atts = shortcode_atts(
		array(
			'name' => '',
		),
		$atts,
		'block_ifnot_theme_option'
	);

	// Don't render if the name attribute is empty.
	if ( '' === $atts['name'] ) {
		return '';
	}

	return ( ! get_theme_mod( $atts['name'] ) ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_ifnot_theme_option', 'tumblr3_block_ifnot_theme_option' );

/**
 * Conditional check for if we're in the loop.
 * This catches a bunch of blocks that should only render in the loop.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_body( $atts, $content = '' ): string {
	return ( in_the_loop() ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_body', 'tumblr3_block_body' );
add_shortcode( 'block_date', 'tumblr3_block_body' );
add_shortcode( 'block_postsummary', 'tumblr3_block_body' );
add_shortcode( 'block_excerpt', 'tumblr3_block_body' );
add_shortcode( 'block_host', 'tumblr3_block_body' );
add_shortcode( 'block_author', 'tumblr3_block_body' );

/**
 * Outputs content if we should stretch the header image.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_stretchheaderimage( $atts, $content = '' ): string {
	return ( get_theme_mod( 'stretch_header_image', true ) ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_stretchheaderimage', 'tumblr3_block_stretchheaderimage' );

/**
 * Outputs content if we should not stretch the header image.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_nostretchheaderimage( $atts, $content = '' ): string {
	return ( get_theme_mod( 'stretch_header_image', true ) ) ? '' : tumblr3_do_shortcode( $content );
}
add_shortcode( 'block_nostretchheaderimage', 'tumblr3_block_nostretchheaderimage' );

/**
 * Output content if we've chosen to show the site avatar.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_showavatar( $atts, $content = '' ): string {
	return ( get_theme_mod( 'show_avatar', true ) ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_showavatar', 'tumblr3_block_showavatar' );

/**
 * Output content if we've chosen to hide the site avatar.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_hideavatar( $atts, $content = '' ): string {
	return ( get_theme_mod( 'show_avatar', true ) ) ? '' : tumblr3_do_shortcode( $content );
}
add_shortcode( 'block_hideavatar', 'tumblr3_block_hideavatar' );

/**
 * Output content if we've chosen to show the site title and description.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_showtitle( $atts, $content = '' ): string {
	return display_header_text() ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_showtitle', 'tumblr3_block_showtitle' );

/**
 * Output content if we've chosen to hide the site title and description.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_hidetitle( $atts, $content = '' ): string {
	return display_header_text() ? '' : tumblr3_do_shortcode( $content );
}
add_shortcode( 'block_hidetitle', 'tumblr3_block_hidetitle' );

/**
 * Output content if we've chosen to show the site description.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_showdescription( $atts, $content = '' ): string {
	return display_header_text() ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_showdescription', 'tumblr3_block_showdescription' );

/**
 * Output content if we've chosen to hide the site description.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_hidedescription( $atts, $content = '' ): string {
	return display_header_text() ? '' : tumblr3_do_shortcode( $content );
}
add_shortcode( 'block_hidedescription', 'tumblr3_block_hidedescription' );

/**
 * Rendered on index pages for posts with Read More breaks.
 *
 * @todo Test if the post has a read-more tag, currently this is always true if we're in the loop.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_more( $atts, $content = '' ): string {
	return in_the_loop() ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_more', 'tumblr3_block_more' );

/**
 * Rendered if the post has an excerpt.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_description( $atts, $content = '' ): string {
	return has_excerpt() ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_description', 'tumblr3_block_description' );

/**
 * The main posts loop.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_posts( $atts, $content = '' ): string {
	tumblr3_set_parse_context( 'posts', true );
	$output = '';

	// Use the content inside this shortcode as a template for each post.
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();

			$output .= tumblr3_do_shortcode( $content );
		}
	}

	tumblr3_set_parse_context( 'theme', true );

	return $output;
}
add_shortcode( 'block_posts', 'tumblr3_block_posts' );

/**
 * Conditional if there are no posts.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_noposts( $atts, $content = '' ): string {
	return have_posts() ? '' : tumblr3_do_shortcode( $content );
}
add_shortcode( 'block_noposts', 'tumblr3_block_noposts' );

/**
 * Post tags loop.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_tags( $atts, $content = '' ): string {
	$output = '';
	$terms  = wp_get_post_terms( get_the_ID() );

	foreach ( $terms as $term ) {
		tumblr3_set_parse_context( 'term', $term );
		$output .= tumblr3_do_shortcode( $content );
	}

	tumblr3_set_parse_context( 'theme', true );

	return $output;
}
add_shortcode( 'block_tags', 'tumblr3_block_tags' );

/**
 * Rendered for each custom page.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_pages( $atts, $content = '' ): string {
	$output = '';

	$pages_query = new WP_Query(
		array(
			'post_type'      => 'page',
			'posts_per_page' => -1,
		)
	);

	// Use the content inside this shortcode as a template for each post.
	if ( $pages_query->have_posts() ) {
		while ( $pages_query->have_posts() ) {
			$pages_query->the_post();

			$output .= tumblr3_do_shortcode( $content );
		}
	}

	wp_reset_postdata();

	return $output;
}
add_shortcode( 'block_pages', 'tumblr3_block_pages' );

/**
 * Boolean check for if we're on a search page.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_searchpage( $atts, $content = '' ): string {
	return is_search() ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_searchpage', 'tumblr3_block_searchpage' );

/**
 * Render content if there are no search results.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_nosearchresults( $atts, $content = '' ): string {
	global $wp_query;

	return ( is_search() && 0 === $wp_query->found_posts ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_nosearchresults', 'tumblr3_block_nosearchresults' );

/**
 * Render content if there are search results.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_searchresults( $atts, $content = '' ): string {
	global $wp_query;

	return ( is_search() && $wp_query->found_posts > 0 ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_searchresults', 'tumblr3_block_searchresults' );

/**
 * Render content if this site is not currently public.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_hidefromsearchenabled( $atts, $content = '' ): string {
	return ( '1' !== get_option( 'blog_public' ) ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_hidefromsearchenabled', 'tumblr3_block_hidefromsearchenabled' );

/**
 * Boolean check for if we're on a taxonomy page.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_tagpage( $atts, $content = '' ): string {
	return ( is_tag() || is_category() ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_tagpage', 'tumblr3_block_tagpage' );

/**
 * Boolean check for if we're on a single post or page.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_permalinkpage( $atts, $content = '' ): string {
	return ( is_page() || is_single() ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_permalinkpage', 'tumblr3_block_permalinkpage' );
add_shortcode( 'block_permalink', 'tumblr3_block_permalinkpage' );

/**
 * Boolean check for if we're on the home page.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_indexpage( $atts, $content = '' ): string {
	return is_home() ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_indexpage', 'tumblr3_block_indexpage' );

/**
 * Boolean check for if we're on the "front page".
 * (This changes depending on settings chosen inside WordPress).
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_homepage( $atts, $content = '' ): string {
	return is_front_page() ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_homepage', 'tumblr3_block_homepage' );

/**
 * Sets the global parse context so we know we're outputting a post title.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_title( $atts, $content = '' ): string {
	return ! empty( get_the_title() ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_title', 'tumblr3_block_title' );

/**
 * If the current page is able to pagination, render the content.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_pagination( $atts, $content = '' ): string {
	return ( get_next_posts_page_link() || get_previous_posts_page_link() ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_pagination', 'tumblr3_block_pagination' );

/**
 * The Jump pagination block.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_jumppagination( $atts, $content = '' ): string {
	// Parse shortcode attributes.
	$atts = shortcode_atts(
		array(
			'length' => '5',
		),
		$atts,
		'block_jumppagination'
	);

	$output = '';

	if ( $atts['length'] > 0 ) {
		for ( $i = 1; $i <= $atts['length']; $i++ ) {
			tumblr3_set_parse_context( 'jumppagination', $i );
			$output .= tumblr3_do_shortcode( $content );
		}
	}

	tumblr3_set_parse_context( 'theme', true );

	return $output;
}
add_shortcode( 'block_jumppagination', 'tumblr3_block_jumppagination' );

/**
 * The currentpage block inside jumppagination.
 * Renders only if the current page is equal to the context.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_currentpage( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();
	$var     = get_query_var( 'paged' );
	$paged   = $var ? $var : 1;

	return ( isset( $context['jumppagination'] ) && $paged === $context['jumppagination'] ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_currentpage', 'tumblr3_block_currentpage' );

/**
 * The jumppage block inside jumppagination.
 * Render if the current page is not equal to the context.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_jumppage( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();
	$var     = get_query_var( 'paged' );
	$paged   = $var ? $var : 1;

	return ( isset( $context['jumppagination'] ) && $paged !== $context['jumppagination'] ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_jumppage', 'tumblr3_block_jumppage' );

/**
 * Boolean check for if we're on a single post or page.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_posttitle( $atts, $content = '' ): string {
	return is_single() ? tumblr3_block_title( $content ) : '';
}
add_shortcode( 'block_posttitle', 'tumblr3_block_posttitle' );

/**
 * Rendered if you have defined any custom pages.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_haspages( $atts, $content = '' ): string {
	$pages_query = get_posts(
		array(
			'post_type'      => 'page',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	return ( ! empty( $pages_query ) ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_haspages', 'tumblr3_block_haspages' );

/**
 * Rendered if you have "Show header image" enabled.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_showheaderimage( $atts, $content = '' ): string {
	return ( 'remove-header' !== get_theme_mod( 'header_image', 'remove-header' ) ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_showheaderimage', 'tumblr3_block_showheaderimage' );

/**
 * Rendered if you have "Show header image" disabled.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_hideheaderimage( $atts, $content = '' ): string {
	return ( 'remove-header' === get_theme_mod( 'header_image', 'remove-header' ) ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_hideheaderimage', 'tumblr3_block_hideheaderimage' );

/**
 * If a post is not a reblog, render the content.
 *
 * @todo This should be conditional, but WordPress doesn't currently support reblogs so it's static.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_notreblog( $atts, $content = '' ): string {
	return tumblr3_do_shortcode( $content );
}
add_shortcode( 'block_notreblog', 'tumblr3_block_notreblog' );

/**
 * Rendered if the post has tags.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_hastags( $atts, $content = '' ): string {
	return ( has_tag() ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_hastags', 'tumblr3_block_hastags' );

/**
 * Rendered if the post has comments or comments open.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_post_notes( $atts, $content = '' ): string {
	return ( is_single() || is_page() ) && ( get_comments_number() || comments_open() ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_postnotes', 'tumblr3_block_post_notes' );

/**
 * Rendered if the post has at least one comment.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_notecount( $atts, $content = '' ): string {
	return ( get_comments_number() > 0 ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_notecount', 'tumblr3_block_notecount' );

/**
 * Rendered for legacy Text posts and NPF posts.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_text( $atts, $content = '' ): string {
	return ( false === get_post_format() ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_text', 'tumblr3_block_text' );
add_shortcode( 'block_regular', 'tumblr3_block_text' );

/**
 * Rendered for legacy quote posts, or the WordPress quote post format.
 * Post logic is handled here, and then passed to the global context.
 * Tags inside the quote block are handed data from the global context.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_quote( $atts, $content = '' ): string {
	global $post;

	// Don't parse all blocks if the post format is not quote.
	if ( 'quote' !== get_post_format() ) {
		return '';
	}

	$blocks = parse_blocks( $post->post_content );
	$output = '';
	$source = '';

	// Handle all blocks in the post content.
	foreach ( $blocks as $block ) {

		// Stop on the first quote block.
		if ( 'core/quote' === $block['blockName'] ) {
			$processor = new CupcakeLabs\T3\Processor( $block['innerHTML'] );

			// Set bookmarks to extract HTML positions.
			while ( $processor->next_tag(
				array(
					'tag_name'    => 'CITE',
					'tag_closers' => 'visit',
				)
			) ) {
				$processor->is_tag_closer() ? $processor->set_bookmark( 'CITE-CLOSE' ) : $processor->set_bookmark( 'CITE-OPEN' );
			}

			// Get the processor bookmarks.
			$offset_open  = $processor->get_bookmark( 'CITE-OPEN' );
			$offset_close = $processor->get_bookmark( 'CITE-CLOSE' );

			// Extract the source from the quote block.
			if ( is_a( $offset_open, 'WP_HTML_Span' ) && is_a( $offset_close, 'WP_HTML_Span' ) ) {
				$source = substr( $block['innerHTML'], $offset_open->start, $offset_close->start + $offset_close->length - $offset_open->start );
			}

			// Rebuild the quote block content. CITE does not live in an innerBlock.
			foreach ( $block['innerBlocks'] as $inner_block ) {
				$output .= $inner_block['innerHTML'];
			}

			// Only parse the first quote block.
			break;
		}
	}

	// Set the current context.
	tumblr3_set_parse_context(
		'quote',
		array(
			'quote'  => wp_kses(
				$output,
				array(
					'br'     => array(),
					'span'   => array(),
					'strong' => array(),
					'em'     => array(),
				)
			),
			'source' => $source,
			'length' => strlen( $output ),
		)
	);

	// Parse the content of the quote block before resetting the context.
	$content = tumblr3_do_shortcode( $content );

	tumblr3_set_parse_context( 'theme', true );

	return $content;
}
add_shortcode( 'block_quote', 'tumblr3_block_quote' );

/**
 * Tests for a source in the quote post format.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_source( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();

	// Test if the current context is a quote post and has a source.
	if ( isset( $context['quote'], $context['quote']['source'] ) && ! empty( $context['quote']['source'] ) ) {
		return tumblr3_do_shortcode( $content );
	}

	// Return nothing if no source is found.
	return '';
}
add_shortcode( 'block_source', 'tumblr3_block_source' );

/**
 * Rendered for chat posts.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_chat( $atts, $content = '' ): string {
	global $post;

	// Don't parse all blocks if the post format is not chat.
	if ( 'chat' !== get_post_format() ) {
		return '';
	}

	$blocks = parse_blocks( $post->post_content );
	$lines  = array();

	foreach ( $blocks as $block ) {
		// capture each paragraph in the chat post as a chat block line.
		if ( 'core/paragraph' === $block['blockName'] ) {
			$lines[] = wp_strip_all_tags( $block['innerHTML'] );
		}
	}

	tumblr3_set_parse_context(
		'chat',
		array(
			'lines' => $lines,
		)
	);

	// Parse the content of the chat block before resetting the context.
	$content = tumblr3_do_shortcode( $content );

	tumblr3_set_parse_context( 'theme', true );

	return $content;
}
add_shortcode( 'block_chat', 'tumblr3_block_chat' );
add_shortcode( 'block_conversation', 'tumblr3_block_chat' );

/**
 * Legacy Chat Post rendered for each line of the post
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_lines( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();
	$output  = '';

	if ( ! isset( $context['chat']['lines'] ) || empty( $context['chat']['lines'] ) ) {
		return '';
	}

	foreach ( $context['chat']['lines'] as $block ) {
		// if : is not found, set whole block as line
		if ( strpos( $block, ':' ) === false ) {
			$line  = $block;
			$label = '';
		} else {
			// split $block into two parts, the first part is the label, the second part is the line
			$parts = explode( ':', $block, 2 );
			$label = $parts[0] . ':';
			$line  = $parts[1];
		}

		tumblr3_set_parse_context(
			'chat',
			array(
				'label' => $label,
				'line'  => $line,
			)
		);

		$output .= tumblr3_do_shortcode( $content );
	}

	return $output;
}
add_shortcode( 'block_lines', 'tumblr3_block_lines' );

/**
 * Legacy Chat Post block:label
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_label( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();

	if ( ! isset( $context['chat']['label'] ) || empty( $context['chat']['label'] ) ) {
		return '';
	}

	return tumblr3_do_shortcode( $content );
}
add_shortcode( 'block_label', 'tumblr3_block_label' );

/**
 * Rendered for link posts.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_link( $atts, $content = '' ): string {
	global $post;

	// Don't parse all blocks if the post format is not chat.
	if ( 'link' !== get_post_format() ) {
		return '';
	}

	$blocks = parse_blocks( $post->post_content );
	$url    = '';

	foreach ( $blocks as $block ) {
		// capture each paragraph in the chat post as a chat block line.
		if ( 'core/media-text' === $block['blockName'] ) {
			$url = $block['attrs']['mediaLink'];

			break;
		}
	}

	tumblr3_set_parse_context(
		'link',
		array(
			'url' => $url,
		)
	);

	// Parse the content of the chat block before resetting the context.
	$content = tumblr3_do_shortcode( $content );

	tumblr3_set_parse_context( 'theme', true );

	return $content;
}
add_shortcode( 'block_link', 'tumblr3_block_link' );

/**
 * Rendered for audio posts.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_audio( $atts, $content = '' ): string {
	global $post;

	// Don't parse all blocks if the post format is not quote.
	if ( 'audio' !== get_post_format() ) {
		return '';
	}

	$blocks     = parse_blocks( $post->post_content );
	$player     = '';
	$trackname  = '';
	$artist     = '';
	$album      = '';
	$media_id   = null;
	$provider   = '';
	$embed_html = '';
	$embed_url  = '';

	// Handle all blocks in the post content.
	foreach ( $blocks as $block ) {

		// Stop on the first audio block.
		if ( 'core/audio' === $block['blockName'] ) {
			$media_id   = isset( $block['attrs']['id'] ) ? $block['attrs']['id'] : 0;
			$trackname  = isset( $block['attrs']['mediaTitle'] ) ? $block['attrs']['mediaTitle'] : '';
			$artist     = isset( $block['attrs']['mediaArtist'] ) ? $block['attrs']['mediaArtist'] : '';
			$album      = isset( $block['attrs']['mediaAlbum'] ) ? $block['attrs']['mediaAlbum'] : '';
			$provider   = isset( $block['attrs']['providerName'] ) ? $block['attrs']['providerName'] : '';
			$embed_html = isset( $block['attrs']['embedHtml'] ) ? $block['attrs']['embedHtml'] : '';
			$embed_url  = isset( $block['attrs']['src'] ) ? $block['attrs']['src'] : '';
			$processor  = new CupcakeLabs\T3\Processor( $block['innerHTML'] );

			// Set bookmarks to extract HTML positions.
			while ( $processor->next_tag(
				array(
					'tag_name'    => 'AUDIO',
					'tag_closers' => 'visit',
				)
			) ) {
				// Check if we're in a closer or opener and handle accordingly.
				if ( $processor->is_tag_closer() ) {
					$processor->set_bookmark( 'AUDIO-CLOSE' );
				} else {
					$processor->add_class( 'tumblr_audio_player' );
					$processor->set_bookmark( 'AUDIO-OPEN' );
				}
			}

			// Get the processor bookmarks.
			$offset_open  = $processor->get_bookmark( 'AUDIO-OPEN' );
			$offset_close = $processor->get_bookmark( 'AUDIO-CLOSE' );

			// Extract the player from the quote block.
			if ( is_a( $offset_open, 'WP_HTML_Span' ) && is_a( $offset_close, 'WP_HTML_Span' ) ) {
				$player = substr( $processor->get_updated_html(), $offset_open->start, $offset_close->start + $offset_close->length - $offset_open->start );
			}

			break;
		}
	}

	// Extract metadata from the media ID, don't overwrite values if they're already set.
	if ( is_int( $media_id ) && $media_id > 0 ) {
		$meta      = wp_get_attachment_metadata( $media_id );
		$trackname = ( empty( $trackname ) && isset( $meta['title'] ) ) ? $meta['title'] : $trackname;
		$artist    = ( empty( $artist ) && isset( $meta['artist'] ) ) ? $meta['artist'] : $artist;
		$album     = ( empty( $album ) && isset( $meta['album'] ) ) ? $meta['album'] : $album;
	}

	// Set the current context.
	tumblr3_set_parse_context(
		'audio',
		array(
			'player'     => $player,
			'art'        => get_the_post_thumbnail_url(),
			'trackname'  => $trackname,
			'artist'     => $artist,
			'album'      => $album,
			'media_id'   => $media_id,
			'provider'   => $provider,
			'embed_html' => $embed_html,
			'embed_url'  => $embed_url,
		)
	);

	// Parse the content of the quote block before resetting the context.
	$content = tumblr3_do_shortcode( $content );

	tumblr3_set_parse_context( 'theme', true );

	return $content;
}
add_shortcode( 'block_audio', 'tumblr3_block_audio' );

/**
 * Rendered for audio posts with an audioplayer block.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_audioplayer( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();

	return ( isset( $context['audio']['player'] ) && ! empty( $context['audio']['player'] ) ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_audioplayer', 'tumblr3_block_audioplayer' );
add_shortcode( 'block_audioembed', 'tumblr3_block_audioplayer' );

/**
 * Rendered for audio posts with an external audio block.
 * Calculated as meaning the media ID is 0, which means it's an external audio file.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_externalaudio( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();

	return ( isset( $context['audio']['media_id'] ) && 0 === $context['audio']['media_id'] ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_externalaudio', 'tumblr3_block_externalaudio' );

/**
 * Rendered for audio posts with a featured image set.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_albumart( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();

	return ( isset( $context['audio']['player'] ) && ! empty( $context['audio']['player'] ) ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_albumart', 'tumblr3_block_albumart' );

/**
 * Rendered for audio posts with a track name set.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_trackname( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();

	return ( isset( $context['audio']['trackname'] ) && '' !== $context['audio']['trackname'] ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_trackname', 'tumblr3_block_trackname' );

/**
 * Rendered for audio posts with an artist name set.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_artist( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();

	return ( isset( $context['audio']['artist'] ) && '' !== $context['audio']['artist'] ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_artist', 'tumblr3_block_artist' );

/**
 * Rendered for audio posts with an album name set.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_album( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();

	return ( isset( $context['audio']['album'] ) && '' !== $context['audio']['album'] ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_album', 'tumblr3_block_album' );

/**
 * Rendered for video posts.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_video( $atts, $content = '' ): string {
	global $post;

	// Don't parse all blocks if the post format is not quote.
	if ( 'video' !== get_post_format() ) {
		return '';
	}

	$blocks                   = parse_blocks( $post->post_content );
	$thumbnail                = '';
	$url                      = '';
	$media                    = array();
	$provider                 = '';
	$embed_html               = '';
	$embed_iframe             = array();
	$embed_url                = '';
	$metadata                 = array();
	$attribution              = array();
	$can_autoplay_on_cellular = false;
	$duration                 = 0;

	// Handle all blocks in the post content.
	foreach ( $blocks as $block ) {

		// Stop on the first video block.
		if ( 'core/video' === $block['blockName'] ) {
			$url                      = isset( $block['attrs']['url'] ) ? $block['attrs']['url'] : '';
			$media                    = isset( $block['attrs']['media'] ) ? $block['attrs']['media'] : array();
			$provider                 = isset( $block['attrs']['providerNameSlug'] ) ? $block['attrs']['providerNameSlug'] : '';
			$embed_html               = isset( $block['attrs']['embedHtml'] ) ? $block['attrs']['embedHtml'] : '';
			$embed_iframe             = isset( $block['attrs']['embedIframe'] ) ? $block['attrs']['embedIframe'] : array();
			$embed_url                = isset( $block['attrs']['embedUrl'] ) ? $block['attrs']['embedUrl'] : '';
			$metadata                 = isset( $block['attrs']['metadata'] ) ? $block['attrs']['metadata'] : array();
			$attribution              = isset( $block['attrs']['attribution'] ) ? $block['attrs']['attribution'] : array();
			$can_autoplay_on_cellular = isset( $block['attrs']['canAutoplayOnCellular'] ) ? $block['attrs']['canAutoplayOnCellular'] : false;
			$duration                 = isset( $block['attrs']['mediaDuration'] ) ? $block['attrs']['mediaDuration'] : 0;

			$processor = new CupcakeLabs\T3\Processor( $block['innerHTML'] );

			// Set bookmarks to extract HTML positions.
			while ( $processor->next_tag(
				array(
					'tag_name'    => 'VIDEO',
					'tag_closers' => 'visit',
				)
			) ) {
				// Check if we're in a closer or opener and handle accordingly.
				if ( $processor->is_tag_closer() ) {
					$processor->set_bookmark( 'VIDEO-CLOSE' );
				} else {
					$processor->set_bookmark( 'VIDEO-OPEN' );
					$thumbnail = $processor->get_attribute( 'poster' );
				}
			}

			// Get the processor bookmarks.
			$offset_open  = $processor->get_bookmark( 'VIDEO-OPEN' );
			$offset_close = $processor->get_bookmark( 'VIDEO-CLOSE' );

			// Extract the player from the quote block.
			if ( is_a( $offset_open, 'WP_HTML_Span' ) && is_a( $offset_close, 'WP_HTML_Span' ) ) {
				$player = substr( $block['innerHTML'], $offset_open->start, $offset_close->start + $offset_close->length - $offset_open->start );
			}

			break;
		}

		// No video block found, check for an embed block instead.
		if ( 'core/embed' === $block['blockName'] ) {
			$player = wp_oembed_get( $block['attrs']['url'] );

			break;
		}
	}

	// Set the current context.
	tumblr3_set_parse_context(
		'video',
		array(
			'player'                   => $player,
			'thumbnail'                => $thumbnail,
			'url'                      => $url,
			'media'                    => $media,
			'provider'                 => $provider,
			'embed_html'               => $embed_html,
			'embed_iframe'             => $embed_iframe,
			'embed_url'                => $embed_url,
			'metadata'                 => $metadata,
			'attribution'              => $attribution,
			'can_autoplay_on_cellular' => $can_autoplay_on_cellular,
			'duration'                 => $duration,
		)
	);

	// Parse the content of the quote block before resetting the context.
	$content = tumblr3_do_shortcode( $content );

	tumblr3_set_parse_context( 'theme', true );

	return $content;
}
add_shortcode( 'block_video', 'tumblr3_block_video' );

/**
 * Rendered for video posts with a video player and video thumbnail.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_videothumbnail( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();

	return ( isset( $context['video']['thumbnail'] ) && ! empty( $context['video']['thumbnail'] ) ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_videothumbnail', 'tumblr3_block_videothumbnail' );
add_shortcode( 'block_videothumbnails', 'tumblr3_block_videothumbnail' );

/**
 * Rendered for photo and panorama posts.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_photo( $atts, $content = '' ): string {
	global $post;

	// Don't parse all blocks if the post format is not quote.
	if ( 'image' !== get_post_format() ) {
		return '';
	}

	$blocks        = parse_blocks( $post->post_content );
	$highres       = false;
	$highres_sizes = array( 'large', 'full' );
	$image_id      = 0;
	$link_dest     = 'none';
	$lightbox      = false;

	// Handle all blocks in the post content.
	foreach ( $blocks as $key => $block ) {
		if ( 'core/image' === $block['blockName'] ) {
			$highres   = isset( $block['attrs']['sizeSlug'] ) ? in_array( $block['attrs']['sizeSlug'], $highres_sizes, true ) : false;
			$image_id  = isset( $block['attrs']['id'] ) ? $block['attrs']['id'] : 0;
			$link_dest = isset( $block['attrs']['linkDestination'] ) ? $block['attrs']['linkDestination'] : 'none';
			$lightbox  = isset( $block['attrs']['lightbox'] );

			$image_src = wp_get_attachment_image_src( $image_id, 'large' );
			if ( isset( $image_src[0] ) ) {
				// Grab the internal image.
				$image_src = $image_src[0];
			} else {
				// If the image source is not found, use the block's innerHTML.
				$processor = new CupcakeLabs\T3\Processor( $block['innerHTML'] );

				while ( $processor->next_tag( array( 'tag_name' => 'IMG' ) ) ) {
					$image_src = $processor->get_attribute( 'src' );
					break;
				}
			}

			/**
			 * In Image (Photo) posts, the caption tag is for the rest of the post content.
			 *
			 * @see https://www.tumblr.com/docs/en/custom_themes#photo-posts:~:text=%7BCaption%7D%20for%20legacy%20Photo%20and%20Photoset%20posts
			 */
			unset( $blocks[ $key ] );

			// Only parse the first image block.
			break;
		}
	}

	// Set the current context.
	tumblr3_set_parse_context(
		'image',
		array(
			'highres'  => $highres,
			'image'    => (string) $image_src,
			'link'     => $link_dest,
			'lightbox' => $lightbox,
			'caption'  => serialize_blocks( $blocks ),
			'data'     => wp_get_attachment_metadata( $image_id, true ),
		)
	);

	// Parse the content of the quote block before resetting the context.
	$content = tumblr3_do_shortcode( $content );

	tumblr3_set_parse_context( 'theme', true );

	return $content;
}
add_shortcode( 'block_photo', 'tumblr3_block_photo' );

/**
 * Render each photo in a photoset (gallery) post.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_photos( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();
	$output  = '';

	if ( ! isset( $context['gallery']['photos'] ) || empty( $context['gallery']['photos'] ) ) {
		return '';
	}

	foreach ( $context['gallery']['photos'] as $block ) {
		$highres_sizes = array( 'large', 'full' );
		$highres       = isset( $block['attrs']['sizeSlug'] ) ? in_array( $block['attrs']['sizeSlug'], $highres_sizes, true ) : false;
		$image_id      = $block['attrs']['id'];
		$link_dest     = isset( $block['attrs']['linkDestination'] ) ? $block['attrs']['linkDestination'] : 'none';
		$lightbox      = isset( $block['attrs']['lightbox'] );

		$image_src = wp_get_attachment_image_src( $image_id, 'large' );
		if ( isset( $image_src[0] ) ) {
			// Grab the internal image.
			$image_src = $image_src[0];
		} else {
			// If the image source is not found, use the block's innerHTML.
			$processor = new CupcakeLabs\T3\Processor( $block['innerHTML'] );

			while ( $processor->next_tag( array( 'tag_name' => 'IMG' ) ) ) {
				$image_src = $processor->get_attribute( 'src' );
				break;
			}
		}

		// Set the current context.
		tumblr3_set_parse_context(
			'image',
			array(
				'highres'  => $highres,
				'image'    => $image_src,
				'link'     => $link_dest,
				'lightbox' => $lightbox,
				'data'     => wp_get_attachment_metadata( $image_id, true ),
			)
		);

		// Parse the content of the quote block before resetting the context.
		$output .= tumblr3_do_shortcode( $content );
	}

	return $output;
}
add_shortcode( 'block_photos', 'tumblr3_block_photos' );

/**
 * Rendered for photo and panorama posts which have a link set on the image.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_linkurl( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();

	if ( ! isset( $context['image']['link'] ) ) {
		return '';
	}

	return ( true === $context['image']['lightbox'] || 'none' !== $context['image']['link'] ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_linkurl', 'tumblr3_block_linkurl' );

/**
 * Rendered for photo and panorama posts which have the image size set as "large" or "fullsize".
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_highres( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();

	return ( isset( $context['image']['highres'] ) && true === $context['image']['highres'] ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_highres', 'tumblr3_block_highres' );

/**
 * Rendered render content if the image has exif data.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_exif( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();

	return ( isset( $context['image']['data'] ) && ! empty( $context['image']['data']['image_meta'] ) ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_exif', 'tumblr3_block_exif' );

/**
 * Conditionally load content based on if the image has camera exif data.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_camera( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();

	if ( ! isset( $context['image']['data']['image_meta']['camera'] ) ) {
		return '';
	}

	$camera = $context['image']['data']['image_meta']['camera'];

	return ( empty( $camera ) || '0' === $camera ) ? '' : tumblr3_do_shortcode( $content );
}
add_shortcode( 'block_camera', 'tumblr3_block_camera' );

/**
 * Conditionally load content based on if the image has lens exif data.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_aperture( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();

	if ( ! isset( $context['image']['data']['image_meta']['aperture'] ) ) {
		return '';
	}

	$aperture = $context['image']['data']['image_meta']['aperture'];

	return ( empty( $aperture ) || '0' === $aperture ) ? '' : tumblr3_do_shortcode( $content );
}
add_shortcode( 'block_aperture', 'tumblr3_block_aperture' );

/**
 * Conditionally load content based on if the image has focal length exif data.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_exposure( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();

	if ( ! isset( $context['image']['data']['image_meta']['shutter_speed'] ) ) {
		return '';
	}

	$exposure = $context['image']['data']['image_meta']['shutter_speed'];

	return ( empty( $exposure ) || '0' === $exposure ) ? '' : tumblr3_do_shortcode( $content );
}
add_shortcode( 'block_exposure', 'tumblr3_block_exposure' );

/**
 * Conditionally load content based on if the image has focal length exif data.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_focallength( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();

	if ( ! isset( $context['image']['data']['image_meta']['focal_length'] ) ) {
		return '';
	}

	$focal_length = $context['image']['data']['image_meta']['focal_length'];

	return ( empty( $focal_length ) || '0' === $focal_length ) ? '' : tumblr3_do_shortcode( $content );
}
add_shortcode( 'block_focallength', 'tumblr3_block_focallength' );

/**
 * Rendered for photoset (gallery) posts.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_photoset( $atts, $content = '' ): string {
	global $post;

	// Don't parse all blocks if the post format is not quote.
	if ( 'gallery' !== get_post_format() ) {
		return '';
	}

	$blocks     = parse_blocks( $post->post_content );
	$gallery    = '';
	$photocount = 0;
	$photos     = array();

	// Handle all blocks in the post content.
	foreach ( $blocks as $key => $block ) {
		if ( 'core/gallery' === $block['blockName'] ) {
			$photocount = count( $block['innerBlocks'] );
			$photos     = $block['innerBlocks'];

			// Capture the gallery block.
			$gallery = serialize_block( $block );

			/**
			 * In Gallery posts, the caption tag is for the rest of the post content.
			 *
			 * @see https://www.tumblr.com/docs/en/custom_themes#photo-posts:~:text=%7BCaption%7D%20for%20legacy%20Photo%20and%20Photoset%20posts
			 */
			unset( $blocks[ $key ] );

			// Only parse the first quote block.
			break;
		}
	}

	// Set the current context.
	tumblr3_set_parse_context(
		'gallery',
		array(
			'gallery'    => $gallery,
			'photocount' => $photocount,
			'caption'    => serialize_blocks( $blocks ),
			'photos'     => $photos,
		)
	);

	// Parse the content of the quote block before resetting the context.
	$content = tumblr3_do_shortcode( $content );

	tumblr3_set_parse_context( 'theme', true );

	return $content;
}
add_shortcode( 'block_photoset', 'tumblr3_block_photoset' );

/**
 * Rendered for link posts with a thumbnail image set.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_thumbnail( $atts, $content = '' ): string {
	return has_post_thumbnail() ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_thumbnail', 'tumblr3_block_thumbnail' );

/**
 * Rendered for photoset (gallery) posts with caption content.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_caption( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();
	$format  = get_post_format();

	if ( ! isset( $context[ $format ]['caption'] ) ) {
		return '';
	}

	return tumblr3_do_shortcode( $content );
}
add_shortcode( 'block_caption', 'tumblr3_block_caption' );

/**
 * Rendered for legacy Text posts and NPF posts.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_daypage( $atts, $content = '' ): string {
	return ( is_day() ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_daypage', 'tumblr3_block_daypage' );

/**
 * Rendered if older posts are available.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_previouspage( $atts, $content = '' ): string {
	return ( get_next_posts_link() ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_previouspage', 'tumblr3_block_previouspage' );

/**
 * Rendered if newer posts are available.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_nextpage( $atts, $content = '' ): string {
	return ( get_previous_posts_link() ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_nextpage', 'tumblr3_block_nextpage' );

/**
 * Boolean check for if we're on a single post or page.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_permalinkpagination( $atts, $content = '' ): string {
	return is_single() ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_permalinkpagination', 'tumblr3_block_permalinkpagination' );

/**
 * Check if there's a previous adjacent post.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_previouspost( $atts, $content = '' ): string {
	return ( get_previous_post() ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_previouspost', 'tumblr3_block_previouspost' );

/**
 * Check if there's a next adjacent post.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_nextpost( $atts, $content = '' ): string {
	return ( get_next_post() ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_nextpost', 'tumblr3_block_nextpost' );

/**
 * Rendered if the post has been marked as sticky.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_pinnedpostlabel( $atts, $content = '' ): string {
	return is_sticky() ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_pinnedpostlabel', 'tumblr3_block_pinnedpostlabel' );

/**
 * Render content if the current language is equal to the specified language.
 *
 * @param array  $atts           The attributes of the shortcode.
 * @param string $content        The content of the shortcode.
 * @param string $shortcode_name The name of the shortcode.
 *
 * @return string The parsed content or an empty string.
 */
function tumblr3_block_language( $atts, $content, $shortcode_name ): string {
	// Map shortcodes to their respective locales
	$language_map = array(
		'block_english'               => 'en_US',
		'block_german'                => 'de_DE',
		'block_french'                => 'fr_FR',
		'block_italian'               => 'it_IT',
		'block_turkish'               => 'tr_TR',
		'block_spanish'               => 'es_ES',
		'block_russian'               => 'ru_RU',
		'block_japanese'              => 'ja_JP',
		'block_polish'                => 'pl_PL',
		'block_portuguesept'          => 'pt_PT',
		'block_portuguesebr'          => 'pt_BR',
		'block_dutch'                 => 'nl_NL',
		'block_korean'                => 'ko_KR',
		'block_chinesesimplified'     => 'zh_CN',
		'block_chinesetraditional'    => 'zh_TW',
		'block_chinesehk'             => 'zh_HK',
		'block_indonesian'            => 'id_ID',
		'block_hindi'                 => 'hi_IN',
		'block_notenglish'            => 'en_US',
		'block_notgerman'             => 'de_DE',
		'block_notfrench'             => 'fr_FR',
		'block_notitalian'            => 'it_IT',
		'block_notturkish'            => 'tr_TR',
		'block_notspanish'            => 'es_ES',
		'block_notrussian'            => 'ru_RU',
		'block_notjapanese'           => 'ja_JP',
		'block_notpolish'             => 'pl_PL',
		'block_notportuguesept'       => 'pt_PT',
		'block_notportuguesebr'       => 'pt_BR',
		'block_notdutch'              => 'nl_NL',
		'block_notkorean'             => 'ko_KR',
		'block_notchinesesimplified'  => 'zh_CN',
		'block_notchinesetraditional' => 'zh_TW',
		'block_notchinesehk'          => 'zh_HK',
		'block_notindonesian'         => 'id_ID',
		'block_nothindi'              => 'hi_IN',
	);

	// Get the current locale
	$current_locale = get_locale();

	// Check if the shortcode name matches a defined language and compare it with the current locale
	if ( 0 === strpos( $shortcode_name, 'block_not' ) ) {
		if ( isset( $language_map[ $shortcode_name ] ) && $language_map[ $shortcode_name ] !== $current_locale ) {
			return tumblr3_do_shortcode( $content );
		}
	} elseif ( isset( $language_map[ $shortcode_name ] ) && $language_map[ $shortcode_name ] === $current_locale ) {
		return tumblr3_do_shortcode( $content );
	}

	return '';
}
add_shortcode( 'block_english', 'tumblr3_block_language' );
add_shortcode( 'block_german', 'tumblr3_block_language' );
add_shortcode( 'block_french', 'tumblr3_block_language' );
add_shortcode( 'block_italian', 'tumblr3_block_language' );
add_shortcode( 'block_turkish', 'tumblr3_block_language' );
add_shortcode( 'block_spanish', 'tumblr3_block_language' );
add_shortcode( 'block_russian', 'tumblr3_block_language' );
add_shortcode( 'block_japanese', 'tumblr3_block_language' );
add_shortcode( 'block_polish', 'tumblr3_block_language' );
add_shortcode( 'block_portuguesept', 'tumblr3_block_language' );
add_shortcode( 'block_portuguesebr', 'tumblr3_block_language' );
add_shortcode( 'block_dutch', 'tumblr3_block_language' );
add_shortcode( 'block_korean', 'tumblr3_block_language' );
add_shortcode( 'block_chinesesimplified', 'tumblr3_block_language' );
add_shortcode( 'block_chinesetraditional', 'tumblr3_block_language' );
add_shortcode( 'block_chinesehk', 'tumblr3_block_language' );
add_shortcode( 'block_indonesian', 'tumblr3_block_language' );
add_shortcode( 'block_hindi', 'tumblr3_block_language' );
add_shortcode( 'block_notenglish', 'tumblr3_block_language' );
add_shortcode( 'block_notgerman', 'tumblr3_block_language' );
add_shortcode( 'block_notfrench', 'tumblr3_block_language' );
add_shortcode( 'block_notitalian', 'tumblr3_block_language' );
add_shortcode( 'block_notturkish', 'tumblr3_block_language' );
add_shortcode( 'block_notspanish', 'tumblr3_block_language' );
add_shortcode( 'block_notrussian', 'tumblr3_block_language' );
add_shortcode( 'block_notjapanese', 'tumblr3_block_language' );
add_shortcode( 'block_notpolish', 'tumblr3_block_language' );
add_shortcode( 'block_notportuguesept', 'tumblr3_block_language' );
add_shortcode( 'block_notportuguesebr', 'tumblr3_block_language' );
add_shortcode( 'block_notdutch', 'tumblr3_block_language' );
add_shortcode( 'block_notkorean', 'tumblr3_block_language' );
add_shortcode( 'block_notchinesesimplified', 'tumblr3_block_language' );
add_shortcode( 'block_notchinesetraditional', 'tumblr3_block_language' );
add_shortcode( 'block_notchinesehk', 'tumblr3_block_language' );
add_shortcode( 'block_notindonesian', 'tumblr3_block_language' );
add_shortcode( 'block_nothindi', 'tumblr3_block_language' );

/**
 * Rendered if this is post number N (0 - 15) in the loop.
 *
 * @param array  $atts           The attributes of the shortcode.
 * @param string $content        The content of the shortcode.
 * @param string $shortcode_name The name of the shortcode.
 *
 * @return string The parsed content or an empty string.
 */
function tumblr3_block_post_n( $atts, $content, $shortcode_name ): string {
	global $wp_query;

	// Extract the post number from the shortcode name (assuming 'block_postN' where N is a number)
	if ( preg_match( '/block_post(\d+)/', $shortcode_name, $matches ) ) {

		// Check if in the loop and if the current post is the post number N
		if ( in_the_loop() && absint( $matches[1] - 1 ) === $wp_query->current_post ) {
			return tumblr3_do_shortcode( $content );
		}
	}

	return '';
}
add_shortcode( 'block_post1', 'tumblr3_block_post_n' );
add_shortcode( 'block_post2', 'tumblr3_block_post_n' );
add_shortcode( 'block_post3', 'tumblr3_block_post_n' );
add_shortcode( 'block_post4', 'tumblr3_block_post_n' );
add_shortcode( 'block_post5', 'tumblr3_block_post_n' );
add_shortcode( 'block_post6', 'tumblr3_block_post_n' );
add_shortcode( 'block_post7', 'tumblr3_block_post_n' );
add_shortcode( 'block_post8', 'tumblr3_block_post_n' );
add_shortcode( 'block_post9', 'tumblr3_block_post_n' );
add_shortcode( 'block_post10', 'tumblr3_block_post_n' );
add_shortcode( 'block_post11', 'tumblr3_block_post_n' );
add_shortcode( 'block_post12', 'tumblr3_block_post_n' );
add_shortcode( 'block_post13', 'tumblr3_block_post_n' );
add_shortcode( 'block_post14', 'tumblr3_block_post_n' );
add_shortcode( 'block_post15', 'tumblr3_block_post_n' );

/**
 * Render content if the current post is an odd post in the loop.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_odd( $atts, $content = '' ): string {
	global $wp_query;

	// Check if in the loop and if the current post index is odd
	return ( in_the_loop() && ( $wp_query->current_post % 2 ) !== 0 ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_odd', 'tumblr3_block_odd' );

/**
 * Render content if the current post is an even post in the loop.
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_even( $atts, $content = '' ): string {
	global $wp_query;

	// Check if in the loop and if the current post index is even
	return ( in_the_loop() && ( $wp_query->current_post % 2 ) === 0 ) ? tumblr3_do_shortcode( $content ) : '';
}
add_shortcode( 'block_even', 'tumblr3_block_even' );

/**
 * Test if the blog has featured tags available.
 *
 * @see https://github.com/Automattic/featured-tags
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_hasfeaturedtags( $atts, $content = '' ): string {
	$output = '';
	$tags   = get_terms(
		array(
			'taxonomy'   => 'post_tag',
			'meta_key'   => 'featured',
			'meta_value' => '1',
		)
	);

	if ( ! empty( $tags ) ) {
		tumblr3_set_parse_context( 'hasfeaturedtags', $tags );
		$output = tumblr3_do_shortcode( $content );
		tumblr3_set_parse_context( 'theme', true );
	}

	return $output;
}
add_shortcode( 'block_hasfeaturedtags', 'tumblr3_block_hasfeaturedtags' );

/**
 * If the blog has featured tags, render each of them.
 *
 * @see https://github.com/Automattic/featured-tags
 *
 * @param array  $atts    The attributes of the shortcode.
 * @param string $content The content of the shortcode.
 *
 * @return string
 */
function tumblr3_block_featuredtags( $atts, $content = '' ): string {
	$context = tumblr3_get_parse_context();
	$output  = '';

	if ( ! isset( $context['hasfeaturedtags'] ) ) {
		return '';
	}

	foreach ( $context['hasfeaturedtags'] as $term ) {
		tumblr3_set_parse_context( 'term', $term );
		$output .= tumblr3_do_shortcode( $content );
	}

	tumblr3_set_parse_context( 'theme', true );

	return $output;
}
add_shortcode( 'block_featuredtags', 'tumblr3_block_featuredtags' );
