import { _x } from '@wordpress/i18n';

/**
 * These playful messages were written by Tumblr staff.
 *
 * CSS classNames reference built-in wp-admin styles, and styles declared in _theme_garden.scss.
 */
export const ThemeGardenNoThemes = () => {
	const playfulNoThemesText = [
		_x( 'Sadly, nothing.', 'The message displayed when no themes were found.', 'ttgarden' ),
		_x( 'Tragically, nothing.', 'The message displayed when no themes were found.', 'ttgarden' ),
		_x(
			'We found nothing. Here it isn’t.',
			'The message displayed when no themes were found.',
			'ttgarden'
		),
		_x(
			'Couldn’t find that. Please, don’t be upset. Please.',
			'The message displayed when no themes were found.',
			'ttgarden'
		),
		_x(
			'Sincerely, we found nothing.',
			'The message displayed when no themes were found.',
			'ttgarden'
		),
		_x( 'Nothing to see here.', 'The message displayed when no themes were found.', 'ttgarden' ),
		_x(
			'If you were looking for nothing, congrats, you found it.',
			'The message displayed when no themes were found.',
			'ttgarden'
		),
	];
	const textKey = Math.floor( Math.random() * playfulNoThemesText.length );
	return (
		<p className="no-themes" id="tumblr-no-themes">
			{ playfulNoThemesText[ textKey ] }
		</p>
	);
};
