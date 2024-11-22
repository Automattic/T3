import apiFetch from '@wordpress/api-fetch';
import { createReduxStore, register } from '@wordpress/data';

const DEFAULT_STATE = {
    logoUrl: themeGardenData.logoUrl,
    themes: themeGardenData.themes,
    categories: themeGardenData.categories,
    selectedCategory: themeGardenData.selectedCategory,
    baseUrl: themeGardenData.baseUrl,
};

const reducer = ( state = DEFAULT_STATE, action ) => {
    switch ( action.type ) {
        case 'RECEIVE_THEMES':
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
    *fetchThemes(category) {
        try {
            return controls.FETCH_THEMES(category);
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
        }
    },
    getThemes(state) {
        return state.themes;
    }
};

const controls = {
    FETCH_THEMES(category) {
        return apiFetch( {
            path: '/tumblr3/v1/themes?category=' + category,
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
