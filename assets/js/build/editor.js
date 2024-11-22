/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/js/src/components/audio-block.js":
/*!*************************************************!*\
  !*** ./assets/js/src/components/audio-block.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/hooks */ "@wordpress/hooks");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_5__);







/**
 * Register new attributes for the audio block.
 */
(0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_4__.addFilter)('blocks.registerBlockType', 'tumblr3/extend-audio-block', (settings, name) => {
  if (typeof settings.attributes !== 'undefined' && name === 'core/audio') {
    settings.attributes = Object.assign(settings.attributes, {
      mediaURL: {
        type: 'string',
        default: ''
      },
      mediaTitle: {
        type: 'string',
        default: ''
      },
      mediaArtist: {
        type: 'string',
        default: ''
      },
      mediaAlbum: {
        type: 'string',
        default: ''
      },
      provider: {
        type: 'string',
        default: ''
      },
      embedHtml: {
        type: 'string',
        default: ''
      },
      embedUrl: {
        type: 'string',
        default: ''
      },
      poster: {
        type: 'object',
        default: {}
      },
      attribution: {
        type: 'object',
        default: {}
      }
    });
  }
  return settings;
});

/**
 * Extend the audio block with sidebar controls.
 */
(0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_4__.addFilter)('editor.BlockEdit', 'tumblr3/extend-audio-block', (0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_5__.createHigherOrderComponent)(BlockEdit => {
  return props => {
    const {
      attributes,
      setAttributes,
      isSelected
    } = props;
    const {
      src,
      mediaURL,
      mediaTitle,
      mediaArtist,
      mediaAlbum,
      poster
    } = attributes;

    // Sync mediaURL with src if they differ
    if (src && src !== mediaURL) {
      setAttributes({
        mediaURL: src
      });
    }
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(react__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(BlockEdit, {
      ...props
    }), isSelected && props.name === 'core/audio' && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelBody, {
      title: "Media Information",
      initialOpen: true
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {
      label: "Media Title",
      value: mediaTitle,
      onChange: value => setAttributes({
        mediaTitle: value
      })
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {
      label: "Media Artist",
      value: mediaArtist,
      onChange: value => setAttributes({
        mediaArtist: value
      })
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {
      label: "Media Album",
      value: mediaAlbum,
      onChange: value => setAttributes({
        mediaAlbum: value
      })
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUploadCheck, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.BaseControl.VisualLabel, null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Poster image')), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.MediaUpload, {
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Select poster image'),
      onSelect: media => setAttributes({
        poster: {
          url: media.url,
          alt: media.alt
        }
      }),
      allowedTypes: ['image'],
      render: ({
        open
      }) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Button, {
        onClick: open,
        variant: "primary"
      }, poster.url ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Replace') : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Select'))
    }), poster.url && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
      src: poster.url,
      alt: poster.alt || 'Poster Image',
      style: {
        maxWidth: '100%',
        marginTop: '10px'
      }
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Button, {
      isDestructive: true,
      onClick: () => setAttributes({
        poster: {}
      })
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Remove')))))));
  };
}, 'enhanced-audio-block'));

/***/ }),

/***/ "./assets/js/src/components/image-block.js":
/*!*************************************************!*\
  !*** ./assets/js/src/components/image-block.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/hooks */ "@wordpress/hooks");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__);


/**
 * Register new attributes for the image block.
 */
(0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__.addFilter)('blocks.registerBlockType', 'tumblr3/extend-image-block', (settings, name) => {
  if (typeof settings.attributes !== 'undefined' && name === 'core/image') {
    settings.attributes = Object.assign(settings.attributes, {
      media: {
        type: 'array',
        items: {
          type: 'object'
        },
        default: []
      },
      feedbackToken: {
        type: 'string',
        default: ''
      }
    });
  }
  return settings;
});

/***/ }),

/***/ "./assets/js/src/components/post-formats.js":
/*!**************************************************!*\
  !*** ./assets/js/src/components/post-formats.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/plugins */ "@wordpress/plugins");
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/editor */ "@wordpress/editor");
/* harmony import */ var _wordpress_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_editor__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_7__);

/* eslint-disable react-hooks/rules-of-hooks */








/**
 * @see https://github.com/WordPress/gutenberg/blob/dd7451ff41acae3c2e9fa56b6ed7a1f14db04a55/packages/editor/src/components/post-format/index.js#L83
 */
