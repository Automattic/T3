<?php

defined( 'ABSPATH' ) || exit;

/**
 * All non-supported tags are assigned here.
 *
 * @return string Nothing, this tag is not supported.
 */
function tumblr3_tag_functionality_missing(): string {
	return '';
}

/**
 * Handles inline replacement of lang: tags.
 *
 * @see \CupcakeLabs\T3\Parser
 *
 * @param array $atts Array of shortcode attributes.
 *
 * @return string
 */
function tumblr3_tag_lang( $atts ): string {
	// Parse shortcode attributes.
	$atts = shortcode_atts(
		array(
			'key'   => '',
			'value' => '',
		),
		$atts,
		'tag_lang'
	);

	$plugin   = tumblr3_get_plugin_instance();
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
		if ( false !== strpos( $atts['key'], $keyword ) ) {
			$stack[] = $callback;
		}
	}

	// If stack is empty at this point, the language string is not supported.
	if ( empty( $stack ) ) {
		return '';
	}

	// If keywords are found, return the language string with keyword replacements.
	return vsprintf(
		( is_array( TUMBLR3_LANG[ $atts['key'] ] ) ) ? TUMBLR3_LANG[ $atts['key'] ][1] : TUMBLR3_LANG[ $atts['key'] ],
		array_map(
			function ( $callback ) {
				return call_user_func( $callback );
			},
			$stack
		)
	);
}
add_shortcode( 'tag_lang', 'tumblr3_tag_lang' );

/**
 * Outputs target attribute for links.
 *
 * @return string
 */
function tumblr3_tag_target(): string {
	return get_theme_mod( 'target_blank' ) ? 'target="_blank"' : '';
}
add_shortcode( 'tag_target', 'tumblr3_tag_target' );

/**
 * Returns the NPF JSON string of the current post.
 *
 * @todo Rafael is writing a PHP parser for NPF.
 *
 * @return string Nothing, this tag is currently not supported.
 */
function tumblr3_tag_npf(): string {
	return '';
}
add_shortcode( 'tag_npf', 'tumblr3_tag_npf' );

/**
 * The author name of the current post.
 *
 * @return string Post author name.
 */
function tumblr3_tag_postauthorname(): string {
	return get_the_author();
}
add_shortcode( 'tag_postauthorname', 'tumblr3_tag_postauthorname' );

/**
 * Returns the group member display name.
 *
 * @return string Username or empty.
 */
function tumblr3_tag_groupmembername(): string {
	$context = tumblr3_get_parse_context();

	if ( isset( $context['groupmember'] ) && is_a( $context['groupmember'], 'WP_User' ) ) {
		return $context['groupmember']->user_nicename;
	}

	return '';
}
add_shortcode( 'tag_groupmembername', 'tumblr3_tag_groupmembername' );

/**
 * The URL of the group members posts page.
 *
 * @return string The URL of the group member.
 */
function tumblr3_tag_groupmemberurl(): string {
	$context = tumblr3_get_parse_context();

	if ( isset( $context['groupmember'] ) && is_a( $context['groupmember'], 'WP_User' ) ) {
		return get_author_posts_url( $context['groupmember']->ID );
	}

	return '';
}
add_shortcode( 'tag_groupmemberurl', 'tumblr3_tag_groupmemberurl' );

/**
 * Gets the group member portrait URL.
 *
 * @param array $atts Shortcode attributes.
 *
 * @return string The URL of the group member avatar.
 */
function tumblr3_tag_groupmemberportraiturl( $atts ): string {
	// Parse shortcode attributes.
	$atts = shortcode_atts(
		array(
			'size' => '',
		),
		$atts,
		'tag_groupmemberportraiturl'
	);

	$context = tumblr3_get_parse_context();

	if ( isset( $context['groupmember'] ) && is_a( $context['groupmember'], 'WP_User' ) ) {
		$groupmember_avatar = get_avatar_url(
			$context['groupmember']->ID,
			array(
				'size' => $atts['size'],
			)
		);

		if ( ! $groupmember_avatar ) {
			return '';
		}

		return esc_url( $groupmember_avatar );
	}

	return '';
}
add_shortcode( 'tag_groupmemberportraiturl-16', 'tumblr3_tag_groupmemberportraiturl' );
add_shortcode( 'tag_groupmemberportraiturl-24', 'tumblr3_tag_groupmemberportraiturl' );
add_shortcode( 'tag_groupmemberportraiturl-30', 'tumblr3_tag_groupmemberportraiturl' );
add_shortcode( 'tag_groupmemberportraiturl-40', 'tumblr3_tag_groupmemberportraiturl' );
add_shortcode( 'tag_groupmemberportraiturl-48', 'tumblr3_tag_groupmemberportraiturl' );
add_shortcode( 'tag_groupmemberportraiturl-64', 'tumblr3_tag_groupmemberportraiturl' );
add_shortcode( 'tag_groupmemberportraiturl-96', 'tumblr3_tag_groupmemberportraiturl' );
add_shortcode( 'tag_groupmemberportraiturl-128', 'tumblr3_tag_groupmemberportraiturl' );

/**
 * The blog title of the post author.
 *
 * @return string
 */
function tumblr3_tag_postauthortitle(): string {
	return esc_attr( get_bloginfo( 'name' ) );
}
add_shortcode( 'tag_postauthortitle', 'tumblr3_tag_postauthortitle' );
add_shortcode( 'tag_groupmembertitle', 'tumblr3_tag_postauthortitle' );
add_shortcode( 'tag_postblogname', 'tumblr3_tag_postauthortitle' );

/**
 * The URL of the post author.
 *
 * @return string URL to the author archive.
 */
