<?php
/**
 * Individual tag functions.
 *
 * @package TumblrThemeGarden
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles inline replacement of lang: tags.
 *
 * @see \CupcakeLabs\TumblrThemeGarden\CLTTG_Parser
 *
 * @param array $atts Array of shortcode attributes.
 *
 * @return string
 */
function ttgarden_tag_lang( $atts ): string {
	// Parse shortcode attributes.
	$atts = shortcode_atts(
		array(
			'key'   => '',
			'value' => '',
		),
		$atts,
		'tag_lang'
	);

	$plugin   = ttgarden_get_plugin_instance();
	$keywords = $plugin->parser->supported_keywords;
	$stack    = array();

	// Return nothing if we don't have a key or value.
	if ( empty( $atts['key'] ) && empty( $atts['value'] ) ) {
		return '';
	}

	// If a value is provided, return it.
	if ( ! empty( $atts['value'] ) ) {
		return $atts['value'];
	}

	// Find keywords in the key and add them to the stack.
	foreach ( $keywords as $keyword => $callback ) {
		$pos = strpos( $atts['key'], $keyword );
		if ( false !== $pos ) {
			$stack[ $pos ] = $callback;
		}
	}

	// If stack is empty at this point, the language string is not supported.
	if ( empty( $stack ) ) {
		return '';
	}

	// Order the stack by key.
	ksort( $stack );

	// Buffer the stack with empty string callbacks.
	$stack[] = '__return_empty_string';
	$stack[] = '__return_empty_string';

	// If keywords are found, return the language string with keyword replacements.
	return vsprintf(
		( is_array( CLTTG_LANG[ $atts['key'] ] ) ) ? CLTTG_LANG[ $atts['key'] ][1] : CLTTG_LANG[ $atts['key'] ],
		array_map(
			function ( $callback ) {
				return call_user_func( $callback );
			},
			$stack
		)
	);
}
add_shortcode( 'tag_lang', 'ttgarden_tag_lang' );

/**
 * Outputs target attribute for links.
 *
 * @return string
 */
function ttgarden_tag_target(): string {
	return get_theme_mod( 'target_blank' ) ? 'target="_blank"' : '';
}
add_shortcode( 'tag_target', 'ttgarden_tag_target' );

/**
 * Returns the NPF JSON string of the current post.
 *
 * @return string The NPF JSON representation of the current post content.
 */
function ttgarden_tag_npf(): string {
	// Get the current post content
	$post_content = get_the_content();

	// Create an instance of the Blocks2Npf class
	$converter = new CupcakeLabs\RedVelvet\Converter\Blocks2Npf();

	// Convert the content to NPF format
	return $converter->convert( $post_content );
}
add_shortcode( 'tag_npf', 'ttgarden_tag_npf' );

/**
 * The author name of the current post.
 *
 * @return string Post author name.
 */
function ttgarden_tag_postauthorname(): string {
	return get_the_author();
}
add_shortcode( 'tag_postauthorname', 'ttgarden_tag_postauthorname' );
add_shortcode( 'tag_author', 'ttgarden_tag_postauthorname' );

/**
 * Returns the group member display name.
 *
 * @return string Username or empty.
 */
function ttgarden_tag_groupmembername(): string {
	$context = ttgarden_get_parse_context();

	if ( isset( $context['groupmember'] ) && is_a( $context['groupmember'], 'WP_User' ) ) {
		return $context['groupmember']->user_nicename;
	}

	return '';
}
add_shortcode( 'tag_groupmembername', 'ttgarden_tag_groupmembername' );

/**
 * The URL of the group members posts page.
 *
 * @return string The URL of the group member.
 */
function ttgarden_tag_groupmemberurl(): string {
	$context = ttgarden_get_parse_context();

	if ( isset( $context['groupmember'] ) && is_a( $context['groupmember'], 'WP_User' ) ) {
		return get_author_posts_url( $context['groupmember']->ID );
	}

	return '';
}
add_shortcode( 'tag_groupmemberurl', 'ttgarden_tag_groupmemberurl' );

/**
 * Gets the group member portrait URL.
 *
 * @param array  $atts           Shortcode attributes.
 * @param string $content        Shortcode content.
 * @param string $shortcode_name Shortcode name.
 *
 * @return string The URL of the group member avatar.
 */
function ttgarden_tag_groupmemberportraiturl( $atts, $content, $shortcode_name ): string {
	$size = str_replace( 'tag_groupmemberportraiturl-', '', $shortcode_name );

	$context = ttgarden_get_parse_context();

	if ( isset( $context['groupmember'] ) && is_a( $context['groupmember'], 'WP_User' ) ) {
		$groupmember_avatar = get_avatar_url(
			$context['groupmember']->ID,
			array(
				'size' => $size,
			)
		);

		if ( ! $groupmember_avatar ) {
			return '';
		}

		return esc_url( $groupmember_avatar );
	}

	return '';
}
add_shortcode( 'tag_groupmemberportraiturl-16', 'ttgarden_tag_groupmemberportraiturl' );
add_shortcode( 'tag_groupmemberportraiturl-24', 'ttgarden_tag_groupmemberportraiturl' );
add_shortcode( 'tag_groupmemberportraiturl-30', 'ttgarden_tag_groupmemberportraiturl' );
add_shortcode( 'tag_groupmemberportraiturl-40', 'ttgarden_tag_groupmemberportraiturl' );
add_shortcode( 'tag_groupmemberportraiturl-48', 'ttgarden_tag_groupmemberportraiturl' );
add_shortcode( 'tag_groupmemberportraiturl-64', 'ttgarden_tag_groupmemberportraiturl' );
add_shortcode( 'tag_groupmemberportraiturl-96', 'ttgarden_tag_groupmemberportraiturl' );
add_shortcode( 'tag_groupmemberportraiturl-128', 'ttgarden_tag_groupmemberportraiturl' );

/**
 * The blog title of the post author.
 *
 * @return string
 */
function ttgarden_tag_postauthortitle(): string {
	return esc_attr( get_bloginfo( 'name' ) );
}
add_shortcode( 'tag_postauthortitle', 'ttgarden_tag_postauthortitle' );
add_shortcode( 'tag_groupmembertitle', 'ttgarden_tag_postauthortitle' );
add_shortcode( 'tag_postblogname', 'ttgarden_tag_postauthortitle' );

/**
 * The URL of the post author.
 *
 * @return string URL to the author archive.
 */
function ttgarden_tag_postauthorurl(): string {
	return esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
}
add_shortcode( 'tag_postauthorurl', 'ttgarden_tag_postauthorurl' );

