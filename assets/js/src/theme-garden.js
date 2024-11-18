// window.addEventListener(
// 	'DOMContentLoaded',
// 	function () {
// 		const categorySelect = document.getElementById( 't3-categories' );
// 		const categorySelectForm = document.getElementById(
// 			't3-category-select-form'
// 		);
//
// 		categorySelect.addEventListener( 'change', function () {
// 			categorySelectForm.submit();
// 		} );
// 	},
// 	false
// );
import { createRoot } from '@wordpress/element';

const App = () => {
	return <p>test!</p>;
}

const rootElement = document.getElementById( 'tumblr-theme-garden' );
if ( rootElement ) {
	const root = createRoot( rootElement );
	root.render( <App /> );
} else {
	console.error( 'Failed to find the root element for the settings panel.' );
}

