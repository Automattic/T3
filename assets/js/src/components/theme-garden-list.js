import { useEffect, useState } from '@wordpress/element';
import { withDispatch, withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { _x } from '@wordpress/i18n';
import { ThemeGardenNoThemes } from './theme-garden-no-themes';
import './theme-garden-store';

/**
 * Displays a list of Tumblr themes.
 *
 * CSS classNames reference built-in wp-admin styles, and styles declared in _theme_garden.scss.
 *
 * @param {Object}   props
 * @param {Array}    props.themes
 * @param {boolean}  props.isFetchingThemes
 * @param {Function} props.fetchThemeById
 */
const _ThemeGardenList = ( { themes, isFetchingThemes, fetchThemeById } ) => {
	const [ localThemes, setLocalThemes ] = useState( themes );

	useEffect( () => {
		setLocalThemes( themes );
	}, [ themes ] );

	const handleDetailsClick = async ( { currentTarget: { value: themeId } } ) => {
		const currentUrl = new URL( window.location.href );
		const params = new URLSearchParams( currentUrl.search );
		params.append( 'theme', themeId );
		currentUrl.search = params.toString();
		await fetchThemeById( themeId );
		window.history.pushState( {}, '', currentUrl.toString() );
	};

	if ( isFetchingThemes ) {
		return (
			<div className="loading-content">
				<span className="spinner"></span>
			</div>
		);
	}

	if ( localThemes.length === 0 ) {
		return <ThemeGardenNoThemes />;
	}

	return (
		<div className="tumblr-themes">
			{ themes.map( theme => {
				const label = `ttgarden-theme-details-${ theme.id }`;
				return (
					<article className="tumblr-theme" key={ theme.title }>
						<header className="tumblr-theme-header">
							<div className="tumblr-theme-title-wrapper">
								<span className="tumblr-theme-title">{ theme.title }</span>
							</div>
						</header>
						<div className="tumblr-theme-content">
							<button
								className="tumblr-theme-details"
								onClick={ handleDetailsClick }
								value={ theme.id }
								id={ label }
							>
								<label htmlFor={ label }>
									<span className="tumblr-theme-detail-button">
										{ _x(
											'Theme details',
											'Text on a button that will show more information about a Tumblr theme',
											'tumblr-theme-garden'
										) }
									</span>
								</label>
								<img src={ theme.thumbnail } alt="" />
							</button>
							<div className="tumblr-theme-footer">
								<a className="rainbow-button" href={ theme.activate_url }>
									Activate
								</a>
							</div>
						</div>
					</article>
				);
			} ) }
		</div>
	);
};

export const ThemeGardenList = compose(
	withSelect( select => ( {
		themes: select( 'tumblr-theme-garden/theme-garden-store' ).getThemes(),
		isFetchingThemes: select( 'tumblr-theme-garden/theme-garden-store' ).getIsFetchingThemes(),
	} ) ),
	withDispatch( dispatch => ( {
		closeOverlay: () => {
			return dispatch( 'tumblr-theme-garden/theme-garden-store' ).closeOverlay();
		},
	} ) )
)( _ThemeGardenList );