/**
 * The portrait URL of the post author.
 *
 * @param array  $atts           The attributes of the shortcode.
 * @param string $content        The content of the shortcode.
 * @param string $shortcode_name The name of the shortcode.
 *
 * @return string The URL of the author portrait.
 */
function ttgarden_tag_postauthorportraiturl( $atts, $content, $shortcode_name ): string {
	$size      = str_replace( 'tag_postauthorportraiturl-', '', $shortcode_name );
	$author_id = get_the_author_meta( 'ID' );
	$author    = get_user_by( 'ID', $author_id );

	if ( ! $author ) {
		return '';
	}

	$author_avatar = get_avatar_url(
		$author_id,
		array(
			'size' => $size,
		)
	);

	if ( ! $author_avatar ) {
		return '';
	}

	return esc_url( $author_avatar );
}
add_shortcode( 'tag_postauthorportraiturl-16', 'ttgarden_tag_postauthorportraiturl' );
add_shortcode( 'tag_postauthorportraiturl-24', 'ttgarden_tag_postauthorportraiturl' );
add_shortcode( 'tag_postauthorportraiturl-30', 'ttgarden_tag_postauthorportraiturl' );
add_shortcode( 'tag_postauthorportraiturl-40', 'ttgarden_tag_postauthorportraiturl' );
add_shortcode( 'tag_postauthorportraiturl-48', 'ttgarden_tag_postauthorportraiturl' );
add_shortcode( 'tag_postauthorportraiturl-64', 'ttgarden_tag_postauthorportraiturl' );
add_shortcode( 'tag_postauthorportraiturl-96', 'ttgarden_tag_postauthorportraiturl' );
add_shortcode( 'tag_postauthorportraiturl-128', 'ttgarden_tag_postauthorportraiturl' );

/**
 * Outputs the twitter username theme option.
 *
 * @return string Attribute safe twitter username.
 */
function ttgarden_tag_twitterusername(): string {
	return esc_attr( get_theme_mod( 'twitter_username' ) );
}
add_shortcode( 'tag_twitterusername', 'ttgarden_tag_twitterusername' );

/**
 * The current state of a page in nav.
 * E.g is this the current page?
 *
 * @return string
 */
function ttgarden_tag_currentstate(): string {
	return get_the_permalink() === home_url( add_query_arg( null, null ) ) ? 'current-page' : '';
}
add_shortcode( 'tag_currentstate', 'ttgarden_tag_currentstate' );
add_shortcode( 'tag_externalstate', 'ttgarden_tag_currentstate' );

