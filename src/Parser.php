<?php

namespace CupcakeLabs\T3;

defined( 'ABSPATH' ) || exit;

final class Parser {

	/**
	 * Initializes the Theme Parser.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  void
	 */
	public function initialize(): void {
		// Handle output modifiers.
		add_filter( 'do_shortcode_tag', array( $this, 'modifiers' ), 10, 3 );

		// Parse the Tumblr theme.
		add_filter( 'tumblr3_theme_output', array( $this, 'parse_theme' ), 10 );
	}

	/**
	 * Filter to handle modifiers in shortcode output.
	 *
	 * @param string $output The shortcode output.
	 * @param string $tag    The shortcode name.
	 * @param array  $attr   The shortcode attributes.
	 *
	 * @return string The modified output.
	 */
	public function modifiers( $output, $tag, $attr ) {
		if ( isset( $attr['modifier'] ) ) {
			switch ( $attr['modifier'] ) {
				case 'rgb':
					// Convert hex to RGB
					if ( preg_match( '/^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i', $output, $parts ) ) {
						$r      = hexdec( $parts[1] );
						$g      = hexdec( $parts[2] );
						$b      = hexdec( $parts[3] );
						$output = "$r, $g, $b";
					}
					break;
				case 'plaintext':
					$output = wp_strip_all_tags( $output );
					break;
				case 'js':
					$output = wp_json_encode( $output );
					break;
				case 'jsplaintext':
					$output = wp_json_encode( wp_strip_all_tags( $output ) );
					break;
				case 'urlencoded':
					$output = rawurlencode( $output );
					break;
			}
		}
		return $output;
	}

	/**
	 * The main parser in the plugin.
	 * This turns a Tumblr .HTML template into something parseable by WordPress.
	 * Currently that's [shortcode] syntax, this could change in the future if needed.
	 *
	 * @param string $content Tumblr theme HTML content.
	 *
	 * @return string Parsed content.
	 */
	public function parse_theme( $content ): string {
		$tags          = array_map( 'strtolower', TUMBLR3_TAGS );
		$blocks        = array_map( 'strtolower', TUMBLR3_BLOCKS );
		$lang          = array_change_key_case( TUMBLR3_LANG, CASE_LOWER );
		$options       = array_map( 'strtolower', TUMBLR3_OPTIONS );
		$modifiers     = array_map( 'strtolower', TUMBLR3_MODIFIERS );
		$block_openers = array();
		$position      = 0;

		// Capture each Tumblr Tag in the page and verify it against our arrays.
		$content = preg_replace_callback(
			'/\{([a-zA-Z0-9][a-zA-Z0-9\\-\/=" ]*|font\:[a-zA-Z0-9 ]+|text\:[a-zA-Z0-9 ]+|select\:[a-zA-Z0-9 ]+|image\:[a-zA-Z0-9 ]+|color\:[a-zA-Z0-9 ]+|RGBcolor\:[a-zA-Z0-9 ]+|lang\:[a-zA-Z0-9- ]+|[\/]?block\:[a-zA-Z0-9]+( [a-zA-Z0-9=" ]+)*)\}/i',
			function ( $matches ) use ( $tags, $blocks, $lang, $options, $modifiers, &$block_openers, &$position ) {
				++$position;
				$captured_tag = $matches[0];
				$raw_tag      = strtolower( $matches[1] );
				$trim_tag     = strtolower( explode( ' ', $raw_tag )[0] );
				$attr         = '';

				// Check if this is a block opener.
				if ( 0 === stripos( $raw_tag, 'block:' ) ) {
					// Uh oh, we've got two of the same openers in a row, attempt to fix.
					if ( end( $block_openers ) === $raw_tag ) {

						// Log the error.
						error_log(
							$raw_tag . ' is a duplicate block opener. Found at position ' . $position . PHP_EOL,
							3,
							TUMBLR3_PATH . 'parser.log'
						);

						$fixed   = true;
						$raw_tag = '/' . $raw_tag;
						array_pop( $block_openers );

						// Phew, this is a normal block opener.
					} else {
						$block_openers[] = $raw_tag;
					}
					// Check if this is a block closer, and test for openers.
				} elseif ( 0 === stripos( $raw_tag, '/block:' ) && end( $block_openers ) === substr( $raw_tag, 1 ) ) {
					array_pop( $block_openers );
				}

				/**
				 * Convert "IfNot" theme option boolean blocks into a custom shortcode.
				 */
				if ( str_starts_with( ltrim( $raw_tag, '/' ), 'block:if' ) ) {
					$condition       = ( str_starts_with( ltrim( $raw_tag, '/' ), 'block:ifnot' ) ) ? 'ifnot' : 'if';
					$normalized_attr = str_replace(
						array(
							' ',
							'block:ifnot',
							'block:if',
							'/',
						),
						'',
						$raw_tag
					);
					$shortcode       = 'block_' . $condition . '_' . $normalized_attr;

					// Fix for nesting if blocks.
					add_shortcode( $shortcode, 'tumblr3_block_' . $condition . '_theme_option' );

					return ( str_starts_with( $raw_tag, '/' ) ) ? '[/' . $shortcode . ']' : '[' . $shortcode . " name=\"$normalized_attr\"]";
				}

				/**
				 * Test for modifiers.
				 */
				$applied_modifier = '';
				foreach ( $modifiers as $modifier ) {
					if ( str_starts_with( $raw_tag, $modifier ) ) {
						$applied_modifier = strtolower( $modifier );
						$raw_tag          = substr( $raw_tag, strlen( $modifier ) );
						$trim_tag         = substr( $trim_tag, strlen( $modifier ) );
						break;
					}
				}

				/**
				 * Handle theme options (dynamic tags).
				 */
				foreach ( $options as $option ) {
					if ( str_starts_with( $raw_tag, $option ) ) {
						// Normalize the option name.
						$theme_mod = get_theme_mod( tumblr3_normalize_option_name( $raw_tag ) );

						return $theme_mod ? $theme_mod : $captured_tag;
					}
				}

				// Verify the block against our array.
				// @todo write attribute parser.
				if ( str_starts_with( ltrim( $raw_tag, '/' ), 'block:' ) ) {
					$block_parts = explode( ' ', trim( $raw_tag ) );

					if ( in_array( ltrim( $block_parts[0], '/' ), $blocks, true ) ) {
						return '[' . str_replace( 'block:', 'block_', $raw_tag ) . ']';
					}

					// False positive.
					return $captured_tag;
				}

				// Verify the tag against our array of known tags.
				if ( in_array( ltrim( $trim_tag, '/' ), $tags, true ) ) {
					$shortcode  = 'tag_' . $trim_tag;
					$attributes = $applied_modifier ? "modifier=\"$applied_modifier\"" : '';
					return ( ! empty( $attributes ) ) ? "[{$shortcode} {$attributes}]" : "[{$shortcode}]";
				}

				// If the lang tag is found, return the correct language. Accounts for different return types.
				if ( array_key_exists( $raw_tag, $lang ) ) {
					$return_lang = $lang[ $raw_tag ];
					return ( is_array( $return_lang ) ) ? $return_lang[0] : $return_lang;
				}

				return $captured_tag;
			},
			$content
		);

		// At this point, we can clean out anything that's unsupported, replace with an empty string.
		$pattern = get_shortcode_regex( array_merge( TUMBLR3_MISSING_BLOCKS, TUMBLR3_MISSING_TAGS ) );
		return tumblr3_do_shortcode( preg_replace_callback( "/$pattern/", '__return_empty_string', $content ) );
	}
}
