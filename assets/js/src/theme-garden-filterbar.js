import { useState, useEffect } from '@wordpress/element';
import { __, _x } from '@wordpress/i18n';
import { withDispatch, withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import './theme-garden-store';

/**
 * ThemeGardenFilterBar component
 *
 * This component appears at the top of the theme browser, and has a category selector and a search bar.
 *
 * @param props
 * @param props.initialProps
 * @param props.initialProps.baseUrl
 * @param props.initialProps.selectedCategory
 * @param props.initialProps.categories
 * @param props.themes
 * @param props.fetchThemes
 * @param props.receiveThemes
 */
const _ThemeGardenFilterBar = ({
								   initialProps: {baseUrl, selectedCategory: initialSelectedCategory, categories},
								   themes,
								   fetchThemes,
								   receiveThemes
							   }) => {
	const [selectedCategory, setSelectedCategory] = useState(initialSelectedCategory);
	const [themeList, setThemeList] = useState(themes);

	useEffect(() => {
		setThemeList(themes);
	}, [themes]);

	useEffect(() => {
		window.addEventListener('popstate', onBrowserNavigation);
		return () => {
			window.removeEventListener('popstate', onBrowserNavigation)
		}
	}, []);

	const onBrowserNavigation = async () => {
		const urlParams = new URLSearchParams(window.location.search);
		const paramValue = urlParams.get('category') || 'featured';
		const response = await fetchThemes(paramValue);
		receiveThemes(response);
		setSelectedCategory(paramValue);
	}

	const onChangeCategory = async ({currentTarget}) => {
		try {
			const newCategory = currentTarget.value;
			const response = await fetchThemes(newCategory);
			receiveThemes(response);
			setSelectedCategory(newCategory);
			window.history.pushState( {}, '', baseUrl + '&category=' + currentTarget.value);
		} catch ( saveError ) {
			console.error(saveError);
		}
	}

	return (
		<div className="wp-filter">
			<div className="filter-count">
				<span className="count">{themeList.length}</span>
			</div>
			<label htmlFor="t3-categories">{__('Categories', 'tumblr3')}</label>
			<select id="t3-categories" name="category" onChange={onChangeCategory}>
				<option value="featured">{_x('Featured', 'The name of a category in a list of categories.', 'tumblr3')}</option>
				{categories.map(
					(category) => {
						return(
							<option value={category.text_key} selected={selectedCategory === category.text_key}>
								{category.name}
							</option>
						);
					}
				)}
			</select>
		</div>
	);
}

export const ThemeGardenFilterbar = compose(
	withSelect( ( select ) => ( {
		initialProps: select( 'tumblr3/theme-garden-store' ).getInitialFilterBarProps(),
		themes: select( 'tumblr3/theme-garden-store' ).getThemes()
	} ) ),
	withDispatch( ( dispatch ) => ( {
		fetchThemes: (category) => {
			return dispatch( 'tumblr3/theme-garden-store' ).fetchThemes(category);
		},
		receiveThemes: (themes) => {
			return dispatch( 'tumblr3/theme-garden-store' ).receiveThemes(themes);
		}
	} ) )
)( _ThemeGardenFilterBar );