const POST_FORMATS = [{
  id: 'standard',
  caption: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Text'),
  icon: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    height: "35",
    width: "40",
    viewBox: "0 0 20.8 13"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M.1 13h2.8l.9-3h4.7l.8 3h2.9L7.7 0h-3L.1 13zm6-10.1l2 5.1H4.2l1.9-5.1zM20 10V6c0-1.1-.2-1.9-1-2.3-.7-.5-1.7-.7-2.7-.7-1.6 0-2.7.4-3.4 1.2-.4.5-.6 1.2-.7 2h2.4c.1-.4.2-.6.4-.8.2-.3.6-.4 1.2-.4.5 0 .9.1 1.2.2.3.1.4.4.4.8 0 .3-.2.5-.5.7-.2.1-.5.2-1 .2l-.9.1c-1 .1-1.7.3-2.2.6-.9.5-1.4 1.3-1.4 2.5 0 .9.3 1.6.8 2 .6.5 1.3.9 2.2.9.7 0 1.2-.3 1.7-.6.4-.2.8-.6 1.2-.9 0 .2 0 .4.1.6 0 .2.1.8.2 1h2.7v-.8c-.1-.1-.3-.2-.4-.3.1-.3-.3-1.7-.3-2zm-2.2-1.1c0 .8-.3 1.4-.7 1.7-.4.3-1 .5-1.5.5-.3 0-.6-.1-.9-.3-.2-.2-.4-.5-.4-.9 0-.5.2-.8.6-1 .2-.1.6-.2 1.1-.3l.6-.1c.3-.1.5-.1.7-.2.2-.1.3-.1.5-.2v.8z"
  }))
}, {
  id: 'image',
  caption: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Image'),
  icon: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    height: "35",
    width: "40",
    viewBox: "0 0 17 15"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M14.6 1h-2.7l-.6-1h-6l-.6 1H2.4C1.1 1 0 2 0 3.3v9.3C0 13.9 1.1 15 2.4 15h12.2c1.3 0 2.4-1.1 2.4-2.4V3.3C17 2 15.9 1 14.6 1zM8.3 13.1c-2.9 0-5.2-2.3-5.2-5.1s2.3-5.1 5.2-5.1c2.9 0 5.2 2.3 5.2 5.1s-2.3 5.1-5.2 5.1zm5.9-8.3c-.6 0-1.1-.5-1.1-1.1 0-.6.5-1.1 1.1-1.1s1.1.5 1.1 1.1c0 .6-.5 1.1-1.1 1.1zm-10 3.1c0 1.2.5 2.2 1.3 3 0-.2 0-.4-.1-.6 0-2.2 1.8-4 4.1-4 1.1 0 2 .4 2.8 1.1-.3-2-2-3.4-4-3.4-2.2-.1-4.1 1.7-4.1 3.9z"
  }))
}, {
  id: 'gallery',
  caption: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Gallery'),
  icon: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    height: "35",
    width: "40",
    viewBox: "0 0 24 24"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    fillRule: "evenodd",
    clipRule: "evenodd",
    d: "M16 3H4C3.44772 3 3 3.44772 3 4V16C3 16.5523 3.44772 17 4 17C4.55229 17 5 16.5523 5 16V8C5 6.34315 6.34315 5 8 5H16C16.5523 5 17 4.55228 17 4C17 3.44772 16.5523 3 16 3ZM20 5C19.4477 5 19 4.55228 19 4C19 2.34315 17.6569 1 16 1H4C2.34315 1 1 2.34315 1 4V16C1 17.6569 2.34315 19 4 19C4.55229 19 5 19.4477 5 20C5 21.6569 6.34315 23 8 23H20C21.6569 23 23 21.6569 23 20V8C23 6.34315 21.6569 5 20 5ZM14 10C14 11.1046 13.1046 12 12 12C10.8954 12 10 11.1046 10 10C10 8.89543 10.8954 8 12 8C13.1046 8 14 8.89543 14 10ZM7 17.6829C7 17.4082 7.11304 17.1455 7.31257 16.9567L8.79638 15.5522C9.54271 14.8457 10.7029 14.8198 11.48 15.4923L12.4939 16.3698C12.9005 16.7216 13.5125 16.689 13.8794 16.2959L16.3619 13.636C17.1761 12.7637 18.568 12.7938 19.3437 13.7005L20.7599 15.3557C20.9148 15.5368 21 15.7674 21 16.0058V20C21 20.5523 20.5523 21 20 21H8C7.44772 21 7 20.5523 7 20V17.6829Z"
  }))
}, {
  id: 'quote',
  caption: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Quote'),
  icon: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    height: "35",
    width: "40",
    viewBox: "0 0 17 13"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M3.5 5.5C4 3.7 5.8 2.4 7.2 1.3L5.9 0C3 1.8 0 5 0 8.5 0 11 1.3 13 4 13c2 0 3.7-1.5 3.7-3.6C7.7 7 6 5.5 3.5 5.5zm9.3 0c.4-1.8 2.2-3.1 3.7-4.2L15.2 0c-2.8 1.8-5.9 5-5.9 8.5 0 2.4 1.3 4.5 4 4.5 2 0 3.7-1.5 3.7-3.6 0-2.4-1.7-3.9-4.2-3.9z"
  }))
}, {
  id: 'link',
  caption: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Link'),
  icon: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    height: "35",
    width: "40",
    viewBox: "0 0 17 17"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M9.9 5.1c-.2.3-.5 1.4-.6 2.6l1.1-.1c.7-.1 1.3-.3 1.5-.5.6-.6.6-1.4 0-2-.6-.5-1.4-.5-2 0zM8.5 0C3.8 0 0 3.8 0 8.5S3.8 17 8.5 17 17 13.2 17 8.5 13.2 0 8.5 0zm4.4 8.2c-.5.5-1.5.8-2.5.9l-1.2.2c-.1 1.3-.4 2.9-1 3.6-1.1 1.1-3 1.2-4.1 0-1.1-1.1-1.1-3 0-4.1.5-.5 1.5-.8 2.6-.9v1.5c-1.2.3-1.5.5-1.6.5-.6.6-.6 1.4 0 2 .5.5 1.4.5 2 0 .2-.2.5-1.1.6-2.5l.1-.9c0-1.3.2-3.6 1-4.4 1.1-1.1 3-1.2 4.1 0 1.2 1.1 1.2 2.9 0 4.1z"
  }))
}, {
  id: 'chat',
  caption: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Chat'),
  icon: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    height: "35",
    width: "40",
    viewBox: "0 0 18.7 17"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M16 6.1V2.6C16 .8 15 0 13.1 0H2.9C1 0 0 1.1 0 3.3v10.4C0 15.9 1 17 2.9 17h10.2c1.9 0 2.9-.8 2.9-2.6v-2.9l2.7-2.9c0-.1-2.7-2.5-2.7-2.5zm-4.5-.7c0-.5.3-.8.7-.8s.8.3.8.8v1.7l-.3 2.5c0 .3-.2.4-.4.4s-.4-.1-.4-.4l-.3-2.5V5.4zm-3.8 6.4c0 .4-.1.8-.7.8-.5 0-.7-.4-.7-.8V9.1C6.3 8.4 6 8 5.4 8c-.5 0-1 .4-1 1.2v2.6c0 .4-.1.8-.7.8s-.7-.4-.7-.8V5.4c0-.5.3-.8.7-.8.4 0 .7.3.7.8v2.1c.3-.4.7-.8 1.5-.8s1.7.5 1.7 2c.1.1.1 3.1.1 3.1zm2.5 0c0 .4-.1.8-.7.8-.5 0-.7-.4-.7-.8V7.5c0-.4.1-.8.7-.8.5 0 .7.4.7.8v4.3zm-.7-5.6c-.4 0-.7-.4-.7-.8s.3-.8.7-.8c.4 0 .7.4.7.8s-.3.8-.7.8zm2.8 6.3c-.4 0-.8-.4-.8-.9s.3-.9.8-.9.8.4.8.9-.4.9-.8.9z"
  }))
}, {
  id: 'audio',
  caption: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Audio'),
  icon: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    height: "35",
    width: "40",
    viewBox: "0 0 19 16"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M17.7 7.3c-.4-4.4-4-7.3-8.3-7.3-4.3 0-7.9 2.9-8.3 7.4C.5 7.4 0 8 0 8.6v5c0 .8.6 1.4 1.3 1.4H4c.2.4.8 1 1.2 1 .6 0 .8-1 .8-1.6V7.8c0-.5-.2-1.6-.8-1.6-.4 0-1 .8-1.2 1.1H2.9c.4-3.5 3.2-5.6 6.5-5.6s6.2 2.1 6.5 5.6H15c0-.3-.7-1.1-1.1-1.1-.6 0-.9 1-.9 1.6v6.6c0 .5.3 1.6.9 1.6.4 0 1.1-.6 1.2-1h2.6c.7 0 1.3-.6 1.3-1.3v-5c0-.8-.6-1.3-1.3-1.4zM3 8.5v1l-2 1.3V8.5h2zm15 .9l-2 1.3V8.5h2v.9zm-6.4.3l-1.6.5V6.4c0-.1-.1-.2-.2-.2s-.2 0-.2.1L7.2 12v.2c.1.1.1.1.2.1L9 12v3.8c0 .1-.2.2-.1.2h.3c.1 0 .2 0 .2-.1l2.4-5.9v-.2c-.1-.1-.2-.1-.2-.1z"
  }))
}, {
  id: 'video',
  caption: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Video'),
  icon: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    height: "35",
    width: "40",
    viewBox: "0 0 16 15"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M15.7 7.8c-.2-.1-.5 0-.6.1l-2.9 2.2c-.1.1-.1.1-.2.1V8H0v3h2v3.2c0 .4.3.8.8.8h8.4c.5 0 .8-.4.8-.8V12c0 .1.1.2.2.2l2.9 2.2c.2.2.4.2.6.1.2-.1.3-.3.3-.5V8.4c0-.2-.1-.5-.3-.6zM2.8 6.9c.3 0 .8.1 1.1.1h5.5c.3 0 .8-.1 1-.1 1.6-.3 2.8-1.7 2.8-3.4 0-1.9-1.6-3.5-3.5-3.5-1.2 0-2.4.6-3 1.7h-.1C5.9.6 4.8 0 3.6 0 1.6 0 0 1.6 0 3.5c0 1.7 1.2 3 2.8 3.4zM9 4.2c1 0 1.8-.8 1.8-1.8v-.3c.4.3.6.8.6 1.4 0 1-.8 1.8-1.8 1.8-.9 0-1.6-.6-1.8-1.5.3.3.7.4 1.2.4zm-6.2.1c1 0 1.8-.8 1.8-1.8v-.3c.4.2.6.7.6 1.3 0 1-.8 1.8-1.8 1.8-.9 0-1.6-.6-1.8-1.5.3.3.7.5 1.2.5z"
  }))
}];
(0,_wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__.registerPlugin)('tumblr3-updated-postformat-ui', {
  render: () => {
    // Create a suggestion for the post format based on the blocks in the content.
    const createSuggestion = blocks => {
      if (blocks.length > 2) {
        return null;
      }
      let name;

      // If there is only one block in the content of the post grab its name
      // so we can derive a suitable post format from it.
      if (blocks.length === 1) {
        name = blocks[0].name;
        // Check for core/embed `video` and `audio` eligible suggestions.
        if (name === 'core/embed') {
          const provider = blocks[0].attributes?.providerNameSlug;
          if (['youtube', 'vimeo'].includes(provider)) {
            name = 'core/video';
          } else if (['spotify', 'soundcloud'].includes(provider)) {
            name = 'core/audio';
          }
        }
      }

      // If there are two blocks in the content and the last one is a text blocks
      // grab the name of the first one to also suggest a post format from it.
      if (blocks.length === 2 && blocks[1].name === 'core/paragraph') {
        name = blocks[0].name;
      }

      // We only convert to default post formats in core.
      switch (name) {
        case 'core/image':
          return 'image';
        case 'core/quote':
        case 'core/pullquote':
          return 'quote';
        case 'core/gallery':
          return 'gallery';
        case 'core/video':
          return 'video';
        case 'core/audio':
          return 'audio';
        default:
          return null;
      }
    };

    // Get the `editPost` action from the `core/editor` store.
    const {
      editPost
    } = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.useDispatch)('core/editor');

    // Get the current post format from the store.
    const activeFormat = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.useSelect)(select => select('core/editor').getEditedPostAttribute('format'), []);
    const blocks = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.useSelect)(select => select(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6__.store).getBlocks(), []);

    // Get the suggestion for the post format.
    const suggestion = createSuggestion(blocks);

    // Update the post format.
    const updatePostFormat = id => {
      editPost({
        format: id
      });

      // Finish early if there are already blocks in the content.
      if (blocks.length > 1) {
        return;
      }

      // Finish early if there is only one block in the content and it is not a paragraph.
      if (blocks.length === 1 && blocks[0].name !== 'core/paragraph') {
        return;
      }

      // Insert block format based on the selected post format.
      switch (id) {
        case 'image':
          (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.dispatch)('core/block-editor').insertBlocks((0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_7__.createBlock)('core/image'), 0);
          break;
        case 'quote':
          (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.dispatch)('core/block-editor').insertBlocks((0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_7__.createBlock)('core/quote'), 0);
          break;
        case 'gallery':
          (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.dispatch)('core/block-editor').insertBlocks((0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_7__.createBlock)('core/gallery'), 0);
          break;
        case 'video':
          (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.dispatch)('core/block-editor').insertBlocks((0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_7__.createBlock)('core/video'), 0);
          break;
        case 'audio':
          (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.dispatch)('core/block-editor').insertBlocks((0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_7__.createBlock)('core/audio'), 0);
          break;
        default:
          break;
      }
    };
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_editor__WEBPACK_IMPORTED_MODULE_2__.PluginPostStatusInfo, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "tumblr3-post-format-selector"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("strong", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Post Formats', 'tumblr3'))), POST_FORMATS.map(({
      id,
      caption,
      icon
    }) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__.Button, {
      key: id,
      onClick: () => updatePostFormat(id),
      className: `post-format-${id} ${id === activeFormat ? 'active' : ''}`,
      align: "center"
    }, icon, caption)), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null), suggestion && suggestion !== activeFormat && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
      className: "editor-post-format__suggestion"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__.Button, {
      __next40pxDefaultSize: true,
      variant: "link",
      onClick: () => updatePostFormat(suggestion),
      style: {
        width: '100%'
      }
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.sprintf)(/* translators: %s: post format */
    (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Apply suggested format: %s', 'tumblr3'), suggestion)))));
  }
});