/**
 * The display shape of your avatar ("circle" or "square").
 *
 * @return string Either "circle" or "square".
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_avatarshape(): string {
	return esc_html( get_theme_mod( 'avatar_shape', 'circle' ) );
}
add_shortcode( 'tag_avatarshape', 'ttgarden_tag_avatarshape' );

/**
 * The background color of your blog.
 *
 * @return string The background colour in HEX format.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_backgroundcolor(): string {
	return '#' . sanitize_hex_color_no_hash( get_theme_mod( 'background_color', '#fff' ) );
}
add_shortcode( 'tag_backgroundcolor', 'ttgarden_tag_backgroundcolor' );

/**
 * The accent color of your blog.
 *
 * @return string The accent colour in HEX format.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_accentcolor(): string {
	return '#' . sanitize_hex_color_no_hash( get_theme_mod( 'accent_color', '#0073aa' ) );
}
add_shortcode( 'tag_accentcolor', 'ttgarden_tag_accentcolor' );

/**
 * The title color of your blog.
 *
 * @return string The title colour in HEX format.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_titlecolor(): string {
	return '#' . sanitize_hex_color_no_hash( get_theme_mod( 'header_textcolor', '#000' ) );
}
add_shortcode( 'tag_titlecolor', 'ttgarden_tag_titlecolor' );

/**
 * Get the title font theme option.
 *
 * @return string The title fontname.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_titlefont(): string {
	return esc_html( get_theme_mod( 'title_font', 'Arial' ) );
}
add_shortcode( 'tag_titlefont', 'ttgarden_tag_titlefont' );

/**
 * The weight of your title font ("normal" or "bold").
 *
 * @return string Either "bold" or "normal".
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_titlefontweight(): string {
	return esc_html( get_theme_mod( 'title_font_weight', 'bold' ) );
}
add_shortcode( 'tag_titlefontweight', 'ttgarden_tag_titlefontweight' );

/**
 * Get the header image theme option.
 *
 * @return string Either "remove-header" or the URL of the header image.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_headerimage(): string {
	return get_theme_mod( 'header_image', 'remove-header' );
}
add_shortcode( 'tag_headerimage', 'ttgarden_tag_headerimage' );

/**
 * Get either a post title, or the blog title.
 *
 * @return string The title of the post or the blog.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_title(): string {
	$context = ttgarden_get_parse_context();

	// Consume global context and return the appropriate title.
	return ( isset( $context['theme'] ) ) ? get_bloginfo( 'name' ) : get_the_title();
}
add_shortcode( 'tag_title', 'ttgarden_tag_title' );
add_shortcode( 'tag_posttitle', 'ttgarden_tag_title' );

/**
 * The post content.
 *
 * @return string The content of the post with filters applied.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_body(): string {
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- WP core function.
	return apply_filters( 'the_content', get_the_content() );
}
add_shortcode( 'tag_body', 'ttgarden_tag_body' );

/**
 * The post content.
 *
 * @return string The content of the post with filters applied.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_excerpt(): string {
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- WP core function.
	return wp_strip_all_tags( apply_filters( 'the_content', get_the_content() ) );
}
add_shortcode( 'tag_excerpt', 'ttgarden_tag_excerpt' );
add_shortcode( 'tag_sharestring', 'ttgarden_tag_excerpt' );

/**
 * The blog description, or subtitle.
 *
 * @return string The blog description.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_description(): string {
	$context = ttgarden_get_parse_context();

	// This tag is used as the_content for audio posts.
	if ( isset( $context['audio'] ) ) {
		$blocks = parse_blocks( get_the_content() );

		// Remove audio blocks from the content.
		foreach ( $blocks as $key => $block ) {
			if ( 'core/audio' === $block['blockName'] ) {
				unset( $blocks[ $key ] );
			}
		}

		// Re-build the content without audio blocks.
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- WP core function.
		return apply_filters( 'the_content', serialize_blocks( $blocks ) );
	}

	// By default, return the blog description.
	// We decode the HTML entities to allow for some allowed HTML tags to be rendered
	return wp_kses_post( wp_specialchars_decode( get_bloginfo( 'description' ) ) );
}
add_shortcode( 'tag_description', 'ttgarden_tag_description' );

/**
 * Attribute safe blog description.
 *
 * @return string The blog description with HTML entities encoded.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_metadescription(): string {
	return esc_attr( get_bloginfo( 'description' ) );
}
add_shortcode( 'tag_metadescription', 'ttgarden_tag_metadescription' );

/**
 * The homepage URL of the blog.
 *
 * @return string The URL of the blog.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_blogurl(): string {
	return esc_url( home_url( '/' ) );
}
add_shortcode( 'tag_blogurl', 'ttgarden_tag_blogurl' );

/**
 * The RSS feed URL of the blog.
 *
 * @return string The URL of the blog RSS feed.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_rss(): string {
	return esc_url( get_feed_link() );
}
add_shortcode( 'tag_rss', 'ttgarden_tag_rss' );

/**
 * The site favicon image URL.
 *
 * @return string The URL of the site favicon.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_favicon(): string {
	return esc_url( get_site_icon_url() );
}
add_shortcode( 'tag_favicon', 'ttgarden_tag_favicon' );

/**
 * The portrait URL of the blog, uses the custom logo if set.
 *
 * @param array  $atts           The attributes of the shortcode.
 * @param string $content        The content of the shortcode.
 * @param string $shortcode_name The name of the shortcode.
 *
 * @return string The URL of the blog portrait.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_portraiturl( $atts, $content, $shortcode_name ): string {
	if ( ! has_custom_logo() ) {
		return '';
	}

	$size            = str_replace( 'tag_portraiturl-', '', $shortcode_name );
	$custom_logo_id  = get_theme_mod( 'custom_logo' );
	$custom_logo_src = wp_get_attachment_image_src(
		$custom_logo_id,
		array(
			$size,
			$size,
		)
	);

	if ( ! $custom_logo_src ) {
		return '';
	}

	return esc_url( $custom_logo_src[0] );
}
add_shortcode( 'tag_portraiturl-16', 'ttgarden_tag_portraiturl' );
add_shortcode( 'tag_portraiturl-24', 'ttgarden_tag_portraiturl' );
add_shortcode( 'tag_portraiturl-30', 'ttgarden_tag_portraiturl' );
add_shortcode( 'tag_portraiturl-40', 'ttgarden_tag_portraiturl' );
add_shortcode( 'tag_portraiturl-48', 'ttgarden_tag_portraiturl' );
add_shortcode( 'tag_portraiturl-64', 'ttgarden_tag_portraiturl' );
add_shortcode( 'tag_portraiturl-96', 'ttgarden_tag_portraiturl' );
add_shortcode( 'tag_portraiturl-128', 'ttgarden_tag_portraiturl' );

/**
 * Returns the custom CSS option of the theme.
 *
 * @return string The custom CSS of the theme.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_customcss(): string {
	return esc_html( wp_get_custom_css() );
}
add_shortcode( 'tag_customcss', 'ttgarden_tag_customcss' );

/**
 * Identical to {PostTitle}, but will automatically generate a summary if a title doesn't exist.
 *
 * @return string The post title or summary.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_postsummary(): string {
	$title = get_the_title();
	return ( '' === $title ) ? $title : get_the_excerpt();
}
add_shortcode( 'tag_postsummary', 'ttgarden_tag_postsummary' );

/**
 * Character limited version of {PostSummary} that is suitable for Twitter.
 *
 * @return string The post summary limited to 280 characters.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_tweetsummary(): string {
	return esc_html( substr( ttgarden_tag_postsummary(), 0, 280 ) );
}
add_shortcode( 'tag_tweetsummary', 'ttgarden_tag_tweetsummary' );
add_shortcode( 'tag_mailsummary', 'ttgarden_tag_tweetsummary' );

/**
 * Various contextual uses, typically outputs a post permalink.
 *
 * @return string The URL of the post.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_url(): string {
	$context = ttgarden_get_parse_context();

	// Link post format.
	if ( isset( $context['link'], $context['link']['url'] ) ) {
		return esc_url( $context['link']['url'] );
	}

	// Handle the jump pagination context for this tag.
	if ( isset( $context['jumppagination'] ) ) {
		return '/page/' . intval( $context['jumppagination'] );
	}

	return get_permalink();
}
add_shortcode( 'tag_url', 'ttgarden_tag_url' );
add_shortcode( 'tag_permalink', 'ttgarden_tag_url' );
add_shortcode( 'tag_relativepermalink', 'ttgarden_tag_url' );
add_shortcode( 'tag_shorturl', 'ttgarden_tag_url' );
add_shortcode( 'tag_embedurl', 'ttgarden_tag_url' );

/**
 * Typically a page title, used in a page loop e.g navigation.
 * Also used as the Chat label for legacy chat posts.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 * @see https://www.tumblr.com/docs/en/custom_themes#chat-posts
 */
function ttgarden_tag_label(): string {
	$context = ttgarden_get_parse_context();

	if ( ! isset( $context['chat']['label'] ) ) {
		// By default, return the page title.
		return wp_kses_post( get_the_title() );
	}

	return $context['chat']['label'];
}
add_shortcode( 'tag_label', 'ttgarden_tag_label' );

/**
 * Current line of a legacy chat post.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#chat-posts
 */
function ttgarden_tag_line(): string {
	$context = ttgarden_get_parse_context();

	// Check if we are in a chat context.
	if ( ! isset( $context['chat']['line'] ) ) {
		return '';
	}

	return $context['chat']['line'];
}
add_shortcode( 'tag_line', 'ttgarden_tag_line' );

