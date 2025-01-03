<?php
/**
 * CLTTG_BlockExtensions class.
 *
 * @package TumblrThemeGarden
 */

namespace CupcakeLabs\TumblrThemeGarden;

defined( 'ABSPATH' ) || exit;

/**
 * CLTTG_BlockExtensions class
 */
class CLTTG_BlockExtensions {
	/**
	 * The TumblrThemeGarden active status.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     bool
	 */
	private $is_clttg_active;

	/**
	 * Initialize block extensions
	 *
	 * @param bool $is_clttg_active Whether flag is active.
	 * @return void
	 */
	public function initialize( $is_clttg_active ): void {
		$this->is_clttg_active = $is_clttg_active;

		// Only run if the TumblrThemeGarden theme is active.
		if ( $this->is_clttg_active ) {
			add_filter( 'render_block', array( $this, 'tumblr_audio_block_output' ), 10, 2 );
		}
	}

	/**
	 * Modifies the front-end rendering of the audio block to render Tumblr audio block markup.
	 *
	 * @param string $block_content The block content.
	 * @param array  $block         The full block, including name and attributes.
	 * @return string Modified block content.
	 */
	public function tumblr_audio_block_output( $block_content, $block ) {
		if ( get_post_format() !== false && 'core/audio' !== $block['blockName'] ) {
			return $block_content;
		}

		$attrs = $block['attrs'];

		// Only modify if we have the Tumblr custom attributes
		if ( $this->is_clttg_active && empty( $attrs['mediaURL'] ) && empty( $attrs['mediaTitle'] ) ) {
			return $block_content;
		}

		$media_url    = $attrs['mediaURL'] ?? '';
		$media_title  = $attrs['mediaTitle'] ?? '';
		$media_artist = $attrs['mediaArtist'] ?? '';
		$media_album  = $attrs['mediaAlbum'] ?? '';
		$poster_url   = $attrs['poster']['url'] ?? '';

		$output = sprintf(
			'<figure class="tmblr-full">
				<figcaption class="audio-caption">
					<span class="tmblr-audio-meta audio-details">
						<span class="tmblr-audio-meta title">%s</span>
						<span class="tmblr-audio-meta artist">%s</span>
						<span class="tmblr-audio-meta album">%s</span>
					</span>
					%s
				</figcaption>
				<audio controls="controls">
					<source src="%s" type="audio/mpeg">
				</audio>
			</figure>',
			esc_html( $media_title ),
			esc_html( $media_artist ),
			esc_html( $media_album ),
			$poster_url ? sprintf( '<img class="album-cover" src="%s" alt="' . esc_attr__( 'Poster image', 'tumblr-theme-garden' ) . '">', esc_url( $poster_url ) ) : '',
			esc_url( $media_url )
		);

		return $output;
	}
}
