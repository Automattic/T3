import { addFilter } from '@wordpress/hooks';

/**
 * Register new attributes for the image block.
 */
addFilter( 'blocks.registerBlockType', 'tumblr-theme-garden/extend-image-block', ( settings, name ) => {
	if ( typeof settings.attributes !== 'undefined' && name === 'core/image' ) {
		settings.attributes = Object.assign( settings.attributes, {
			media: {
				type: 'array',
				items: {
					type: 'object',
				},
				default: [],
			},
			feedbackToken: {
				type: 'string',
				default: '',
			},
		} );
	}

	return settings;
} );
