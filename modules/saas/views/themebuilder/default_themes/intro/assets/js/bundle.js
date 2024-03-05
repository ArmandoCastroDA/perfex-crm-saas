/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/js/index.js":
/*!*************************!*\
  !*** ./src/js/index.js ***!
  \*************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _css_animate_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../css/animate.css */ \"./src/css/animate.css\");\n/* harmony import */ var _css_style_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../css/style.css */ \"./src/css/style.css\");\n/* harmony import */ var wowjs__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! wowjs */ \"./node_modules/wowjs/dist/wow.js\");\n/* harmony import */ var wowjs__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(wowjs__WEBPACK_IMPORTED_MODULE_2__);\n\n\n\nwindow.wow = new (wowjs__WEBPACK_IMPORTED_MODULE_2___default().WOW)({\n  live: false\n});\nwindow.wow.init({\n  offset: 50\n});\n\n(function () {\n  'use strict'; // ======= Sticky\n\n  window.onscroll = function () {\n    var ud_header = document.querySelector('.header');\n    var sticky = ud_header.offsetTop;\n\n    if (window.pageYOffset > sticky) {\n      ud_header.classList.add('sticky');\n    } else {\n      ud_header.classList.remove('sticky');\n    } // show or hide the back-top-top button\n\n\n    var backToTop = document.querySelector('.back-to-top');\n\n    if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {\n      backToTop.style.display = 'flex';\n    } else {\n      backToTop.style.display = 'none';\n    }\n  }; // ===== responsive navbar\n\n\n  var navbarToggler = document.querySelector('#navbarToggler');\n  var navbarCollapse = document.querySelector('#navbarCollapse');\n  navbarToggler.addEventListener('click', function () {\n    navbarToggler.classList.toggle('navbarTogglerActive');\n    navbarCollapse.classList.toggle('ud-hidden');\n  }); //===== close navbar-collapse when a  clicked\n\n  document.querySelectorAll('#navbarCollapse ul li:not(.submenu-item) a').forEach(function (e) {\n    return e.addEventListener('click', function () {\n      navbarToggler.classList.remove('navbarTogglerActive');\n      navbarCollapse.classList.add('ud-hidden');\n    });\n  }); // ===== Sub-menu\n\n  var submenuItems = document.querySelectorAll('.submenu-item');\n  submenuItems.forEach(function (el) {\n    el.querySelector('a').addEventListener('click', function () {\n      el.querySelector('.submenu').classList.toggle('ud-hidden');\n    });\n  }); // ===== Faq accordion\n\n  var faqs = document.querySelectorAll('.single-faq');\n  faqs.forEach(function (el) {\n    el.querySelector('.faq-btn').addEventListener('click', function () {\n      el.querySelector('.icon').classList.toggle('ud-rotate-180');\n      el.querySelector('.faq-content').classList.toggle('ud-hidden');\n    });\n  }); // ====== scroll top js\n\n  function scrollTo(element) {\n    var to = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;\n    var duration = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 500;\n    var start = element.scrollTop;\n    var change = to - start;\n    var increment = 20;\n    var currentTime = 0;\n\n    var animateScroll = function animateScroll() {\n      currentTime += increment;\n      var val = Math.easeInOutQuad(currentTime, start, change, duration);\n      element.scrollTop = val;\n\n      if (currentTime < duration) {\n        setTimeout(animateScroll, increment);\n      }\n    };\n\n    animateScroll();\n  }\n\n  Math.easeInOutQuad = function (t, b, c, d) {\n    t /= d / 2;\n    if (t < 1) return c / 2 * t * t + b;\n    t--;\n    return -c / 2 * (t * (t - 2) - 1) + b;\n  };\n\n  document.querySelector('.back-to-top').onclick = function () {\n    scrollTo(document.documentElement);\n  };\n})(); // Document Loaded\n\n\ndocument.addEventListener('DOMContentLoaded', function () {});\n\n//# sourceURL=webpack://saas-tailwind/./src/js/index.js?");

/***/ }),

/***/ "./src/css/animate.css":
/*!*****************************!*\
  !*** ./src/css/animate.css ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n// extracted by mini-css-extract-plugin\n\n\n//# sourceURL=webpack://saas-tailwind/./src/css/animate.css?");

/***/ }),

