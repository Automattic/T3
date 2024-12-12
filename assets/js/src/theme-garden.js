import { createRoot, useEffect, useCallback } from '@wordpress/element';
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
 * @param {string}   props.selectedCategory
 * @param {string}   props.search
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
	closeOverlay,
	search,
	selectedCategory,
} ) => {
	const fetchThemesByCategory = useCallback(
		async category => {
			beforeFetchThemes();
			const response = await fetchThemes( category );
			receiveThemes( response, category, '' );
		},
		[ beforeFetchThemes, receiveThemes, fetchThemes ]
	);

	const fetchThemesByQuery = useCallback(
		async newSearch => {
			beforeFetchThemes();
			const response = await searchThemes( newSearch );
			receiveThemes( response, '', newSearch );
		},
		[ beforeFetchThemes, receiveThemes, searchThemes ]
	);
	/**
	 * After backwards or forwards navigation, check URL search params for indicators that we have to re-fetch themes.
	 *
	 * @return {Promise<void>}
	 */
	const onBrowserNavigation = useCallback( async () => {
		const urlParams = new URLSearchParams( window.location.search );
		const category = urlParams.get( 'category' ) || 'featured';
		const searchParam = urlParams.get( 'search' ) || '';
		const theme = urlParams.get( 'theme' ) || '';

		if ( searchParam !== '' && searchParam !== search ) {
			await fetchThemesByQuery( searchParam );
		} else if ( category !== '' && category !== selectedCategory ) {
			await fetchThemesByCategory( category );
		}

		if ( theme !== '' ) {
			beforeFetchTheme();
			const response = await fetchTheme( theme );
			receiveTheme( response, theme );
		} else {
			closeOverlay();
		}
	}, [
		selectedCategory,
		search,
		beforeFetchTheme,
		closeOverlay,
		fetchTheme,
		fetchThemesByCategory,
		fetchThemesByQuery,
		receiveTheme,
	] );
	/**
	 * Detect backwards and forwards browser navigation.
	 */
	useEffect( () => {
		window.addEventListener( 'popstate', onBrowserNavigation );
		return () => {
			window.removeEventListener( 'popstate', onBrowserNavigation );
		};
	}, [ onBrowserNavigation ] );

	const fetchThemeById = useCallback(
		async themeId => {
			beforeFetchTheme();
			const response = await fetchTheme( themeId );
			receiveTheme( response, themeId );
		},
		[ beforeFetchTheme, receiveTheme, fetchTheme ]
	);

	return (
		<div className="wrap">
			<h1 className="wp-heading-inline" id="theme-garden-heading">
				<img className="tumblr-logo-icon" src={ logoUrl } alt="" />
				<span>{ __( 'Tumblr Themes', 'tumblr-theme-garden' ) }</span>
			</h1>
			<ThemeGardenFilterBar
				fetchThemesByCategory={ fetchThemesByCategory }
				fetchThemesByQuery={ fetchThemesByQuery }
			/>
			<ThemeGardenList fetchThemeById={ fetchThemeById } />
			<ThemeGardenOverlay fetchThemeById={ fetchThemeById } />
		</div>
	);
};

export const ConnectedThemeGarden = compose(
	withSelect( select => ( {
		logoUrl: select( 'tumblr-theme-garden/theme-garden-store' ).getLogoUrl(),
		selectedCategory: select( 'tumblr-theme-garden/theme-garden-store' ).getSelectedCategory(),
		search: select( 'tumblr-theme-garden/theme-garden-store' ).getSearch(),
	} ) ),
	withDispatch( dispatch => ( {
		beforeFetchThemes: () => {
			return dispatch( 'tumblr-theme-garden/theme-garden-store' ).beforeFetchThemes();
		},
		fetchThemes: category => {
			return dispatch( 'tumblr-theme-garden/theme-garden-store' ).fetchThemes( category );
		},
		searchThemes: query => {
			return dispatch( 'tumblr-theme-garden/theme-garden-store' ).searchThemes( query );
		},
		receiveThemes: ( themes, category, search ) => {
			return dispatch( 'tumblr-theme-garden/theme-garden-store' ).receiveThemes( themes, category, search );
		},
		beforeFetchTheme: () => {
			return dispatch( 'tumblr-theme-garden/theme-garden-store' ).beforeFetchTheme();
		},
		fetchTheme: id => {
			return dispatch( 'tumblr-theme-garden/theme-garden-store' ).fetchTheme( id );
		},
		receiveTheme: ( theme, themeId ) => {
			return dispatch( 'tumblr-theme-garden/theme-garden-store' ).receiveTheme( theme, themeId );
		},
		closeOverlay: () => {
			return dispatch( 'tumblr-theme-garden/theme-garden-store' ).closeOverlay();
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
