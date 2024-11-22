import { useEffect, useState, Suspense } from '@wordpress/element';
import { withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { _x } from '@wordpress/i18n';
import './theme-garden-store';
const _ThemeGardenList = ({themes}) => {
	const [localThemes, setLocalThemes] = useState(themes);

	useEffect( () => {
		setLocalThemes(themes);
	}, [ themes ] );

	return (
		<div className="tumblr-themes">
			{
				themes.map(
					(theme) => (
						<article className="tumblr-theme">
							<header className='tumblr-theme-header'>
								<div className='tumblr-theme-title-wrapper'>
									<span className="tumblr-theme-title">{theme.title}</span>
								</div>
							</header>
							<div className='tumblr-theme-content'>
								<img className="tumblr-theme-thumbnail" src={theme.thumbnail}/>
								<ul className="tumblr-theme-buttons">
									<li><a
										href={theme.activate_url}>{_x('Activate', 'Text on a button to activate a theme.', 'tumblr3')}</a>
									</li>
								</ul>
							</div>
						</article>
					)
				)
			}
		</div>
	);
}

export const ThemeGardenList = compose(
	withSelect((select) => ({
		themes: select('tumblr3/theme-garden-store').getThemes(),
	}))
)(_ThemeGardenList);
