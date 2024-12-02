import { withDispatch, withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';

/**
 * Displays an overlay with details about a Tumblr theme.
 *
 * CSS classNames reference built-in wp-admin styles, and styles declared in _theme_garden.scss.
 *
 * @param {Object} props
 * @param {boolean} props.isOverlayOpen
 * @param {Function} props.closeOverlay
 */
const _ThemeGardenOverlay = ({isOverlayOpen, closeOverlay}) => {
	const handleCloseOverlay = () => {
		closeOverlay();
	}
	if ( !isOverlayOpen ) {
		return null;
	}

	return(
		<div className="theme-overlay" id="tumblr-theme-overlay">
			<div className="theme-backdrop"></div>
			<div className="theme-wrap wp-clearfix">
				<div className="theme-header">
					<button className="close dashicons dashicons-no" onClick={closeOverlay}><span className="screen-reader-text">Close details dialog</span></button>
				</div>
				<div className="theme-about wp-clearfix">
					<div className="theme-screenshots">
						<div className="screenshot">
							<img src="https://i.ytimg.com/vi/tfBIpzdckeU/maxresdefault.jpg" alt="" />
						</div>
					</div>
					<div className="theme-info">
						<h2 className="theme-name">Banana Phone</h2>
						<div>It's a phone that can banana.</div>
					</div>
				</div>
			</div>
		</div>
	)
}

export const ThemeGardenOverlay = compose(
	withSelect( select => ( {
		isOverlayOpen: select( 'tumblr3/theme-garden-store' ).getIsOverlayOpen(),
	} ) ),
	withDispatch( dispatch => ( {
		closeOverlay: themes => {
			return dispatch( 'tumblr3/theme-garden-store' ).closeOverlay();
		},
	} ) )
)( _ThemeGardenOverlay );
