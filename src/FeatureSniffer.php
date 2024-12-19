<?php
/**
 * TumblrThemeGarden theme feature sniffer.
 *
 * @package TumblrThemeGarden
 */

namespace CupcakeLabs\TumblrThemeGarden;

/**
 * Customize Themes Panel Class
 *
 * @since 4.9.0
 *
 * @see WP_Customize_Panel
 */
class FeatureSniffer {
	/**
	 * Array of (currently) unsupported features, noted by a null dependency.
	 * Also an array of supported features that need an extra plugin to function, noted by a dependency array.
	 *
	 * Schema:
array(
	'{feature}' => array(
		'name'       => '{Feature Name}',
		'dependency' => array(
			'name' => '{Plugin Name}',
			'url'  => '{Plugin URL}',
		),
		'alt_tags'   => array(
			'{alt_tag_1}',
			'{alt_tag_2}',
		),
	),
)
	 *
	 * @var array
	 */
	public array $unsupported_features = array(
		'{npf}'        => array(
			'name'       => 'Neue Post Format',
			'dependency' => null,
			'alt_tags'   => array(
				'{jsnpf}',
			),
		),
		'{likebutton}' => array(
			'name'       => 'Like Button',
			'dependency' => array(
				'name' => 'Jetpack',
				'url'  => 'https://wordpress.org/plugins/jetpack/',
			),
		),
	);

	/**
	 * Undocumented variable
	 *
	 * @var array
	 */
	public array $unsupported_found = array();

	/**
	 * The HTML to be sniffed.
	 *
	 * @var string
	 */
	public string $html = '';

	/**
	 * Undocumented function
	 *
	 * @param string $html The HTML to be sniffed.
	 */
	public function __construct( $html = '' ) {
		$this->find_unsupported_features();

		if ( ! empty( $html ) ) {
			$this->html = $html;
		}
	}

	/**
	 * Sniffs the HTML for unsupported features.
	 *
	 * @return void
	 */
	public function find_unsupported_features(): void {
		// Load in either the HTML passed to the class constructor or the option value.
		$html = ( '' === $this->html ) ? strtolower( get_option( 'ttgarden_theme_html' ) ) : strtolower( $this->html );

		// Check each unsupported feature.
		foreach ( $this->unsupported_features as $feature => $data ) {
			// Test the top level feature tag.
			if ( false !== strpos( $html, $feature ) ) {
				$this->unsupported_found[ $data['name'] ] = $data;
			}

			// Test any alternate tags for the feature.
			if ( isset( $data['alt_tags'] ) ) {
				foreach ( $data['alt_tags'] as $alt_tag ) {
					if ( false !== strpos( $html, $alt_tag ) ) {
						$this->unsupported_found[ $data['name'] ] = $data;
					}
				}
			}
		}
	}

	/**
	 * Returns the HTML list for the unsupported features.
	 *
	 * @return string
	 */
	public function get_unsupported_features_html(): string {
		$feature_list = array_map(
			function ( $feature ) {
				return $feature['name'];
			},
			$this->unsupported_found
		);

		return sprintf(
			'<ul><li>%s</li></ul><p>%s</p><a href="#" class="button primary">%s</a>',
			implode( '</li><li>', $feature_list ),
			__( 'These features may require an additional supporting plugin to be installed.', 'tumblr-theme-garden' ),
			__( 'Learn More', 'tumblr-theme-garden' )
		);
	}

	/**
	 * Returns the internal array of found unsupported features.
	 *
	 * @return array
	 */
	public function get_unsupported_features(): array {
		return $this->unsupported_found;
	}
}
