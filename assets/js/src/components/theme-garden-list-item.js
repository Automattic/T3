import { _x } from '@wordpress/i18n';

/**
 * Displays a tumblr theme in context of <ThemeGardenList />.
 *
 * CSS classNames reference built-in wp-admin styles, and styles declared in _theme_garden.scss.
 *
 * @param {Object}   props
 * @param {Array}    props.theme
 * @param {string}   props.theme.activate_url
 * @param {string}   props.theme.id
 * @param {string}   props.theme.thumbnail
 * @param {string}   props.theme.title
 * @param {Function} props.handleDetailsClick
 */
export const ThemeGardenListItem = ({theme: {activate_url, id, thumbnail, title}, handleDetailsClick}) => {
	const label = `tumblr-theme-garden-theme-details-${ id }`;

	return (
		<article className="tumblr-theme" key={title}>
			<header className="tumblr-theme-header">
				<div className="tumblr-theme-title-wrapper">
					<span className="tumblr-theme-title">{title}</span>
				</div>
			</header>
			<div className="tumblr-theme-content">
				<button
					className="tumblr-theme-details"
					onClick={handleDetailsClick}
					value={id}
					id={label}
				>
					<label htmlFor={label}>
									<span className="tumblr-theme-detail-button">
										{_x(
											'Theme details',
											'Text on a button that will show more information about a Tumblr theme',
											'tumblr-theme-garden'
										)}
									</span>
					</label>
					<img src={thumbnail} alt=""/>
				</button>
				<div className="tumblr-theme-footer">
					<a className="rainbow-button" href={activate_url}>
						Activate
					</a>
				</div>
			</div>
		</article>
	);
}
