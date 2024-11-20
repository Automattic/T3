import apiFetch from '@wordpress/api-fetch';
import { createReduxStore, register } from '@wordpress/data';

const DEFAULT_STATE = {
	logoUrl: themeGardenData.logoUrl,
	themes: themeGardenData.themes,
	categories: themeGardenData.categories,
	hello: 'fred'
};

const reducer = ( state = DEFAULT_STATE, action ) => {
	switch ( action.type ) {
		case 'RECEIVE_THEMES_AND_CATEGORIES':
			return { ...state, hello: action.hello };
		default:
			return state;
	}
};

const actions = {
	receiveThemesAndCategories( themesAndCategories ) {
		return {
			type: 'RECEIVE_THEMES_AND_CATEGORIES',
			themesAndCategories,
		};
	},
};

const selectors = {
	getLogoUrl(state) {
		return state.logoUrl;
	},
	getFilterBarProps(state) {
		return {
			themes: state.themes,
			categories: state.categories,
			initialCategory: state.selectedCategory,
			baseUrl: state.baseUrl,
			hello: state.hello
		}
	}
};

const controls = {
	FETCH_THEMES_AND_CATEGORIES() {
		return apiFetch( { path: '/tumblr3/v1/themes-and-categories' } );
	},
};

const resolvers = {
	getThemesAndCategories:
		() =>
			async ( { dispatch } ) => {
				const data = await controls.FETCH_THEMES_AND_CATEGORIES();
				dispatch( actions.receiveThemesAndCategories( data ) );
			},
};


const store = createReduxStore( 'tumblr3/theme-garden-store', {
	reducer,
	actions,
	selectors,
	controls,
	resolvers,
} );

register( store );

export default store;
