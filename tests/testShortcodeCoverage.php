<?php
/**
 * Class SampleTest
 *
 * @package Tumblr_Theme_Translator
 */

/**
 * Sample test case.
 */
class TestShortcodeCoverage extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
	public function test_tag_coverage() {
		$tags         = TUMBLR3_TAGS;
		$missing_tags = TUMBLR3_MISSING_TAGS;

		// Arrays to store tags with and without shortcodes.
		$tags_with_shortcodes         = array();
		$tags_without_shortcodes      = array();
		$tags_with_missing_shortcodes = array();

		foreach ( $tags as $tag ) {
			$tag_shortcode = 'tag_' . strtolower( $tag );

			// Check if the shortcode exists and store the result.
			if ( shortcode_exists( $tag_shortcode ) ) {
				$tags_with_shortcodes[] = $tag;
			} elseif ( in_array( $tag_shortcode, $missing_tags, true ) ) {
				$tags_with_missing_shortcodes[] = $tag;
			} else {
				$tags_without_shortcodes[] = $tag;
			}
		}

		// ANSI color codes for green and red.
		$green  = "\033[32m";
		$red    = "\033[31m";
		$yellow = "\033[33m";
		$reset  = "\033[0m";

		// Output the results in color.
		fwrite( STDOUT, "Tags with shortcode coverage:\n" );
		fwrite( STDOUT, $green . implode( ', ', $tags_with_shortcodes ) . $reset . "\n\n" );

		fwrite( STDOUT, "Tags without shortcode coverage:\n" );
		fwrite( STDOUT, $red . implode( ', ', $tags_without_shortcodes ) . $reset . "\n\n" );

		fwrite( STDOUT, "Tags with missing WordPress functionality:\n" );
		fwrite( STDOUT, $yellow . implode( ', ', $tags_with_missing_shortcodes ) . $reset . "\n\n" );

		// Optionally, use assertions for better integration with PHPUnit output.
		$this->assertTrue( ! empty( $tags_without_shortcodes ) );
	}

	public function test_block_coverage() {
		$blocks         = TUMBLR3_BLOCKS;
		$missing_blocks = TUMBLR3_MISSING_BLOCKS;

		// Arrays to store blocks with and without shortcodes.
		$blocks_with_shortcodes         = array();
		$blocks_without_shortcodes      = array();
		$blocks_with_missing_shortcodes = array();

		foreach ( $blocks as $block ) {
			$block_shortcode = 'block_' . strtolower( str_replace( 'block:', '', $block ) );

			// Check if the shortcode exists and store the result.
			if ( shortcode_exists( $block_shortcode ) ) {
				$blocks_with_shortcodes[] = $block;
			} elseif ( in_array( $block_shortcode, $missing_blocks, true ) ) {
				$blocks_with_missing_shortcodes[] = $block;
			} else {
				$blocks_without_shortcodes[] = $block;
			}
		}

		// ANSI color codes for green and red.
		$green  = "\033[32m";
		$red    = "\033[31m";
		$yellow = "\033[33m";
		$reset  = "\033[0m";

		// Output the results in color.
		fwrite( STDOUT, "Blocks with shortcode coverage:\n" );
		fwrite( STDOUT, $green . implode( ', ', $blocks_with_shortcodes ) . $reset . "\n\n" );

		fwrite( STDOUT, "Blocks without shortcode coverage:\n" );
		fwrite( STDOUT, $red . implode( ', ', $blocks_without_shortcodes ) . $reset . "\n\n" );

		fwrite( STDOUT, "Blocks with missing WordPress functionality:\n" );
		fwrite( STDOUT, $yellow . implode( ', ', $blocks_with_missing_shortcodes ) . $reset . "\n\n" );

		// Optionally, use assertions for better integration with PHPUnit output.
		$this->assertTrue( ! empty( $blocks_without_shortcodes ) );
	}
}