/***/ "./src/css/style.css":
/*!***************************!*\
  !*** ./src/css/style.css ***!
  \***************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n// extracted by mini-css-extract-plugin\n\n\n//# sourceURL=webpack://saas-tailwind/./src/css/style.css?");

/***/ }),

/***/ "./node_modules/wowjs/dist/wow.js":
/*!****************************************!*\
  !*** ./node_modules/wowjs/dist/wow.js ***!
  \****************************************/
/***/ (function() {

eval("(function() {\n  var MutationObserver, Util, WeakMap, getComputedStyle, getComputedStyleRX,\n    bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },\n    indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };\n\n  Util = (function() {\n    function Util() {}\n\n    Util.prototype.extend = function(custom, defaults) {\n      var key, value;\n      for (key in defaults) {\n        value = defaults[key];\n        if (custom[key] == null) {\n          custom[key] = value;\n        }\n      }\n      return custom;\n    };\n\n    Util.prototype.isMobile = function(agent) {\n      return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(agent);\n    };\n\n    Util.prototype.createEvent = function(event, bubble, cancel, detail) {\n      var customEvent;\n      if (bubble == null) {\n        bubble = false;\n      }\n      if (cancel == null) {\n        cancel = false;\n      }\n      if (detail == null) {\n        detail = null;\n      }\n      if (document.createEvent != null) {\n        customEvent = document.createEvent('CustomEvent');\n        customEvent.initCustomEvent(event, bubble, cancel, detail);\n      } else if (document.createEventObject != null) {\n        customEvent = document.createEventObject();\n        customEvent.eventType = event;\n      } else {\n        customEvent.eventName = event;\n      }\n      return customEvent;\n    };\n\n    Util.prototype.emitEvent = function(elem, event) {\n      if (elem.dispatchEvent != null) {\n        return elem.dispatchEvent(event);\n      } else if (event in (elem != null)) {\n        return elem[event]();\n      } else if ((\"on\" + event) in (elem != null)) {\n        return elem[\"on\" + event]();\n      }\n    };\n\n    Util.prototype.addEvent = function(elem, event, fn) {\n      if (elem.addEventListener != null) {\n        return elem.addEventListener(event, fn, false);\n      } else if (elem.attachEvent != null) {\n        return elem.attachEvent(\"on\" + event, fn);\n      } else {\n        return elem[event] = fn;\n      }\n    };\n\n    Util.prototype.removeEvent = function(elem, event, fn) {\n      if (elem.removeEventListener != null) {\n        return elem.removeEventListener(event, fn, false);\n      } else if (elem.detachEvent != null) {\n        return elem.detachEvent(\"on\" + event, fn);\n      } else {\n        return delete elem[event];\n      }\n    };\n\n    Util.prototype.innerHeight = function() {\n      if ('innerHeight' in window) {\n        return window.innerHeight;\n      } else {\n        return document.documentElement.clientHeight;\n      }\n    };\n\n    return Util;\n\n  })();\n\n  WeakMap = this.WeakMap || this.MozWeakMap || (WeakMap = (function() {\n    function WeakMap() {\n      this.keys = [];\n      this.values = [];\n    }\n\n    WeakMap.prototype.get = function(key) {\n      var i, item, j, len, ref;\n      ref = this.keys;\n      for (i = j = 0, len = ref.length; j < len; i = ++j) {\n        item = ref[i];\n        if (item === key) {\n          return this.values[i];\n        }\n      }\n    };\n\n    WeakMap.prototype.set = function(key, value) {\n      var i, item, j, len, ref;\n      ref = this.keys;\n      for (i = j = 0, len = ref.length; j < len; i = ++j) {\n        item = ref[i];\n        if (item === key) {\n          this.values[i] = value;\n          return;\n        }\n      }\n      this.keys.push(key);\n      return this.values.push(value);\n    };\n\n    return WeakMap;\n\n  })());\n\n  MutationObserver = this.MutationObserver || this.WebkitMutationObserver || this.MozMutationObserver || (MutationObserver = (function() {\n    function MutationObserver() {\n      if (typeof console !== \"undefined\" && console !== null) {\n        console.warn('MutationObserver is not supported by your browser.');\n      }\n      if (typeof console !== \"undefined\" && console !== null) {\n        console.warn('WOW.js cannot detect dom mutations, please call .sync() after loading new content.');\n      }\n    }\n\n    MutationObserver.notSupported = true;\n\n    MutationObserver.prototype.observe = function() {};\n\n    return MutationObserver;\n\n  })());\n\n  getComputedStyle = this.getComputedStyle || function(el, pseudo) {\n    this.getPropertyValue = function(prop) {\n      var ref;\n      if (prop === 'float') {\n        prop = 'styleFloat';\n      }\n      if (getComputedStyleRX.test(prop)) {\n        prop.replace(getComputedStyleRX, function(_, _char) {\n          return _char.toUpperCase();\n        });\n      }\n      return ((ref = el.currentStyle) != null ? ref[prop] : void 0) || null;\n    };\n    return this;\n  };\n\n  getComputedStyleRX = /(\\-([a-z]){1})/g;\n\n  this.WOW = (function() {\n    WOW.prototype.defaults = {\n      boxClass: 'wow',\n      animateClass: 'animated',\n      offset: 0,\n      mobile: true,\n      live: true,\n      callback: null,\n      scrollContainer: null\n    };\n\n    function WOW(options) {\n      if (options == null) {\n        options = {};\n      }\n      this.scrollCallback = bind(this.scrollCallback, this);\n      this.scrollHandler = bind(this.scrollHandler, this);\n      this.resetAnimation = bind(this.resetAnimation, this);\n      this.start = bind(this.start, this);\n      this.scrolled = true;\n      this.config = this.util().extend(options, this.defaults);\n      if (options.scrollContainer != null) {\n        this.config.scrollContainer = document.querySelector(options.scrollContainer);\n      }\n      this.animationNameCache = new WeakMap();\n      this.wowEvent = this.util().createEvent(this.config.boxClass);\n    }\n\n    WOW.prototype.init = function() {\n      var ref;\n      this.element = window.document.documentElement;\n      if ((ref = document.readyState) === \"interactive\" || ref === \"complete\") {\n        this.start();\n      } else {\n        this.util().addEvent(document, 'DOMContentLoaded', this.start);\n      }\n      return this.finished = [];\n    };\n\n    WOW.prototype.start = function() {\n      var box, j, len, ref;\n      this.stopped = false;\n      this.boxes = (function() {\n        var j, len, ref, results;\n        ref = this.element.querySelectorAll(\".\" + this.config.boxClass);\n        results = [];\n        for (j = 0, len = ref.length; j < len; j++) {\n          box = ref[j];\n          results.push(box);\n        }\n        return results;\n      }).call(this);\n      this.all = (function() {\n        var j, len, ref, results;\n        ref = this.boxes;\n        results = [];\n        for (j = 0, len = ref.length; j < len; j++) {\n          box = ref[j];\n          results.push(box);\n        }\n        return results;\n      }).call(this);\n      if (this.boxes.length) {\n        if (this.disabled()) {\n          this.resetStyle();\n        } else {\n          ref = this.boxes;\n          for (j = 0, len = ref.length; j < len; j++) {\n            box = ref[j];\n            this.applyStyle(box, true);\n          }\n        }\n      }\n      if (!this.disabled()) {\n        this.util().addEvent(this.config.scrollContainer || window, 'scroll', this.scrollHandler);\n        this.util().addEvent(window, 'resize', this.scrollHandler);\n        this.interval = setInterval(this.scrollCallback, 50);\n      }\n      if (this.config.live) {\n        return new MutationObserver((function(_this) {\n          return function(records) {\n            var k, len1, node, record, results;\n            results = [];\n            for (k = 0, len1 = records.length; k < len1; k++) {\n              record = records[k];\n              results.push((function() {\n                var l, len2, ref1, results1;\n                ref1 = record.addedNodes || [];\n                results1 = [];\n                for (l = 0, len2 = ref1.length; l < len2; l++) {\n                  node = ref1[l];\n                  results1.push(this.doSync(node));\n                }\n                return results1;\n              }).call(_this));\n            }\n            return results;\n          };\n        })(this)).observe(document.body, {\n          childList: true,\n          subtree: true\n        });\n      }\n    };\n\n    WOW.prototype.stop = function() {\n      this.stopped = true;\n      this.util().removeEvent(this.config.scrollContainer || window, 'scroll', this.scrollHandler);\n      this.util().removeEvent(window, 'resize', this.scrollHandler);\n      if (this.interval != null) {\n        return clearInterval(this.interval);\n      }\n    };\n\n    WOW.prototype.sync = function(element) {\n      if (MutationObserver.notSupported) {\n        return this.doSync(this.element);\n      }\n    };\n\n    WOW.prototype.doSync = function(element) {\n      var box, j, len, ref, results;\n      if (element == null) {\n        element = this.element;\n      }\n      if (element.nodeType !== 1) {\n        return;\n      }\n      element = element.parentNode || element;\n      ref = element.querySelectorAll(\".\" + this.config.boxClass);\n      results = [];\n      for (j = 0, len = ref.length; j < len; j++) {\n        box = ref[j];\n        if (indexOf.call(this.all, box) < 0) {\n          this.boxes.push(box);\n          this.all.push(box);\n          if (this.stopped || this.disabled()) {\n            this.resetStyle();\n          } else {\n            this.applyStyle(box, true);\n          }\n          results.push(this.scrolled = true);\n        } else {\n          results.push(void 0);\n        }\n      }\n      return results;\n    };\n\n    WOW.prototype.show = function(box) {\n      this.applyStyle(box);\n      box.className = box.className + \" \" + this.config.animateClass;\n      if (this.config.callback != null) {\n        this.config.callback(box);\n      }\n      this.util().emitEvent(box, this.wowEvent);\n      this.util().addEvent(box, 'animationend', this.resetAnimation);\n      this.util().addEvent(box, 'oanimationend', this.resetAnimation);\n      this.util().addEvent(box, 'webkitAnimationEnd', this.resetAnimation);\n      this.util().addEvent(box, 'MSAnimationEnd', this.resetAnimation);\n      return box;\n    };\n\n    WOW.prototype.applyStyle = function(box, hidden) {\n      var delay, duration, iteration;\n      duration = box.getAttribute('data-wow-duration');\n      delay = box.getAttribute('data-wow-delay');\n      iteration = box.getAttribute('data-wow-iteration');\n      return this.animate((function(_this) {\n        return function() {\n          return _this.customStyle(box, hidden, duration, delay, iteration);\n        };\n      })(this));\n    };\n\n    WOW.prototype.animate = (function() {\n      if ('requestAnimationFrame' in window) {\n        return function(callback) {\n          return window.requestAnimationFrame(callback);\n        };\n      } else {\n        return function(callback) {\n          return callback();\n        };\n      }\n    })();\n\n    WOW.prototype.resetStyle = function() {\n      var box, j, len, ref, results;\n      ref = this.boxes;\n      results = [];\n      for (j = 0, len = ref.length; j < len; j++) {\n        box = ref[j];\n        results.push(box.style.visibility = 'visible');\n      }\n      return results;\n    };\n\n    WOW.prototype.resetAnimation = function(event) {\n      var target;\n      if (event.type.toLowerCase().indexOf('animationend') >= 0) {\n        target = event.target || event.srcElement;\n        return target.className = target.className.replace(this.config.animateClass, '').trim();\n      }\n    };\n\n    WOW.prototype.customStyle = function(box, hidden, duration, delay, iteration) {\n      if (hidden) {\n        this.cacheAnimationName(box);\n      }\n      box.style.visibility = hidden ? 'hidden' : 'visible';\n      if (duration) {\n        this.vendorSet(box.style, {\n          animationDuration: duration\n        });\n      }\n      if (delay) {\n        this.vendorSet(box.style, {\n          animationDelay: delay\n        });\n      }\n      if (iteration) {\n        this.vendorSet(box.style, {\n          animationIterationCount: iteration\n        });\n      }\n      this.vendorSet(box.style, {\n        animationName: hidden ? 'none' : this.cachedAnimationName(box)\n      });\n      return box;\n    };\n\n    WOW.prototype.vendors = [\"moz\", \"webkit\"];\n\n    WOW.prototype.vendorSet = function(elem, properties) {\n      var name, results, value, vendor;\n      results = [];\n      for (name in properties) {\n        value = properties[name];\n        elem[\"\" + name] = value;\n        results.push((function() {\n          var j, len, ref, results1;\n          ref = this.vendors;\n          results1 = [];\n          for (j = 0, len = ref.length; j < len; j++) {\n            vendor = ref[j];\n            results1.push(elem[\"\" + vendor + (name.charAt(0).toUpperCase()) + (name.substr(1))] = value);\n          }\n          return results1;\n        }).call(this));\n      }\n      return results;\n    };\n\n    WOW.prototype.vendorCSS = function(elem, property) {\n      var j, len, ref, result, style, vendor;\n      style = getComputedStyle(elem);\n      result = style.getPropertyCSSValue(property);\n      ref = this.vendors;\n      for (j = 0, len = ref.length; j < len; j++) {\n        vendor = ref[j];\n        result = result || style.getPropertyCSSValue(\"-\" + vendor + \"-\" + property);\n      }\n      return result;\n    };\n\n    WOW.prototype.animationName = function(box) {\n      var animationName, error;\n      try {\n        animationName = this.vendorCSS(box, 'animation-name').cssText;\n      } catch (error) {\n        animationName = getComputedStyle(box).getPropertyValue('animation-name');\n      }\n      if (animationName === 'none') {\n        return '';\n      } else {\n        return animationName;\n      }\n    };\n\n    WOW.prototype.cacheAnimationName = function(box) {\n      return this.animationNameCache.set(box, this.animationName(box));\n    };\n\n    WOW.prototype.cachedAnimationName = function(box) {\n      return this.animationNameCache.get(box);\n    };\n\n    WOW.prototype.scrollHandler = function() {\n      return this.scrolled = true;\n    };\n\n    WOW.prototype.scrollCallback = function() {\n      var box;\n      if (this.scrolled) {\n        this.scrolled = false;\n        this.boxes = (function() {\n          var j, len, ref, results;\n          ref = this.boxes;\n          results = [];\n          for (j = 0, len = ref.length; j < len; j++) {\n            box = ref[j];\n            if (!(box)) {\n              continue;\n            }\n            if (this.isVisible(box)) {\n              this.show(box);\n              continue;\n            }\n            results.push(box);\n          }\n          return results;\n        }).call(this);\n        if (!(this.boxes.length || this.config.live)) {\n          return this.stop();\n        }\n      }\n    };\n\n    WOW.prototype.offsetTop = function(element) {\n      var top;\n      while (element.offsetTop === void 0) {\n        element = element.parentNode;\n      }\n      top = element.offsetTop;\n      while (element = element.offsetParent) {\n        top += element.offsetTop;\n      }\n      return top;\n    };\n\n    WOW.prototype.isVisible = function(box) {\n      var bottom, offset, top, viewBottom, viewTop;\n      offset = box.getAttribute('data-wow-offset') || this.config.offset;\n      viewTop = (this.config.scrollContainer && this.config.scrollContainer.scrollTop) || window.pageYOffset;\n      viewBottom = viewTop + Math.min(this.element.clientHeight, this.util().innerHeight()) - offset;\n      top = this.offsetTop(box);\n      bottom = top + box.clientHeight;\n      return top <= viewBottom && bottom >= viewTop;\n    };\n\n    WOW.prototype.util = function() {\n      return this._util != null ? this._util : this._util = new Util();\n    };\n\n    WOW.prototype.disabled = function() {\n      return !this.config.mobile && this.util().isMobile(navigator.userAgent);\n    };\n\n    return WOW;\n\n  })();\n\n}).call(this);\n\n\n//# sourceURL=webpack://saas-tailwind/./node_modules/wowjs/dist/wow.js?");

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
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
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
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = __webpack_require__("./src/js/index.js");
/******/ 	
/******/ })()
;