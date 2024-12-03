import { withDispatch, withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { _x } from '@wordpress/i18n';

/**
 * Displays an overlay with details about a Tumblr theme.
 *
 * CSS classNames reference built-in wp-admin styles, and styles declared in _theme_garden.scss.
 *
 * @param {Object} props
 * @param {boolean} props.isOverlayOpen
 * @param {boolean} props.isFetchingTheme
 * @param {Function} props.closeOverlay
 * @param {Function} props.fetchTheme
 */
const _ThemeGardenOverlay = ({isOverlayOpen, isFetchingTheme, closeOverlay, fetchTheme}) => {
	const handleCloseOverlay = () => {
		closeOverlay();
	}

	const renderThemeDetails = () => {
		if( isFetchingTheme ) {
			return (
				<div className="loading-content">
					<span className="spinner"></span>
				</div>
			);
		}
		return (
			<div className="theme-about wp-clearfix">
				<div className="theme-screenshots">
					<div className="screenshot">
						<img src="https://i.ytimg.com/vi/tfBIpzdckeU/maxresdefault.jpg" alt=""/>
					</div>
				</div>
				<div className="theme-info">
					<h2 className="theme-name">Banana Phone</h2>
					<div>It's a phone that can banana.</div>
				</div>
			</div>
		)
	}

	if (!isOverlayOpen) {
		return null;
	}

	return (
		<div className="theme-overlay" id="tumblr-theme-overlay">
			<div className="theme-backdrop"></div>
			<div className="theme-wrap wp-clearfix">
				<div className="theme-header">
					<button className="close dashicons dashicons-no" onClick={closeOverlay}><span
						className="screen-reader-text">{_x('Close theme details overlay', 'label for a button that will close an overlay', 'tumblr3')}</span></button>
				</div>
				{ renderThemeDetails() }
			</div>
		</div>
	)
}

export const ThemeGardenOverlay = compose(
	withSelect( select => ( {
		isOverlayOpen: select( 'tumblr3/theme-garden-store' ).getIsOverlayOpen(),
		isFetchingTheme: select( 'tumblr3/theme-garden-store' ).getIsFetchingTheme(),
	} ) ),
	withDispatch( dispatch => ( {
		closeOverlay: () => {
			return dispatch( 'tumblr3/theme-garden-store' ).closeOverlay();
		},
		fetchTheme: ( id ) => {
			return dispatch( 'tumblr3/theme-garden-store' ).fetchTheme( id );
		},
	} ) )
)( _ThemeGardenOverlay );
