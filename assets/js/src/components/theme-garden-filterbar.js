import { useState, useEffect, useRef } from '@wordpress/element';
import { __, _x } from '@wordpress/i18n';
import { withDispatch, withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import './theme-garden-store';

/**
 * This component appears at the top of the theme browser, and has a category selector and a search bar.
 *
 * Class names reference built-in wp-admin styles, and styles declared in _theme_garden.scss.
 *
 * @param {Object}   props
 * @param {Object}   props.initialProps
 * @param {string}   props.initialProps.baseUrl
 * @param {string}   props.initialProps.selectedCategory
 * @param {Array}    props.initialProps.categories
 * @param {string }  props.initialProps.search
 * @param {Array}    props.themes
 * @param {Function} props.fetchThemes
 * @param {Function} props.receiveThemes
 * @param {Function} props.prefetchThemes
 * @param {Function} props.searchThemes
 */
const _ThemeGardenFilterBar = ( {
	initialProps: {
		baseUrl,
		selectedCategory: initialSelectedCategory,
		categories,
		search: initialSearch,
	},
	themes,
	fetchThemes,
	receiveThemes,
	prefetchThemes,
	searchThemes,
} ) => {
	const [ selectedCategory, setSelectedCategory ] = useState( initialSelectedCategory );
	const [ search, setSearch ] = useState( initialSearch );
	const [ themeList, setThemeList ] = useState( themes );
	const timerRef = useRef();

	/**
	 * Whenever themes from the store change, re-render the component.
	 */
	useEffect( () => {
		setThemeList( themes );
	}, [ themes ] );

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
		prefetchThemes();
		const response = await fetchThemes( category );
		receiveThemes( response );
	};

	const fetchThemesByQuery = async newSearch => {
		prefetchThemes();
		const response = await searchThemes( newSearch );
		receiveThemes( response );
	};

	/**
	 * After backwards or forwards navigation, check URL search params for indicators that we have to re-fetch themes.
	 *
	 * @return {Promise<void>}
	 */
	const onBrowserNavigation = async () => {
		const urlParams = new URLSearchParams( window.location.search );
		const category = urlParams.get( 'category' ) || 'featured';
		const searchParam = urlParams.get( 'search' ) || '';
		if ( searchParam !== '' ) {
			setSearch( searchParam );
		} else {
			await fetchThemesByCategory( category );
			setSelectedCategory( category );
		}
	};

	const onChangeCategory = async ( { currentTarget } ) => {
		const newCategory = currentTarget.value;
		await fetchThemesByCategory( newCategory );
		setSelectedCategory( newCategory );
		setSearch( '' );
		window.history.pushState( {}, '', baseUrl + '&category=' + newCategory );
	};

	const onChangeSearch = async ( { currentTarget } ) => {
		const newSearch = currentTarget.value;
		clearTimeout( timerRef.current );
		setSearch( newSearch );
		timerRef.current = setTimeout( async () => {
			await fetchThemesByQuery( newSearch );
			window.history.pushState( {}, '', baseUrl + '&search=' + newSearch );
			setSelectedCategory( '' );
		}, 500 );
	};

	return (
		<div className="wp-filter">
			<div className="filter-count">
				<span className="count">{ themeList.length }</span>
			</div>
			<label htmlFor="t3-categories">{ __( 'Categories', 'tumblr3' ) }</label>
			<select id="t3-categories" name="category" onChange={ onChangeCategory }>
				<option value="featured">
					{ _x( 'Featured', 'The name of a category in a list of categories.', 'tumblr3' ) }
				</option>
				{ categories.map( category => {
					return (
						<option
							key={ category.text_key }
							value={ category.text_key }
							selected={ selectedCategory === category.text_key }
						>
							{ category.name }
						</option>
					);
				} ) }
			</select>
			<p className="search-box">
				<label htmlFor="wp-filter-search-input">Search Themes</label>
				<input
					type="search"
					aria-describedby="live-search-desc"
					id="wp-filter-search-input"
					className="wp-filter-search"
					name="search"
					value={ search }
					onChange={ onChangeSearch }
				/>
			</p>
		</div>
	);
};

export const ThemeGardenFilterBar = compose(
	withSelect( select => ( {
		initialProps: select( 'tumblr3/theme-garden-store' ).getInitialFilterBarProps(),
		themes: select( 'tumblr3/theme-garden-store' ).getThemes(),
	} ) ),
	withDispatch( dispatch => ( {
		prefetchThemes: () => {
			return dispatch( 'tumblr3/theme-garden-store' ).prefetchThemes();
		},
		fetchThemes: category => {
			return dispatch( 'tumblr3/theme-garden-store' ).fetchThemes( category );
		},
		searchThemes: query => {
			return dispatch( 'tumblr3/theme-garden-store' ).searchThemes( query );
		},
		receiveThemes: themes => {
			return dispatch( 'tumblr3/theme-garden-store' ).receiveThemes( themes );
		},
	} ) )
)( _ThemeGardenFilterBar );
