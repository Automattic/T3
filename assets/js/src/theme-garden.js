import { createRoot } from '@wordpress/element';
import {ThemeGardenFilterbar} from "./theme-garden-filterbar";
import { __ } from '@wordpress/i18n';

const ThemeGarden = () => {
	return (
		<div className="wrap">
			<h1 className="wp-heading-inline" id="theme-garden-heading">
				<img className="tumblr-logo-icon" src={themeGardenData.logoUrl} alt="" />
				<span>{__('Tumblr Themes', 'tumblr3')}</span>
			</h1>
			<ThemeGardenFilterbar
				themeCount={themeGardenData.themes.length}
				categories={themeGardenData.categories}
				initialCategory={themeGardenData.selectedCategory}
				baseUrl={themeGardenData.baseUrl}
			/>
		</div>
	)
}



const rootElement = document.getElementById('tumblr-theme-garden');
if (rootElement) {
	const root = createRoot(rootElement);
	root.render(<ThemeGarden />);
} else {
	console.error('Failed to find the root element for the settings panel.');
}

