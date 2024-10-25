<?php

defined( 'ABSPATH' ) || exit;

// Shortcodes don't currently have a doing_shortcode() or similar.
// So we need a global to track the context.
tumblr3_set_parse_context( 'theme', true );

$theme = get_option( 'tumblr3_theme_html', '' );

/**
 * Capture wp_head output.
 *
 * @todo Can this be done in a more elegant way?
 */
ob_start();
wp_head();
$head = ob_get_contents();
ob_end_clean();

/**
 * Capture wp_footer output.
 *
 * @todo Can this be done in a more elegant way?
 */
ob_start();
wp_footer();
$footer = ob_get_contents();
ob_end_clean();

$theme = str_replace( '</head>', $head . '</head>', $theme );
$theme = str_replace( '</body>', $footer . '</body>', $theme );

echo apply_filters( 'tumblr3_theme_output', $theme );
