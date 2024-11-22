<?php
/**
 * Plugin main class.
 *
 * @package Tumblr3
 */

namespace CupcakeLabs\T3;

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin class.
 *
 * @since   1.0.0
 * @version 1.0.0
 */
class Plugin {

	/**
	 * The Tumblr3 active status.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     bool
	 */
	public bool $tumblr3_active = false;

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
		$this->tumblr3_active = 'tumblr3' === get_option( 'template' );

		// Setup all plugin hooks.
		$this->hooks = new Hooks();
		$this->hooks->initialize( $this->tumblr3_active );

		// Setup the customizer with default and custom theme options.
		$this->customizer = new Customizer();
		$this->customizer->initialize( $this->tumblr3_active );

		$this->theme_garden = new ThemeGarden();
		$this->theme_garden->initialize();

		// In the frontend, setup the parser.
		if ( ! is_admin() ) {
			$this->parser = new Parser();
			$this->parser->initialize();
		}
	}
}
