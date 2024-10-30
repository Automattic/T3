<?php

namespace CupcakeLabs\T3;

defined( 'ABSPATH' ) || exit;

/**
 * The Tumblr theme parser.
 */
final class Parser {

	/**
	 * Supported keywords in lang: tags that trigger text replacements.
	 *
	 * @var array
	 */
	public array $supported_keywords = array(
		'CurrentPage'          => 'tumblr3_tag_currentpage',
		'TotalPages'           => 'tumblr3_tag_totalpages',
		'SearchResultCount'    => 'tumblr3_tag_searchresultcount',
		'SearchQuery'          => 'tumblr3_tag_searchquery',
		'TimeAgo'              => 'tumblr3_tag_timeago',
		'DayOfWeek'            => 'tumblr3_tag_dayofweek',
		'DayOfMonth'           => 'tumblr3_tag_dayofmonth',
		'DayOfMonthWithSuffix' => 'tumblr3_tag_dayofmonthsuffix',
		'Month'                => 'tumblr3_tag_month',
		'Year'                 => 'tumblr3_tag_year',
		'FormattedTime'        => 'tumblr3_tag_timeago',
		'NoteCount'            => 'tumblr3_tag_notecount',
		'PostAuthorName'       => 'tumblr3_tag_postauthorname',
		'PostTypeNoun'         => '__return_empty_string',
		'Tag'                  => '__return_empty_string',
		'TagResultCount'       => '__return_empty_string',
	);

	/**
	 * Unsupported keywords, these can move to the supported list if as functionality is added.
	 *
	 * @var array
	 */
	public array $unsupported_keywords = array(
		'Asker'             => '__return_empty_string',
		'ReblogParentName'  => '__return_empty_string',
		'ReblogParentTitle' => '__return_empty_string',
		'ReblogRootName'    => '__return_empty_string',
		'ReblogRootTitle'   => '__return_empty_string',
		'PlayCount'         => '__return_empty_string',
	);

	/**
	 * Array to store block openers for unbalanced block tags.
	 *
	 * @var array
	 */
	public array $block_openers = array();

