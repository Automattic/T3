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
	public array $unsupported_features = array();

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
		$this->unsupported_features = array(
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
					'name'  => 'Jetpack',
					'slug'  => 'jetpack/jetpack.php',
					'url'   => admin_url( 'plugin-install.php?tab=plugin-information&plugin=jetpack&TB_iframe=true' ),
					'wporg' => true,
				),
			),
		);

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

		// Remove any features with dependencies that are installed.
		foreach ( $this->unsupported_found as $feature => $data ) {
			if ( null !== $data['dependency'] && is_plugin_active( $data['dependency']['slug'] ) ) {
				unset( $this->unsupported_found[ $feature ] );
			}
		}
	}

	/**
	 * Returns the HTML list for the unsupported features.
	 *
	 * @return string
	 */
	public function get_unsupported_features_html( $list_only = false ): string {
		// Returns full list and descriptions for customizer and Tumblr theme details.
		if ( ! $list_only ) {
			return sprintf(
				'<ul><li>%s</li></ul><p>%s</p><a href="%s" class="button primary">%s</a>',
				implode(
					'</li><li>',
					array_map(
						function ( $feature ) {
							return sprintf(
								'%s %s',
								$feature['name'],
								null === $feature['dependency'] ? '<span class="dashicons dashicons-no"></span>' : '<span class="dashicons dashicons-yes"></span>'
							);
						},
						$this->unsupported_found
					)
				),
				sprintf(
					'%s %s %s %s',
					esc_html__( 'These may require an additional plugin to be installed', 'tumblr-theme-garden' ),
					'<span class="dashicons dashicons-yes"></span>',
					esc_html__( 'or may be currently unsupported and behave unexpectedly', 'tumblr-theme-garden' ),
					'<span class="dashicons dashicons-no"></span>'
				),
				esc_url( admin_url( 'plugins.php' ) ),
				esc_html__( 'Install Feature Plugins', 'tumblr-theme-garden' )
			);
		}

		/**
		 * Returns a list of features only for the plugin row meta.
		 *
		 * Creates a HTML list of unsupported features with links to the required plugins.
		 */
		return sprintf(
			'<ul><li>%s</li></ul>',
			implode(
				'</li><li>',
				array_filter(
					array_map(
						function ( $feature ) {
							if ( null === $feature['dependency'] ) {
								return null;
							}

							return sprintf(
								'%s: <a href="%s" %s>%s</a>',
								$feature['name'],
								$feature['dependency']['url'],
								$feature['dependency']['wporg'] ? 'class="thickbox open-plugin-details-modal"' : 'target="_blank"',
								$feature['dependency']['name']
							);
						},
						$this->unsupported_found
					)
				)
			)
		);
	}

	/**
	 * Returns the internal array of found unsupported features.
	 *
	 * @return array
	 */
	public function get_unsupported_features( $context = 'customizer' ): array {
		if ( 'customizer' === $context ) {
			return $this->unsupported_found;
		}

		// If we're not in the customizer context, filter out features with null dependencies or dependencies that are installed.
		$unsupported = array();
		foreach ( $this->unsupported_found as $feature => $data ) {
			if ( null === $data['dependency'] || is_plugin_active( $data['dependency']['slug'] ) ) {
				continue;
			}

			$unsupported[ $feature ] = $data;
		}

		return $unsupported;
	}
}
