import { InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, TextControl, Button } from '@wordpress/components';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';

/**
 * Register new attributes for the audio block.
 */
addFilter( 'blocks.registerBlockType', 'tumblr3/extend-audio-block', ( settings, name ) => {
	if ( typeof settings.attributes !== 'undefined' && name === 'core/audio' ) {
		settings.attributes = Object.assign( settings.attributes, {
			mediaURL: {
				type: 'string',
				default: '',
			},
			mediaTitle: {
				type: 'string',
				default: '',
			},
			mediaArtist: {
				type: 'string',
				default: '',
			},
			mediaAlbum: {
				type: 'string',
				default: '',
			},
			poster: {
				type: 'object',
				default: {},
			},
		} );
	}

	return settings;
} );

/**
 * Extend the audio block with sidebar controls.
 */
addFilter(
	'editor.BlockEdit',
	'tumblr3/extend-audio-block',
	createHigherOrderComponent( BlockEdit => {
		return props => {
			const { attributes, setAttributes, isSelected } = props;
			const { src, mediaURL, mediaTitle, mediaArtist, mediaAlbum, poster } = attributes;

			// Sync mediaURL with src if they differ
			if ( src && src !== mediaURL ) {
				setAttributes( { mediaURL: src } );
			}

			return (
				<>
					<BlockEdit { ...props } />
					{ isSelected && props.name === 'core/audio' && (
						<InspectorControls>
							<PanelBody title="Media Information" initialOpen={ true }>
								<TextControl
									label="Media Title"
									value={ mediaTitle }
									onChange={ value => setAttributes( { mediaTitle: value } ) }
								/>
								<TextControl
									label="Media Artist"
									value={ mediaArtist }
									onChange={ value => setAttributes( { mediaArtist: value } ) }
								/>
								<TextControl
									label="Media Album"
									value={ mediaAlbum }
									onChange={ value => setAttributes( { mediaAlbum: value } ) }
								/>
								<MediaUploadCheck>
									<MediaUpload
										onSelect={ media =>
											setAttributes( { poster: { url: media.url, alt: media.alt } } )
										}
										allowedTypes={ [ 'image' ] }
										render={ ( { open } ) => (
											<Button onClick={ open } variant="secondary">
												{ poster.url ? 'Replace Poster Image' : 'Upload Poster Image' }
											</Button>
										) }
									/>
									{ poster.url && (
										<div>
											<img
												src={ poster.url }
												alt={ poster.alt || 'Poster Image' }
												style={ { maxWidth: '100%' } }
											/>
											<Button isDestructive onClick={ () => setAttributes( { poster: {} } ) }>
												Remove Poster Image
											</Button>
										</div>
									) }
								</MediaUploadCheck>
							</PanelBody>
						</InspectorControls>
					) }
				</>
			);
		};
	}, 'enhanced-audio-block' )
);
