import { useState, useEffect, useRef } from '@wordpress/element';
import { _x } from '@wordpress/i18n';
import { withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import './theme-garden-store';

/**
 * This component appears at the top of the theme browser, and has a category selector and a search bar.
 *
 * CSS classNames reference built-in wp-admin styles, and styles declared in _theme_garden.scss.
 *
 * @param {Object}   props
 * @param {string}   props.baseUrl
 * @param {string}   props.selectedCategory
 * @param {Array}    props.categories
 * @param {string }  props.search
 * @param {Array}    props.themes
 * @param {Function} props.fetchThemesByCategory
 * @param {Function} props.fetchThemesByQuery
 */
const _ThemeGardenFilterBar = ( {
	baseUrl,
	selectedCategory,
	categories,
	search,
	themes,
	fetchThemesByQuery,
	fetchThemesByCategory,
} ) => {
	const [ localSelectedCategory, setLocalSelectedCategory ] = useState( selectedCategory );
	const [ localSearch, setLocalSearch ] = useState( search );
	const [ localThemes, setLocalThemes ] = useState( themes );
	const timerRef = useRef();

	/**
	 * Listeners to detect changes in the store, and update local state.
	 * Changes in the store will come from back/forward browser navigation which is handled in theme-garden.js.
	 */
	useEffect( () => {
		setLocalThemes( themes );
	}, [ themes ] );
	useEffect( () => {
		setLocalSelectedCategory( selectedCategory );
	}, [ selectedCategory ] );
	useEffect( () => {
		setLocalSearch( search );
	}, [ search ] );

	const onChangeCategory = async ( { currentTarget } ) => {
		const newCategory = currentTarget.value;
		setLocalSelectedCategory( newCategory );
		await fetchThemesByCategory( newCategory );
		window.history.pushState( {}, '', baseUrl + '&category=' + newCategory );
	};

	const onChangeSearch = async ( { currentTarget } ) => {
		const newSearch = currentTarget.value;
		setLocalSearch( newSearch );
		// Debounce so we don't send multiple requests while user is typing.
		clearTimeout( timerRef.current );
		timerRef.current = setTimeout( async () => {
			await fetchThemesByQuery( newSearch );
			window.history.pushState( {}, '', baseUrl + '&search=' + newSearch );
		}, 500 );
	};

	return (
		<div className="wp-filter">
			<div className="filter-count">
				<span className="count">{ localThemes.length }</span>
			</div>
			<label htmlFor="tumblr-theme-garden-categories">
				{ _x( 'Categories', 'label for a dropdown list of theme categories', 'tumblr-theme-garden' ) }
			</label>
			<select id="tumblr-theme-garden-categories" name="category" onChange={ onChangeCategory }>
				<option value="featured">
					{ _x( 'Featured', 'The name of a category in a list of categories.', 'tumblr-theme-garden' ) }
				</option>
				{ categories.map( category => {
					return (
						<option
							key={ category.text_key }
							value={ category.text_key }
							selected={ localSelectedCategory === category.text_key }
						>
							{ category.name }
						</option>
					);
				} ) }
			</select>
			<p className="search-box">
				<label htmlFor="wp-filter-search-input">
					{ _x( 'Search Themes', 'label for a text input', 'tumblr-theme-garden' ) }
				</label>
				<input
					type="search"
					aria-describedby="live-search-desc"
					id="wp-filter-search-input"
					className="wp-filter-search"
					name="search"
					value={ localSearch }
					onChange={ onChangeSearch }
				/>
			</p>
		</div>
	);
};

export const ThemeGardenFilterBar = compose(
	withSelect( select => ( {
		baseUrl: select( 'tumblr-theme-garden/theme-garden-store' ).getBaseUrl(),
		selectedCategory: select( 'tumblr-theme-garden/theme-garden-store' ).getSelectedCategory(),
		categories: select( 'tumblr-theme-garden/theme-garden-store' ).getCategories(),
		search: select( 'tumblr-theme-garden/theme-garden-store' ).getSearch(),
		themes: select( 'tumblr-theme-garden/theme-garden-store' ).getThemes(),
	} ) )
)( _ThemeGardenFilterBar );