/**
 * Tagsasclasses outputs the tags of a post as HTML-safe classes.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tagsasclasses(): string {
	$tags = get_the_tags();

	if ( ! $tags || is_wp_error( $tags ) ) {
		return '';
	}

	$classes = array();
	foreach ( $tags as $tag ) {
		$classes[] = esc_attr( $tag->name );
	}

	return implode( ' ', $classes );
}
add_shortcode( 'tag_tagsasclasses', 'ttgarden_tagsasclasses' );

/**
 * Label in post footer indicating this is a pinned post.
 *
 * @return string The label for a pinned post.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_pinnedpostlabel(): string {
	return esc_html( CLTTG_LANG['lang:pinned post'] );
}
add_shortcode( 'tag_pinnedpostlabel', 'ttgarden_tag_pinnedpostlabel' );

/**
 * Gets the previous post URL (single post pagination)
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_previouspost(): string {
	return untrailingslashit( esc_url( get_permalink( get_adjacent_post( false, '', true ) ) ) );
}
add_shortcode( 'tag_previouspost', 'ttgarden_tag_previouspost' );

/**
 * Gets the next post URL (single post pagination)
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_nextpost(): string {
	return untrailingslashit( esc_url( get_permalink( get_adjacent_post( false, '', false ) ) ) );
}
add_shortcode( 'tag_nextpost', 'ttgarden_tag_nextpost' );

/**
 * Gets the previous posts page URL (pagination)
 *
 * @return string|null
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_previouspage(): string|null {
	return untrailingslashit( esc_url( get_previous_posts_page_link() ) );
}
add_shortcode( 'tag_previouspage', 'ttgarden_tag_previouspage' );

/**
 * Gets the next posts page URL (pagination)
 *
 * @return string|null
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_nextpage(): string|null {
	return untrailingslashit( esc_url( get_next_posts_page_link() ) );
}
add_shortcode( 'tag_nextpage', 'ttgarden_tag_nextpage' );

/**
 * Gets the current page value (pagination)
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_currentpage(): string {
	$page = get_query_var( 'paged' );
	return ( $page > 0 ) ? (string) $page : '1';
}
add_shortcode( 'tag_currentpage', 'ttgarden_tag_currentpage' );

/**
 * The pagenumber tag inside jump pagination.
 *
 * @return string
 */
function ttgarden_tag_pagenumber(): string {
	$context = ttgarden_get_parse_context();
	return isset( $context['jumppagination'] ) ? (string) $context['jumppagination'] : '';
}
add_shortcode( 'tag_pagenumber', 'ttgarden_tag_pagenumber' );

/**
 * Gets the query total pages (pagination)
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_totalpages(): string {
	global $wp_query;
	return ( $wp_query->max_num_pages > 0 ) ? (string) $wp_query->max_num_pages : '1';
}
add_shortcode( 'tag_totalpages', 'ttgarden_tag_totalpages' );

/**
 * Displays the span of years your blog has existed.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_copyrightyears(): string {
	// Get the oldest post.
	$oldest_post = get_posts(
		array(
			'numberposts' => 1,
			'orderby'     => 'date',
			'order'       => 'ASC',
			'fields'      => 'ids',
		)
	);

	if ( empty( $oldest_post ) ) {
		return '';
	}

	return get_the_date( 'Y', $oldest_post[0] ) . '-' . gmdate( 'Y' );
}
add_shortcode( 'tag_copyrightyears', 'ttgarden_tag_copyrightyears' );

/**
 * The numeric ID for a post.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_postid(): string {
	return esc_attr( get_the_ID() );
}
add_shortcode( 'tag_postid', 'ttgarden_tag_postid' );

/**
 * The name of the current legacy post type.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_posttype(): string {
	$format = get_post_format();
	return ( $format ) ? $format : 'text';
}
add_shortcode( 'tag_posttype', 'ttgarden_tag_posttype' );

/**
 * Current tag name in a loop.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_tag(): string {
	$context = ttgarden_get_parse_context();

	// Check if we are in a tag context.
	if ( ! isset( $context['term'] ) || ! is_a( $context['term'], 'WP_Term' ) ) {
		return '';
	}

	return $context['term']->name;
}
add_shortcode( 'tag_tag', 'ttgarden_tag_tag' );

/**
 * Current tag name in a loop.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_urlsafetag(): string {
	$context = ttgarden_get_parse_context();

	// Check if we are in a tag context.
	if ( ! isset( $context['term'] ) || ! is_a( $context['term'], 'WP_Term' ) ) {
		return '';
	}

	return rawurlencode( $context['term']->name );
}
add_shortcode( 'tag_urlsafetag', 'ttgarden_tag_urlsafetag' );

/**
 * Current tag url in a loop.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_tagurl(): string {
	$context = ttgarden_get_parse_context();

	// Check if we are in a tag context.
	if ( ! isset( $context['term'] ) || ! is_a( $context['term'], 'WP_Term' ) ) {
		return '';
	}

	return get_term_link( $context['term'] );
}
add_shortcode( 'tag_tagurl', 'ttgarden_tag_tagurl' );
add_shortcode( 'tag_tagurlchrono', 'ttgarden_tag_tagurl' );

/**
 * The total number of comments on a post.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_notecount(): int {
	return (int) get_comments_number();
}
add_shortcode( 'tag_notecount', 'ttgarden_tag_notecount' );

/**
 * The total number of comments on a post in text form.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_notecountwithlabel(): string {
	return get_comments_number_text();
}
add_shortcode( 'tag_notecountwithlabel', 'ttgarden_tag_notecountwithlabel' );
add_shortcode( 'tag_formattednotecount', 'ttgarden_tag_notecountwithlabel' );

/**
 * The post comments.
 *
 * @param array $atts The attributes of the shortcode.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_postnotes( $atts ): string {
	// Parse shortcode attributes.
	$atts = shortcode_atts(
		array(
			'size' => '',
		),
		$atts,
		'tag_postnotes'
	);

	ob_start();

	comments_template();

	$comments = ob_get_contents();
	ob_end_clean();

	return $comments;
}
add_shortcode( 'tag_postnotes', 'ttgarden_tag_postnotes' );
add_shortcode( 'tag_postnotes-16', 'ttgarden_tag_postnotes' );
add_shortcode( 'tag_postnotes-64', 'ttgarden_tag_postnotes' );

/**
 * The current search query.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_searchquery(): string {
	return esc_html( get_search_query() );
}
add_shortcode( 'tag_searchquery', 'ttgarden_tag_searchquery' );

/**
 * The current search query URL encoded.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_urlsafesearchquery(): string {
	return rawurlencode( get_search_query() );
}
add_shortcode( 'tag_urlsafesearchquery', 'ttgarden_tag_urlsafesearchquery' );

/**
 * The found posts count of the search result.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_searchresultcount(): string {
	global $wp_query;
	return $wp_query->found_posts;
}
add_shortcode( 'tag_searchresultcount', 'ttgarden_tag_searchresultcount' );

/**
 * Quote post content.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_quote(): string {
	$context = ttgarden_get_parse_context();

	// Test if the current context is a quote post and has a source.
	if ( isset( $context['quote'], $context['quote']['quote'] ) ) {
		return $context['quote']['quote'];
	}

	// Empty string if no quote block is found.
	return '';
}
add_shortcode( 'tag_quote', 'ttgarden_tag_quote' );

/**
 * Quote post source.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_source(): string {
	$context = ttgarden_get_parse_context();

	// Test if the current context is a quote post and has a source.
	if ( isset( $context['quote'], $context['quote']['source'] ) ) {
		return $context['quote']['source'];
	}

	return '';
}
add_shortcode( 'tag_source', 'ttgarden_tag_source' );

/**
 * Quote content length.
 * "short", "medium", "long"
 *
 * @return string
 *
 * @see https://github.tumblr.net/Tumblr/tumblr/blob/046755128a6d61010fcaf4459f8efdc895140ad0/app/models/post.php#L7459
 */
