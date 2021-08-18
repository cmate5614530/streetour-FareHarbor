/*detect parameters on script tag*/
var script_tag = document.getElementById('js-turitop');
var business_ga = script_tag.getAttribute("data-ga");
var business_id = script_tag.getAttribute("data-company");
var business_lang = script_tag.getAttribute("data-lang");
var business_cssclass = script_tag.getAttribute("data-cssclass");
var business_buttoncolor = script_tag.getAttribute("data-buttoncolor");
var business_tag = script_tag.getAttribute("data-afftag");
var httpTuritop = script_tag.src.split('.')[0]; //detect if loading from production or development urls
var widgetBackOffice_tag = script_tag.getAttribute("data-widget-backoffice");
var dataSourceWidgetBackOffice_tag = script_tag.getAttribute("data-source-widget-backoffice");
var resellerwidgetBackOffice_tag = script_tag.getAttribute("data-reseller-widget-backoffice");

//Add OS classes for CSS
var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
if (iOS) { document.getElementsByTagName("html")[0].classList.add('turitop-ios'); }

//load css
var link = document.createElement("link");
link.id = "loadturitop";
link.href = httpTuritop + ".turitop.com/css/load-turitop.min.css";
link.type = "text/css";
link.rel = "stylesheet";
document.getElementsByTagName("head")[0].appendChild(link);

// iframe resizer library (js for iframe parent) from https://github.com/davidjbradshaw/iframe-resizer

