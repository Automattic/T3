import { useCallback } from '@wordpress/element';
import { withDispatch, withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { _x, sprintf } from '@wordpress/i18n';
import classNames from 'classnames';

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
 * @param {Function} props.fetchThemeById
 */
const _ThemeGardenOverlay = ( {
	themes,
	isOverlayOpen,
	isFetchingTheme,
	closeOverlay,
	themeDetails,
	fetchThemeById,
} ) => {
	const handleCloseClick = useCallback( () => {
		const currentUrl = new URL( window.location.href );
		const params = new URLSearchParams( currentUrl.search );
		params.delete( 'theme' );
		currentUrl.search = params.toString();
		window.history.pushState( {}, '', currentUrl.toString() );
		closeOverlay();
	}, [ closeOverlay ] );

	console.log(themeDetails);

	const renderThemeAuthor = () => {
		if (!themeDetails.author) {
			return null;
		}
		const authorLink = `<a href="${themeDetails.author.url}">${themeDetails.author.name}</a>`;
		const authorHtml = _x(sprintf('By %s', authorLink), 'By-line for a theme', 'tumblr-theme-garden');
		return <p className='theme-author' dangerouslySetInnerHTML={{__html: authorHtml}}></p>;
	}

	const renderThemeDetails = useCallback(() => {
		const authorLink = `<a href="${ themeDetails.author.url}">${themeDetails.author.name}</a>`;
		const authorHtml =  _x(sprintf('By %s', authorLink), 'By-line for a theme', 'tumblr-theme-garden' );
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
					{renderThemeAuthor()}
					<p className="theme-tags"><span></span></p>
				</div>
			</div>
		);
	}, [ themeDetails, isFetchingTheme ] );

	const onClickNavigate = useCallback(
		async nextIndex => {
			const currentUrl = new URL( window.location.href );
			const params = new URLSearchParams( currentUrl.search );
			const nextId = themes[ nextIndex ].id;
			params.delete( 'theme' );
			params.append( 'theme', nextId );
			currentUrl.search = params.toString();
			await fetchThemeById( nextId );
			window.history.pushState( {}, '', currentUrl.toString() );
		},
		[ themes, fetchThemeById ]
	);

	const renderThemeHeader = useCallback( () => {
		const currentIndex = themes.findIndex( theme => theme.id === themeDetails.id );
		const prevButtonDisabled = currentIndex === -1 || currentIndex === 0;
		const nextButtonDisabled = currentIndex === -1 || currentIndex === themes.length - 1;

		return (
			<div className="theme-header">
				<button
					className={ classNames( 'left', 'dashicons', 'dashicons-no', {
						disabled: prevButtonDisabled,
					} ) }
					disabled={ prevButtonDisabled }
					onClick={ () => onClickNavigate( currentIndex - 1 ) }
				>
					<span className="screen-reader-text">
						{ _x(
							'Show previous theme',
							'label for a button that will navigate to previous theme',
							'tumblr-theme-garden'
						) }
					</span>
				</button>
				<button
					className={ classNames( 'right', 'dashicons', 'dashicons-no', {
						disabled: nextButtonDisabled,
					} ) }
					disabled={ nextButtonDisabled }
					onClick={ () => onClickNavigate( currentIndex + 1 ) }
				>
					<span className="screen-reader-text">
						{ _x(
							'Show next theme',
							'label for a button that will navigate to next theme',
							'tumblr-theme-garden'
						) }
					</span>
				</button>
				<button className="close dashicons dashicons-no" onClick={ handleCloseClick }>
					<span className="screen-reader-text">
						{ _x(
							'Close theme details overlay',
							'label for a button that will close an overlay',
							'tumblr-theme-garden'
						) }
					</span>
				</button>
			</div>
		);
	}, [ themeDetails, themes, onClickNavigate, handleCloseClick ] );

	if ( ! isOverlayOpen || ! themeDetails ) {
		return null;
	}

	return (
		<div className="theme-overlay" id="tumblr-theme-overlay">
			<div className="theme-backdrop"></div>
			<div className="theme-wrap wp-clearfix">
				{ renderThemeHeader() }
				{ renderThemeDetails() }
			</div>
		</div>
	);
};

export const ThemeGardenOverlay = compose(
	withSelect( select => ( {
		themes: select( 'tumblr-theme-garden/theme-garden-store' ).getThemes(),
		isOverlayOpen: select( 'tumblr-theme-garden/theme-garden-store' ).getIsOverlayOpen(),
		isFetchingTheme: select( 'tumblr-theme-garden/theme-garden-store' ).getIsFetchingTheme(),
		themeDetails: select( 'tumblr-theme-garden/theme-garden-store' ).getThemeDetails(),
	} ) ),
	withDispatch( dispatch => ( {
		closeOverlay: () => {
			return dispatch( 'tumblr-theme-garden/theme-garden-store' ).closeOverlay();
		},
	} ) )
)( _ThemeGardenOverlay );