function ttgarden_tag_length(): string {
	$context = ttgarden_get_parse_context();

	// Test if the current context is a quote post and has a length.
	if ( isset( $context['quote'], $context['quote']['length'] ) ) {
		$length = $context['quote']['length'];

		if ( $length < 100 ) {
			return 'short';
		} elseif ( $length < 250 ) {
			return 'medium';
		}
	}

	// Default to long.
	return 'long';
}
add_shortcode( 'tag_length', 'ttgarden_tag_length' );

/**
 * Audioplayer HTML.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_audioplayer(): string {
	$context = ttgarden_get_parse_context();

	// Test if the current context is an audio post and has a player.
	if ( isset( $context['audio'], $context['audio']['player'] ) ) {
		return $context['audio']['player'];
	}

	return '';
}
add_shortcode( 'tag_audioplayer', 'ttgarden_tag_audioplayer' );
add_shortcode( 'tag_audioembed', 'ttgarden_tag_audioplayer' );
add_shortcode( 'tag_audioembed-640', 'ttgarden_tag_audioplayer' );
add_shortcode( 'tag_audioembed-500', 'ttgarden_tag_audioplayer' );
add_shortcode( 'tag_audioembed-400', 'ttgarden_tag_audioplayer' );
add_shortcode( 'tag_audioembed-250', 'ttgarden_tag_audioplayer' );
add_shortcode( 'tag_audioplayerblack', 'ttgarden_tag_audioplayer' );
add_shortcode( 'tag_audioplayerwhite', 'ttgarden_tag_audioplayer' );
add_shortcode( 'tag_audioplayergrey', 'ttgarden_tag_audioplayer' );

/**
 * Album art URL, uses the featured image if available.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_albumarturl(): string {
	$context = ttgarden_get_parse_context();

	// Test if the current context is an audio post and has a player.
	if ( isset( $context['audio'], $context['audio']['art'] ) ) {
		return $context['audio']['art'];
	}

	return '';
}
add_shortcode( 'tag_albumarturl', 'ttgarden_tag_albumarturl' );

/**
 * Renders the audio player track name.
 *
 * @return string
 */
function ttgarden_tag_trackname(): string {
	$context = ttgarden_get_parse_context();

	// Test if the current context is an audio post and has a player.
	if ( isset( $context['audio'], $context['audio']['trackname'] ) ) {
		return $context['audio']['trackname'];
	}

	return '';
}
add_shortcode( 'tag_trackname', 'ttgarden_tag_trackname' );

/**
 * Renders the audio player artist name.
 *
 * @return string
 */
function ttgarden_tag_artist(): string {
	$context = ttgarden_get_parse_context();

	// Test if the current context is an audio post and has a player.
	if ( isset( $context['audio'], $context['audio']['artist'] ) ) {
		return $context['audio']['artist'];
	}

	return '';
}
add_shortcode( 'tag_artist', 'ttgarden_tag_artist' );

/**
 * Renders the audio player album name.
 *
 * @return string
 */
function ttgarden_tag_album(): string {
	$context = ttgarden_get_parse_context();

	// Test if the current context is an audio post and has a player.
	if ( isset( $context['audio'], $context['audio']['album'] ) ) {
		return $context['audio']['album'];
	}

	return '';
}
add_shortcode( 'tag_album', 'ttgarden_tag_album' );

/**
 * Renders the audio player media URL if it's external.
 *
 * @return string
 */
function ttgarden_tag_externalaudiourl(): string {
	$context = ttgarden_get_parse_context();

	// Test if the current context is an audio post and has a player.
	if ( isset( $context['audio'], $context['audio']['player'] ) ) {
		$processor = new CupcakeLabs\TumblrThemeGarden\CLTTG_Processor( $context['audio']['player'] );

		while ( $processor->next_tag( 'AUDIO' ) ) {
			$src = $processor->get_attribute( 'SRC' );

			if ( $src ) {
				return esc_url( $src );
			}
		}
	}

	return '';
}
add_shortcode( 'tag_externalaudiourl', 'ttgarden_tag_externalaudiourl' );
add_shortcode( 'tag_rawaudiourl', 'ttgarden_tag_externalaudiourl' );

/**
 * Renders the post gallery if one was found.
 *
 * @return string
 */
function ttgarden_tag_photoset(): string {
	$context = ttgarden_get_parse_context();

	// Return nothing if no gallery is found.
	if ( ! isset( $context['gallery']['gallery'] ) || empty( $context['gallery']['gallery'] ) ) {
		return '';
	}

	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- WP core function.
	return apply_filters( 'the_content', $context['gallery']['gallery'] );
}
add_shortcode( 'tag_photoset', 'ttgarden_tag_photoset' );
add_shortcode( 'tag_photoset-700', 'ttgarden_tag_photoset' );
add_shortcode( 'tag_photoset-540', 'ttgarden_tag_photoset' );
add_shortcode( 'tag_photoset-500', 'ttgarden_tag_photoset' );
add_shortcode( 'tag_photoset-400', 'ttgarden_tag_photoset' );
add_shortcode( 'tag_photoset-250', 'ttgarden_tag_photoset' );
add_shortcode( 'tag_photoset-100', 'ttgarden_tag_photoset' );

/**
 * Renders the post gallery layout if one was found.
 *
 * @return string
 */
function ttgarden_tag_photosetlayout(): string {
	return ttgarden_tag_photocount();
}
add_shortcode( 'tag_photosetlayout', 'ttgarden_tag_photosetlayout' );

/**
 * Renders the post gallery photo count if one was found.
 *
 * @return string
 */
function ttgarden_tag_photocount(): string {
	$context = ttgarden_get_parse_context();

	// Return nothing if no gallery is found.
	if ( ! isset( $context['gallery']['photocount'] ) ) {
		return '';
	}

	return esc_html( $context['gallery']['photocount'] );
}
add_shortcode( 'tag_photocount', 'ttgarden_tag_photocount' );
add_shortcode( 'tag_photosetlayout', 'ttgarden_tag_photocount' );

/**
 * Renders the post gallery caption if one was found.
 *
 * @return string
 */
