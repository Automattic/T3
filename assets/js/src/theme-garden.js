import { createRoot, useEffect } from '@wordpress/element';
import { ThemeGardenFilterBar } from './components/theme-garden-filterbar';
import { ThemeGardenList } from './components/theme-garden-list';
import { __ } from '@wordpress/i18n';
import { withDispatch, withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import './components/theme-garden-store';
import { ThemeGardenOverlay } from './components/theme-garden-overlay';

/**
 * ThemeGarden Component
 *
 * This component provides a user interface for browsing themes from Tumblr's theme garden.
 *
 * CSS classNames reference built-in wp-admin styles, and styles declared in _theme_garden.scss.
 *
 * @param {Object}   props
 * @param {string}   props.logoUrl
 * @param {Function} props.beforeFetchThemes
 * @param {Function} props.fetchThemes
 * @param {Function} props.receiveThemes
 * @param {Function} props.searchThemes
 * @param {Function} props.beforeFetchTheme
 * @param {Function} props.fetchTheme
 * @param {Function} props.receiveTheme
 * @param {Function} props.closeOverlay
 */
const ThemeGarden = ( {
	logoUrl,
	beforeFetchThemes,
	fetchThemes,
	receiveThemes,
	searchThemes,
						  beforeFetchTheme,
	fetchTheme,
	receiveTheme,
	closeOverlay
} ) => {
	/**
	 * Detect backwards and forwards browser navigation.
	 */
	useEffect( () => {
		window.addEventListener( 'popstate', onBrowserNavigation );
		return () => {
			window.removeEventListener( 'popstate', onBrowserNavigation );
		};
	}, [] );

	const fetchThemesByCategory = async category => {
		beforeFetchThemes();
		const response = await fetchThemes( category );
		receiveThemes( response, category, '' );
	};

	const fetchThemesByQuery = async newSearch => {
		beforeFetchThemes();
		const response = await searchThemes( newSearch );
		receiveThemes( response, '', newSearch );
	};

	const fetchThemeById = async themeId => {
		beforeFetchTheme();
		const response = await fetchTheme(themeId);
		receiveTheme( response, themeId );
	}

	/**
	 * After backwards or forwards navigation, check URL search params for indicators that we have to re-fetch themes.
	 *
	 * @return {Promise<void>}
	 */
	const onBrowserNavigation = async () => {
		const urlParams = new URLSearchParams( window.location.search );
		const category = urlParams.get( 'category' ) || 'featured';
		const searchParam = urlParams.get( 'search' ) || '';
		const theme = urlParams.get( 'theme' ) || '';

		console.info( 'detected browser navigation' );
		if ( searchParam !== '' ) {
			console.info( 'fetching themes by search' );
			await fetchThemesByQuery( searchParam );
		} else if ( category !== '' ) {
			console.info( 'fetching themes by category' );
			await fetchThemesByCategory( category );
		}

		if ( theme !== '' ) {
			beforeFetchTheme();
			const response = await fetchTheme( theme );
			receiveTheme( response, theme );
		} else {
			closeOverlay();
		}
	};

	return (
		<div className="wrap">
			<h1 className="wp-heading-inline" id="theme-garden-heading">
				<img className="tumblr-logo-icon" src={ logoUrl } alt="" />
				<span>{ __( 'Tumblr Themes', 'tumblr3' ) }</span>
			</h1>
			<ThemeGardenFilterBar
				fetchThemesByCategory={ fetchThemesByCategory }
				fetchThemesByQuery={ fetchThemesByQuery }
			/>
			<ThemeGardenList fetchThemeById={fetchThemeById} />
			<ThemeGardenOverlay />
		</div>
	);
};

export const ConnectedThemeGarden = compose(
	withSelect( select => ( {
		logoUrl: select( 'tumblr3/theme-garden-store' ).getLogoUrl(),
	} ) ),
	withDispatch( dispatch => ( {
		beforeFetchThemes: () => {
			return dispatch( 'tumblr3/theme-garden-store' ).beforeFetchThemes();
		},
		fetchThemes: category => {
			return dispatch( 'tumblr3/theme-garden-store' ).fetchThemes( category );
		},
		searchThemes: query => {
			return dispatch( 'tumblr3/theme-garden-store' ).searchThemes( query );
		},
		receiveThemes: ( themes, category, search ) => {
			return dispatch( 'tumblr3/theme-garden-store' ).receiveThemes( themes, category, search );
		},
		beforeFetchTheme: () => {
			return dispatch( 'tumblr3/theme-garden-store' ).beforeFetchTheme();
		},
		fetchTheme: id => {
			return dispatch( 'tumblr3/theme-garden-store' ).fetchTheme( id );
		},
		receiveTheme: ( theme, themeId ) => {
			return dispatch( 'tumblr3/theme-garden-store' ).receiveTheme( theme, themeId );
		},
		closeOverlay: () => {
			return dispatch( 'tumblr3/theme-garden-store' ).closeOverlay();
		},
	} ) )
)( ThemeGarden );

const rootElement = document.getElementById( 'tumblr-theme-garden' );
if ( rootElement ) {
	const root = createRoot( rootElement );
	root.render( <ConnectedThemeGarden /> );
} else {
	console.error( 'Failed to find the root element for the settings panel.' ); // eslint-disable-line no-console
}
