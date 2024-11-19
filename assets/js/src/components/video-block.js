import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';

/**
 * Register new attributes for the audio block.
 */
addFilter( 'blocks.registerBlockType', 'tumblr3/extend-video-block', ( settings, name ) => {
	if ( typeof settings.attributes !== 'undefined' && name === 'core/video' ) {
		settings.attributes = Object.assign( settings.attributes, {
			url: {
				type: 'string',
				default: '',
			},
			media: {
				type: 'object',
				default: {},
			},
			provider: {
				type: 'string',
				default: '',
			},
			embedHtml: {
				type: 'string',
				default: '',
			},
			embedIframe: {
				type: 'object',
				default: {},
			},
			embedUrl: {
				type: 'string',
				default: '',
			},
			metadata: {
				type: 'object',
				default: {},
			},
			attribution: {
				type: 'object',
				default: {},
			},
			canAutoplayOnCellular: {
				type: 'boolean',
				default: false,
			},
			duration: {
				type: 'number',
				default: 0,
			},
		} );
	}

	return settings;
} );

/**
 * Extend the video block with sidebar controls.
 */
addFilter(
	'editor.BlockEdit',
	'tumblr3/extend-video-block',
	createHigherOrderComponent( BlockEdit => {
		return props => {
			const { attributes, setAttributes, isSelected } = props;
			const { src, url, provider } = attributes;

			// Sync mediaURL with src if they differ
			if ( src && src !== url ) {
				setAttributes( { url: src } );
			}

			return (
				<>
					<BlockEdit { ...props } />
					{ isSelected && props.name === 'core/video' && (
						<InspectorControls>
							<PanelBody title="Media Information" initialOpen={ true }>
								<TextControl
									label="URL"
									value={ url }
									onChange={ value => setAttributes( { url: value } ) }
								/>
								<TextControl
									label="Content provider"
									value={ provider }
									onChange={ value => setAttributes( { provider: value } ) }
								/>
							</PanelBody>
						</InspectorControls>
					) }
				</>
			);
		};
	}, 'enhanced-video-block' )
);
