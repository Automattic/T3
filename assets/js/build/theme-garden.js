/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/js/src/theme-garden-filterbar.js":
/*!*************************************************!*\
  !*** ./assets/js/src/theme-garden-filterbar.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   ThemeGardenFilterBar: () => (/* binding */ ThemeGardenFilterBar)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _theme_garden_store__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./theme-garden-store */ "./assets/js/src/theme-garden-store.js");







/**
 * ThemeGardenFilterBar component
 *
 * This component appears at the top of the theme browser, and has a category selector and a search bar.
 *
 * @param props
 * @param props.initialProps
 * @param props.initialProps.baseUrl
 * @param props.initialProps.selectedCategory
 * @param props.initialProps.categories
 * @param props.initialProps.themeList
 * @param props
 * @param props.fetchThemes
 * @param props.receiveThemes
 */
const _ThemeGardenFilterBar = ({
  initialProps: {
    baseUrl,
    selectedCategory: initialSelectedCategory,
    categories,
    themeList: initialThemeList
  },
  fetchThemes,
  receiveThemes
}) => {
  const [selectedCategory, setSelectedCategory] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(initialSelectedCategory);
  const [themeList, setThemeList] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(initialThemeList);
  const onChangeCategory = async ({
    currentTarget
  }) => {
    try {
      const response = await fetchThemes();
      receiveThemes(response);
      window.history.pushState({}, '', baseUrl + '&category=' + currentTarget.value);
    } catch (saveError) {
      console.log(saveError);
      /*setError(
      	__( 'Failed to save settings. Please try again.', 'post-queue' )
      );*/
    }
  };
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "wp-filter"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "filter-count"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "count"
  }, themeList.length)), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
    htmlFor: "t3-categories"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Categories', 'tumblr3')), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("select", {
    id: "t3-categories",
    name: "category",
    onChange: onChangeCategory
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("option", {
    value: "featured"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__._x)('Featured', 'The name of a category in a list of categories.', 'tumblr3')), categories.map(category => {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("option", {
      value: category.text_key,
      selected: selectedCategory === category.text_key
    }, category.name);
  })));
};
const ThemeGardenFilterBar = (0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_4__.compose)((0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.withSelect)(select => ({
  initialProps: select('tumblr3/theme-garden-store').getInitialFilterBarProps()
})), (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.withDispatch)(dispatch => ({
  fetchThemes: () => {
    return dispatch('tumblr3/theme-garden-store').fetchThemes();
  },
  receiveThemes: themesAndCategories => {
    return dispatch('tumblr3/theme-garden-store').receiveThemes(themesAndCategories);
  }
})))(_ThemeGardenFilterBar);

/***/ }),

/***/ "./assets/js/src/theme-garden-list.js":
/*!********************************************!*\
  !*** ./assets/js/src/theme-garden-list.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   ThemeGardenList: () => (/* binding */ ThemeGardenList)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _theme_garden_store__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./theme-garden-store */ "./assets/js/src/theme-garden-store.js");





const _ThemeGardenList = ({
  themes
}) => {
  const [localThemes, setLocalThemes] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(themes);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    console.log('effect used');
    console.log(themes);
    setLocalThemes(themes);
  }, [themes]);
  return localThemes.map(theme => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, theme.title));
};
const ThemeGardenList = (0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__.compose)((0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.withSelect)(select => ({
  themes: select('tumblr3/theme-garden-store').getThemes()
})))(_ThemeGardenList);

/***/ }),

/***/ "./assets/js/src/theme-garden-store.js":
/*!*********************************************!*\
  !*** ./assets/js/src/theme-garden-store.js ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);


const DEFAULT_STATE = {
  logoUrl: themeGardenData.logoUrl,
  themes: themeGardenData.themes,
  categories: themeGardenData.categories,
  selectedCategory: themeGardenData.selectedCategory,
  baseUrl: themeGardenData.baseUrl,
  hello: 'fred'
};
const reducer = (state = DEFAULT_STATE, action) => {
  switch (action.type) {
    case 'RECEIVE_THEMES':
      console.log('action dispatched');
      console.log(action);
      return {
        ...state,
        themes: action.themes
      };
    default:
      return state;
  }
};
const actions = {
  receiveThemes(themes) {
    return {
      type: 'RECEIVE_THEMES',
      themes
    };
  },
  *fetchThemes() {
    try {
      return controls.FETCH_THEMES();
    } catch (error) {
      throw new Error('Failed to update settings');
    }
  }
};
const selectors = {
  getLogoUrl() {
    return DEFAULT_STATE.logoUrl;
  },
  getInitialFilterBarProps() {
    return {
      themeList: DEFAULT_STATE.themes,
      categories: DEFAULT_STATE.categories,
      selectedCategory: DEFAULT_STATE.selectedCategory,
      baseUrl: DEFAULT_STATE.baseUrl,
      hello: DEFAULT_STATE.hello
    };
  },
  getThemes(state) {
    return state.themes;
  }
};
const controls = {
  FETCH_THEMES() {
    return _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_0___default()({
      path: '/tumblr3/v1/themes',
      method: 'GET'
    }).then(response => {
      return response;
    }).catch(error => {
      console.error('API Error:', error);
      throw error;
    });
  }
};
const resolvers = {};
const store = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.createReduxStore)('tumblr3/theme-garden-store', {
  reducer,
  actions,
  selectors,
  controls,
  resolvers
});
(0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.register)(store);
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (store);

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = window["React"];

/***/ }),

/***/ "@wordpress/api-fetch":
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["wp"]["apiFetch"];

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

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

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
/*!***************************************!*\
  !*** ./assets/js/src/theme-garden.js ***!
  \***************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   ConnectedThemeGarden: () => (/* binding */ ConnectedThemeGarden)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _theme_garden_filterbar__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./theme-garden-filterbar */ "./assets/js/src/theme-garden-filterbar.js");
/* harmony import */ var _theme_garden_list__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./theme-garden-list */ "./assets/js/src/theme-garden-list.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _theme_garden_store__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./theme-garden-store */ "./assets/js/src/theme-garden-store.js");









/**
 * ThemeGarden Component
 *
 * This component provides a user interface for browsing themes from Tumblr's theme garden.
 *
 * @param props
 * @param props.logoUrl
 */
const ThemeGarden = ({
  logoUrl
}) => {
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "wrap"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h1", {
    className: "wp-heading-inline",
    id: "theme-garden-heading"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    className: "tumblr-logo-icon",
    src: logoUrl,
    alt: ""
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Tumblr Themes', 'tumblr3'))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_theme_garden_filterbar__WEBPACK_IMPORTED_MODULE_2__.ThemeGardenFilterBar, null), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_theme_garden_list__WEBPACK_IMPORTED_MODULE_3__.ThemeGardenList, null));
};
const ConnectedThemeGarden = (0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_6__.compose)((0,_wordpress_data__WEBPACK_IMPORTED_MODULE_5__.withSelect)(select => ({
  logoUrl: select('tumblr3/theme-garden-store').getLogoUrl()
})))(ThemeGarden);
const rootElement = document.getElementById('tumblr-theme-garden');
if (rootElement) {
  const root = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createRoot)(rootElement);
  root.render((0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(ConnectedThemeGarden, null));
} else {
  console.error('Failed to find the root element for the settings panel.');
}
})();

/******/ })()
;
//# sourceMappingURL=theme-garden.js.map