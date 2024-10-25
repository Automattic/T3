<?php

defined( 'ABSPATH' ) || exit;

// Shortcodes don't currently have a doing_shortcode() or similar.
// So we need a global to track the context.
tumblr3_set_parse_context( 'theme', true );

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

$tumblr3_theme = str_replace( '</head>', $tumblr3_head . '</head>', $tumblr3_theme );
$tumblr3_theme = str_replace( '</body>', $tumblr3_footer . '</body>', $tumblr3_theme );

echo apply_filters( 'tumblr3_theme_output', $tumblr3_theme );