	/**
	 * Position tracker of the current tag in the theme.
	 *
	 * @var int
	 */
	public int $position = 0;

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
			$output = $this->apply_modifier( $output, $attr['modifier'] );
		}

		return $output;
	}

	/**
	 * Applys the current modifier to the output string.
	 *
	 * @param string $output   HTML stub.
	 * @param string $modifier The modifier to apply.
	 *
	 * @return string
	 */
	public function apply_modifier( $output, $modifier ): string {
		switch ( $modifier ) {
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
		$tags      = array_map( 'strtolower', TUMBLR3_TAGS );
		$blocks    = array_map( 'strtolower', TUMBLR3_BLOCKS );
		$options   = array_map( 'strtolower', TUMBLR3_OPTIONS );
		$modifiers = array_map( 'strtolower', TUMBLR3_MODIFIERS );

		/**
		 * Before parsing, clean out comments that could contain tags and cause issues, e.g unbalanced blocks.
		 */
		$content = preg_replace(
			array(
				'/<!--.*?-->/s',               // HTML comments
				'/\/\*[\s\S]*?\*\//',          // CSS and multi-line JS comments
				'/(^|\s+|\;)\s*\/\/[^\r\n]*/', // Single-line JS comments only after line start, whitespace, or semicolon
			),
			'',
			$content
		);

		/**
		 * This is the main parser loop.
		 * It uses a regular expression to find Tumblr tags and blocks and then replaces them with WordPress shortcodes.
		 * The shortcode system allows for dynamic content inside a post loop, and other areas.
		 */
		$content = preg_replace_callback(
			'/\{([a-zA-Z0-9][a-zA-Z0-9\\-\/=" ]*|font\:[a-zA-Z0-9 ]+|text\:[a-zA-Z0-9 ]+|select\:[a-zA-Z0-9 ]+|image\:[a-zA-Z0-9 ]+|color\:[a-zA-Z0-9 ]+|RGBcolor\:[a-zA-Z0-9 ]+|lang\:[a-zA-Z0-9- ]+|[\/]?block\:[a-zA-Z0-9]+( [a-zA-Z0-9=" ]+)*)\}/i',
			function ( $matches ) use ( $tags, $blocks, $options, $modifiers ) {
				++$this->position;

				/**
				 * Return the language string with keyword replacements.
				 *
				 * @return string
				 */
				if ( array_key_exists( $matches[1], TUMBLR3_LANG ) ) {
					return $this->language_helper( $matches[1] );
				}

				// Refactor the matches to lowercase.
				$captured_tag     = $matches[0];
				$raw_tag          = strtolower( $matches[1] );
				$trim_tag         = strtolower( explode( ' ', $raw_tag )[0] );
				$applied_modifier = '';

				// Fix unbalanced block tags.
				$raw_tag = $this->block_fixer( $raw_tag );

				/**
				 * Convert "If" and "IfNot" theme option boolean blocks into a custom shortcode.
				 */
				if ( str_starts_with( ltrim( $raw_tag, '/' ), 'block:if' ) ) {
					return $this->boolean_helper( $raw_tag );
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

				/**
				 * Rewrite prepended modifiers. Only Tags support modifiers.
				 */
				foreach ( $modifiers as $modifier ) {
					if ( str_starts_with( $raw_tag, $modifier ) ) {
						$applied_modifier = strtolower( $modifier );
						$trim_tag         = substr( $trim_tag, strlen( $modifier ) );
						$raw_tag          = substr( $raw_tag, strlen( $modifier ) );
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

						if ( '' !== $applied_modifier && $theme_mod ) {
							return $this->apply_modifier( $theme_mod, $applied_modifier );
						}

						return $theme_mod ? $theme_mod : '';
					}
				}

				// Verify the tag against our array of known tags.
				if ( in_array( ltrim( $trim_tag, '/' ), $tags, true ) ) {
					$shortcode  = 'tag_' . $trim_tag;
					$attributes = $applied_modifier ? "modifier=\"$applied_modifier\"" : '';
					return ( ! empty( $attributes ) ) ? "[{$shortcode} {$attributes}]" : "[{$shortcode}]";
				}

				return $captured_tag;
			},
			$content
		);

		/**
		 * At this point, we can clean out anything that's unsupported.
		 * First: Create a new shortcode regex for the unsupported tags and blocks.
		 * Second: Use the new regex to find and replace the unsupported tags and blocks with an empty string.
		 * Third: Run the content through the shortcode parser to kick-off page creation.
		 */
		$pattern = get_shortcode_regex( array_merge( TUMBLR3_MISSING_BLOCKS, TUMBLR3_MISSING_TAGS ) );
		return tumblr3_do_shortcode( preg_replace_callback( "/$pattern/", '__return_empty_string', $content ) );
	}

	/**
	 * Helper function to fix unbalanced block tags.
	 *
	 * @param string $raw_tag The raw tag.
	 *
	 * @return string The fixed tag.
	 */
	public function block_fixer( $raw_tag ): string {
		if ( 0 === stripos( $raw_tag, 'block:' ) ) {
			// Uh oh, we've got two of the same openers in a row, attempt to fix.
			if ( end( $this->block_openers ) === $raw_tag ) {

				// Log the error.
				error_log(
					$raw_tag . ' is a duplicate block opener. Found at position ' . $this->position . PHP_EOL,
					3,
					TUMBLR3_PATH . 'parser.log'
				);

				$raw_tag = '/' . $raw_tag;
				array_pop( $this->block_openers );

				// Phew, this is a normal block opener.
			} else {
				$this->block_openers[] = $raw_tag;
			}
			// Check if this is a block closer, and test for openers.
		} elseif ( 0 === stripos( $raw_tag, '/block:' ) && end( $this->block_openers ) === substr( $raw_tag, 1 ) ) {
			array_pop( $this->block_openers );
		}

		return $raw_tag;
	}

	/**
	 * Helper function to return a language string with keyword replacements.
	 *
	 * @param string $key The language key.
	 *
	 * @return string The modified language string.
	 */
	public function language_helper( $key ): string {
		// Check if the key contains a supported keyword, if so, return the shortcode.
		foreach ( $this->supported_keywords as $keyword => $callback ) {
			if ( false !== strpos( $key, $keyword ) ) {
				return '[tag_lang key="' . $key . '"]';
			}
		}

		// If no keywords are found, return the shortcode.
		return '[tag_lang value="' . TUMBLR3_LANG[ $key ] . '"]';
	}

	/**
	 * Helper function to convert Tumblr boolean blocks into a custom shortcode.
	 *
	 * @param string $raw_tag The raw tag.
	 *
	 * @return string The converted tag.
	 */
	public function boolean_helper( $raw_tag ): string {
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
}
