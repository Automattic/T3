/******/ (() => { // webpackBootstrap
/*!****************************************!*\
  !*** ./assets/js/src/theme-install.js ***!
  \****************************************/
/* global t3Install */
window.addEventListener('DOMContentLoaded', function () {
  const body = document.getElementById('wpbody-content');
  const filterLinks = body.getElementsByClassName('filter-links');
  const handleClick = function () {
    window.location = t3Install.browseUrl;
  };
  if (filterLinks[0] && filterLinks[0].tagName.toLowerCase() === 'ul') {
    const list = filterLinks[0];
    const listItem = document.createElement('li');
    const link = document.createElement('button');
    listItem.setAttribute('class', 'tumblr-theme-garden-list-item');
    link.setAttribute('title', t3Install.buttonText);
    link.addEventListener('click', handleClick);
    link.setAttribute('class', 'tumblr-theme-garden-link');
    listItem.appendChild(link);
    list.appendChild(listItem);
  }
}, false);
/******/ })()
;
//# sourceMappingURL=theme-install.js.map