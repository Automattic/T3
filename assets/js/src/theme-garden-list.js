import { useEffect, useState } from '@wordpress/element';
import { withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import './theme-garden-store';
const _ThemeGardenList = ({hello}) => {
	const [localHello, setLocalHello] = useState(hello);

	useEffect( () => {
		console.log('effect used');
		console.log(hello);
		setLocalHello(hello);
	}, [ hello ] );

	return <p>{localHello}</p>;
}

export const ThemeGardenList = compose(
	withSelect( ( select ) => ( {
		hello: select( 'tumblr3/theme-garden-store' ).getThemes(),
	} ) )
)( _ThemeGardenList );