function tumblr3_tag_postauthorurl(): string {
	return esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
}
add_shortcode( 'tag_postauthorurl', 'tumblr3_tag_postauthorurl' );

/**
 * The portrait URL of the post author.
 *
 * @param array $atts The attributes of the shortcode.
 *
 * @return string The URL of the author portrait.
 */
function tumblr3_tag_postauthorportraiturl( $atts ): string {
	// Parse shortcode attributes.
	$atts = shortcode_atts(
		array(
			'size' => '',
		),
		$atts,
		'tag_postauthorportraiturl'
	);

	$author_id = get_the_author_meta( 'ID' );
	$author    = get_user_by( 'ID', $author_id );

	if ( ! $author ) {
		return '';
	}

	$author_avatar = get_avatar_url(
		$author_id,
		array(
			'size' => $atts['size'],
		)
	);

	if ( ! $author_avatar ) {
		return '';
	}

	return esc_url( $author_avatar );
}
add_shortcode( 'tag_postauthorportraiturl-16', 'tumblr3_tag_postauthorportraiturl' );
add_shortcode( 'tag_postauthorportraiturl-24', 'tumblr3_tag_postauthorportraiturl' );
add_shortcode( 'tag_postauthorportraiturl-30', 'tumblr3_tag_postauthorportraiturl' );
add_shortcode( 'tag_postauthorportraiturl-40', 'tumblr3_tag_postauthorportraiturl' );
add_shortcode( 'tag_postauthorportraiturl-48', 'tumblr3_tag_postauthorportraiturl' );
add_shortcode( 'tag_postauthorportraiturl-64', 'tumblr3_tag_postauthorportraiturl' );
add_shortcode( 'tag_postauthorportraiturl-96', 'tumblr3_tag_postauthorportraiturl' );
add_shortcode( 'tag_postauthorportraiturl-128', 'tumblr3_tag_postauthorportraiturl' );

/**
 * Outputs the twitter username theme option.
 *
 * @return string Attribute safe twitter username.
 */
function tumblr3_tag_twitterusername(): string {
	return esc_attr( get_theme_mod( 'twitter_username' ) );
}
add_shortcode( 'tag_twitterusername', 'tumblr3_tag_twitterusername' );

/**
 * The current state of a page in nav.
 * E.g is this the current page?
 *
 * @return string
 */
function tumblr3_tag_currentstate(): string {
	return get_the_permalink() === home_url( add_query_arg( null, null ) ) ? 'current-page' : '';
}
add_shortcode( 'tag_currentstate', 'tumblr3_tag_currentstate' );
add_shortcode( 'tag_externalstate', 'tumblr3_tag_currentstate' );

