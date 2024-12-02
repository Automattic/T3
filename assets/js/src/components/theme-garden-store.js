import apiFetch from '@wordpress/api-fetch';
import { createReduxStore, register } from '@wordpress/data';

/**
 * Default state is loaded from an inline script declared in ThemeGarden.php.
 */
const DEFAULT_STATE = {
	logoUrl: themeGardenData.logoUrl, // eslint-disable-line no-undef
	themes: themeGardenData.themes, // eslint-disable-line no-undef
	categories: themeGardenData.categories, // eslint-disable-line no-undef
	selectedCategory: themeGardenData.selectedCategory, // eslint-disable-line no-undef
	search: themeGardenData.search, // eslint-disable-line no-undef
	baseUrl: themeGardenData.baseUrl, // eslint-disable-line no-undef
	isFetchingThemes: false,
	isOverlayOpen: false,
	themeDetails: false
};

const reducer = ( state = DEFAULT_STATE, action ) => {
	switch ( action.type ) {
		case 'BEFORE_FETCH_THEMES':
			return { ...state, isFetchingThemes: true };
		case 'RECEIVE_THEMES':
			return { ...state, themes: action.themes, isFetchingThemes: false };
		case 'RECEIVE_THEME':
			return { ...state, isOverlayOpen: true };
		case 'CLOSE_OVERLAY':
			return { ...state, isOverlayOpen: false };
		default:
			return state;
	}
};

const actions = {
	closeOverlay() {
		return {
			type: 'CLOSE_OVERLAY'
		};
	},
	receiveTheme() {
		return {
			type: 'RECEIVE_THEME'
		};
	},
	receiveThemes( themes ) {
		return {
			type: 'RECEIVE_THEMES',
			themes,
		};
	},
	beforeFetchThemes() {
		return { type: 'BEFORE_FETCH_THEMES' };
	},
	*fetchThemes( category ) {
		try {
			return controls.FETCH_THEMES( category );
		} catch ( error ) {
			throw new Error( 'Failed to update settings' );
		}
	},
	*searchThemes( query ) {
		try {
			return controls.SEARCH_THEMES( query );
		} catch ( error ) {
			throw new Error( 'Failed to update settings' );
		}
	},
};

const selectors = {
	getLogoUrl() {
		return DEFAULT_STATE.logoUrl;
	},
	getInitialFilterBarProps() {
		return {
			categories: DEFAULT_STATE.categories,
			selectedCategory: DEFAULT_STATE.selectedCategory,
			baseUrl: DEFAULT_STATE.baseUrl,
			search: DEFAULT_STATE.search,
		};
	},
	getIsFetchingThemes( state ) {
		return state.isFetchingThemes;
	},
	getThemes( state ) {
		return state.themes;
	},
	getIsOverlayOpen( state ) {
		return state.isOverlayOpen;
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
};

const store = createReduxStore( 'tumblr3/theme-garden-store', {
	reducer,
	actions,
	selectors,
	controls,
} );

register( store );

export default store;
