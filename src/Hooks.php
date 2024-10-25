<?php

namespace CupcakeLabs\T3;

defined( 'ABSPATH' ) || exit;

/**
 * Logical node for all integration functionalities.
 *
 * @since   1.0.0
 * @version 1.0.0
 */
final class Hooks {

	/**
	 * Initializes the Hooks.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  void
	 */
	public function initialize(): void {
		add_filter( 'do_shortcode_tag', array( $this, 'tumblr3_handle_modifiers' ), 10, 3 );

		add_filter( 'tumblr3_theme_output', array( $this, 'tumblr3_theme_parse' ), 10 );
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
	public function tumblr3_handle_modifiers( $output, $tag, $attr ) {
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
	public function tumblr3_theme_parse( $content ): string {
		$tags      = array_map( 'strtolower', array_keys( TUMBLR3_TAGS ) );
		$blocks    = array_map( 'strtolower', array_keys( TUMBLR3_BLOCKS ) );
		$lang      = array_map( 'strtolower', array_keys( TUMBLR3_LANG ) );
		$options   = array_map( 'strtolower', array_keys( TUMBLR3_OPTIONS ) );
		$modifiers = array_map( 'strtolower', TUMBLR3_MODIFIERS );

		// Capture each Tumblr Tag in the page and verify it against our arrays.
		$content = preg_replace_callback(
			'/\{([^\s}][^(}]*)\}/',
			function ( $matches ) use ( $tags, $blocks, $lang, $options, $modifiers ) {
				$captured_tag = $matches[0];
				$raw_tag      = strtolower( $matches[1] );
				$trim_tag     = strtolower( explode( ' ', $raw_tag )[0] );
				$attr         = '';

				/**
				 * Convert "IfNot" theme option boolean blocks into a custom shortcode.
				 */
				if ( str_starts_with( ltrim( $raw_tag, '/' ), 'block:ifnot' ) ) {
					$normalized_attr = str_replace(
						array(
							' ',
							'block:ifnot',
						),
						'',
						$raw_tag
					);

					return ( str_starts_with( $raw_tag, '/' ) ) ? '[/block_ifnot_theme_option]' : '[block_ifnot_theme_option name="' . $normalized_attr . '"]';
				}

				/**
				 * Convert "If" theme option boolean blocks into a custom shortcode.
				 */
				if ( str_starts_with( ltrim( $raw_tag, '/' ), 'block:if' ) ) {
					$normalized_attr = str_replace(
						array(
							' ',
							'block:if',
						),
						'',
						$raw_tag
					);

					return ( str_starts_with( $raw_tag, '/' ) ) ? '[/block_if_theme_option]' : '[block_if_theme_option name="' . $normalized_attr . '"]';
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

				// Is the tag one of the portrait functions? Convert the sizing to an attribute to avoid a function for each size.
				if ( str_ends_with( $trim_tag, '-16' ) ||
				str_ends_with( $trim_tag, '-24' ) ||
				str_ends_with( $trim_tag, '-30' ) ||
				str_ends_with( $trim_tag, '-40' ) ||
				str_ends_with( $trim_tag, '-48' ) ||
				str_ends_with( $trim_tag, '-64' ) ||
				str_ends_with( $trim_tag, '-96' ) ||
				str_ends_with( $trim_tag, '-100' ) ||
				str_ends_with( $trim_tag, '-128' ) ||
				str_ends_with( $trim_tag, '-250' ) ||
				str_ends_with( $trim_tag, '-400' ) ||
				str_ends_with( $trim_tag, '-500' ) ||
				str_ends_with( $trim_tag, '-640' ) ||
				str_ends_with( $trim_tag, '-700' )
				) {
					$split_tag = explode( '-', $trim_tag );

					// Ensure the tag is in the correct format.
					if ( count( $split_tag ) !== 2 ) {
						return $captured_tag;
					}

					$trim_tag = $split_tag[0];
					$attr     = ' size=' . $split_tag[1];
				}

				// Verify the tag against our array of known tags.
				if ( in_array( ltrim( $trim_tag, '/' ), $tags, true ) ) {
					$shortcode         = 'tag_' . $trim_tag;
					$attributes        = array_filter( array( $attr, $applied_modifier ? "modifier=\"$applied_modifier\"" : '' ) );
					$attributes_string = implode( ' ', $attributes );
					return ( ! empty( $attributes_string ) ) ? "[{$shortcode} {$attributes_string}]" : "[{$shortcode}]";
				}

				// Verify the lang tag against our array of known tags.
				$pos = array_search( substr( $raw_tag, 5 ), $lang, true );

				// If the lang tag is found, return the correct language. Accounts for different return types.
				if ( false !== $pos ) {
					$return_lang = array_values( TUMBLR3_LANG )[ $pos ];
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