/**
 * The display shape of your avatar ("circle" or "square").
 *
 * @return string Either "circle" or "square".
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_avatarshape(): string {
	return esc_html( get_theme_mod( 'avatar_shape', 'circle' ) );
}
add_shortcode( 'tag_avatarshape', 'tumblr3_tag_avatarshape' );

/**
 * The background color of your blog.
 *
 * @return string The background colour in HEX format.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_backgroundcolor(): string {
	return '#' . sanitize_hex_color_no_hash( get_theme_mod( 'background_color', '#fff' ) );
}
add_shortcode( 'tag_backgroundcolor', 'tumblr3_tag_backgroundcolor' );

/**
 * The accent color of your blog.
 *
 * @return string The accent colour in HEX format.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_accentcolor(): string {
	return '#' . sanitize_hex_color_no_hash( get_theme_mod( 'accent_color', '#0073aa' ) );
}
add_shortcode( 'tag_accentcolor', 'tumblr3_tag_accentcolor' );

/**
 * The title color of your blog.
 *
 * @return string The title colour in HEX format.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_titlecolor(): string {
	return '#' . sanitize_hex_color_no_hash( get_theme_mod( 'header_textcolor', '#000' ) );
}
add_shortcode( 'tag_titlecolor', 'tumblr3_tag_titlecolor' );

/**
 * Get the title font theme option.
 *
 * @return string The title fontname.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_titlefont(): string {
	return esc_html( get_theme_mod( 'title_font', 'Arial' ) );
}
add_shortcode( 'tag_titlefont', 'tumblr3_tag_titlefont' );

/**
 * The weight of your title font ("normal" or "bold").
 *
 * @return string Either "bold" or "normal".
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_titlefontweight(): string {
	return esc_html( get_theme_mod( 'title_font_weight', 'bold' ) );
}
add_shortcode( 'tag_titlefontweight', 'tumblr3_tag_titlefontweight' );

/**
 * Get the header image theme option.
 *
 * @return string Either "remove-header" or the URL of the header image.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_headerimage(): string {
	return get_theme_mod( 'header_image', 'remove-header' );
}
add_shortcode( 'tag_headerimage', 'tumblr3_tag_headerimage' );

/**
 * Get either a post title, or the blog title.
 *
 * @return string The title of the post or the blog.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_title(): string {
	$context = tumblr3_get_parse_context();

	// Consume global context and return the appropriate title.
	return ( isset( $context['theme'] ) ) ? get_bloginfo( 'name' ) : get_the_title();
}
add_shortcode( 'tag_title', 'tumblr3_tag_title' );
add_shortcode( 'tag_posttitle', 'tumblr3_tag_title' );

/**
 * The post content.
 *
 * @return string The content of the post with filters applied.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_body(): string {
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- WP core function.
	return apply_filters( 'the_content', get_the_content() );
}
add_shortcode( 'tag_body', 'tumblr3_tag_body' );

/**
 * The post content.
 *
 * @return string The content of the post with filters applied.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_excerpt(): string {
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- WP core function.
	return wp_strip_all_tags( apply_filters( 'the_content', get_the_content() ) );
}
add_shortcode( 'tag_excerpt', 'tumblr3_tag_excerpt' );
add_shortcode( 'tag_sharestring', 'tumblr3_tag_excerpt' );

/**
 * The blog description, or subtitle.
 *
 * @return string The blog description.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_description(): string {
	$context = tumblr3_get_parse_context();

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
	return wp_kses_post( get_bloginfo( 'description' ) );
}
add_shortcode( 'tag_description', 'tumblr3_tag_description' );

/**
 * Attribute safe blog description.
 *
 * @return string The blog description with HTML entities encoded.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_metadescription(): string {
	return esc_attr( get_bloginfo( 'description' ) );
}
add_shortcode( 'tag_metadescription', 'tumblr3_tag_metadescription' );

/**
 * The homepage URL of the blog.
 *
 * @return string The URL of the blog.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_blogurl(): string {
	return esc_url( home_url( '/' ) );
}
add_shortcode( 'tag_blogurl', 'tumblr3_tag_blogurl' );

/**
 * The RSS feed URL of the blog.
 *
 * @return string The URL of the blog RSS feed.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_rss(): string {
	return esc_url( get_feed_link() );
}
add_shortcode( 'tag_rss', 'tumblr3_tag_rss' );

/**
 * The site favicon image URL.
 *
 * @return string The URL of the site favicon.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_favicon(): string {
	return esc_url( get_site_icon_url() );
}
add_shortcode( 'tag_favicon', 'tumblr3_tag_favicon' );

/**
 * The portrait URL of the blog, uses the custom logo if set.
 *
 * @param array $atts The attributes of the shortcode.
 *
 * @return string The URL of the blog portrait.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_portraiturl( $atts ): string {
	// Parse shortcode attributes.
	$atts = shortcode_atts(
		array(
			'size' => '',
		),
		$atts,
		'tag_portraiturl'
	);

	if ( ! has_custom_logo() ) {
		return '';
	}

	$custom_logo_id  = get_theme_mod( 'custom_logo' );
	$custom_logo_src = wp_get_attachment_image_src(
		$custom_logo_id,
		array(
			$atts['size'],
			$atts['size'],
		)
	);

	if ( ! $custom_logo_src ) {
		return '';
	}

	return esc_url( $custom_logo_src[0] );
}
add_shortcode( 'tag_portraiturl-16', 'tumblr3_tag_portraiturl' );
add_shortcode( 'tag_portraiturl-24', 'tumblr3_tag_portraiturl' );
add_shortcode( 'tag_portraiturl-30', 'tumblr3_tag_portraiturl' );
add_shortcode( 'tag_portraiturl-40', 'tumblr3_tag_portraiturl' );
add_shortcode( 'tag_portraiturl-48', 'tumblr3_tag_portraiturl' );
add_shortcode( 'tag_portraiturl-64', 'tumblr3_tag_portraiturl' );
add_shortcode( 'tag_portraiturl-96', 'tumblr3_tag_portraiturl' );
add_shortcode( 'tag_portraiturl-128', 'tumblr3_tag_portraiturl' );

/**
 * Returns the custom CSS option of the theme.
 *
 * @return string The custom CSS of the theme.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_customcss(): string {
	return esc_html( wp_get_custom_css() );
}
add_shortcode( 'tag_customcss', 'tumblr3_tag_customcss' );

/**
 * Identical to {PostTitle}, but will automatically generate a summary if a title doesn't exist.
 *
 * @return string The post title or summary.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_postsummary(): string {
	$title = get_the_title();
	return ( '' === $title ) ? $title : get_the_excerpt();
}
add_shortcode( 'tag_postsummary', 'tumblr3_tag_postsummary' );

/**
 * Character limited version of {PostSummary} that is suitable for Twitter.
 *
 * @return string The post summary limited to 280 characters.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_tweetsummary(): string {
	return esc_html( substr( tumblr3_tag_postsummary(), 0, 280 ) );
}
add_shortcode( 'tag_tweetsummary', 'tumblr3_tag_tweetsummary' );
add_shortcode( 'tag_mailsummary', 'tumblr3_tag_tweetsummary' );

/**
 * Various contextual uses, typically outputs a post permalink.
 *
 * @return string The URL of the post.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_url(): string {
	$context = tumblr3_get_parse_context();

	// Handle the jump pagination context for this tag.
	if ( isset( $context['jumppagination'] ) ) {
		return '/page/' . intval( $context['jumppagination'] );
	}

	return get_permalink();
}
add_shortcode( 'tag_url', 'tumblr3_tag_url' );
add_shortcode( 'tag_permalink', 'tumblr3_tag_url' );
add_shortcode( 'tag_relativepermalink', 'tumblr3_tag_url' );
add_shortcode( 'tag_shorturl', 'tumblr3_tag_url' );
add_shortcode( 'tag_embedurl', 'tumblr3_tag_url' );

/**
 * Typically a page title, used in a page loop e.g navigation.
 * Also used as the Chat label for legacy chat posts.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 * @see https://www.tumblr.com/docs/en/custom_themes#chat-posts
 */
function tumblr3_tag_label(): string {
	$context = tumblr3_get_parse_context();

	if ( ! isset( $context['chat']['label'] ) ) {
		// By default, return the page title.
		return wp_kses_post( get_the_title() );
	}

	return $context['chat']['label'];
}
add_shortcode( 'tag_label', 'tumblr3_tag_label' );

/**
 * Current line of a legacy chat post.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#chat-posts
 */
function tumblr3_tag_line(): string {
	$context = tumblr3_get_parse_context();

	// Check if we are in a chat context.
	if ( ! isset( $context['chat']['line'] ) ) {
		return '';
	}

	return $context['chat']['line'];
}
add_shortcode( 'tag_line', 'tumblr3_tag_line' );