!function (a) { "use strict"; function b(b, c, d) { "addEventListener" in a ? b.addEventListener(c, d, !1) : "attachEvent" in a && b.attachEvent("on" + c, d) } function c(b, c, d) { "removeEventListener" in a ? b.removeEventListener(c, d, !1) : "detachEvent" in a && b.detachEvent("on" + c, d) } function d() { var b, c = ["moz", "webkit", "o", "ms"]; for (b = 0; b < c.length && !N; b += 1)N = a[c[b] + "RequestAnimationFrame"]; N || h("setup", "RequestAnimationFrame not supported") } function e(b) { var c = "Host page: " + b; return a.top !== a.self && (c = a.parentIFrame && a.parentIFrame.getId ? a.parentIFrame.getId() + ": " + b : "Nested host page: " + b), c } function f(a) { return K + "[" + e(a) + "]" } function g(a) { return P[a] ? P[a].log : G } function h(a, b) { k("log", a, b, g(a)) } function i(a, b) { k("info", a, b, g(a)) } function j(a, b) { k("warn", a, b, !0) } function k(b, c, d, e) { !0 === e && "object" == typeof a.console && console[b](f(c), d) } function l(d) { function e() { function a() { s(V), p(W) } g("Height"), g("Width"), t(a, V, "init") } function f() { var a = U.substr(L).split(":"); return { iframe: P[a[0]].iframe, id: a[0], height: a[1], width: a[2], type: a[3] } } function g(a) { var b = Number(P[W]["max" + a]), c = Number(P[W]["min" + a]), d = a.toLowerCase(), e = Number(V[d]); h(W, "Checking " + d + " is in range " + c + "-" + b), c > e && (e = c, h(W, "Set " + d + " to min value")), e > b && (e = b, h(W, "Set " + d + " to max value")), V[d] = "" + e } function k() { function a() { function a() { var a = 0, d = !1; for (h(W, "Checking connection is from allowed list of origins: " + c); a < c.length; a++)if (c[a] === b) { d = !0; break } return d } function d() { var a = P[W].remoteHost; return h(W, "Checking connection is from: " + a), b === a } return c.constructor === Array ? a() : d() } var b = d.origin, c = P[W].checkOrigin; if (c && "" + b != "null" && !a()) throw new Error("Unexpected message received from: " + b + " for " + V.iframe.id + ". Message was: " + d.data + ". This error can be disabled by setting the checkOrigin: false option or by providing of array of trusted domains."); return !0 } function l() { return K === ("" + U).substr(0, L) && U.substr(L).split(":")[0] in P } function w() { var a = V.type in { "true": 1, "false": 1, undefined: 1 }; return a && h(W, "Ignoring init message from meta parent page"), a } function y(a) { return U.substr(U.indexOf(":") + J + a) } function z(a) { h(W, "MessageCallback passed: {iframe: " + V.iframe.id + ", message: " + a + "}"), N("messageCallback", { iframe: V.iframe, message: JSON.parse(a) }), h(W, "--") } function A() { var b = document.body.getBoundingClientRect(), c = V.iframe.getBoundingClientRect(); return JSON.stringify({ clientHeight: Math.max(document.documentElement.clientHeight, a.innerHeight || 0), clientWidth: Math.max(document.documentElement.clientWidth, a.innerWidth || 0), offsetTop: parseInt(c.top - b.top, 10), offsetLeft: parseInt(c.left - b.left, 10), scrollTop: a.pageYOffset, scrollLeft: a.pageXOffset }) } function B(a, b) { function c() { u("Send Page Info", "pageInfo:" + A(), a, b) } x(c, 32) } function C() { function d(b, c) { function d() { P[g] ? B(P[g].iframe, g) : e() } ["scroll", "resize"].forEach(function (e) { h(g, b + e + " listener for sendPageInfo"), c(a, e, d) }) } function e() { d("Remove ", c) } function f() { d("Add ", b) } var g = W; f(), P[g].stopPageInfo = e } function D() { P[W] && P[W].stopPageInfo && (P[W].stopPageInfo(), delete P[W].stopPageInfo) } function E() { var a = !0; return null === V.iframe && (j(W, "IFrame (" + V.id + ") not found"), a = !1), a } function F(a) { var b = a.getBoundingClientRect(); return o(W), { x: Math.floor(Number(b.left) + Number(M.x)), y: Math.floor(Number(b.top) + Number(M.y)) } } function G(b) { function c() { M = g, H(), h(W, "--") } function d() { return { x: Number(V.width) + f.x, y: Number(V.height) + f.y } } function e() { a.parentIFrame ? a.parentIFrame["scrollTo" + (b ? "Offset" : "")](g.x, g.y) : j(W, "Unable to scroll to requested position, window.parentIFrame not found") } var f = b ? F(V.iframe) : { x: 0, y: 0 }, g = d(); h(W, "Reposition requested from iFrame (offset x:" + f.x + " y:" + f.y + ")"), a.top !== a.self ? e() : c() } function H() { !1 !== N("scrollCallback", M) ? p(W) : q() } function I(b) { function c() { var a = F(g); h(W, "Moving to in page link (#" + e + ") at x: " + a.x + " y: " + a.y), M = { x: a.x, y: a.y }, H(), h(W, "--") } function d() { a.parentIFrame ? a.parentIFrame.moveToAnchor(e) : h(W, "In page link #" + e + " not found and window.parentIFrame not found") } var e = b.split("#")[1] || "", f = decodeURIComponent(e), g = document.getElementById(f) || document.getElementsByName(f)[0]; g ? c() : a.top !== a.self ? d() : h(W, "In page link #" + e + " not found") } function N(a, b) { return m(W, a, b) } function O() { switch (P[W].firstRun && T(), V.type) { case "close": n(V.iframe); break; case "message": z(y(6)); break; case "scrollTo": G(!1); break; case "scrollToOffset": G(!0); break; case "pageInfo": B(P[W].iframe, W), C(); break; case "pageInfoStop": D(); break; case "inPageLink": I(y(9)); break; case "reset": r(V); break; case "init": e(), N("initCallback", V.iframe), N("resizedCallback", V); break; default: e(), N("resizedCallback", V) } } function Q(a) { var b = !0; return P[a] || (b = !1, j(V.type + " No settings for " + a + ". Message was: " + U)), b } function S() { for (var a in P) u("iFrame requested init", v(a), document.getElementById(a), a) } function T() { P[W].firstRun = !1 } var U = d.data, V = {}, W = null; "[iFrameResizerChild]Ready" === U ? S() : l() ? (V = f(), W = R = V.id, !w() && Q(W) && (h(W, "Received: " + U), E() && k() && O())) : i(W, "Ignored: " + U) } function m(a, b, c) { var d = null, e = null; if (P[a]) { if (d = P[a][b], "function" != typeof d) throw new TypeError(b + " on iFrame[" + a + "] is not a function"); e = d(c) } return e } function n(a) { var b = a.id; h(b, "Removing iFrame: " + b), a.parentNode.removeChild(a), m(b, "closedCallback", b), h(b, "--"), delete P[b] } function o(b) { null === M && (M = { x: void 0 !== a.pageXOffset ? a.pageXOffset : document.documentElement.scrollLeft, y: void 0 !== a.pageYOffset ? a.pageYOffset : document.documentElement.scrollTop }, h(b, "Get page position: " + M.x + "," + M.y)) } function p(b) { null !== M && (a.scrollTo(M.x, M.y), h(b, "Set page position: " + M.x + "," + M.y), q()) } function q() { M = null } function r(a) { function b() { s(a), u("reset", "reset", a.iframe, a.id) } h(a.id, "Size reset requested by " + ("init" === a.type ? "host page" : "iFrame")), o(a.id), t(b, a, "reset") } function s(a) { function b(b) { a.iframe.style[b] = a[b] + "px", h(a.id, "IFrame (" + e + ") " + b + " set to " + a[b] + "px") } function c(b) { H || "0" !== a[b] || (H = !0, h(e, "Hidden iFrame detected, creating visibility listener"), y()) } function d(a) { b(a), c(a) } var e = a.iframe.id; P[e] && (P[e].sizeHeight && d("height"), P[e].sizeWidth && d("width")) } function t(a, b, c) { c !== b.type && N ? (h(b.id, "Requesting animation frame"), N(a)) : a() } function u(a, b, c, d) { function e() { var e = P[d].targetOrigin; h(d, "[" + a + "] Sending msg to iframe[" + d + "] (" + b + ") targetOrigin: " + e), c.contentWindow.postMessage(K + b, e) } function f() { i(d, "[" + a + "] IFrame(" + d + ") not found"), P[d] && delete P[d] } function g() { c && "contentWindow" in c && null !== c.contentWindow ? e() : f() } d = d || c.id, P[d] && g() } function v(a) { return a + ":" + P[a].bodyMarginV1 + ":" + P[a].sizeWidth + ":" + P[a].log + ":" + P[a].interval + ":" + P[a].enablePublicMethods + ":" + P[a].autoResize + ":" + P[a].bodyMargin + ":" + P[a].heightCalculationMethod + ":" + P[a].bodyBackground + ":" + P[a].bodyPadding + ":" + P[a].tolerance + ":" + P[a].inPageLinks + ":" + P[a].resizeFrom + ":" + P[a].widthCalculationMethod } function w(a, c) { function d() { function b(b) { 1 / 0 !== P[w][b] && 0 !== P[w][b] && (a.style[b] = P[w][b] + "px", h(w, "Set " + b + " = " + P[w][b] + "px")) } function c(a) { if (P[w]["min" + a] > P[w]["max" + a]) throw new Error("Value for min" + a + " can not be greater than max" + a) } c("Height"), c("Width"), b("maxHeight"), b("minHeight"), b("maxWidth"), b("minWidth") } function e() { var a = c && c.id || S.id + F++; return null !== document.getElementById(a) && (a += F++), a } function f(b) { return R = b, "" === b && (a.id = b = e(), G = (c || {}).log, R = b, h(b, "Added missing iframe ID: " + b + " (" + a.src + ")")), b } function g() { h(w, "IFrame scrolling " + (P[w].scrolling ? "enabled" : "disabled") + " for " + w), a.style.overflow = !1 === P[w].scrolling ? "hidden" : "auto", a.scrolling = !1 === P[w].scrolling ? "no" : "yes" } function i() { ("number" == typeof P[w].bodyMargin || "0" === P[w].bodyMargin) && (P[w].bodyMarginV1 = P[w].bodyMargin, P[w].bodyMargin = "" + P[w].bodyMargin + "px") } function k() { var b = P[w].firstRun, c = P[w].heightCalculationMethod in O; !b && c && r({ iframe: a, height: 0, width: 0, type: "init" }) } function l() { Function.prototype.bind && (P[w].iframe.iFrameResizer = { close: n.bind(null, P[w].iframe), resize: u.bind(null, "Window resize", "resize", P[w].iframe), moveToAnchor: function (a) { u("Move to anchor", "inPageLink:" + a, P[w].iframe, w) }, sendMessage: function (a) { a = JSON.stringify(a), u("Send Message", "message:" + a, P[w].iframe, w) } }) } function m(c) { function d() { u("iFrame.onload", c, a), k() } b(a, "load", d), u("init", c, a) } function o(a) { if ("object" != typeof a) throw new TypeError("Options is not an object") } function p(a) { for (var b in S) S.hasOwnProperty(b) && (P[w][b] = a.hasOwnProperty(b) ? a[b] : S[b]) } function q(a) { return "" === a || "file://" === a ? "*" : a } function s(b) { b = b || {}, P[w] = { firstRun: !0, iframe: a, remoteHost: a.src.split("/").slice(0, 3).join("/") }, o(b), p(b), P[w].targetOrigin = !0 === P[w].checkOrigin ? q(P[w].remoteHost) : "*" } function t() { return w in P && "iFrameResizer" in a } var w = f(a.id); t() ? j(w, "Ignored iFrame, already setup.") : (s(c), g(), d(), i(), m(v(w)), l()) } function x(a, b) { null === Q && (Q = setTimeout(function () { Q = null, a() }, b)) } function y() { function b() { function a(a) { function b(b) { return "0px" === P[a].iframe.style[b] } function c(a) { return null !== a.offsetParent } c(P[a].iframe) && (b("height") || b("width")) && u("Visibility change", "resize", P[a].iframe, a) } for (var b in P) a(b) } function c(a) { h("window", "Mutation observed: " + a[0].target + " " + a[0].type), x(b, 16) } function d() { var a = document.querySelector("body"), b = { attributes: !0, attributeOldValue: !1, characterData: !0, characterDataOldValue: !1, childList: !0, subtree: !0 }, d = new e(c); d.observe(a, b) } var e = a.MutationObserver || a.WebKitMutationObserver; e && d() } function z(a) { function b() { B("Window " + a, "resize") } h("window", "Trigger event: " + a), x(b, 16) } function A() { function a() { B("Tab Visable", "resize") } "hidden" !== document.visibilityState && (h("document", "Trigger event: Visiblity change"), x(a, 16)) } function B(a, b) { function c(a) { return "parent" === P[a].resizeFrom && P[a].autoResize && !P[a].firstRun } for (var d in P) c(d) && u(a, b, document.getElementById(d), d) } function C() { b(a, "message", l), b(a, "resize", function () { z("resize") }), b(document, "visibilitychange", A), b(document, "-webkit-visibilitychange", A), b(a, "focusin", function () { z("focus") }), b(a, "focus", function () { z("focus") }) } function D() { function a(a, c) { function d() { if (!c.tagName) throw new TypeError("Object is not a valid DOM element"); if ("IFRAME" !== c.tagName.toUpperCase()) throw new TypeError("Expected <IFRAME> tag, found <" + c.tagName + ">") } c && (d(), w(c, a), b.push(c)) } var b; return d(), C(), function (c, d) { switch (b = [], typeof d) { case "undefined": case "string": Array.prototype.forEach.call(document.querySelectorAll(d || "iframe"), a.bind(void 0, c)); break; case "object": a(c, d); break; default: throw new TypeError("Unexpected data type (" + typeof d + ")") }return b } } function E(a) { a.fn.iFrameResize = function (a) { return this.filter("iframe").each(function (b, c) { w(c, a) }).end() } } var F = 0, G = !1, H = !1, I = "message", J = I.length, K = "[iFrameSizer]", L = K.length, M = null, N = a.requestAnimationFrame, O = { max: 1, scroll: 1, bodyScroll: 1, documentElementScroll: 1 }, P = {}, Q = null, R = "Host Page", S = { autoResize: !0, bodyBackground: null, bodyMargin: null, bodyMarginV1: 8, bodyPadding: null, checkOrigin: !0, inPageLinks: !1, enablePublicMethods: !0, heightCalculationMethod: "bodyOffset", id: "iFrameResizer", interval: 32, log: !1, maxHeight: 1 / 0, maxWidth: 1 / 0, minHeight: 0, minWidth: 0, resizeFrom: "parent", scrolling: !1, sizeHeight: !0, sizeWidth: !1, tolerance: 0, widthCalculationMethod: "scroll", closedCallback: function () { }, initCallback: function () { }, messageCallback: function () { j("MessageCallback function not defined") }, resizedCallback: function () { }, scrollCallback: function () { return !0 } }; a.jQuery && E(jQuery), "function" == typeof define && define.amd ? define([], D) : "object" == typeof module && "object" == typeof module.exports ? module.exports = D() : a.iFrameResize = a.iFrameResize || D() }(window || {});
//# sourceMappingURL=iframeResizer.map

