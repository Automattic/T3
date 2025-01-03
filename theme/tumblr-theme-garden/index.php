<?php
/**
 * TumblrThemeGarden theme index file.
 *
 * @package TumblrThemeGarden
 */

defined( 'ABSPATH' ) || exit;

// Shortcodes don't currently have a doing_shortcode() or similar.
// So we need a global to track the context.
clttg_set_parse_context( 'theme', true );

// Get the parsed theme HTML.
$clttg_theme = get_option( 'clttg_theme_html', '' );

/**
 * Capture wp_head output.
 *
 * @todo Can this be done in a more elegant way?
 */
ob_start();
wp_head();
$clttg_head = ob_get_contents();
ob_end_clean();

// Parse and potentially store the parsed theme HTML.
$clttg_theme = apply_filters( 'clttg_theme_output', $clttg_theme );

/**
 * Capture wp_footer output.
 *
 * @todo Can this be done in a more elegant way?
 */
ob_start();
wp_footer();
$clttg_footer = ob_get_contents();
ob_end_clean();

// Add the head and footer to the theme.
$clttg_theme = str_replace( '</head>', $clttg_head . '</head>', $clttg_theme );
$clttg_theme = str_replace( '</body>', $clttg_footer . '</body>', $clttg_theme );

// @todo: Sanitize the theme output?!
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo $clttg_theme;
