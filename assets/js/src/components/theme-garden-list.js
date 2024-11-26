import { useEffect, useState } from '@wordpress/element';
import { withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { _x } from '@wordpress/i18n';
import { ThemeGardenNoThemes } from './theme-garden-no-themes';
import './theme-garden-store';

/**
 * Displays a list of Tumblr themes.
 *
 * Class names reference built-in wp-admin styles, and styles declared in _theme_garden.scss.
 *
 * @param {Object}  props
 * @param {Array}   props.themes
 * @param {boolean} props.isFetchingThemes
 */
const _ThemeGardenList = ( { themes, isFetchingThemes } ) => {
	const [ localThemes, setLocalThemes ] = useState( themes );

	useEffect( () => {
		setLocalThemes( themes );
	}, [ themes ] );

	if ( isFetchingThemes ) {
		return (
			<div className="loading-content">
				<span className="spinner"></span>
			</div>
		);
	}

	if ( localThemes.length === 0 ) {
		return <ThemeGardenNoThemes />;
	}

	return (
		<div className="tumblr-themes">
			{ themes.map( theme => (
				<article className="tumblr-theme" key={ theme.title }>
					<header className="tumblr-theme-header">
						<div className="tumblr-theme-title-wrapper">
							<span className="tumblr-theme-title">{ theme.title }</span>
						</div>
					</header>
					<div className="tumblr-theme-content">
						<img className="tumblr-theme-thumbnail" src={ theme.thumbnail } alt="" />
						<ul className="tumblr-theme-buttons">
							<li>
								<a href={ theme.activate_url }>
									{ _x( 'Activate', 'Text on a button to activate a theme.', 'tumblr3' ) }
								</a>
							</li>
						</ul>
					</div>
				</article>
			) ) }
		</div>
	);
};

export const ThemeGardenList = compose(
	withSelect( select => ( {
		themes: select( 'tumblr3/theme-garden-store' ).getThemes(),
		isFetchingThemes: select( 'tumblr3/theme-garden-store' ).getIsFetchingThemes(),
	} ) )
)( _ThemeGardenList );