function ttgarden_tag_caption(): string {
	$context = ttgarden_get_parse_context();
	$format  = get_post_format();

	if ( ! isset( $context[ $format ], $context[ $format ]['caption'] ) ) {
		return '';
	}

	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- WP core function.
	return apply_filters( 'the_content', $context[ $format ]['caption'] );
}
add_shortcode( 'tag_caption', 'ttgarden_tag_caption' );

/**
 * Renders the post image URL if one was found.
 *
 * @param array  $atts           The attributes of the shortcode.
 * @param string $content        The content of the shortcode.
 * @param string $shortcode_name The name of the shortcode.
 *
 * @return string
 */
function ttgarden_tag_photourl( $atts, $content, $shortcode_name ): string {
	// Parse shortcode attributes.
	$atts = shortcode_atts(
		array(
			'size' => '',
		),
		$atts,
		$shortcode_name
	);

	$context = ttgarden_get_parse_context();

	// Return nothing if no image is found.
	if ( ! isset( $context['image']['image'] ) ) {
		return '';
	}

	return esc_url( $context['image']['image'] );
}
add_shortcode( 'tag_photourl-500', 'ttgarden_tag_photourl' );
add_shortcode( 'tag_photourl-400', 'ttgarden_tag_photourl' );
add_shortcode( 'tag_photourl-250', 'ttgarden_tag_photourl' );
add_shortcode( 'tag_photourl-100', 'ttgarden_tag_photourl' );
add_shortcode( 'tag_photourl-highres', 'ttgarden_tag_photourl' );
add_shortcode( 'tag_photourl-75sq', 'ttgarden_tag_photourl' );

/**
 * Renders the post image thumbnail URL if one was found.
 *
 * @param array  $atts           The attributes of the shortcode.
 * @param string $content        The content of the shortcode.
 * @param string $shortcode_name The name of the shortcode.
 *
 * @return string
 */
function ttgarden_tag_thumbnail( $atts, $content, $shortcode_name ): string {
	$sizes = array(
		'tag_thumbnail'         => 'thumbnail',
		'tag_thumbnail-highres' => 'full',
	);

	return get_the_post_thumbnail_url( get_the_id(), $sizes[ $shortcode_name ] );
}
add_shortcode( 'tag_thumbnail', 'ttgarden_tag_thumbnail' );
add_shortcode( 'tag_thumbnail-highres', 'ttgarden_tag_thumbnail' );

/**
 * Renders the post image link URL if one was found.
 *
 * @todo Hook up lightbox and custom link contexts.
 *
 * @return string
 */
function ttgarden_tag_linkurl(): string {
	$context = ttgarden_get_parse_context();

	if ( ! isset( $context['image']['link'] ) ) {
		return '';
	}

	// Links to attachment pages.
	if ( 'attachment' === $context['image']['link'] || 'media' === $context['image']['link'] ) {
		return get_permalink( $context['image']['image'] );
	}

	// Links to a custom URL.
	if ( 'custom' === $context['image']['link'] ) {
		return $context['image']['custom'];
	}

	// Links to lightbox.
	if ( true === $context['image']['lightbox'] ) {
		return wp_get_attachment_image_src( $context['image']['image'] )[0];
	}

	return '';
}
add_shortcode( 'tag_linkurl', 'ttgarden_tag_linkurl' );

/**
 * Renders the post image link open tag conditionally.
 *
 * @uses ttgarden_tag_linkurl()
 * @return string
 */
function ttgarden_tag_linkopentag(): string {
	$context = ttgarden_get_parse_context();

	return ( isset( $context['image']['link'] ) && 'none' !== $context['image']['link'] ) ? '<a href="' . ttgarden_tag_linkurl() . '">' : '';
}
add_shortcode( 'tag_linkopentag', 'ttgarden_tag_linkopentag' );

/**
 * Renders the post image link close tag conditionally.
 *
 * @return string
 */
function ttgarden_tag_linkclosetag(): string {
	$context = ttgarden_get_parse_context();

	return ( isset( $context['image']['link'] ) && 'none' !== $context['image']['link'] ) ? '</a>' : '';
}
add_shortcode( 'tag_linkclosetag', 'ttgarden_tag_linkclosetag' );

/**
 * Renders the post image camera exif data if found.
 *
 * @return string
 */
function ttgarden_tag_camera(): string {
	$context = ttgarden_get_parse_context();

	return isset( $context['image']['data']['image_meta']['camera'] ) ? esc_html( $context['image']['data']['image_meta']['camera'] ) : '';
}
add_shortcode( 'tag_camera', 'ttgarden_tag_camera' );

/**
 * Renders the post image lens exif data if found.
 *
 * @return string
 */
function ttgarden_tag_aperture(): string {
	$context = ttgarden_get_parse_context();

	return isset( $context['image']['data']['image_meta']['aperture'] ) ? esc_html( $context['image']['data']['image_meta']['aperture'] ) : '';
}
add_shortcode( 'tag_aperture', 'ttgarden_tag_aperture' );

/**
 * Renders the post image focal length exif data if found.
 *
 * @return string
 */
function ttgarden_tag_focallength(): string {
	$context = ttgarden_get_parse_context();

	return isset( $context['image']['data']['image_meta']['focal_length'] ) ? esc_html( $context['image']['data']['image_meta']['focal_length'] ) : '';
}
add_shortcode( 'tag_focallength', 'ttgarden_tag_focallength' );

/**
 * Renders the post image shutter speed exif data if found.
 *
 * @return string
 */
function ttgarden_tag_exposure(): string {
	$context = ttgarden_get_parse_context();

	return isset( $context['image']['data']['image_meta']['shutter_speed'] ) ? esc_html( $context['image']['data']['image_meta']['shutter_speed'] ) : '';
}
add_shortcode( 'tag_exposure', 'ttgarden_tag_exposure' );

/**
 * Renders the post image alt text if one was found.
 *
 * @return string
 */
function ttgarden_tag_photoalt(): string {
	$context = ttgarden_get_parse_context();

	if ( ! isset( $context['image']['image'] ) ) {
		return '';
	}

	return esc_attr( get_post_meta( $context['image']['image'], '_wp_attachment_image_alt', true ) );
}
add_shortcode( 'tag_photoalt', 'ttgarden_tag_photoalt' );

/**
 * Renders the post image width if one was found.
 *
 * @return string
 */
