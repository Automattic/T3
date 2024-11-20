import apiFetch from '@wordpress/api-fetch';
import { createReduxStore, register } from '@wordpress/data';

const DEFAULT_STATE = {
	logoUrl: themeGardenData.logoUrl
};

const reducer = ( state = DEFAULT_STATE, action ) => {
	switch ( action.type ) {
		default:
			return state;
	}
};

const actions = {};

const selectors = {
	getLogoUrl() {
		return DEFAULT_STATE.logoUrl;
	},
	getFilterBarProps() {
		return {
			themeCount: themeGardenData.themes.length,
			categories: themeGardenData.categories,
			initialCategory: themeGardenData.selectedCategory,
			baseUrl: themeGardenData.baseUrl,
		}
	}
};

const controls = {};

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
