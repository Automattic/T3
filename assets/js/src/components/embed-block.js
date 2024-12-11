import { InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { PanelBody, TextControl, Button, BaseControl } from '@wordpress/components';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';

/**
 * Register new attributes for the embed block.
 */
addFilter( 'blocks.registerBlockType', 'tumblr-theme-garden/extend-embed-block', ( settings, name ) => {
	if ( typeof settings.attributes !== 'undefined' && name === 'core/embed' ) {
		settings.attributes = Object.assign( settings.attributes, {
			title: {
				type: 'string',
				default: '',
			},
			description: {
				type: 'string',
				default: '',
			},
			author: {
				type: 'string',
				default: '',
			},
			siteName: {
				type: 'string',
				default: '',
			},
			displayUrl: {
				type: 'string',
				default: '',
			},
			poster: {
				type: 'array',
				default: [],
				items: {
					type: 'object',
				},
			},
		} );
	}

	return settings;
} );

/**
 * Extend the embed block with sidebar controls.
 */
addFilter(
	'editor.BlockEdit',
	'tumblr-theme-garden/extend-embed-block',
	createHigherOrderComponent( BlockEdit => {
		return props => {
			const { attributes, setAttributes, isSelected } = props;
			const { url, title, description, author, siteName, displayUrl, poster } = attributes;

			// Sync displayUrl with url if they differ
			if ( url && url !== displayUrl ) {
				setAttributes( { displayUrl: url } );
			}

			return (
				<>
					<BlockEdit { ...props } />
					{ isSelected && props.name === 'core/embed' && (
						<InspectorControls>
							<PanelBody title="Media Information" initialOpen={ true }>
								<TextControl
									label="Title"
									value={ title }
									onChange={ value => setAttributes( { title: value } ) }
								/>
								<TextControl
									label="Description"
									value={ description }
									onChange={ value => setAttributes( { description: value } ) }
								/>
								<TextControl
									label="Author"
									value={ author }
									onChange={ value => setAttributes( { author: value } ) }
								/>
								<TextControl
									label="Site Name"
									value={ siteName }
									onChange={ value => setAttributes( { siteName: value } ) }
								/>
								<TextControl
									label="Display URL"
									value={ displayUrl }
									onChange={ value => setAttributes( { displayUrl: value } ) }
								/>
								<MediaUploadCheck>
									<BaseControl.VisualLabel>{ __( 'Poster image' ) }</BaseControl.VisualLabel>
									<MediaUpload
										title={ __( 'Select poster image' ) }
										onSelect={ media =>
											setAttributes( {
												poster: [
													{
														id: media.id,
														url: media.url,
														alt: media.alt || '',
													},
												],
											} )
										}
										allowedTypes={ [ 'image' ] }
										render={ ( { open } ) => (
											<Button onClick={ open } variant="primary">
												{ poster.length > 0 ? __( 'Replace' ) : __( 'Select' ) }
											</Button>
										) }
									/>
									{ poster.length > 0 && (
										<div>
											<img
												src={ poster[ 0 ].url }
												alt={ poster[ 0 ].alt || 'Poster Image' }
												style={ { maxWidth: '100%', marginTop: '10px' } }
											/>
											<Button isDestructive onClick={ () => setAttributes( { poster: [] } ) }>
												{ __( 'Remove' ) }
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
	}, 'enhanced-embed-block' )
);
