console.log( T3_Install.themeGardenUrl );
window.addEventListener(
	'DOMContentLoaded',
	function () {
		const body = document.getElementById( 'wpbody-content' );
		const filterLinks = body.getElementsByClassName( 'filter-links' );

		const handleClick = function () {
			window.location = T3_Install.browseUrl;
		};

		if (
			filterLinks[ 0 ] &&
			filterLinks[ 0 ].tagName.toLowerCase() === 'ul'
		) {
			const list = filterLinks[ 0 ];
			const listItem = document.createElement( 'li' );
			const link = document.createElement( 'button' );
			const img = document.createElement( 'img' );
			listItem.setAttribute( 'class', 'tumblr-theme-garden-list-item' );
			link.setAttribute( 'title', T3_Install.buttonText );
			link.addEventListener( 'click', handleClick );
			link.setAttribute( 'class', 'tumblr-theme-garden-link' );
			listItem.appendChild( link );
			list.appendChild( listItem );
		}
	},
	false
);
