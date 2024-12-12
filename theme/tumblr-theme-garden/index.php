<?php
/**
 * TumblrThemeGarden theme index file.
 *
 * @package TumblrThemeGarden
 */

defined( 'ABSPATH' ) || exit;

// Shortcodes don't currently have a doing_shortcode() or similar.
// So we need a global to track the context.
ttgarden_set_parse_context( 'theme', true );

// Get the parsed theme HTML.
$ttgarden_theme = get_option( 'ttgarden_theme_html', '' );

/**
 * Capture wp_head output.
 *
 * @todo Can this be done in a more elegant way?
 */
ob_start();
wp_head();
$ttgarden_head = ob_get_contents();
ob_end_clean();

// Parse and potentially store the parsed theme HTML.
$ttgarden_theme = apply_filters( 'ttgarden_theme_output', $ttgarden_theme );

/**
 * Capture wp_footer output.
 *
 * @todo Can this be done in a more elegant way?
 */
ob_start();
wp_footer();
$ttgarden_footer = ob_get_contents();
ob_end_clean();

// Add the head and footer to the theme.
$ttgarden_theme = str_replace( '</head>', $ttgarden_head . '</head>', $ttgarden_theme );
$ttgarden_theme = str_replace( '</body>', $ttgarden_footer . '</body>', $ttgarden_theme );

// @todo: Sanitize the theme output?!
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo $ttgarden_theme;
