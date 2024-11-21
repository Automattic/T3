<?php

namespace CupcakeLabs\T3;

/**
 * This class is responsible for the REST API side of the plugin.
 * It registers the REST routes and handles the requests to the API.
 */
class RestApi {
	public function initialize() {
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * Register REST routes.
	 *
	 * @return void
	 */
	public function register_rest_routes(): void {
		register_rest_route(
			'tumblr3/v1',
			'/themes',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_themes' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);
	}

	/**
	 * Get the settings for the queue.
	 *
	 * @return \WP_REST_Response The settings for the queue.
	 */
	public function get_themes(): \WP_REST_Response {
		$data = ['hello' => 'world'];
		return new \WP_REST_Response( $data, 200 );
	}
}
