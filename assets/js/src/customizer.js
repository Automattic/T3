/* global _ */
/* global jQuery */
/* global themeGardenCustomizerData */

import './customizer.scss';

window.wp = window.wp || {};
window.wp.customize = window.wp.customize || {};

( function ( window, document, $, wp ) {
	'use strict';

	const app = {};
	wp.customize.resizer = app;
	let mouseLeft = 0;
	let expanded = false;

	app.cache = function () {
		app.$ = {};
		app.$.window = $( window );
		app.$.body = $( document.body );
		app.$.customizer = $( document.getElementById( 'customize-controls' ) );
		app.$.overlay = $( '.wp-full-overlay.expanded' );
		app.$.collapser = $( '.collapse-sidebar-label' );
	};

	app.init = function () {
		const themeGardenListItemHtml =
			'<li id="accordion-section-tumblr_themes" class="theme-section control-subsection">' +
			'<button type="button" id="tumblr-themes-button" class="customize-themes-section-title themes-section-tumblr_themes">Tumblr themes</button>' +
			'</li>';
		app.cache();
		app.$.customizer.append( '<div class="customizer-resizer"></div>' );
		$( '#sub-accordion-section-themes' ).append( themeGardenListItemHtml );
		app.$.tumblrThemesButton = $( '#tumblr-themes-button' );
		app.$.resizer = $( '.customizer-resizer' );
		app.checkWidth();
		app.events();
	};

	app.events = function () {
		// We need the iframe to bubble up mouse events
		app.initIframeMouseEvents();

		app.$.resizer.on( 'mousedown', app.resizerEngage );
		app.$.collapser.on( 'click', app.snapToDefault );
		app.$.tumblrThemesButton.on( 'click', app.goToTumblrThemes );
		$( window ).resize( _.debounce( app.checkWidth, 50 ) );
	};

	app.checkWidth = function () {
		const winWidth = app.$.window.width();

		// the breakpoint where mobile view is triggered.
		if ( winWidth < 640 ) {
			expanded = false;
			app.$.body.removeClass( 'resizable' );
			return app.snapToDefault();
		}

		if ( ! expanded ) {
			app.$.body.addClass( 'resizable' );
			expanded = true;
		}
	};

	app.resizerEngage = function ( evt ) {
		const winWidth = app.$.window.width();
		const iframeWidth = winWidth - app.$.customizer.width();

		evt.preventDefault();

		if ( iframeWidth < 100 ) {
			/*
			Decrease customizer width below
			threshold where it snaps to full-width
			 */
			app.sizeCustomizer( winWidth - 320 );
			app.fullWidth( 'no' );
		} else {
			// Disable customizer sizing animation
			app.$.customizer.addClass( 'no-animation' );

			// add event listeners for the dragging duration
			$( document ).on( 'mousemove', app.resizerMovement );
			$( document ).on( 'mouseup', app.resizerDisengage );
		}
	};

	app.resizerMovement = function ( evt ) {
		// Check if the customizer is expanding (vs shrinking)
		const expanding = mouseLeft < evt.pageX;
		// Re-cache mouseLeft
		mouseLeft = evt.pageX;

		const iframeWidth = app.$.window.width() - mouseLeft;

		// If iframe width is less than a workable width, snap full-screen
		if ( iframeWidth < 300 && iframeWidth > 100 ) {
			app.snapToDefault();
			app.resizerDisengage();

			return app.fullWidth( 'yes' );
		}

		app.fullWidth( 'no' );

		// If we're expanding larger than default, increae the width
		if ( mouseLeft >= 320 || ( mouseLeft >= 300 && expanding ) ) {
			return app.sizeCustomizer( mouseLeft );
		}

		// If we're condensing, and close to our default, snap to it
		if ( ! expanding && mouseLeft > 270 && mouseLeft < 320 ) {
			return app.snapToDefault();
		}

		// If we're condensing past our default, just trigger the collapse
		if ( mouseLeft < 270 ) {
			app.snapToDefault();
			app.resizerDisengage();
			app.$.collapser.trigger( 'click' );
		}
	};

	app.resizerDisengage = function () {
		// remove temp. event listeners
		$( document ).off( 'mousemove', app.resizerMovement );
		$( document ).off( 'mouseup', app.resizerDisengage );

		// Re-enable customizer sizing animation
		app.$.customizer.removeClass( 'no-animation' );
	};

	app.fullWidth = function ( makeFull ) {
		const method = 'yes' === makeFull ? 'addClass' : 'removeClass';
		app.$.body[ method ]( 'fullwidth-customizer' );
	};

	app.snapToDefault = function () {
		app.sizeCustomizer();
	};

	app.sizeCustomizer = function ( size ) {
		size = size || '';
		// Overlay margin needs to be nudged (give more space)
		app.$.overlay.css( { 'margin-left': size } );
		// Move the resizer handle
		app.$.resizer.css( { 'margin-left': size ? size - 5 : size } );
		// And of course, resize the customizer
		app.$.customizer.width( size );
	};

	app.initIframeMouseEvents = function () {
		// Need to recursively check for existence of iframe
		setTimeout( function () {
			const $iframe = app.$.overlay.find( 'iframe' );

			if ( $iframe.length ) {
				// Setup iframe bubbling
				app.bubbleMouseEvent( $iframe[ 0 ], 'mousemove' );
				app.bubbleMouseEvent( $iframe[ 0 ], 'mouseup' );
			} else {
				app.initIframeMouseEvents();
			}
		}, 500 );
	};

	app.bubbleMouseEvent = function ( iframe, evtName ) {
		const longName = 'on' + evtName;
		// Save any previous handler
		const existingMouseEvt = iframe.contentWindow[ longName ];

		// Attach a new listener
		iframe.contentWindow[ longName ] = function ( evt ) {
			// Fire existing listener
			if ( existingMouseEvt ) {
				existingMouseEvt( evt );
			}

			// Create a new event for the this window
			const newEvt = document.createEvent( 'MouseEvents' );

			// We'll need this to offset the mouse appropriately
			const boundingClientRect = iframe.getBoundingClientRect();

			// Initialize the event, copying exiting event values
			// for the most part
			newEvt.initMouseEvent(
				evtName,
				true, // bubbles
				false, // not cancelable
				window,
				evt.detail,
				evt.screenX,
				evt.screenY,
				evt.clientX + boundingClientRect.left,
				evt.clientY + boundingClientRect.top,
				evt.ctrlKey,
				evt.altKey,
				evt.shiftKey,
				evt.metaKey,
				evt.button,
				null // no related element
			);

			// Dispatch the event on the iframe element
			iframe.dispatchEvent( newEvt );
		};
	};

	app.goToTumblrThemes = function () {
		window.location = themeGardenCustomizerData.baseUrl;
	};

	wp.customize.bind( 'ready', app.init );
} )( window, document, jQuery, window.wp );