function ttgarden_tag_photowidth(): string {
	$context = ttgarden_get_parse_context();

	if ( ! isset( $context['image']['data'], $context['image']['data']['width'] ) ) {
		return '';
	}

	return (string) $context['image']['data']['width'];
}
add_shortcode( 'tag_photowidth-500', 'ttgarden_tag_photowidth' );
add_shortcode( 'tag_photowidth-400', 'ttgarden_tag_photowidth' );
add_shortcode( 'tag_photowidth-250', 'ttgarden_tag_photowidth' );
add_shortcode( 'tag_photowidth-100', 'ttgarden_tag_photowidth' );
add_shortcode( 'tag_photowidth-highres', 'ttgarden_tag_photowidth' );

/**
 * Renders the post image height if one was found.
 *
 * @return string
 */
function ttgarden_tag_photoheight(): string {
	$context = ttgarden_get_parse_context();

	if ( ! isset( $context['image']['data'], $context['image']['data']['height'] ) ) {
		return '';
	}

	return (string) $context['image']['data']['height'];
}
add_shortcode( 'tag_photoheight-500', 'ttgarden_tag_photoheight' );
add_shortcode( 'tag_photoheight-400', 'ttgarden_tag_photoheight' );
add_shortcode( 'tag_photoheight-250', 'ttgarden_tag_photoheight' );
add_shortcode( 'tag_photoheight-100', 'ttgarden_tag_photoheight' );
add_shortcode( 'tag_photoheight-highres', 'ttgarden_tag_photoheight' );

/**
 * Renders the post video player.
 *
 * @return string
 */
function ttgarden_tag_video(): string {
	$context = ttgarden_get_parse_context();

	// Test if the current context is a video post and has a player.
	if ( isset( $context['video'], $context['video']['player'] ) ) {
		return $context['video']['player'];
	}

	return '';
}
add_shortcode( 'tag_video-700', 'ttgarden_tag_video' );
add_shortcode( 'tag_video-540', 'ttgarden_tag_video' );
add_shortcode( 'tag_video-500', 'ttgarden_tag_video' );
add_shortcode( 'tag_video-400', 'ttgarden_tag_video' );
add_shortcode( 'tag_video-250', 'ttgarden_tag_video' );
add_shortcode( 'tag_videoembed-700', 'ttgarden_tag_video' );
add_shortcode( 'tag_videoembed-500', 'ttgarden_tag_video' );
add_shortcode( 'tag_videoembed-400', 'ttgarden_tag_video' );
add_shortcode( 'tag_videoembed-250', 'ttgarden_tag_video' );

/**
 * Renders the post video thumbnail URL.
 *
 * @return string
 */
function ttgarden_tag_videothumbnailurl(): string {
	$context = ttgarden_get_parse_context();

	// Test if the current context is a video post and has a player.
	if ( isset( $context['video'], $context['video']['thumbnail'] ) ) {
		return $context['video']['thumbnail'];
	}

	return '';
}
add_shortcode( 'tag_videothumbnailurl', 'ttgarden_tag_videothumbnailurl' );

/**
 * The link post type title (This is also the link URL).
 *
 * @return string
 */
function ttgarden_tag_name(): string {
	if ( 'link' === get_post_format() ) {
		return get_the_title( get_the_ID() );
	}

	return get_the_author();
}
add_shortcode( 'tag_name', 'ttgarden_tag_name' );

/**
 * Renders the link post host url.
 *
 * @return string
 */
function ttgarden_tag_host(): string {
	$context = ttgarden_get_parse_context();

	if ( isset( $context['theme'] ) ) {
		return esc_url( home_url( '/' ) );
	}

	$url = wp_http_validate_url( get_the_title() );

	// If this wasn't a valid URL, return an empty string.
	if ( false === $url ) {
		return '';
	}

	$parsed_url = wp_parse_url( $url );

	// Return the host of the URL.
	return esc_url( $parsed_url['host'] );
}
add_shortcode( 'tag_host', 'ttgarden_tag_host' );

