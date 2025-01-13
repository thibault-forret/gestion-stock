/******/ (() => { // webpackBootstrap
/*!*********************************!*\
  !*** ./resources/js/sidebar.js ***!
  \*********************************/
document.getElementById('sidebar-toggle').addEventListener('click', function () {
  document.getElementById('sidebar').style.left = '0';
  document.getElementById('overlay').style.display = 'block';
});
document.getElementById('close-sidebar').addEventListener('click', function () {
  document.getElementById('sidebar').style.left = '-100%';
  document.getElementById('overlay').style.display = 'none';
});
document.getElementById('overlay').addEventListener('click', function () {
  document.getElementById('sidebar').style.left = '-100%';
  document.getElementById('overlay').style.display = 'none';
});
/******/ })()
;