/**
 * Tagsasclasses outputs the tags of a post as HTML-safe classes.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tagsasclasses(): string {
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
add_shortcode( 'tag_tagsasclasses', 'tumblr3_tagsasclasses' );

/**
 * Label in post footer indicating this is a pinned post.
 *
 * @return string The label for a pinned post.
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_pinnedpostlabel(): string {
	return esc_html( TUMBLR3_LANG['Pinned Post'] );
}
add_shortcode( 'tag_pinnedpostlabel', 'tumblr3_tag_pinnedpostlabel' );

/**
 * Gets the previous post URL (single post pagination)
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_previouspost(): string {
	return esc_url( get_permalink( get_adjacent_post( false, '', true ) ) );
}
add_shortcode( 'tag_previouspost', 'tumblr3_tag_previouspost' );

/**
 * Gets the next post URL (single post pagination)
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_nextpost(): string {
	return esc_url( get_permalink( get_adjacent_post( false, '', false ) ) );
}
add_shortcode( 'tag_nextpost', 'tumblr3_tag_nextpost' );

/**
 * Gets the previous posts page URL (pagination)
 *
 * @return string|null
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_previouspage(): string|null {
	return esc_url( get_next_posts_page_link() );
}
add_shortcode( 'tag_previouspage', 'tumblr3_tag_previouspage' );

/**
 * Gets the next posts page URL (pagination)
 *
 * @return string|null
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_nextpage(): string|null {
	return esc_url( get_previous_posts_page_link() );
}
add_shortcode( 'tag_nextpage', 'tumblr3_tag_nextpage' );

/**
 * Gets the current page value (pagination)
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_currentpage(): string {
	$page = get_query_var( 'paged' );
	return ( $page > 0 ) ? (string) $page : '1';
}
add_shortcode( 'tag_currentpage', 'tumblr3_tag_currentpage' );

/**
 * The pagenumber tag inside jump pagination.
 *
 * @return string
 */
function tumblr3_tag_pagenumber(): string {
	$context = tumblr3_get_parse_context();
	return isset( $context['jumppagination'] ) ? (string) $context['jumppagination'] : '';
}
add_shortcode( 'tag_pagenumber', 'tumblr3_tag_pagenumber' );

/**
 * Gets the query total pages (pagination)
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_totalpages(): string {
	global $wp_query;
	return $wp_query->max_num_pages;
}
add_shortcode( 'tag_totalpages', 'tumblr3_tag_totalpages' );

/**
 * Displays the span of years your blog has existed.
 *
 * @return string
 *
 * @todo find a way to get the install date of the blog.
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_copyrightyears(): string {
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
add_shortcode( 'tag_copyrightyears', 'tumblr3_tag_copyrightyears' );

/**
 * The numeric ID for a post.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_postid(): string {
	return esc_attr( get_the_ID() );
}
add_shortcode( 'tag_postid', 'tumblr3_tag_postid' );

/**
 * The name of the current legacy post type.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_posttype(): string {
	$format = get_post_format();
	return ( $format ) ? $format : 'text';
}
add_shortcode( 'tag_posttype', 'tumblr3_tag_posttype' );

/**
 * Current tag name in a loop.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_tag(): string {
	$context = tumblr3_get_parse_context();

	// Check if we are in a tag context.
	if ( ! isset( $context['term'] ) || ! is_a( $context['term'], 'WP_Term' ) ) {
		return '';
	}

	return $context['term']->name;
}
add_shortcode( 'tag_tag', 'tumblr3_tag_tag' );

/**
 * Current tag name in a loop.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_urlsafetag(): string {
	$context = tumblr3_get_parse_context();

	// Check if we are in a tag context.
	if ( ! isset( $context['term'] ) || ! is_a( $context['term'], 'WP_Term' ) ) {
		return '';
	}

	return rawurlencode( $context['term']->name );
}
add_shortcode( 'tag_urlsafetag', 'tumblr3_tag_urlsafetag' );

/**
 * Current tag url in a loop.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_tagurl(): string {
	$context = tumblr3_get_parse_context();

	// Check if we are in a tag context.
	if ( ! isset( $context['term'] ) || ! is_a( $context['term'], 'WP_Term' ) ) {
		return '';
	}

	return get_term_link( $context['term'] );
}
add_shortcode( 'tag_tagurl', 'tumblr3_tag_tagurl' );
add_shortcode( 'tag_tagurlchrono', 'tumblr3_tag_tagurl' );

/**
 * The total number of comments on a post.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_notecount(): string {
	return get_comments_number();
}
add_shortcode( 'tag_notecount', 'tumblr3_tag_notecount' );

/**
 * The total number of comments on a post in text form.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_notecountwithlabel(): string {
	return get_comments_number_text();
}
add_shortcode( 'tag_notecountwithlabel', 'tumblr3_tag_notecountwithlabel' );

/**
 * The post comments.
 *
 * @todo We need to match the output of tumblr post notes for styling consistency.
 *
 * @param array $atts The attributes of the shortcode.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_postnotes( $atts ): string {
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
add_shortcode( 'tag_postnotes', 'tumblr3_tag_postnotes' );
add_shortcode( 'tag_postnotes-16', 'tumblr3_tag_postnotes' );
add_shortcode( 'tag_postnotes-64', 'tumblr3_tag_postnotes' );

/**
 * The current search query.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_searchquery(): string {
	return esc_html( get_search_query() );
}
add_shortcode( 'tag_searchquery', 'tumblr3_tag_searchquery' );

/**
 * The current search query URL encoded.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_urlsafesearchquery(): string {
	return rawurlencode( get_search_query() );
}
add_shortcode( 'tag_urlsafesearchquery', 'tumblr3_tag_urlsafesearchquery' );

/**
 * The found posts count of the search result.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_searchresultcount(): string {
	global $wp_query;
	return $wp_query->found_posts;
}
add_shortcode( 'tag_searchresultcount', 'tumblr3_tag_searchresultcount' );

/**
 * Quote post content.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_quote(): string {
	$context = tumblr3_get_parse_context();

	// Test if the current context is a quote post and has a source.
	if ( isset( $context['quote'], $context['quote']['quote'] ) ) {
		return $context['quote']['quote'];
	}

	// Empty string if no quote block is found.
	return '';
}
add_shortcode( 'tag_quote', 'tumblr3_tag_quote' );

/**
 * Quote post source.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_source(): string {
	$context = tumblr3_get_parse_context();

	// Test if the current context is a quote post and has a source.
	if ( isset( $context['quote'], $context['quote']['source'] ) ) {
		return $context['quote']['source'];
	}

	return '';
}
add_shortcode( 'tag_source', 'tumblr3_tag_source' );

/**
 * Quote content length.
 * "short", "medium", "long"
 *
 * @return string
 *
 * @see https://github.tumblr.net/Tumblr/tumblr/blob/046755128a6d61010fcaf4459f8efdc895140ad0/app/models/post.php#L7459
 */
