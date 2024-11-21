import { useState } from '@wordpress/element';
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
 * @param props.initialProps.themeList
 * @param props.fetchThemes
 * @param props.receiveThemes
 */
const _ThemeGardenFilterBar = ({
	initialProps: {baseUrl, selectedCategory: initialSelectedCategory, categories, themeList: initialThemeList},
	fetchThemes,
	receiveThemes
}) => {
	const [selectedCategory, setSelectedCategory] = useState(initialSelectedCategory);
	const [themeList, setThemeList] = useState(initialThemeList);

	const onChangeCategory = async ({currentTarget}) => {
		const newCategory = currentTarget.value;
		// window.history.pushState( {}, '', baseUrl + '&category=' + currentTarget.value);

		try {
			const response = await fetchThemes();
			receiveThemes(response);
		} catch ( saveError ) {
			console.log(saveError);
			/*setError(
				__( 'Failed to save settings. Please try again.', 'post-queue' )
			);*/
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

export const ThemeGardenFilterBar = compose(
	withSelect( ( select ) => ( {
		initialProps: select( 'tumblr3/theme-garden-store' ).getInitialFilterBarProps(),
	} ) ),
	withDispatch( ( dispatch ) => ( {
		fetchThemes: () => {
			return dispatch( 'tumblr3/theme-garden-store' ).fetchThemes();
		},
		receiveThemes: (themesAndCategories) => {
			return dispatch( 'tumblr3/theme-garden-store' ).receiveThemes(themesAndCategories);
		}
	} ) )
)( _ThemeGardenFilterBar );
