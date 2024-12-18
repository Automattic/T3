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
 * @param {boolean}  props.isActive
 */
export const ThemeGardenListItem = ({theme: {activate_url, id, thumbnail, title}, handleDetailsClick, isActive}) => {
	const label = `tumblr-theme-garden-theme-details-${ id }`;

	const renderActivationButton = () => {
		return isActive ? (
			<span>Active!</span>
		) : (
			<a className="rainbow-button" href={activate_url}>
				Activate
			</a>
		)
	}

	return (
		<article className="tumblr-theme">
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
					{renderActivationButton()}
				</div>
			</div>
		</article>
	);
}
