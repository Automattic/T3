(()=>{"use strict";var e={554:(e,t,r)=>{const a=window.wp.apiFetch;var s=r.n(a),o=r(143);const l={logoUrl:themeGardenData.logoUrl,themes:themeGardenData.themes,categories:themeGardenData.categories,selectedCategory:themeGardenData.selectedCategory,baseUrl:themeGardenData.baseUrl},m={receiveThemes:e=>({type:"RECEIVE_THEMES",themes:e}),*fetchThemes(e){try{return n.FETCH_THEMES(e)}catch(e){throw new Error("Failed to update settings")}}},c={getLogoUrl:()=>l.logoUrl,getInitialFilterBarProps:()=>({categories:l.categories,selectedCategory:l.selectedCategory,baseUrl:l.baseUrl}),getThemes:e=>e.themes},n={FETCH_THEMES:e=>s()({path:"/tumblr3/v1/themes?category="+e,method:"GET"}).then((e=>e)).catch((e=>{throw console.error("API Error:",e),e}))},h=(0,o.createReduxStore)("tumblr3/theme-garden-store",{reducer:(e=l,t)=>"RECEIVE_THEMES"===t.type?{...e,themes:t.themes}:e,actions:m,selectors:c,controls:n,resolvers:{}});(0,o.register)(h)},609:e=>{e.exports=window.React},491:e=>{e.exports=window.wp.compose},143:e=>{e.exports=window.wp.data},87:e=>{e.exports=window.wp.element},723:e=>{e.exports=window.wp.i18n}},t={};function r(a){var s=t[a];if(void 0!==s)return s.exports;var o=t[a]={exports:{}};return e[a](o,o.exports,r),o.exports}r.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return r.d(t,{a:t}),t},r.d=(e,t)=>{for(var a in t)r.o(t,a)&&!r.o(e,a)&&Object.defineProperty(e,a,{enumerable:!0,get:t[a]})},r.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t);var a=r(609),s=r(87),o=r(143),l=r(491),m=r(723);r(554),(0,l.compose)((0,o.withSelect)((e=>({themes:e("tumblr3/theme-garden-store").getThemes()}))))((({themes:e})=>{const[t,r]=(0,s.useState)(e);return(0,s.useEffect)((()=>{r(e)}),[e]),(0,a.createElement)("div",{className:"tumblr-themes"},e.map((e=>(0,a.createElement)("article",{className:"tumblr-theme"},(0,a.createElement)("header",{className:"tumblr-theme-header"},(0,a.createElement)("div",{className:"tumblr-theme-title-wrapper"},(0,a.createElement)("span",{className:"tumblr-theme-title"},e.title))),(0,a.createElement)("div",{className:"tumblr-theme-content"},(0,a.createElement)("img",{className:"tumblr-theme-thumbnail",src:e.thumbnail}),(0,a.createElement)("ul",{className:"tumblr-theme-buttons"},(0,a.createElement)("li",null,(0,a.createElement)("a",{href:e.activate_url},(0,m._x)("Activate","Text on a button to activate a theme.","tumblr3")))))))))}))})();