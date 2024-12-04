import { useCallback } from '@wordpress/element';
import { withDispatch, withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { _x } from '@wordpress/i18n';

/**
 * Displays an overlay with details about a Tumblr theme.
 *
 * CSS classNames reference built-in wp-admin styles, and styles declared in _theme_garden.scss.
 *
 * @param {Object}   props
 * @param {Array}    props.themes
 * @param {boolean}  props.isOverlayOpen
 * @param {boolean}  props.isFetchingTheme
 * @param {Function} props.closeOverlay
 * @param {Object}   props.themeDetails
 */
const _ThemeGardenOverlay = ( {
	themes,
	isOverlayOpen,
	isFetchingTheme,
	closeOverlay,
	themeDetails,
} ) => {
	const handleCloseClick = () => {
		const currentUrl = new URL( window.location.href );
		const params = new URLSearchParams( currentUrl.search );
		params.delete( 'theme' );
		currentUrl.search = params.toString();
		window.history.pushState( {}, '', currentUrl.toString() );
		closeOverlay();
	};

	const renderThemeDetails = useCallback( () => {
		if ( isFetchingTheme || ! themeDetails ) {
			return (
				<div className="loading-content wp-clearfix">
					<span className="spinner"></span>
				</div>
			);
		}

		return (
			<div className="theme-about wp-clearfix">
				<div className="theme-screenshots">
					<div className="screenshot">
						<img src={ themeDetails.screenshots[ 0 ] } alt="" />
					</div>
				</div>
				<div className="theme-info">
					<h2 className="theme-name">{ themeDetails.title }</h2>
					<div dangerouslySetInnerHTML={ { __html: themeDetails.description } }></div>
				</div>
			</div>
		);
	}, [ themeDetails, isFetchingTheme ] );

	if ( ! isOverlayOpen ) {
		return null;
	}

	return (
		<div className="theme-overlay" id="tumblr-theme-overlay">
			<div className="theme-backdrop"></div>
			<div className="theme-wrap wp-clearfix">
				<div className="theme-header">
					<button className="close dashicons dashicons-no" onClick={ handleCloseClick }>
						<span className="screen-reader-text">
							{ _x(
								'Close theme details overlay',
								'label for a button that will close an overlay',
								'tumblr3'
							) }
						</span>
					</button>
				</div>
				{ renderThemeDetails() }
			</div>
		</div>
	);
};

export const ThemeGardenOverlay = compose(
	withSelect( select => ( {
		themes: select( 'tumblr3/theme-garden-store' ).getThemes(),
		isOverlayOpen: select( 'tumblr3/theme-garden-store' ).getIsOverlayOpen(),
		isFetchingTheme: select( 'tumblr3/theme-garden-store' ).getIsFetchingTheme(),
		themeDetails: select( 'tumblr3/theme-garden-store' ).getThemeDetails(),
	} ) ),
	withDispatch( dispatch => ( {
		closeOverlay: () => {
			return dispatch( 'tumblr3/theme-garden-store' ).closeOverlay();
		},
	} ) )
)( _ThemeGardenOverlay );
