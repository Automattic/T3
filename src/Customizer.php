<?php

namespace CupcakeLabs\T3;

defined( 'ABSPATH' ) || exit;

/**
 * This class is responsible for handling customizer settings.
 */
class Customizer {
	/**
	 * Initializes the class.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  void
	 */
	public function initialize(): void {
		// Late action so we can remove menus.
		add_action( 'customize_register', array( $this, 'remove_defaults' ), 999 );

		add_action( 'customize_register', array( $this, 'global_options' ) );

		add_action( 'customize_register', array( $this, 'theme_specific_options' ) );
	}

	/**
	 * Remove WordPress default settings and sections.
	 *
	 * @param WP_Customize_Manager $wp_customize The customizer manager.
	 *
	 * @return void
	 */
	public function remove_defaults( $wp_customize ): void {
		// Remove the Homepage Settings section
		$wp_customize->remove_section( 'static_front_page' );
	}

	/**
	 * Creates global options to match standard Tumblr options.
	 *
	 * @param WP_Customize_Manager $wp_customize The customizer manager.
	 *
	 * @return void
	 */
	public function global_options( $wp_customize ): void {
		// Add an accent_color setting.
		$wp_customize->add_setting(
			'accent_color',
			array(
				'default'           => '#0073aa',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		// Add a control for the accent_color setting.
		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'accent_color',
				array(
					'label'    => __( 'Accent Color', 'tumblr3' ),
					'section'  => 'colors',
					'settings' => 'accent_color',
				)
			)
		);

		// Add a TitleFont text control.
		$wp_customize->add_setting(
			'title_font',
			array(
				'default'           => 'Arial',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'title_font',
			array(
				'label'    => __( 'Title Font', 'tumblr3' ),
				'section'  => 'tumblr3_font',
				'type'     => 'text',
				'priority' => 10,
			)
		);

		// Add a TitleFontWeight select control.
		$wp_customize->add_setting(
			'title_font_weight',
			array(
				'default'           => 'bold',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'title_font_weight',
			array(
				'label'    => __( 'Title Font Weight', 'tumblr3' ),
				'section'  => 'tumblr3_font',
				'type'     => 'select',
				'choices'  => array(
					'normal' => 'Normal',
					'bold'   => 'Bold',
				),
				'priority' => 10,
			)
		);

		// Add an avatar shape select control.
		$wp_customize->add_setting(
			'avatar_shape',
			array(
				'default'           => 'circle',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'avatar_shape',
			array(
				'label'    => __( 'Avatar Shape', 'tumblr3' ),
				'section'  => 'tumblr3_select',
				'type'     => 'select',
				'choices'  => array(
					'circle' => 'Circle',
					'square' => 'Square',
				),
				'priority' => 10,
			)
		);

		// Add a stretch header image checkbox control.
		$wp_customize->add_setting(
			'stretch_header_image',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'stretch_header_image',
			array(
				'label'    => __( 'Stretch Header Image', 'tumblr3' ),
				'section'  => 'tumblr3_boolean',
				'type'     => 'checkbox',
				'priority' => 10,
			)
		);

		// Add a show avatar checkbox control.
		$wp_customize->add_setting(
			'show_avatar',
			array(
				'default'           => 'yes',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'show_avatar',
			array(
				'label'    => __( 'Show Avatar', 'tumblr3' ),
				'section'  => 'tumblr3_boolean',
				'type'     => 'checkbox',
				'priority' => 10,
			)
		);

		// Add a checkbox to control if links should open in a new tab.
		$wp_customize->add_setting(
			'target_blank',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'target_blank',
			array(
				'label'    => __( 'Open Links in New Tab', 'tumblr3' ),
				'section'  => 'tumblr3_boolean',
				'type'     => 'checkbox',
				'priority' => 10,
			)
		);

		// Add a text control for twitter username.
		$wp_customize->add_setting(
			'twitter_username',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'twitter_username',
			array(
				'label'    => __( 'Twitter Username', 'tumblr3' ),
				'section'  => 'tumblr3_text',
				'type'     => 'text',
				'priority' => 10,
			)
		);

		/**
		 * Theme HTML setting.
		 *
		 * @todo lack of sanitization is a security risk.
		 */
		$wp_customize->add_setting(
			'tumblr3_theme_html',
			array(
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'default'           => '',
				'sanitize_callback' => '',
			)
		);

		$wp_customize->add_control(
			'tumblr3_theme_html',
			array(
				'label'    => __( 'HTML', 'tumblr3' ),
				'section'  => 'tumblr3_html',
				'type'     => 'textarea',
				'priority' => 10,
			)
		);

		// Add a "use tumblr theme" checkbox.
		$wp_customize->add_setting(
			'tumblr3_use_theme',
			array(
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'default'           => 0,
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'tumblr3_use_theme',
			array(
				'label'    => __( 'Use Tumblr Theme?', 'tumblr3' ),
				'section'  => 'tumblr3_html',
				'type'     => 'checkbox',
				'priority' => 5,
			)
		);
	}

	/**
	 * Add theme options parsed from the current tumblr theme.
	 *
	 * @see https://www.tumblr.com/docs/en/custom_themes#theme-options
	 *
	 * @param WP_Customize_Manager $wp_customize The customizer manager.
	 *
	 * @return void
	 */
	public function theme_specific_options( $wp_customize ): void {
		// Add Theme HTML section.
		$wp_customize->add_section(
			'tumblr3_html',
			array(
				'title'    => __( 'Tumblr Theme HTML', 'tumblr3' ),
				'priority' => 30,
			)
		);

		// Add select options section.
		$wp_customize->add_section(
			'tumblr3_select',
			array(
				'title'    => __( 'Theme Select Options', 'tumblr3' ),
				'priority' => 30,
			)
		);

		// Add text options section.
		$wp_customize->add_section(
			'tumblr3_text',
			array(
				'title'    => __( 'Theme Text Options', 'tumblr3' ),
				'priority' => 30,
			)
		);

		// Add font options section.
		$wp_customize->add_section(
			'tumblr3_font',
			array(
				'title'    => __( 'Theme Font Options', 'tumblr3' ),
				'priority' => 30,
			)
		);

		// Add boolean options section.
		$wp_customize->add_section(
			'tumblr3_boolean',
			array(
				'title'    => __( 'Theme Boolean Options', 'tumblr3' ),
				'priority' => 30,
			)
		);

		// Add image options section.
		$wp_customize->add_section(
			'tumblr3_image',
			array(
				'title'    => __( 'Theme Image Options', 'tumblr3' ),
				'priority' => 30,
			)
		);

		// Parse the theme HTML.
		$processor      = new \WP_HTML_Tag_Processor( get_option( 'tumblr3_theme_html', '' ) );
		$select_options = array();

		// Stop on META tags.
		while ( $processor->next_tag( 'META' ) ) {
			$name = $processor->get_attribute( 'name' );

			if ( ! $name ) {
				continue;
			}

			/**
			 * Color options.
			 */
			if ( str_starts_with( $name, 'color:' ) ) {
				$color = $processor->get_attribute( 'content' );
				$label = ltrim( $name, 'color:' );

				// Option names need to be lowercase and without spaces.
				$name = tumblr3_normalize_option_name( $name );

				$wp_customize->add_setting(
					$name,
					array(
						'capability'        => 'edit_theme_options',
						'default'           => $color,
						'sanitize_callback' => 'sanitize_hex_color',
					)
				);

				$wp_customize->add_control(
					new \WP_Customize_Color_Control(
						$wp_customize,
						$name,
						array(
							'label'    => $label,
							'section'  => 'colors',
							'settings' => $name,
						)
					)
				);

				// If it doesn't exist, load the default value into the theme mod.
				if ( ! get_theme_mod( $name ) ) {
					set_theme_mod( $name, $color );
				}

				continue;
			}

			/**
			 * Font options.
			 */
			if ( str_starts_with( $name, 'font:' ) ) {
				$font  = $processor->get_attribute( 'content' );
				$label = ltrim( $name, 'font:' );

				// Option names need to be lowercase and without spaces.
				$name = tumblr3_normalize_option_name( $name );

				$wp_customize->add_setting(
					$name,
					array(
						'capability'        => 'edit_theme_options',
						'default'           => $font,
						'sanitize_callback' => 'sanitize_text_field',
					)
				);

				$wp_customize->add_control(
					$name,
					array(
						'label'    => $label,
						'section'  => 'tumblr3_font',
						'type'     => 'text',
						'priority' => 10,
					)
				);

				// If it doesn't exist, load the default value into the theme mod.
				if ( ! get_theme_mod( $name ) ) {
					set_theme_mod( $name, $font );
				}

				continue;
			}

			/**
			 * Boolean options.
			 */
			if ( str_starts_with( $name, 'if:' ) ) {
				$condition = $processor->get_attribute( 'content' );
				$label     = ltrim( $name, 'if:' );

				// Option names need to be lowercase and without spaces.
				$name = tumblr3_normalize_option_name( $name );

				$wp_customize->add_setting(
					$name,
					array(
						'capability'        => 'edit_theme_options',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					)
				);

				$wp_customize->add_control(
					$name,
					array(
						'label'    => $label,
						'section'  => 'tumblr3_boolean',
						'type'     => 'checkbox',
						'priority' => 10,
					)
				);

				continue;
			}

			/**
			 * Text options.
			 */
			if ( str_starts_with( $name, 'text:' ) ) {
				$text  = $processor->get_attribute( 'content' );
				$label = ltrim( $name, 'text:' );

				// Option names need to be lowercase and without spaces.
				$name = tumblr3_normalize_option_name( $name );

				$wp_customize->add_setting(
					$name,
					array(
						'capability'        => 'edit_theme_options',
						'default'           => $text,
						'sanitize_callback' => 'sanitize_text_field',
					)
				);

				$wp_customize->add_control(
					$name,
					array(
						'label'    => $label,
						'section'  => 'tumblr3_text',
						'type'     => 'text',
						'priority' => 10,
					)
				);

				// If it doesn't exist, load the default value into the theme mod.
				if ( ! get_theme_mod( $name ) ) {
					set_theme_mod( $name, $text );
				}

				continue;
			}

			/**
			 * Image options.
			 */
			if ( str_starts_with( $name, 'image:' ) ) {
				$image = $processor->get_attribute( 'content' );
				$label = ltrim( $name, 'image:' );

				// Option names need to be lowercase and without spaces.
				$name = tumblr3_normalize_option_name( $name );

				$wp_customize->add_setting(
					$name,
					array(
						'capability'        => 'edit_theme_options',
						'default'           => $image,
						'sanitize_callback' => 'esc_url_raw',
					)
				);

				$wp_customize->add_control(
					new \WP_Customize_Image_Control(
						$wp_customize,
						$name,
						array(
							'label'    => $label,
							'section'  => 'tumblr3_image',
							'settings' => $name,
							'priority' => 10,
						)
					)
				);

				// If it doesn't exist, load the default value into the theme mod.
				if ( ! get_theme_mod( $name ) ) {
					set_theme_mod( $name, $image );
				}

				continue;
			}

			/**
			 * Select options. These need to be processed after all other options.
			 */
			if ( str_starts_with( $name, 'select:' ) ) {
				$name = substr( $name, strlen( 'select:' ) );

				$select_options[ $name ][] = array(
					'content' => $processor->get_attribute( 'content' ),
					'title'   => $processor->get_attribute( 'title' ),
				);
			}
		}

		// Parse out select options now that we have aggregated them.
		foreach ( $select_options as $label => $options ) {
			$default = ( isset( $options[0], $options[0]['content'] ) ) ? $options[0]['content'] : '';

			// Option names need to be lowercase and without spaces.
			$name = tumblr3_normalize_option_name( $label );

			$wp_customize->add_setting(
				$name,
				array(
					'capability'        => 'edit_theme_options',
					'default'           => $default,
					'sanitize_callback' => 'sanitize_text_field',
				)
			);

			$wp_customize->add_control(
				$name,
				array(
					'label'    => $label,
					'section'  => 'tumblr3_select',
					'type'     => 'select',
					'choices'  => array_column( $options, 'title', 'content' ),
					'priority' => 10,
				)
			);

			// If it doesn't exist, load the default value into the theme mod.
			if ( ! get_theme_mod( $name ) ) {
				set_theme_mod( $name, $default );
			}
		}
	}
}
