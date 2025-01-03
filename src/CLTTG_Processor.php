<?php
/**
 * This is a custom processor that extends the default WP_HTML_Tag_Processor.
 *
 * @package TumblrThemeGarden
 */

namespace CupcakeLabs\TumblrThemeGarden;

defined( 'ABSPATH' ) || exit;

/**
 * This is a custom processor that extends the default WP_HTML_Tag_Processor.
 */
class CLTTG_Processor extends \WP_HTML_Tag_Processor {
	/**
	 * A function to pull bookmark start and end points in a string.
	 *
	 * @param string $name The name of the bookmark.
	 *
	 * @return WP_HTML_Span|null The bookmark or null if it doesn't exist.
	 */
	public function get_bookmark( $name ) {
		if ( ! array_key_exists( $name, $this->bookmarks ) ) {
			return null;
		}

		return $this->bookmarks[ $name ];
	}
}