/***/ }),

/***/ "./assets/js/src/components/video-block.js":
/*!*************************************************!*\
  !*** ./assets/js/src/components/video-block.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/hooks */ "@wordpress/hooks");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_4__);






/**
 * Register new attributes for the audio block.
 */
(0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_3__.addFilter)('blocks.registerBlockType', 'tumblr3/extend-video-block', (settings, name) => {
  if (typeof settings.attributes !== 'undefined' && name === 'core/video') {
    settings.attributes = Object.assign(settings.attributes, {
      url: {
        type: 'string',
        default: ''
      },
      media: {
        type: 'object',
        default: {}
      },
      provider: {
        type: 'string',
        default: ''
      },
      embedHtml: {
        type: 'string',
        default: ''
      },
      embedIframe: {
        type: 'object',
        default: {}
      },
      embedUrl: {
        type: 'string',
        default: ''
      },
      metadata: {
        type: 'object',
        default: {}
      },
      attribution: {
        type: 'object',
        default: {}
      },
      canAutoplayOnCellular: {
        type: 'boolean',
        default: false
      },
      duration: {
        type: 'number',
        default: 0
      },
      filmstrip: {
        type: 'array',
        items: {
          type: 'object'
        },
        default: []
      }
    });
  }
  return settings;
});

/**
 * Extend the video block with sidebar controls.
 */