//add new class to all elements
var turiLoad = document.getElementsByClassName('load-turitop');
for (var j = 0, len = turiLoad.length; j < len; j++) {
  document.getElementsByClassName('load-turitop')[j].className += ' loading-turitop';
}

//functions to set and get cookies
function setCookie(cname, cvalue, exdays) { var d = new Date(); d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000)); var expires = "expires=" + d.toUTCString(); document.cookie = cname + "=" + cvalue + "; " + expires+";path=/"; }

function getCookie(cname) { var name = cname + "="; var ca = document.cookie.split(';'); for (var i = 0; i < ca.length; i++) { var c = ca[i]; while (c.charAt(0) == ' ') c = c.substring(1); if (c.indexOf(name) == 0) return c.substring(name.length, c.length); } return ""; }

//get affiliate id if exists
function getUrlParameter(name) {
  name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
  return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
var qsp = 'ttafid';
if (business_tag != null && business_tag.length > 1) { qsp = business_tag; }
affiliateID = getUrlParameter(qsp);

//Create or update affiliate cookie
if (affiliateID) { setCookie('turitopAff', affiliateID, 30); }

//check if affiliate cookie exists before building lightboxes and iframes
var affiliateCookie = getCookie('turitopAff');


/*start lightbox functions*/
function FullPic() { }

FullPic.prototype.open = function (lightboxUrl) {
  //This element gets appended before lightbox is faded in so that elements with high z-index don't cover lightbox during fade.
  this.lightboxWrapper = document.createElement('div');
  this.lightboxWrapper.className = 'lightbox-wrapper-turitop';
  this.lightbox = document.createElement('div');
  var overlayDiv = document.createElement('div');
  overlayDiv.className = 'lightbox-overlay-turitop';
  this.centralContainer = document.createElement('div');
  this.centralContainer.className = 'lightbox-expanded-turitop';
  this.xDivContainer = document.createElement('div');
  //DEV-1499 Add product short_id class to element centralContainer
  var parser = document.createElement('a');
  parser.href = lightboxUrl;
  var pathName = parser.pathname.split('/');
  var product_id = '';
  if (pathName[4]) {
    product_id = ' product-' + pathName[4];
  }
  this.centralContainer.className = 'lightbox-expanded-turitop' + product_id;
  var xDiv = document.createElement('div');
  xDiv.innerHTML = 'x';
  xDiv.className = 'x-lightbox-turitop';
  this.iframeContainer = document.createElement('div');
  this.iframeContainer.className = 'lightbox-iframe-container-turitop';
  // Wait for cart integration to load properly
  if (typeof (lightboxUrl) === "undefined") { return; }
  var liframe = document.createElement('iframe');
  liframe.className = 'lightbox-iframe-turitop';
  liframe.src = lightboxUrl;

  var maxHeight = '530px';
  if (screen.width < 600) { maxHeight = '900px' };
  this.centralContainer.style.maxHeight = maxHeight;

  var columnWrapper = document.querySelector('#column-wrapper');
  this.lightbox.appendChild(overlayDiv);
  this.lightbox.appendChild(this.centralContainer);
  this.centralContainer.appendChild(this.xDivContainer);
  this.xDivContainer.appendChild(xDiv);
  this.centralContainer.appendChild(this.iframeContainer);
  this.iframeContainer.appendChild(liframe);
  //Fade in lightbox overlay and image.
  document.body.insertBefore(this.lightboxWrapper, columnWrapper);
  this.lightbox.style.opacity = 0;
  this.lightboxWrapper.appendChild(this.lightbox);  //Appended first for z-index fix (see comment above).
  var counter = 0;
  var lightBox = this.lightbox;  //Otherwise "this" in callback function would refer to call function rather than FullPic property.
  var fadeInt = setInterval(function () {
    if (counter < 1.05) {
      lightBox.style.opacity = counter;
      counter += 0.05;
    } else {
      clearInterval(fadeInt);
    }
  }, 15);

  //freeze lightbox background
  document.getElementsByTagName('body')[0].classList.add('freeze-bg-lightbox-turitop');
  document.getElementsByTagName('html')[0].classList.add('freeze-bg-lightbox-turitop');

  //Without passing "this" to "self", "this" would refer to event listener in callback function.
  var self = this;
  overlayDiv.addEventListener('click', function () {
    self.close();
  });
  this.xDivContainer.addEventListener('click', function () {
    self.close();
  });
};

FullPic.prototype.close = function () {
  var counter = 1;
  var lightBox = this.lightbox;
  var lightWrap = this.lightboxWrapper;
  var fadeInt = setInterval(function () {
    if (counter > 0) {
      lightBox.style.opacity = counter;
      counter -= 0.05;
    } else {
      clearInterval(fadeInt);
      document.body.removeChild(lightWrap);
    }
  }, 15);
  //remove background freeze
  document.getElementsByTagName('body')[0].classList.remove('freeze-bg-lightbox-turitop');
  document.getElementsByTagName('html')[0].classList.remove('freeze-bg-lightbox-turitop');

  //Remove CSS Widget Booking Box
  var csswbb = document.getElementById("csswbb");
  if (csswbb) {
    csswbb.remove();
  }

  //Add Load Turitop CSS
  var loadturitop = document.getElementById("loadturitop");
  if (!loadturitop) {
    var link = document.createElement("link");
    link.id = "loadturitop";
    link.href = httpTuritop + ".turitop.com/css/load-turitop.min.css";
    link.type = "text/css";
    link.rel = "stylesheet";
    document.getElementsByTagName("head")[0].appendChild(link);
  }

};
/*end lightbox functions*/

//Load RedeemBookingBox JS
function LoadPluginRedeemBookingBox() {
  var jswbb = document.createElement("script");
  jswbb.id = "jswbb"
  jswbb.src = httpTuritop + ".turitop.com/js/redeembookingbox.js";
  jswbb.type = "text/javascript";
  document.getElementsByTagName("body")[0].appendChild(jswbb);
}

var buttons;
function buildAllElements(gaParameter, gaClientId) {
  function buildButton(text, colorClass, clickUrl) {
    if (text != '') {
      var turitopButton = document.createElement('a');
      turitopButton.className += colorClass;
      turitopButton.innerHTML = text;
      document.getElementsByClassName('loading-turitop')[i].appendChild(turitopButton);
      document.getElementsByClassName('loading-turitop')[i].getElementsByTagName('a')[0].onclick = function () { new FullPic().open(clickUrl); };
    }
  }

  function buildCartButton(text, cartButtonColorClass, cartIconColor, clickUrl) {
    var turitopCart = document.createElement('a');
    turitopCart.setAttribute("src", httpTuritop + ".turitop.com/cart/checkout");

    // TODO show cart as fonticon (fontawesome, html icon)
    //var cartImg = document.createElement('img');
    //cartImg.setAttribute("src", httpTuritop + ".turitop.com/img/cart-" + cartIconColor + ".png");
    var cartImg = document.createElement('span');
    cartImg.setAttribute( "class", "dashicons dashicons-cart" );
    //cartImg.setAttribute( "style", "background-image: url( 'https://live.simpledevel.devel/turitop/wp-content/plugins/turitop-booking-system/assets/images/cart.svg' )" );

    var cartQuantity = document.createElement("span");
    cartQuantity.setAttribute("class", "badge badge-cart");
    var textQuantity = document.createElement("img");
    textQuantity.src = httpTuritop + ".turitop.com/img/loader.gif";
    cartQuantity.appendChild(textQuantity);

    turitopCart.className += cartButtonColorClass;
    turitopCart.id = "turitop-cart-button";
    turitopCart.appendChild(cartImg);

    turitopCart.appendChild(cartQuantity);

    document.getElementsByClassName('loading-turitop')[i].appendChild(turitopCart);

    document.getElementsByClassName('loading-turitop')[i].getElementsByTagName('a')[0].onclick = function () { new FullPic().open(clickUrl); };
  }

  function buildRedeemButton(text, colorClass, clickUrl) {
    //Load Plugin Calendar JQuery WidgetBookingBox
    LoadPluginRedeemBookingBox();
    var turitopButton = document.createElement('a');
    turitopButton.className += colorClass;
    turitopButton.innerHTML = text;
    document.getElementsByClassName('loading-turitop')[i].appendChild(turitopButton);
    document.getElementsByClassName('loading-turitop')[i].getElementsByTagName('a')[0].onclick = function () { new FullPic().openRedeem(clickUrl); };
  }

  var turiElem = document.getElementsByClassName('loading-turitop');
  for (var i = 0, len = turiElem.length; i < len; i++) {
    var turidiv = document.getElementsByClassName('loading-turitop')[i];
    var widgetLang = business_lang;
    if (turidiv.dataset.lang != null && turidiv.dataset.lang.length > 1) { widgetLang = turidiv.dataset.lang; }
    if (widgetLang.substring(0, 9) == 'subdomain') {
      wwwLang = widgetLang.substring(10, 12);
      webSubdomain = (window.location.host).split('.')[0];
      if (webSubdomain == 'www') {
        widgetLang = wwwLang;
      } else if (widgetLang.indexOf(webSubdomain) == -1) {
        widgetLang = wwwLang;
      } else {
        widgetLang = webSubdomain;
      }
    }
    var ele3 = 'box';

    turidiv.dataset.service = (turidiv.dataset.service) ? turidiv.dataset.service.toUpperCase() : " ";
    turidiv.dataset.category = (turidiv.dataset.category) ? turidiv.dataset.category.toUpperCase() : " ";

    var fsl = turidiv.dataset.service[0];
    //if first letter of service short id starts with C it is a category and we have to change 'box' for 'menu' on the url
    if (turidiv.dataset.service[0] == 'C') { ele3 = 'menu'; }
    //if product id = all, load the daily view
    if (turidiv.dataset.service == 'ALL' || turidiv.dataset.category[0] === "C") { ele3 = 'daily'; }
    var coid = business_id;
    if (turidiv.dataset.company != null && turidiv.dataset.company.length > 1) { coid = turidiv.dataset.company }
    if (ele3 == 'daily') {
      var url = httpTuritop + '.turitop.com/booking/daily/' + coid + '/' + widgetLang;
      if (turidiv.dataset.category[0] == "C") {
        url += "/";
        url += turidiv.dataset.category;
      }
    } else {
      if (turidiv.dataset.embed == 'cart') {
        var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
        var eventer = window[eventMethod];
        var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

        // if no message received after 5 seconds, load aux iframe
        var timer = setTimeout(function () {
          var iFrame2 = document.createElement('iFrame');
          iFrame2.style.display = "none";
          iFrame2.frameBorder = 0;
          iFrame2.width = "0";
          iFrame2.height = "0";
          iFrame2.setAttribute("src", httpTuritop + ".turitop.com/cart/integration/" + coid + "/" + widgetLang);
          document.getElementById("turitop-cart-button").appendChild(iFrame2);
        }, 5000);

        // Listen to message from iframe
        // Lolo: DEV-2325 - Rework to allow more than one cart integration per page
        eventer(messageEvent, function (e) {
          if (typeof (e.data.origin) !== "undefined" && e.data.origin.match(/turitop/)) {
            var cart_div = document.querySelectorAll("[id='turitop-cart-button']");

            if (cart_div === null || cart_div.length == 0) {
              console.log("Cart integration not found. Aborting");
              return false;
            }
            for (var i = 0; i < cart_div.length; i++) {
              if (typeof (e.data.count) !== "undefined") {
                var container = cart_div[i].querySelector(".badge-cart");
                if (typeof (container) !== "undefined" && container.innerHTML != e.data.count) {
                  container.innerHTML = e.data.count;
                }
              }
              if (typeof (e.data.hash) !== "undefined" && e.data.hash.length > 0) {
                cart_div[i].onclick = function () {
                  url = httpTuritop + ".turitop.com/cart/checkout/" + coid + "/" + e.data.hash + "/" + widgetLang + "/step2/a/?from=cart-integration-link";
                  new FullPic().open(url);
                };
              }
            }

            // if we receive any message, dont load aux iframe
            clearTimeout(timer);
          }
        }, false);
      } else {
        var url = httpTuritop + '.turitop.com/booking/' + ele3 + '/' + coid + '/' + turidiv.dataset.service + '/' + widgetLang;
      }
    }

    //add timestamp to url to avoid browsers caching the iframe
    var tstamp = Date.now();
    if (typeof url !== "undefined") { url += '?ts=' + tstamp + '&returnUrl=' + btoa(window.location.href); }
    if (turidiv.dataset.gift != null && turidiv.dataset.gift.length > 1 && turidiv.dataset.gift == "checked") {
      url += "&only_gift=1";
    }
    if (widgetBackOffice_tag != null && widgetBackOffice_tag.length == 10) {
      url += "&widget_backoffice=" + widgetBackOffice_tag;
      if (business_tag != null && widgetBackOffice_tag.length > 0) {
        url += "&widget_backoffice_agent=" + business_tag;
      }
      if (dataSourceWidgetBackOffice_tag != null) {
        url += "&dataSourceWidgetBackOffice=" + dataSourceWidgetBackOffice_tag;
      }
      if(resellerwidgetBackOffice_tag != null) {
        url += "&resellerwidgetBackOffice=" + resellerwidgetBackOffice_tag;
      }
    }
    var coid = business_id;
    var affiliateId = '';
    if (affiliateCookie) { affiliateId = affiliateCookie; }
    if (turidiv.dataset.affiliate != null && turidiv.dataset.affiliate.length > 1) { affiliateId = turidiv.dataset.affiliate; }
    if (affiliateId != '') { url += '&affiliate=' + affiliateId; }
    if (gaParameter != '') { url += '&' + gaParameter; }
    if (gaClientId != '') {
      url += '&_gaClientId=' + gaClientId;
    }

    //iframe settings
    var iFrame = document.createElement('iFrame');
    if (typeof url !== "undefined") { iFrame.src = url; }
    var divWidth = document.getElementsByClassName('loading-turitop')[i].clientWidth;
    if (divWidth > 500) { iFrame.height = '600'; } else { iFrame.height = '900' }
    iFrame.className += 'iframe-resizable-turitop';
    if (ele3 == 'daily') { iFrame.className += ' daily-all'; }

    //set button color for lightbox

    var cartbuttoncolor = 'green';
    var carticoncolor = 'white';
    if (turidiv.dataset.embed == 'cart') {
      if (turidiv.dataset.cartbuttoncolor) { cartbuttoncolor = turidiv.dataset.cartbuttoncolor }
      if (turidiv.dataset.carticoncolor) { carticoncolor = turidiv.dataset.carticoncolor }
    } else {
      var writtenColor = '';
      if (business_buttoncolor != undefined) { writtenColor = business_buttoncolor };
      if (turidiv.dataset.buttoncolor) { writtenColor = turidiv.dataset.buttoncolor }

      if (writtenColor == '' || writtenColor == 'none') {
        var buttonColorClass = '';
      } else {
        var buttonColorClass = 'lightbox-button-turitop lightbox-button-turitop-' + writtenColor;
      }
      if (turidiv.dataset.cssclass) {
        buttonColorClass += ' ' + turidiv.dataset.cssclass;
      } else if (business_cssclass != undefined) {
        buttonColorClass += ' ' + business_cssclass;
      }
      //get anchor text for lightbox
      var buttonText = document.getElementsByClassName('loading-turitop')[i].innerHTML.replace(/<(?:.|\n)*?>/gm, '');
      document.getElementsByClassName('loading-turitop')[i].innerHTML = '';
    }


    //build iframe/lightbox
    if (turidiv.dataset.embed == 'box') {
      document.getElementsByClassName('loading-turitop')[i].appendChild(iFrame);
    } else {
      if (turidiv.dataset.embed == 'cart') {
        buildCartButton(buttonText, 'lightbox-button-turitop lightbox-button-turitop-' + cartbuttoncolor, carticoncolor, url);
      } else {
        if (turidiv.dataset.embed == 'redeemgv') {
          buildRedeemButton(buttonText, buttonColorClass, 'https://www.turitop.com/es/');
        } else {
          buildButton(buttonText, buttonColorClass, url);
        }
      }
    }
    document.getElementsByClassName('loading-turitop')[i].classList.remove('load-turitop');
  }
}

function turitopBuild() {
  if (business_ga == 'yes') {
    var runned = false;
    var maxAttempts = 15;
    var attempts = 0;
    var linkerParam = '';
    var clientId = '';
    /*check every 0.2secs if ga loaded to build elements*/
    function gaLoop() {
      setTimeout(function () {
        attempts++;
        if (typeof ga === "function") {
          ga(function () {
            var trackers = ga.getAll();
            trackers.forEach(function (tracker) {
              linkerParam = tracker.get('linkerParam');
              clientId = tracker.get('clientId');
              attempts = maxAttempts;
              runned = true;
              buildAllElements(linkerParam, clientId);
            });
          });
        }

        /*break loop after 3 seconds (15 of 0.2secs)*/
        if (attempts < maxAttempts) {
          gaLoop();
        } else {
          if (!runned) {
            buildAllElements('gaFailed=1', '');
          }
        }
      }, 200)
    }
    gaLoop();
  } else {
    buildAllElements('', '');
  }
}

turitopBuild();

setTimeout(function () {
  //function that activates iframeResizerLibrary
  iFrameResize({ log: false }, '.iframe-resizable-turitop');
}, 1500);
