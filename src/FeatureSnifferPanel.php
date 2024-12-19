<?php
/**
 * TumblrThemeGarden theme hooks.
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
class FeatureSnifferPanel extends \WP_Customize_Panel {
	/**
	 * Panel type.
	 *
	 * @since 4.9.0
	 * @access public
	 * @var string
	 */
	public $type = 'feature-sniffer';

	/**
	 * An Underscore (JS) template for rendering this panel's container.
	 *
	 * The themes panel renders a custom panel heading with the active theme and a switch themes button.
	 *
	 * @see WP_Customize_Panel::print_template()
	 *
	 * @since 4.9.0
	 */
	protected function render_template() {
		$features = new FeatureSniffer();
		?>
		<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }} cannot-expand">
			<h3>{{ data.title }}</h3>
			<?php echo wp_kses_post( $features->get_unsupported_features_html() ); ?>
		</li>
		<?php
	}
}