function tumblr3_tag_length(): string {
	$context = tumblr3_get_parse_context();

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
add_shortcode( 'tag_length', 'tumblr3_tag_length' );

/**
 * Audioplayer HTML.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_audioplayer(): string {
	$context = tumblr3_get_parse_context();

	// Test if the current context is an audio post and has a player.
	if ( isset( $context['audio'], $context['audio']['player'] ) ) {
		return $context['audio']['player'];
	}

	return '';
}
add_shortcode( 'tag_audioplayer', 'tumblr3_tag_audioplayer' );
add_shortcode( 'tag_audioembed', 'tumblr3_tag_audioplayer' );
add_shortcode( 'tag_audioembed-640', 'tumblr3_tag_audioplayer' );
add_shortcode( 'tag_audioembed-500', 'tumblr3_tag_audioplayer' );
add_shortcode( 'tag_audioembed-400', 'tumblr3_tag_audioplayer' );
add_shortcode( 'tag_audioembed-250', 'tumblr3_tag_audioplayer' );
add_shortcode( 'tag_audioplayerblack', 'tumblr3_tag_audioplayer' );
add_shortcode( 'tag_audioplayerwhite', 'tumblr3_tag_audioplayer' );

/**
 * Album art URL, uses the featured image if available.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_albumarturl(): string {
	$context = tumblr3_get_parse_context();

	// Test if the current context is an audio post and has a player.
	if ( isset( $context['audio'], $context['audio']['art'] ) ) {
		return $context['audio']['art'];
	}

	return '';
}
add_shortcode( 'tag_albumarturl', 'tumblr3_tag_albumarturl' );

/**
 * Renders the audio player track name.
 *
 * @return string
 */
function tumblr3_tag_trackname(): string {
	$context = tumblr3_get_parse_context();

	// Test if the current context is an audio post and has a player.
	if ( isset( $context['audio'], $context['audio']['trackname'] ) ) {
		return $context['audio']['trackname'];
	}

	return '';
}
add_shortcode( 'tag_trackname', 'tumblr3_tag_trackname' );

/**
 * Renders the audio player artist name.
 *
 * @return string
 */
function tumblr3_tag_artist(): string {
	$context = tumblr3_get_parse_context();

	// Test if the current context is an audio post and has a player.
	if ( isset( $context['audio'], $context['audio']['artist'] ) ) {
		return $context['audio']['artist'];
	}

	return '';
}
add_shortcode( 'tag_artist', 'tumblr3_tag_artist' );

/**
 * Renders the audio player album name.
 *
 * @return string
 */
function tumblr3_tag_album(): string {
	$context = tumblr3_get_parse_context();

	// Test if the current context is an audio post and has a player.
	if ( isset( $context['audio'], $context['audio']['album'] ) ) {
		return $context['audio']['album'];
	}

	return '';
}
add_shortcode( 'tag_album', 'tumblr3_tag_album' );

/**
 * Renders the audio player media URL if it's external.
 *
 * @return string
 */
function tumblr3_tag_externalaudiourl(): string {
	$context = tumblr3_get_parse_context();

	// Test if the current context is an audio post and has a player.
	if ( isset( $context['audio'], $context['audio']['player'] ) ) {
		$processor = new CupcakeLabs\T3\Processor( $context['audio']['player'] );

		while ( $processor->next_tag( 'AUDIO' ) ) {
			$src = $processor->get_attribute( 'SRC' );

			if ( $src ) {
				return esc_url( $src );
			}
		}
	}

	return '';
}
add_shortcode( 'tag_externalaudiourl', 'tumblr3_tag_externalaudiourl' );
add_shortcode( 'tag_rawaudiourl', 'tumblr3_tag_externalaudiourl' );

/**
 * Renders the post gallery if one was found.
 *
 * @return string
 */
function tumblr3_tag_photoset(): string {
	$context = tumblr3_get_parse_context();

	// Return nothing if no gallery is found.
	if ( ! isset( $context['gallery']['gallery'] ) || empty( $context['gallery']['gallery'] ) ) {
		return '';
	}

	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- WP core function.
	return apply_filters( 'the_content', $context['gallery']['gallery'] );
}
add_shortcode( 'tag_photoset', 'tumblr3_tag_photoset' );
add_shortcode( 'tag_photoset-700', 'tumblr3_tag_photoset' );
add_shortcode( 'tag_photoset-500', 'tumblr3_tag_photoset' );
add_shortcode( 'tag_photoset-400', 'tumblr3_tag_photoset' );
add_shortcode( 'tag_photoset-250', 'tumblr3_tag_photoset' );
add_shortcode( 'tag_photoset-100', 'tumblr3_tag_photoset' );

/**
 * Renders the post gallery layout if one was found.
 *
 * @return string
 */
function tumblr3_tag_photosetlayout(): string {
	return tumblr3_tag_photocount();
}
add_shortcode( 'tag_photosetlayout', 'tumblr3_tag_photosetlayout' );

/**
 * Renders the post gallery photo count if one was found.
 *
 * @return string
 */
function tumblr3_tag_photocount(): string {
	$context = tumblr3_get_parse_context();

	// Return nothing if no gallery is found.
	if ( ! isset( $context['gallery']['photocount'] ) ) {
		return '';
	}

	return esc_html( $context['gallery']['photocount'] );
}
add_shortcode( 'tag_photocount', 'tumblr3_tag_photocount' );
add_shortcode( 'tag_photosetlayout', 'tumblr3_tag_photocount' );

/**
 * Renders the post gallery caption if one was found.
 *
 * @return string
 */
function tumblr3_tag_caption(): string {
	$context = tumblr3_get_parse_context();
	$format  = get_post_format();

	if ( ! isset( $context[ $format ], $context[ $format ]['caption'] ) ) {
		return '';
	}

	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- WP core function.
	return apply_filters( 'the_content', $context[ $format ]['caption'] );
}
add_shortcode( 'tag_caption', 'tumblr3_tag_caption' );

/**
 * Renders the post image URL if one was found.
 *
 * @param array  $atts           The attributes of the shortcode.
 * @param string $content        The content of the shortcode.
 * @param string $shortcode_name The name of the shortcode.
 *
 * @return string
 */
function tumblr3_tag_photourl( $atts, $content, $shortcode_name ): string {
	// Parse shortcode attributes.
	$atts = shortcode_atts(
		array(
			'size' => '',
		),
		$atts,
		$shortcode_name
	);

	$context = tumblr3_get_parse_context();

	// Return nothing if no gallery is found.
	if ( ! isset( $context['image']['image'] ) ) {
		return '';
	}

	$src = wp_get_attachment_image_src( $context['image']['image'] );

	return ( false === $src ) ? '' : esc_url( $src[0] );
}
add_shortcode( 'tag_photourl-500', 'tumblr3_tag_photourl' );
add_shortcode( 'tag_photourl-400', 'tumblr3_tag_photourl' );
add_shortcode( 'tag_photourl-250', 'tumblr3_tag_photourl' );
add_shortcode( 'tag_photourl-100', 'tumblr3_tag_photourl' );
add_shortcode( 'tag_photourl-highres', 'tumblr3_tag_photourl' );
add_shortcode( 'tag_photourl-75sq', 'tumblr3_tag_photourl' );

/**
 * Renders the post image thumbnail URL if one was found.
 *
 * @param array  $atts           The attributes of the shortcode.
 * @param string $content        The content of the shortcode.
 * @param string $shortcode_name The name of the shortcode.
 *
 * @return string
 */
function tumblr3_tag_thumbnail( $atts, $content, $shortcode_name ): string {
	$sizes = array(
		'tag_thumbnail'         => 'thumbnail',
		'tag_thumbnail-highres' => 'full',
	);

	return get_the_post_thumbnail_url( get_the_id(), $sizes[ $shortcode_name ] );
}
add_shortcode( 'tag_thumbnail', 'tumblr3_tag_thumbnail' );
add_shortcode( 'tag_thumbnail-highres', 'tumblr3_tag_thumbnail' );

/**
 * Renders the post image link URL if one was found.
 *
 * @todo Hook up lightbox and custom link contexts.
 *
 * @return string
 */
function tumblr3_tag_linkurl(): string {
	$context = tumblr3_get_parse_context();

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
add_shortcode( 'tag_linkurl', 'tumblr3_tag_linkurl' );

/**
 * Renders the post image link open tag conditionally.
 *
 * @uses tumblr3_tag_linkurl()
 * @return string
 */
function tumblr3_tag_linkopentag(): string {
	$context = tumblr3_get_parse_context();

	return ( isset( $context['image']['link'] ) && 'none' !== $context['image']['link'] ) ? '<a href="' . tumblr3_tag_linkurl() . '">' : '';
}
add_shortcode( 'tag_linkopentag', 'tumblr3_tag_linkopentag' );

/**
 * Renders the post image link close tag conditionally.
 *
 * @return string
 */
function tumblr3_tag_linkclosetag(): string {
	$context = tumblr3_get_parse_context();

	return ( isset( $context['image']['link'] ) && 'none' !== $context['image']['link'] ) ? '</a>' : '';
}
add_shortcode( 'tag_linkclosetag', 'tumblr3_tag_linkclosetag' );

/**
 * Renders the post image camera exif data if found.
 *
 * @return string
 */
function tumblr3_tag_camera(): string {
	$context = tumblr3_get_parse_context();

	return isset( $context['image']['data']['image_meta']['camera'] ) ? esc_html( $context['image']['data']['image_meta']['camera'] ) : '';
}
add_shortcode( 'tag_camera', 'tumblr3_tag_camera' );

/**
 * Renders the post image lens exif data if found.
 *
 * @return string
 */
function tumblr3_tag_aperture(): string {
	$context = tumblr3_get_parse_context();

	return isset( $context['image']['data']['image_meta']['aperture'] ) ? esc_html( $context['image']['data']['image_meta']['aperture'] ) : '';
}
add_shortcode( 'tag_aperture', 'tumblr3_tag_aperture' );

/**
 * Renders the post image focal length exif data if found.
 *
 * @return string
 */
function tumblr3_tag_focallength(): string {
	$context = tumblr3_get_parse_context();

	return isset( $context['image']['data']['image_meta']['focal_length'] ) ? esc_html( $context['image']['data']['image_meta']['focal_length'] ) : '';
}
add_shortcode( 'tag_focallength', 'tumblr3_tag_focallength' );

/**
 * Renders the post image shutter speed exif data if found.
 *
 * @return string
 */
function tumblr3_tag_exposure(): string {
	$context = tumblr3_get_parse_context();

	return isset( $context['image']['data']['image_meta']['shutter_speed'] ) ? esc_html( $context['image']['data']['image_meta']['shutter_speed'] ) : '';
}
add_shortcode( 'tag_exposure', 'tumblr3_tag_exposure' );

/**
 * Renders the post image alt text if one was found.
 *
 * @return string
 */
function tumblr3_tag_photoalt(): string {
	$context = tumblr3_get_parse_context();

	if ( ! isset( $context['image']['image'] ) ) {
		return '';
	}

	return esc_attr( get_post_meta( $context['image']['image'], '_wp_attachment_image_alt', true ) );
}
add_shortcode( 'tag_photoalt', 'tumblr3_tag_photoalt' );

/**
 * Renders the post image width if one was found.
 *
 * @return string
 */
function tumblr3_tag_photowidth(): string {
	$context = tumblr3_get_parse_context();

	if ( ! isset( $context['image']['data'], $context['image']['data']['width'] ) ) {
		return '';
	}

	return (string) $context['image']['data']['width'];
}
add_shortcode( 'tag_photowidth-500', 'tumblr3_tag_photowidth' );
add_shortcode( 'tag_photowidth-400', 'tumblr3_tag_photowidth' );
add_shortcode( 'tag_photowidth-250', 'tumblr3_tag_photowidth' );
add_shortcode( 'tag_photowidth-100', 'tumblr3_tag_photowidth' );
add_shortcode( 'tag_photowidth-highres', 'tumblr3_tag_photowidth' );

/**
 * Renders the post image height if one was found.
 *
 * @return string
 */
function tumblr3_tag_photoheight(): string {
	$context = tumblr3_get_parse_context();

	if ( ! isset( $context['image']['data'], $context['image']['data']['height'] ) ) {
		return '';
	}

	return (string) $context['image']['data']['height'];
}
add_shortcode( 'tag_photoheight-500', 'tumblr3_tag_photoheight' );
add_shortcode( 'tag_photoheight-400', 'tumblr3_tag_photoheight' );
add_shortcode( 'tag_photoheight-250', 'tumblr3_tag_photoheight' );
add_shortcode( 'tag_photoheight-100', 'tumblr3_tag_photoheight' );
add_shortcode( 'tag_photoheight-highres', 'tumblr3_tag_photoheight' );

/**
 * Renders the post video player.
 *
 * @return string
 */
function tumblr3_tag_video(): string {
	$context = tumblr3_get_parse_context();

	// Test if the current context is a video post and has a player.
	if ( isset( $context['video'], $context['video']['player'] ) ) {
		return $context['video']['player'];
	}

	return '';
}
add_shortcode( 'tag_video-700', 'tumblr3_tag_video' );
add_shortcode( 'tag_video-500', 'tumblr3_tag_video' );
add_shortcode( 'tag_video-400', 'tumblr3_tag_video' );
add_shortcode( 'tag_video-250', 'tumblr3_tag_video' );
add_shortcode( 'tag_videoembed-700', 'tumblr3_tag_video' );
add_shortcode( 'tag_videoembed-500', 'tumblr3_tag_video' );
add_shortcode( 'tag_videoembed-400', 'tumblr3_tag_video' );
add_shortcode( 'tag_videoembed-250', 'tumblr3_tag_video' );

/**
 * Renders the post video thumbnail URL.
 *
 * @return string
 */
function tumblr3_tag_videothumbnailurl(): string {
	$context = tumblr3_get_parse_context();

	// Test if the current context is a video post and has a player.
	if ( isset( $context['video'], $context['video']['thumbnail'] ) ) {
		return $context['video']['thumbnail'];
	}

	return '';
}
add_shortcode( 'tag_videothumbnailurl', 'tumblr3_tag_videothumbnailurl' );

/**
 * The link post type title (This is also the link URL).
 *
 * @return string
 */
function tumblr3_tag_name(): string {
	return get_the_title( get_the_ID() );
}
add_shortcode( 'tag_name', 'tumblr3_tag_title' );

/**
 * Renders the link post host url.
 *
 * @return string
 */
function tumblr3_tag_host(): string {
	$url = wp_http_validate_url( get_the_title() );

	// If this wasn't a valid URL, return an empty string.
	if ( false === $url ) {
		return '';
	}

	$parsed_url = wp_parse_url( $url );

	// Return the host of the URL.
	return $parsed_url['host'];
}
add_shortcode( 'tag_host', 'tumblr3_tag_host' );

/**
 * Returns the day of the month without leading zeros.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_dayofmonth(): string {
	return get_the_date( 'j' );
}
add_shortcode( 'tag_dayofmonth', 'tumblr3_tag_dayofmonth' );

/**
 * Returns the day of the month with leading zeros.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_dayofmonthwithzero(): string {
	return get_the_date( 'd' );
}
add_shortcode( 'tag_dayofmonthwithzero', 'tumblr3_tag_dayofmonthwithzero' );

/**
 * Returns the full name of the day of the week.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_dayofweek(): string {
	return get_the_date( 'l' );
}
add_shortcode( 'tag_dayofweek', 'tumblr3_tag_dayofweek' );

/**
 * Returns the abbreviated name of the day of the week.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_shortdayofweek(): string {
	return get_the_date( 'D' );
}
add_shortcode( 'tag_shortdayofweek', 'tumblr3_tag_shortdayofweek' );

/**
 * Returns the day of the week as a number (1 for Monday, 7 for Sunday).
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_dayofweeknumber(): string {
	return get_the_date( 'N' );
}
add_shortcode( 'tag_dayofweeknumber', 'tumblr3_tag_dayofweeknumber' );

/**
 * Returns the English ordinal suffix for the day of the month.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_dayofmonthsuffix(): string {
	return get_the_date( 'S' );
}
add_shortcode( 'tag_dayofmonthsuffix', 'tumblr3_tag_dayofmonthsuffix' );

/**
 * Returns the day of the year (1 to 365).
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_dayofyear(): string {
	return get_the_date( 'z' ) + 1; // Adding 1 because PHP date 'z' is zero-indexed
}
add_shortcode( 'tag_dayofyear', 'tumblr3_tag_dayofyear' );

/**
 * Returns the week of the year (1 to 53).
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_weekofyear(): string {
	return get_the_date( 'W' );
}
add_shortcode( 'tag_weekofyear', 'tumblr3_tag_weekofyear' );

/**
 * Returns the full name of the current month.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_month(): string {
	return get_the_date( 'F' );
}
add_shortcode( 'tag_month', 'tumblr3_tag_month' );

/**
 * Returns the abbreviated name of the current month.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_shortmonth(): string {
	return get_the_date( 'M' );
}
add_shortcode( 'tag_shortmonth', 'tumblr3_tag_shortmonth' );

/**
 * Returns the numeric representation of the month without leading zeros.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_monthnumber(): string {
	return get_the_date( 'n' );
}
add_shortcode( 'tag_monthnumber', 'tumblr3_tag_monthnumber' );

/**
 * Returns the numeric representation of the month with leading zeros.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_monthnumberwithzero(): string {
	return get_the_date( 'm' );
}
add_shortcode( 'tag_monthnumberwithzero', 'tumblr3_tag_monthnumberwithzero' );

/**
 * Returns the full numeric representation of the year (e.g., 2024).
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_year(): string {
	return get_the_date( 'Y' );
}
add_shortcode( 'tag_year', 'tumblr3_tag_year' );

/**
 * Returns the last two digits of the year (e.g., 24 for 2024).
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_shortyear(): string {
	return get_the_date( 'y' );
}
add_shortcode( 'tag_shortyear', 'tumblr3_tag_shortyear' );

/**
 * Returns lowercase 'am' or 'pm' based on the time.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_ampm(): string {
	return get_the_date( 'a' );
}
add_shortcode( 'tag_ampm', 'tumblr3_tag_ampm' );

/**
 * Returns uppercase 'AM' or 'PM' based on the time.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_capitalampm(): string {
	return get_the_date( 'A' );
}
add_shortcode( 'tag_capitalampm', 'tumblr3_tag_capitalampm' );

/**
 * Returns the hour in 12-hour format without leading zeros.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_12hour(): string {
	return get_the_date( 'g' );
}
add_shortcode( 'tag_12hour', 'tumblr3_tag_12hour' );

/**
 * Returns the hour in 24-hour format without leading zeros.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_24hour(): string {
	return get_the_date( 'G' );
}
add_shortcode( 'tag_24hour', 'tumblr3_tag_24hour' );

/**
 * Returns the hour in 12-hour format with leading zeros.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_12hourwithzero(): string {
	return get_the_date( 'h' );
}
add_shortcode( 'tag_12hourwithzero', 'tumblr3_tag_12hourwithzero' );

/**
 * Returns the hour in 24-hour format with leading zeros.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_24hourwithzero(): string {
	return get_the_date( 'H' );
}
add_shortcode( 'tag_24hourwithzero', 'tumblr3_tag_24hourwithzero' );

/**
 * Returns the minutes with leading zeros.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_minutes(): string {
	return get_the_date( 'i' );
}
add_shortcode( 'tag_minutes', 'tumblr3_tag_minutes' );

/**
 * Returns the seconds with leading zeros.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_seconds(): string {
	return get_the_date( 's' );
}
add_shortcode( 'tag_seconds', 'tumblr3_tag_seconds' );

/**
 * Returns the Swatch Internet Time (.beats).
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_beats(): string {
	$now = new DateTime( null, new DateTimeZone( 'UTC' ) );
	return '@' . floor( ( $now->format( 'G' ) * 3600 + $now->format( 'i' ) * 60 + $now->format( 's' ) + 3600 ) / 86.4 );
}
add_shortcode( 'tag_beats', 'tumblr3_tag_beats' );

/**
 * Returns the Unix timestamp of the post.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_timestamp(): string {
	return get_the_date( 'U' );
}
add_shortcode( 'tag_timestamp', 'tumblr3_tag_timestamp' );

/**
 * Returns the time difference between the post date and now, in human-readable format.
 *
 * @return string
 *
 * @see https://www.tumblr.com/docs/en/custom_themes#basic_variables
 */
function tumblr3_tag_timeago(): string {
	$post_time    = get_the_time( 'U' );
	$current_time = current_time( 'timestamp' );
	$time_diff    = human_time_diff( $post_time, $current_time );
	return sprintf( '%s ago', $time_diff );
}
add_shortcode( 'tag_timeago', 'tumblr3_tag_timeago' );