(0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_3__.addFilter)('editor.BlockEdit', 'tumblr3/extend-video-block', (0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_4__.createHigherOrderComponent)(BlockEdit => {
  return props => {
    const {
      attributes,
      setAttributes,
      isSelected
    } = props;
    const {
      src,
      url,
      provider
    } = attributes;

    // Sync mediaURL with src if they differ
    if (src && src !== url) {
      setAttributes({
        url: src
      });
    }
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(react__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(BlockEdit, {
      ...props
    }), isSelected && props.name === 'core/video' && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
      title: "Media Information",
      initialOpen: true
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
      label: "URL",
      value: url,
      onChange: value => setAttributes({
        url: value
      })
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
      label: "Content provider",
      value: provider,
      onChange: value => setAttributes({
        provider: value
      })
    }))));
  };
}, 'enhanced-video-block'));

/***/ }),

/***/ "./assets/js/src/index.scss":
/*!**********************************!*\
  !*** ./assets/js/src/index.scss ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = window["React"];

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ ((module) => {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ ((module) => {

module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/compose":
/*!*********************************!*\
  !*** external ["wp","compose"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["compose"];

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["data"];

/***/ }),

/***/ "@wordpress/editor":
/*!********************************!*\
  !*** external ["wp","editor"] ***!
  \********************************/
/***/ ((module) => {

module.exports = window["wp"]["editor"];

/***/ }),

/***/ "@wordpress/hooks":
/*!*******************************!*\
  !*** external ["wp","hooks"] ***!
  \*******************************/
/***/ ((module) => {

module.exports = window["wp"]["hooks"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "@wordpress/plugins":
/*!*********************************!*\
  !*** external ["wp","plugins"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["plugins"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!*********************************!*\
  !*** ./assets/js/src/editor.js ***!
  \*********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _index_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./index.scss */ "./assets/js/src/index.scss");
/* harmony import */ var _components_post_formats__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/post-formats */ "./assets/js/src/components/post-formats.js");
/* harmony import */ var _components_audio_block__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/audio-block */ "./assets/js/src/components/audio-block.js");
/* harmony import */ var _components_video_block__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components/video-block */ "./assets/js/src/components/video-block.js");
/* harmony import */ var _components_image_block__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./components/image-block */ "./assets/js/src/components/image-block.js");
/* eslint-disable react-hooks/rules-of-hooks */





})();

/******/ })()
;
//# sourceMappingURL=editor.js.map