<?php
/**
 * CLTTG_Plugin main class.
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
class CLTTG_Plugin {

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
	 * @var     CLTTG_Hooks|null
	 */
	public ?CLTTG_Hooks $hooks = null;

	/**
	 * The customizer component.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     CLTTG_Customizer|null
	 */
	public ?CLTTG_Customizer $customizer = null;

	/**
	 * The theme browser component.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     CLTTG_ThemeGarden|null
	 */
	public ?CLTTG_ThemeGarden $theme_garden = null;

	/**
	 * The parser component.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     CLTTG_Parser|null
	 */
	public ?CLTTG_Parser $parser = null;

	/**
	 * The block extensions component.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     CLTTG_BlockExtensions|null
	 */
	public ?CLTTG_BlockExtensions $block_extensions = null;

	/**
	 * CLTTG_Plugin constructor.
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
	 * @return  CLTTG_Plugin
	 *@version 1.0.0
	 *
	 * @since   1.0.0
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
		$this->ttgarden_active = 'tumblr-theme-garden' === get_option( 'template' );

		// Setup all plugin hooks.
		$this->hooks = new CLTTG_Hooks();
		$this->hooks->initialize( $this->ttgarden_active );

		// Setup the customizer with default and custom theme options.
		$this->customizer = new CLTTG_Customizer();
		$this->customizer->initialize( $this->ttgarden_active );

		$this->theme_garden = new CLTTG_ThemeGarden();
		$this->theme_garden->initialize();

		$this->block_extensions = new CLTTG_BlockExtensions();
		$this->block_extensions->initialize( $this->ttgarden_active );

		// In the frontend, setup the parser.
		if ( ! is_admin() ) {
			$this->parser = new CLTTG_Parser();
			$this->parser->initialize();
		}
	}
}
