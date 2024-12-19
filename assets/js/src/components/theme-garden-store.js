/* global themeGardenData */
import apiFetch from '@wordpress/api-fetch';
import { createReduxStore, register } from '@wordpress/data';

/**
 * Default state is loaded from an inline script declared in ThemeGarden.php.
 */
const DEFAULT_STATE = {
	// The following properties are static and will not change while someone browses through themes.
	logoUrl: themeGardenData.logoUrl,
	categories: themeGardenData.categories,
	baseUrl: themeGardenData.baseUrl,
	activeTheme: themeGardenData.activeTheme,
	customizeUrl: themeGardenData.customizeUrl,

	// The following properties will change while someone browses through themes.
	themes: themeGardenData.themes,
	selectedCategory: themeGardenData.selectedCategory,
	search: themeGardenData.search,
	selectedThemeId: themeGardenData.selectedThemeId,
	themeDetails: themeGardenData.themeDetails,
	isFetchingThemes: false,
	isOverlayOpen: !! themeGardenData.selectedThemeId,
	isFetchingTheme: false,
};

const reducer = ( state = DEFAULT_STATE, action ) => {
	switch ( action.type ) {
		case 'BEFORE_FETCH_THEMES':
			return { ...state, isFetchingThemes: true };
		case 'BEFORE_FETCH_THEME':
			return { ...state, isFetchingTheme: true, isOverlayOpen: true };
		case 'RECEIVE_THEMES':
			return {
				...state,
				themes: action.themes,
				isFetchingThemes: false,
				selectedCategory: action.category,
				search: action.search,
			};
		case 'RECEIVE_THEME':
			return {
				...state,
				isFetchingTheme: false,
				themeDetails: action.theme,
				selectedThemeId: action.id,
			};
		case 'CLOSE_OVERLAY':
			return { ...state, isOverlayOpen: false, isFetchingTheme: false, themeDetails: null };
		default:
			return state;
	}
};

const actions = {
	closeOverlay() {
		return {
			type: 'CLOSE_OVERLAY',
		};
	},
	receiveTheme( theme, id ) {
		return {
			type: 'RECEIVE_THEME',
			theme,
			id,
		};
	},
	receiveThemes( themes, category, search ) {
		return {
			type: 'RECEIVE_THEMES',
			themes,
			category,
			search,
		};
	},
	beforeFetchTheme() {
		return { type: 'BEFORE_FETCH_THEME' };
	},
	beforeFetchThemes() {
		return { type: 'BEFORE_FETCH_THEMES' };
	},
	*fetchThemes( category ) {
		try {
			return controls.FETCH_THEMES( category );
		} catch ( error ) {
			throw new Error( 'Failed to fetch themes' );
		}
	},
	*searchThemes( query ) {
		try {
			return controls.SEARCH_THEMES( query );
		} catch ( error ) {
			throw new Error( 'Failed to search themes' );
		}
	},
	*fetchTheme( id ) {
		try {
			return controls.FETCH_THEME( id );
		} catch ( error ) {
			throw new Error( 'Failed to fetch theme' );
		}
	},
};

const selectors = {
	getBaseUrl() {
		return DEFAULT_STATE.baseUrl;
	},
	getCategories() {
		return DEFAULT_STATE.categories;
	},
	getLogoUrl() {
		return DEFAULT_STATE.logoUrl;
	},
	getSelectedCategory( state ) {
		return state.selectedCategory;
	},
	getSearch( state ) {
		return state.search;
	},
	getIsFetchingThemes( state ) {
		return state.isFetchingThemes;
	},
	getIsFetchingTheme( state ) {
		return state.isFetchingTheme;
	},
	getThemes( state ) {
		/*
			We filter out the active theme because <ThemeGardenList /> will insert it at the top of the list.
		 */
		return state.themes.filter(
			theme => parseInt( theme.id ) !== parseInt( DEFAULT_STATE.activeTheme.id )
		);
	},
	getIsOverlayOpen( state ) {
		return state.isOverlayOpen;
	},
	getThemeDetails( state ) {
		return state.themeDetails;
	},
	getActiveTheme() {
		return DEFAULT_STATE.activeTheme;
	},
	getCustomizeUrl() {
		return DEFAULT_STATE.customizeUrl;
	},
};

const controls = {
	FETCH_THEMES( category ) {
		return apiFetch( {
			path: '/tumblr-theme-garden/v1/themes?category=' + category,
			method: 'GET',
		} )
			.then( response => {
				return response;
			} )
			.catch( error => {
				throw error;
			} );
	},
	SEARCH_THEMES( query ) {
		return apiFetch( {
			path: '/tumblr-theme-garden/v1/themes?search=' + query,
			method: 'GET',
		} )
			.then( response => {
				return response;
			} )
			.catch( error => {
				console.error( 'API Error:', error ); // eslint-disable-line no-console
				throw error;
			} );
	},
	FETCH_THEME( id ) {
		return apiFetch( {
			path: '/tumblr-theme-garden/v1/theme?theme=' + id,
			method: 'GET',
		} )
			.then( response => {
				return response;
			} )
			.catch( error => {
				console.error( 'API Error:', error ); // eslint-disable-line no-console
				throw error;
			} );
	},
};

const store = createReduxStore( 'tumblr-theme-garden/theme-garden-store', {
	reducer,
	actions,
	selectors,
	controls,
} );

register( store );

export default store;
