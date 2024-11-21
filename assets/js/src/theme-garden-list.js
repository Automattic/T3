import { useEffect, useState } from '@wordpress/element';
import { withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import './theme-garden-store';
const _ThemeGardenList = ({themes}) => {
	const [localThemes, setLocalThemes] = useState(themes);

	useEffect( () => {
		setLocalThemes(themes);
	}, [ themes ] );

	return (
		localThemes.map(
			(theme) => <p>{theme.title}</p>
		)
	)
}

export const ThemeGardenList = compose(
	withSelect( ( select ) => ( {
		themes: select( 'tumblr3/theme-garden-store' ).getThemes(),
	} ) )
)( _ThemeGardenList );
