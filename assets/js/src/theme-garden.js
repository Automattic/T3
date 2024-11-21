import { createRoot } from '@wordpress/element';
import { ThemeGardenFilterBar } from "./theme-garden-filterbar";
import { ThemeGardenList } from "./theme-garden-list";
import { __ } from '@wordpress/i18n';
import { withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import './theme-garden-store';

/**
 * ThemeGarden Component
 *
 * This component provides a user interface for browsing themes from Tumblr's theme garden.
 *
 * @param props
 * @param props.logoUrl
 */
const ThemeGarden = ({ logoUrl }) => {
	return (
		<div className="wrap">
			<h1 className="wp-heading-inline" id="theme-garden-heading">
				<img className="tumblr-logo-icon" src={logoUrl} alt="" />
				<span>{__('Tumblr Themes', 'tumblr3')}</span>
			</h1>
			<ThemeGardenFilterBar />
			<ThemeGardenList />
		</div>
	)
}

export const ConnectedThemeGarden = compose(
	withSelect( ( select ) => ( {
		logoUrl: select( 'tumblr3/theme-garden-store' ).getLogoUrl(),
	} ) ),
)( ThemeGarden );

const rootElement = document.getElementById('tumblr-theme-garden');
if (rootElement) {
	const root = createRoot(rootElement);
	root.render(<ConnectedThemeGarden />);
} else {
	console.error('Failed to find the root element for the settings panel.');
}

