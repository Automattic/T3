import apiFetch from '@wordpress/api-fetch';
import { createReduxStore, register } from '@wordpress/data';

const DEFAULT_STATE = {
	logoUrl: themeGardenData.logoUrl,
	themes: themeGardenData.themes,
	categories: themeGardenData.categories,
	selectedCategory: themeGardenData.selectedCategory,
	baseUrl: themeGardenData.baseUrl,
	hello: 'fred'
};

const reducer = ( state = DEFAULT_STATE, action ) => {
	switch ( action.type ) {
		case 'RECEIVE_THEMES':
			console.log('action dispatched');
			console.log(action);
			return { ...state, themes: action.themes };
		default:
			return state;
	}
};

const actions = {
	receiveThemes( themes ) {
		return {
			type: 'RECEIVE_THEMES',
			themes,
		};
	},
	*fetchThemes() {
		try {
			return controls.FETCH_THEMES();
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
			themeList: DEFAULT_STATE.themes,
			categories: DEFAULT_STATE.categories,
			selectedCategory: DEFAULT_STATE.selectedCategory,
			baseUrl: DEFAULT_STATE.baseUrl,
			hello: DEFAULT_STATE.hello
		}
	},
	getThemes(state) {
		return state.themes;
	}
};

const controls = {
	FETCH_THEMES() {
		return apiFetch( {
			path: '/tumblr3/v1/themes',
			method: 'GET',
		} )
			.then( ( response ) => {
				return response;
			} )
			.catch( ( error ) => {
				console.error( 'API Error:', error );
				throw error;
			} );
	},
};

const resolvers = {};


const store = createReduxStore( 'tumblr3/theme-garden-store', {
	reducer,
	actions,
	selectors,
	controls,
	resolvers,
} );

register( store );

export default store;