/**
 * Returns the day of the month without leading zeros.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_dayofmonth(): string {
	return get_the_date( 'j' );
}
add_shortcode( 'tag_dayofmonth', 'ttgarden_tag_dayofmonth' );

/**
 * Returns the day of the month with leading zeros.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_dayofmonthwithzero(): string {
	return get_the_date( 'd' );
}
add_shortcode( 'tag_dayofmonthwithzero', 'ttgarden_tag_dayofmonthwithzero' );

/**
 * Returns the full name of the day of the week.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_dayofweek(): string {
	return get_the_date( 'l' );
}
add_shortcode( 'tag_dayofweek', 'ttgarden_tag_dayofweek' );

/**
 * Returns the abbreviated name of the day of the week.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_shortdayofweek(): string {
	return get_the_date( 'D' );
}
add_shortcode( 'tag_shortdayofweek', 'ttgarden_tag_shortdayofweek' );

/**
 * Returns the day of the week as a number (1 for Monday, 7 for Sunday).
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_dayofweeknumber(): string {
	return get_the_date( 'N' );
}
add_shortcode( 'tag_dayofweeknumber', 'ttgarden_tag_dayofweeknumber' );

/**
 * Returns the English ordinal suffix for the day of the month.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_dayofmonthsuffix(): string {
	return get_the_date( 'S' );
}
add_shortcode( 'tag_dayofmonthsuffix', 'ttgarden_tag_dayofmonthsuffix' );

/**
 * Returns the day of the month with the English ordinal suffix.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_dayofmonthwithsuffix(): string {
	return get_the_date( 'jS' );
}

/**
 * Returns the time of the post in 12-hour format without leading zeros.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_formattedtime(): string {
	return get_the_date( 'g:i a' );
}

/**
 * Returns the day of the year (1 to 365).
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_dayofyear(): string {
	return get_the_date( 'z' ) + 1; // Adding 1 because PHP date 'z' is zero-indexed
}
add_shortcode( 'tag_dayofyear', 'ttgarden_tag_dayofyear' );

/**
 * Returns the week of the year (1 to 53).
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_weekofyear(): string {
	return get_the_date( 'W' );
}
add_shortcode( 'tag_weekofyear', 'ttgarden_tag_weekofyear' );

/**
 * Returns the full name of the current month.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_month(): string {
	return get_the_date( 'F' );
}
add_shortcode( 'tag_month', 'ttgarden_tag_month' );

/**
 * Returns the abbreviated name of the current month.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_shortmonth(): string {
	return get_the_date( 'M' );
}
add_shortcode( 'tag_shortmonth', 'ttgarden_tag_shortmonth' );

/**
 * Returns the numeric representation of the month without leading zeros.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_monthnumber(): string {
	return get_the_date( 'n' );
}
add_shortcode( 'tag_monthnumber', 'ttgarden_tag_monthnumber' );

/**
 * Returns the numeric representation of the month with leading zeros.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_monthnumberwithzero(): string {
	return get_the_date( 'm' );
}
add_shortcode( 'tag_monthnumberwithzero', 'ttgarden_tag_monthnumberwithzero' );

/**
 * Returns the full numeric representation of the year (e.g., 2024).
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_year(): string {
	return get_the_date( 'Y' );
}
add_shortcode( 'tag_year', 'ttgarden_tag_year' );

/**
 * Returns the last two digits of the year (e.g., 24 for 2024).
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_shortyear(): string {
	return get_the_date( 'y' );
}
add_shortcode( 'tag_shortyear', 'ttgarden_tag_shortyear' );

/**
 * Returns lowercase 'am' or 'pm' based on the time.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_ampm(): string {
	return get_the_date( 'a' );
}
add_shortcode( 'tag_ampm', 'ttgarden_tag_ampm' );

/**
 * Returns uppercase 'AM' or 'PM' based on the time.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_capitalampm(): string {
	return get_the_date( 'A' );
}
add_shortcode( 'tag_capitalampm', 'ttgarden_tag_capitalampm' );

/**
 * Returns the hour in 12-hour format without leading zeros.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_12hour(): string {
	return get_the_date( 'g' );
}
add_shortcode( 'tag_12hour', 'ttgarden_tag_12hour' );

/**
 * Returns the hour in 24-hour format without leading zeros.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_24hour(): string {
	return get_the_date( 'G' );
}
add_shortcode( 'tag_24hour', 'ttgarden_tag_24hour' );

/**
 * Returns the hour in 12-hour format with leading zeros.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_12hourwithzero(): string {
	return get_the_date( 'h' );
}
add_shortcode( 'tag_12hourwithzero', 'ttgarden_tag_12hourwithzero' );

/**
 * Returns the hour in 24-hour format with leading zeros.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_24hourwithzero(): string {
	return get_the_date( 'H' );
}
add_shortcode( 'tag_24hourwithzero', 'ttgarden_tag_24hourwithzero' );

/**
 * Returns the minutes with leading zeros.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_minutes(): string {
	return get_the_date( 'i' );
}
add_shortcode( 'tag_minutes', 'ttgarden_tag_minutes' );

/**
 * Returns the seconds with leading zeros.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_seconds(): string {
	return get_the_date( 's' );
}
add_shortcode( 'tag_seconds', 'ttgarden_tag_seconds' );

/**
 * Returns the Swatch Internet Time (.beats).
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_beats(): string {
	return get_the_date( 'B' );
}
add_shortcode( 'tag_beats', 'ttgarden_tag_beats' );

/**
 * Returns the Unix timestamp of the post.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_timestamp(): string {
	return get_the_date( 'U' );
}
add_shortcode( 'tag_timestamp', 'ttgarden_tag_timestamp' );

/**
 * Returns the time difference between the post date and now, in human-readable format.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function ttgarden_tag_timeago(): string {
	$post_time    = get_the_time( 'U' );
	$current_time = time(); // Using PHP's time() for a true Unix UTC timestamp
	$time_diff    = human_time_diff( $post_time, $current_time );
	return sprintf( '%s ago', $time_diff );
}
add_shortcode( 'tag_timeago', 'ttgarden_tag_timeago' );

/**
 * Returns the noun of the current post type.
 *
 * @return string
 */
function ttgarden_tag_posttypenoun(): string {
	$format = get_post_format();
	return ucfirst( ( $format ) ? $format : 'post' );
}
add_shortcode( 'tag_posttypenoun', 'ttgarden_tag_posttypenoun' );

/**
 * Creates a WordPress.com reblog URL for the current post.
 *
 * @return string
 */
function ttgarden_tag_reblogurl(): string {
	return esc_url(
		sprintf(
			'https://wordpress.com/post?url=%s?is_post_share=true',
			get_permalink()
		)
	);
}
add_shortcode( 'tag_reblogurl', 'ttgarden_tag_reblogurl' );

/**
 * Creates a like iFrame for the current post. Relies on Jetpack's like button.
 *
 * @return string
 *
 * @see https://github.com/Automattic/jetpack/blob/trunk/projects/plugins/jetpack/extensions/blocks/like/like.php
 * @see https://github.com/Automattic/jetpack/blob/trunk/projects/plugins/jetpack/modules/likes/queuehandler.js#L417
 */
function ttgarden_tag_likebutton(): string {
	if ( ! function_exists( '\Automattic\Jetpack\Extensions\Like\render_block' ) ) {
		return '';
	}

	// Create a block context for the like button.
	$block = new class( get_the_ID() ) {

		/**
		 * The context of the block.
		 *
		 * @var array
		 */
		public array $context = array();

		/**
		 * Constructor.
		 *
		 * @param int $id The post ID.
		 */
		public function __construct( $id ) {
			$this->context['postId'] = $id;
		}
	};

	$block_output = \Automattic\Jetpack\Extensions\Like\render_block(
		array(
			'showAvatars' => false,
		),
		'',
		$block
	);

	$processor = new CupcakeLabs\TumblrThemeGarden\CLTTG_Processor( $block_output );

	// The standard block output won't work, we need to extract details from the block.
	while ( $processor->next_tag(
		array(
			'tag_name'   => 'div',
			'class_name' => 'sharedaddy',
		)
	) ) {
		$class      = $processor->get_attribute( 'class' );
		$id         = $processor->get_attribute( 'id' );
		$data_src   = $processor->get_attribute( 'data-src' );
		$data_name  = $processor->get_attribute( 'data-name' );
		$data_title = $processor->get_attribute( 'data-title' );
	}

	// The processor never found the block, return an empty string.
	if ( ! isset( $class ) ) {
		return '';
	}

	// Here we reconstruct the like button with the extracted details.
	return sprintf(
		'<div class="t3-likes like_button %s" id="%s" data-src="%s" data-name="%s" data-title="%s"><div class="likes-widget-placeholder post-likes-widget-placeholder"></div></div>',
		$class,
		$id,
		$data_src,
		$data_name,
		$data_title
	);
}
add_shortcode( 'tag_likebutton', 'ttgarden_tag_likebutton' );

/**
 * Returns a URL to the post comments HTML partial.
 *
 * @return string
 */
function ttgarden_tag_postnotesurl(): string {
	return sprintf(
		'/?p=%d&ttgarden_html_comments=true',
		get_the_ID()
	);
}
add_shortcode( 'tag_postnotesurl', 'ttgarden_tag_postnotesurl' );
