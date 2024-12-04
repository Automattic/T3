import apiFetch from '@wordpress/api-fetch';
import { createReduxStore, register } from '@wordpress/data';

/**
 * Default state is loaded from an inline script declared in ThemeGarden.php.
 */
const DEFAULT_STATE = {
	// The following properties are static and will not change.
	logoUrl: themeGardenData.logoUrl, // eslint-disable-line no-undef
	categories: themeGardenData.categories, // eslint-disable-line no-undef
	baseUrl: themeGardenData.baseUrl, // eslint-disable-line no-undef

	// The following properties will change during usage of the app.
	themes: themeGardenData.themes, // eslint-disable-line no-undef
	selectedCategory: themeGardenData.selectedCategory, // eslint-disable-line no-undef
	search: themeGardenData.search, // eslint-disable-line no-undef
	selectedThemeId: themeGardenData.selectedThemeId, // eslint-disable-line no-undef
	themeDetails: themeGardenData.themeDetails, // eslint-disable-line no-undef
	isFetchingThemes: false,
	isOverlayOpen: !! themeGardenData.selectedThemeId, // eslint-disable-line no-undef
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
		return state.themes;
	},
	getIsOverlayOpen( state ) {
		return state.isOverlayOpen;
	},
	getThemeDetails( state ) {
		return state.themeDetails;
	},
};

const controls = {
	FETCH_THEMES( category ) {
		return apiFetch( {
			path: '/tumblr3/v1/themes?category=' + category,
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
			path: '/tumblr3/v1/themes?search=' + query,
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
			path: '/tumblr3/v1/theme?theme=' + id,
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

const store = createReduxStore( 'tumblr3/theme-garden-store', {
	reducer,
	actions,
	selectors,
	controls,
} );

register( store );

export default store;
