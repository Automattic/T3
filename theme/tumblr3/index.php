<?php

defined( 'ABSPATH' ) || exit;

// Shortcodes don't currently have a doing_shortcode() or similar.
// So we need a global to track the context.
tumblr3_set_parse_context( 'theme', true );

// Get the parsed theme HTML.
$tumblr3_theme = get_option( 'tumblr3_theme_html', '' );

/**
 * Capture wp_head output.
 *
 * @todo Can this be done in a more elegant way?
 */
ob_start();
wp_head();
$tumblr3_head = ob_get_contents();
ob_end_clean();

/**
 * Capture wp_footer output.
 *
 * @todo Can this be done in a more elegant way?
 */
ob_start();
wp_footer();
$tumblr3_footer = ob_get_contents();
ob_end_clean();

// Parse and potentially store the parsed theme HTML.
$tumblr3_theme = apply_filters( 'tumblr3_theme_output', $tumblr3_theme );

// Add the head and footer to the theme.
$tumblr3_theme = str_replace( '</head>', $tumblr3_head . '</head>', $tumblr3_theme );
$tumblr3_theme = str_replace( '</body>', $tumblr3_footer . '</body>', $tumblr3_theme );

// @todo: Sanitize the theme output?!
echo $tumblr3_theme;
