<?php
/**
 * Plugin main class.
 *
 * @package TumblrThemeGarden
 */

namespace CupcakeLabs\TumblrThemeGarden;

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin class.
 *
 * @since   1.0.0
 * @version 1.0.0
 */
class Plugin {

	/**
	 * The TumblrThemeGarden active status.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     bool
	 */
	public bool $ttgarden_active = false;

	/**
	 * The hooks component.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     Hooks|null
	 */
	public ?Hooks $hooks = null;

	/**
	 * The customizer component.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     Customizer|null
	 */
	public ?Customizer $customizer = null;

	/**
	 * The theme browser component.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     ThemeGarden|null
	 */
	public ?ThemeGarden $theme_garden = null;

	/**
	 * The parser component.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     Parser|null
	 */
	public ?Parser $parser = null;

	/**
	 * The block extensions component.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     BlockExtensions|null
	 */
	public ?BlockExtensions $block_extensions = null;

	/**
	 * Plugin constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	protected function __construct() {
		/* Empty on purpose. */
	}

	/**
	 * Prevent cloning.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  void
	 */
	private function __clone() {
		/* Empty on purpose. */
	}

	/**
	 * Prevent unserializing.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  void
	 */
	public function __wakeup() {
		/* Empty on purpose. */
	}

	/**
	 * Returns the singleton instance of the plugin.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  Plugin
	 */
	public static function get_instance(): self {
		static $instance = null;

		if ( null === $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Initializes the plugin components.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  void
	 */
	public function initialize(): void {
		$theme      = wp_get_theme();
		$theme_tags = $theme->get( 'Tags' );

		$this->ttgarden_active = ( is_array( $theme_tags ) ) ? in_array( 'tumblr-theme', $theme_tags, true ) : false;

		// Setup all plugin hooks.
		$this->hooks = new Hooks();
		$this->hooks->initialize( $this->ttgarden_active );

		// Setup the customizer with default and custom theme options.
		$this->customizer = new Customizer();
		$this->customizer->initialize( $this->ttgarden_active );

		$this->theme_garden = new ThemeGarden();
		$this->theme_garden->initialize();

		$this->block_extensions = new BlockExtensions();
		$this->block_extensions->initialize( $this->ttgarden_active );

		// In the frontend, setup the parser.
		$this->parser = new Parser();
		$this->parser->initialize();
	}
}
