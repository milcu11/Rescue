
<!-- DEBUG-VIEW START 7 APPPATH/Views/admin/evacuation_centers/index.php -->
<!-- DEBUG-VIEW START 6 APPPATH/Views/layouts/admin.php -->
<!DOCTYPE html>
<html lang="en">
<head>
<script  id="debugbar_loader" data-time="1785761671.430032" src="https://drvms.freedev.app/?debugbar"></script><script  id="debugbar_dynamic_script"></script><style  id="debugbar_dynamic_style"></style><script class="kint-rich-script">"use strict";(()=>{function m(n){if(!(n instanceof Element))throw new Error("Invalid argument to dedupeElement()");let t=n.ownerDocument,e=E(n);for(let s of t.querySelectorAll(e))n!==s&&s.parentNode.removeChild(s)}function d(n){return n instanceof Element?n.ownerDocument.contains(n):!1}function E(n){if(!(n instanceof Element))throw new Error("Invalid argument to buildClassSelector()");return[n.nodeName,...n.classList].join(".")}function f(n){if(!(n instanceof Element))throw new Error("Invalid argument to selectText()");let t=n.ownerDocument,e=t.getSelection(),s=t.createRange();s.selectNodeContents(n),e.removeAllRanges(),e.addRange(s)}function I(n,t){let e;return function(...s){clearTimeout(e),e=setTimeout(function(){n(...s)},t)}}function x(n){if(!(n instanceof Element))throw new Error("Invalid argument to offsetTop()");return n.offsetTop+(n.offsetParent?x(n.offsetParent):0)}var u=class n{static#e=new Set;static toggleSearchBox(t,e){let s=t.querySelector(".kint-search"),i=t.parentNode;if(s)if(s.classList.toggle("kint-show",e)){if(s.focus(),s.select(),!n.#e.has(s)){let r=i.querySelectorAll("dl").length,o=200;r>1e4&&(o=700),s.addEventListener("keyup",I(n.#t.bind(null,s),o)),n.#e.add(s)}n.#t(s)}else i.classList.remove("kint-search-root")}static#t(t){let e=t.closest(".kint-parent")?.parentNode;if(e)if(t.classList.contains("kint-show")&&t.value.length){let s=e.dataset.lastSearch;if(e.classList.add("kint-search-root"),s!==t.value){e.dataset.lastSearch=t.value,e.classList.remove("kint-search-match");for(let i of e.querySelectorAll(".kint-search-match"))i.classList.remove("kint-search-match");n.#s(e,t.value.toUpperCase())}}else e.classList.remove("kint-search-root")}static#s(t,e){let s=t.cloneNode(!0);for(let c of s.querySelectorAll(".access-path"))c.remove();if(!s.textContent.toUpperCase().includes(e))return;t.classList.add("kint-search-match");let i=t.firstElementChild;for(;i&&i.tagName!=="DT";)i=i.nextElementSibling;if(!i)return;let r=a.getChildContainer(i);if(!r)return;let o,l;for(let c of r.children)c.tagName==="DL"?n.#s(c,e):c.tagName==="UL"&&(c.classList.contains("kint-tabs")?o=c.children:c.classList.contains("kint-tab-contents")&&(l=c.children));if(!(!o||o.length!==l?.length))for(let c=o.length;c--;){let k=!1,F=!1;o[c].textContent.toUpperCase().includes(e)&&(k=!0);let O=l[c].cloneNode(!0);for(let v of O.querySelectorAll(".access-path"))v.remove();if(O.textContent.toUpperCase().includes(e)&&(k=!0,F=!0),k&&o[c].classList.add("kint-search-match"),F)for(let v of l[c].children)v.tagName==="DL"&&n.#s(v,e)}}};var g=class{static sort(t,e){let s=t.dataset.kintTableSort,i=parseInt(s)===e?-1:1,r=t.tBodies[0];[...r.rows].sort(function(o,l){o=o.cells[e].textContent.trim().toLocaleLowerCase(),l=l.cells[e].textContent.trim().toLocaleLowerCase();let c=0;return!isNaN(o)&&!isNaN(l)?(o=parseFloat(o),l=parseFloat(l),c=o-l):isNaN(o)&&!isNaN(l)?c=1:isNaN(l)&&!isNaN(o)?c=-1:c=(""+o).localeCompare(""+l),c*i}).forEach(o=>r.appendChild(o)),i<0?t.dataset.kintTableSort=null:t.dataset.kintTableSort=e}};var a=class n{#e;#t;#s;constructor(t){if(!(t instanceof h))throw new Error("Invalid argument to Rich.constructor()");this.#e=t,this.#e.runOnInit(this.#i.bind(this));let e=new q(this,t);new b(this,t.window,e)}#i(){let t=this.#e.window.document;if(d(this.#t)||(this.#t=t.querySelector("style.kint-rich-style")),this.#t&&m(this.#t),t.querySelector(".kint-rich.kint-file")){this.setupFolder(t);let e=this.#s.querySelector("dd.kint-foldout"),s=Array.from(t.querySelectorAll(".kint-rich.kint-file"));for(let i of s)i.parentNode!==e&&e.appendChild(i);this.#s.classList.add("kint-show")}}addToFolder(t){let e=t.closest(".kint-rich");if(!e)throw new Error("Bad addToFolder");let s=this.#e.window.document;if(this.setupFolder(s),this.folder.contains(t))throw new Error("Bad addToFolder");let i=this.#s.querySelector("dd.kint-foldout"),r=t.closest(".kint-parent, .kint-rich"),o=Array.from(e.querySelectorAll(".kint-folder-trigger"));if(e===r||e.querySelectorAll(".kint-rich > dl").length===1){for(let l of o)l.remove();e.classList.add("kint-file"),i.insertBefore(e,i.firstChild)}else{let l=s.createElement("div");l.classList.add("kint-rich"),l.classList.add("kint-file"),l.appendChild(r.closest(".kint-rich > dl"));let c=e.lastElementChild;c.matches(".kint-rich > footer")&&l.appendChild(c.cloneNode(!0));for(let k of o)k.remove();i.insertBefore(l,i.firstChild)}n.toggle(this.#s.querySelector(".kint-parent"),!0)}setupFolder(t){if(this.#s)d(this.#s)||(this.#s=t.querySelector(".kint-rich.kint-folder"));else{let e=t.createElement("template");e.innerHTML='<div class="kint-rich kint-folder"><dl><dt class="kint-parent"><nav></nav>Kint</dt><dd class="kint-foldout"></dd></dl></div>',this.#s=e.content.firstChild,t.body.appendChild(this.#s)}}get folder(){return d(this.#s)||(this.#s=this.#e.window.document.querySelector(".kint-rich.kint-folder")),this.#s&&m(this.#s),this.#s}isFolderOpen(){let t=this.#s?.querySelector("dd.kint-foldout");if(t)return t.previousSibling.classList.contains("kint-show")}static getChildContainer(t){let e=t.nextElementSibling;for(;e&&!e.matches("dd");)e=e.nextElementSibling;return e}static toggle(t,e){let s=n.getChildContainer(t);s&&(e=t.classList.toggle("kint-show",e),n.#n(s,e))}static switchTab(t){t.parentNode.getElementsByClassName("kint-active-tab")[0].classList.remove("kint-active-tab"),t.classList.add("kint-active-tab");let e=t,s=0;for(;e=e.previousElementSibling;)s++;let i=t.parentNode.nextSibling.children;for(let r=i.length;r--;)r===s?(i[r].classList.add("kint-show"),n.#n(i[r],!0)):i[r].classList.remove("kint-show")}static toggleChildren(t,e){let s=n.getChildContainer(t);if(!s)return;e===void 0&&(e=t.classList.contains("kint-show"));let i=Array.from(s.getElementsByClassName("kint-parent"));for(let r of i)r.classList.toggle("kint-show",e)}static toggleAccessPath(t,e){let s=t.querySelector(".access-path");s?.classList.toggle("kint-show",e)&&f(s)}static#n(t,e){if(t.children.length===2&&t.lastElementChild.matches("ul.kint-tab-contents"))for(let s of t.lastElementChild.children)s.matches("li.kint-show")&&(t=s);if(t.children.length===1&&t.firstElementChild.matches("dl")){let s=t.firstElementChild.firstElementChild;s?.classList?.contains("kint-parent")&&n.toggle(s,e)}}},b=class{#e;#t;#s;#i=null;#n=null;#o=0;constructor(t,e,s){this.#e=t,this.#t=s,this.#s=e,this.#s.addEventListener("click",this.#a.bind(this),!0)}#r(){clearTimeout(this.#i),this.#i=setTimeout(this.#l.bind(this),250)}#l(){clearTimeout(this.#i),this.#i=null,this.#n=null,this.#o=0}#c(){let t=this.#n;if(!t.matches(".kint-parent > nav"))return;let e=t.parentNode;if(this.#o===1)a.toggleChildren(e),this.#t.onTreeChanged(),this.#r(),this.#o=2;else if(this.#o===2){this.#l();let s=e.classList.contains("kint-show"),i=this.#e.folder?.querySelector(".kint-parent"),r=Array.from(this.#s.document.getElementsByClassName("kint-parent"));for(let o of r)o!==i&&o.classList.toggle("kint-show",s);this.#t.onTreeChanged(),this.#t.scrollToFocus()}}#a(t){if(this.#o){this.#c();return}let e=t.target;if(!e.closest(".kint-rich"))return;if(e.tagName==="DFN"&&f(e),e.tagName==="TH"){t.ctrlKey||g.sort(e.closest("table"),e.cellIndex);return}if(e.tagName==="LI"&&e.parentNode.className==="kint-tabs"){if(e.className!=="kint-active-tab"){let i=e.closest("dl")?.querySelector(".kint-parent > nav")??e;a.switchTab(e),this.#t.onTreeChanged(),this.#t.setCursor(i)}return}let s=e.closest("dt");if(e.tagName==="NAV")e.parentNode.tagName==="FOOTER"?(this.#t.setCursor(e),e.parentNode.classList.toggle("kint-show")):s?.classList.contains("kint-parent")&&(a.toggle(s),this.#t.onTreeChanged(),this.#t.setCursor(e),this.#r(),this.#o=1,this.#n=e);else if(e.classList.contains("kint-access-path-trigger"))s&&a.toggleAccessPath(s);else if(e.classList.contains("kint-search-trigger"))s?.matches(".kint-rich > dl > dt.kint-parent")&&u.toggleSearchBox(s);else if(e.classList.contains("kint-folder-trigger")){if(s?.matches(".kint-rich > dl > dt.kint-parent"))this.#e.addToFolder(e),this.#t.onTreeChanged(),this.#t.setCursor(s.querySelector("nav")),this.#t.scrollToFocus();else if(e.parentNode.tagName==="FOOTER"){let i=e.closest(".kint-rich").querySelector(".kint-parent > nav, .kint-rich > footer > nav");this.#e.addToFolder(e),this.#t.onTreeChanged(),this.#t.setCursor(i),this.#t.scrollToFocus()}}else e.classList.contains("kint-search")||(e.tagName==="PRE"&&t.detail===3?f(e):e.closest(".kint-source")&&t.detail===3?f(e.closest(".kint-source")):e.classList.contains("access-path")?f(e):e.tagName!=="A"&&s?.classList.contains("kint-parent")&&(a.toggle(s),this.#t.onTreeChanged(),this.#t.setCursor(s.querySelector("nav"))))}},j=65,G=68,A=70,S=72,K=74,D=75,p=76,V=83,P=9,T=13,B=27,L=32,N=37,R=38,C=39,H=40,M=".kint-rich .kint-parent > nav, .kint-rich > footer > nav, .kint-rich .kint-tabs > li:not(.kint-active-tab)",q=class{#e=[];#t=0;#s=!1;#i;#n;constructor(t,e){this.#i=t,this.#n=e.window,this.#n.addEventListener("keydown",this.#c.bind(this),!0),e.runOnInit(this.onTreeChanged.bind(this))}scrollToFocus(){let t=this.#e[this.#t];if(!t)return;let e=this.#i.folder;if(t===e?.querySelector(".kint-parent > nav"))return;let s=x(t);if(this.#i.isFolderOpen()){let i=e.querySelector("dd.kint-foldout");i.scrollTo(0,s-i.clientHeight/2)}else this.#n.scrollTo(0,s-this.#n.innerHeight/2)}onTreeChanged(){let t=this.#e[this.#t];this.#e=[];let e=this.#i.folder,s=e?.querySelector(".kint-parent > nav"),i=this.#n.document;this.#i.isFolderOpen()&&(i=e,this.#e.push(s));let r=Array.from(i.querySelectorAll(M));for(let o of r)o.offsetParent!==null&&o!==s&&this.#e.push(o);if(s&&!this.#i.isFolderOpen()&&this.#e.push(s),this.#e.length===0){this.#s=!1,this.#r();return}t&&this.#e.indexOf(t)!==-1?this.#t=this.#e.indexOf(t):this.#r()}setCursor(t){if(this.#i.isFolderOpen()&&!this.#i.folder.contains(t)||!t.matches(M))return!1;let e=this.#e.indexOf(t);if(e===-1&&(this.onTreeChanged(),e=this.#e.indexOf(t)),e!==-1){if(e!==this.#t)return this.#t=e,this.#r(),!0;this.#e[e]?.classList.remove("kint-weak-focus")}else console.error("setCursor failed to find target in list",t),console.info("Please report this as a bug in Kint at https://github.com/kint-php/kint");return!1}#o(t){if(this.#e.length===0)return this.#t=0,null;for(this.#t+=t;this.#t<0;)this.#t+=this.#e.length;for(;this.#t>=this.#e.length;)this.#t-=this.#e.length;return this.#r(),this.#t}#r(){let t=this.#n.document.querySelector(".kint-focused");t&&(t.classList.remove("kint-focused"),t.classList.remove("kint-weak-focus")),this.#s&&this.#e[this.#t]?.classList.add("kint-focused")}#l(t){let e=t.closest(".kint-rich .kint-parent ~ dd")?.parentNode.querySelector(".kint-parent > nav");e&&(this.setCursor(e),this.scrollToFocus())}#c(t){if(t.keyCode===B&&t.target.matches(".kint-search")){t.target.blur(),this.#s&&this.#r();return}if(t.target!==this.#n.document.body||t.altKey||t.ctrlKey)return;if(t.keyCode===G){if(this.#s)this.#s=!1;else{if(this.#s=!0,this.onTreeChanged(),this.#e.length===0){this.#s=!1;return}this.scrollToFocus()}this.#r(),t.preventDefault();return}else if(t.keyCode===B){this.#s&&(this.#s=!1,this.#r(),t.preventDefault());return}else if(!this.#s)return;t.preventDefault(),d(this.#e[this.#t])||this.onTreeChanged();let e=this.#e[this.#t];if([P,R,D,H,K].includes(t.keyCode)){t.keyCode===P?this.#o(t.shiftKey?-1:1):t.keyCode===R||t.keyCode===D?this.#o(-1):(t.keyCode===H||t.keyCode===K)&&this.#o(1),this.scrollToFocus();return}if(e.tagName==="LI"&&[L,T,C,p,N,S].includes(t.keyCode)){t.keyCode===L||t.keyCode===T?(a.switchTab(e),this.onTreeChanged()):t.keyCode===C||t.keyCode===p?this.#o(1):(t.keyCode===N||t.keyCode===S)&&this.#o(-1),this.scrollToFocus();return}if(e.parentNode.tagName==="FOOTER"&&e.closest(".kint-rich")){if(t.keyCode===L||t.keyCode===T)e.parentNode.classList.toggle("kint-show");else if(t.keyCode===N||t.keyCode===S)if(e.parentNode.classList.contains("kint-show"))e.parentNode.classList.remove("kint-show");else{this.#l(e.closest(".kint-rich"));return}else if(t.keyCode===C||t.keyCode===p)e.parentNode.classList.add("kint-show");else if(t.keyCode===A&&!this.#i.isFolderOpen()&&e.matches(".kint-rich > footer > nav")){let i=e.closest(".kint-rich").querySelector(".kint-parent > nav, .kint-rich > footer > nav");this.#i.addToFolder(e),this.onTreeChanged(),this.setCursor(i),this.scrollToFocus()}return}let s=e.closest(".kint-parent");if(s){if(t.keyCode===j){a.toggleAccessPath(s);return}if(t.keyCode===A){!this.#i.isFolderOpen()&&s.matches(".kint-rich:not(.kint-folder) > dl > .kint-parent")&&(this.#i.addToFolder(e),this.onTreeChanged(),this.setCursor(e),this.scrollToFocus());return}if(t.keyCode===V){let i=s.closest(".kint-rich > dl")?.querySelector(".kint-search")?.closest(".kint-parent");if(i){e.classList.add("kint-weak-focus"),u.toggleSearchBox(i,!0);return}}if(t.keyCode===L||t.keyCode===T){a.toggle(s),this.onTreeChanged();return}if([C,p,N,S].includes(t.keyCode)){let i=s.classList.contains("kint-show");if(t.keyCode===C||t.keyCode===p){i&&a.toggleChildren(s,!0),a.toggle(s,!0),this.onTreeChanged();return}else if(i){a.toggleChildren(s,!1),a.toggle(s,!1),this.onTreeChanged();return}else{this.#l(s);return}}}}};var y=class{#e;#t;constructor(t){if(!(t instanceof h))throw new Error("Invalid argument to Plain.constructor()");this.#e=t.window,t.runOnInit(this.#s.bind(this))}#s(){d(this.#t)||(this.#t=this.#e.document.querySelector("style.kint-plain-style")),this.#t&&m(this.#t)}};var w=class{#e;constructor(t){if(!(t instanceof h))throw new Error("Invalid argument to Microtime.constructor()");this.#e=t.window,t.runOnInit(this.#t.bind(this))}#t(){let t={},e=this.#e.document.querySelectorAll("[data-kint-microtime-group]");for(let s of e){let i=s.querySelector(".kint-microtime-lap");if(!i)continue;let r=s.dataset.kintMicrotimeGroup,o=parseFloat(i.textContent),l=parseFloat(s.querySelector(".kint-microtime-avg").textContent);t[r]??={min:o,max:o,avg:l},t[r].min>o&&(t[r].min=o),t[r].max<o&&(t[r].max=o),t[r].avg=l}for(let s of e){let i=s.querySelector(".kint-microtime-lap");if(!i)continue;let r=parseFloat(i.textContent),o=t[s.dataset.kintMicrotimeGroup];if(s.querySelector(".kint-microtime-avg").textContent=o.avg,!(r===o.min&&r===o.max))if(s.classList.add("kint-microtime-js"),r>o.avg){let l=(r-o.avg)/(o.max-o.avg);i.style.background="hsl("+(40-40*l)+", 100%, 65%)"}else{let l=0;o.avg!==o.min&&(l=(o.avg-r)/(o.avg-o.min)),i.style.background="hsl("+(40+80*l)+", 100%, 65%)"}}}};var U=Symbol(),h=class n{static#e=null;#t;#s=[];#i=new Set;static init(t){return n.#e??=new n(t,U),n.#e.#n(),n.#e.runOnLoad(n.#r),n.#e}get window(){return this.#t}constructor(t,e){if(U!==e)throw new Error("Kint constructor is private. Use Kint.init()");if(!(t instanceof Window))throw new Error("Invalid argument to Kint.init()");this.#t=t,this.runOnInit(this.#o.bind(this)),new y(this),new a(this),new w(this)}runOnLoad(t){if(this.#t.document.readyState==="complete")try{t()}catch{}else this.#t.addEventListener("load",t)}runOnInit(t){this.#s.push(t)}#n(){this.#t.document.currentScript&&(this.#i.add(E(window.document.currentScript)),window.document.currentScript.remove())}#o(){for(let t of this.#i.keys())for(let e of this.#t.document.querySelectorAll(t))e.remove()}static#r(){for(let t of n.#e.#s)t()}};window.Kint||(window.Kint=h);window.Kint.init(window);})();
</script><style class="kint-rich-style">.kint-rich{--spacing: 4px;--nav-size: 15px;--backdrop-color: rgba(255, 255, 255, 0.9);--main-background: #e0eaef;--secondary-background: #c1d4df;--text-color: #1d1e1e;--variable-name-color: #1d1e1e;--variable-type-color: #0092db;--variable-type-color-hover: #5cb730;--border-color: #b6cedb;--border-color-hover: #0092db;--border: 1px solid var(--border-color);--foldout-max-size: calc(100vh - 100px);--foldout-zindex: 999999;--caret-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 150'><g stroke-width='2' fill='%23FFF'><path d='M1 1h28v28H1zm5 14h18m-9 9V6M1 61h28v28H1zm5 14h18' stroke='%23379'/><path d='M1 31h28v28H1zm5 14h18m-9 9V36M1 91h28v28H1zm5 14h18' stroke='%235A3'/><path d='M1 121h28v28H1zm5 5l18 18m-18 0l18-18' stroke='%23CCC'/></g></svg>");--ap-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'><g stroke='%23000' fill='transparent'><path d='M2 8h3m3 3v3M8 2v3m3 3h3M3 8' stroke-width='2' stroke-linecap='round'/><circle stroke-width='1.5' r='4.5' cx='8' cy='8'/></g></svg>");--folder-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'><path d='M2 2h4l2 2h6v9H2V2h2' stroke-width='2' stroke='%23000' fill='transparent' stroke-linejoin='round'/></svg>");--search-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'><g stroke='%23000' fill='transparent'><path d='M2 14l3-3' stroke-linecap='round' stroke-width='3'/><circle stroke-width='2' r='5' cx='9' cy='7'/></g></svg>");font-size:13px;overflow-x:auto;white-space:nowrap;background:var(--backdrop-color);direction:ltr;contain:content}.kint-rich.kint-folder{position:fixed;bottom:0;left:0;right:0;z-index:var(--foldout-zindex);width:100%;margin:0;display:block}.kint-rich.kint-folder dd.kint-foldout{max-height:var(--foldout-max-size);padding-right:calc(var(--spacing)*2);overflow-y:scroll;display:none}.kint-rich.kint-folder dd.kint-foldout.kint-show{display:block}.kint-rich::selection{background:var(--border-color-hover);color:var(--text-color)}.kint-rich .kint-focused{box-shadow:0 0 3px 3px var(--variable-type-color-hover)}.kint-rich .kint-focused.kint-weak-focus{box-shadow:0 0 3px 1px color-mix(in srgb, var(--variable-type-color-hover) 50%, transparent)}.kint-rich,.kint-rich::before,.kint-rich::after,.kint-rich *,.kint-rich *::before,.kint-rich *::after{box-sizing:border-box;border-radius:0;color:var(--text-color);float:none !important;font-family:Consolas,Menlo,Monaco,Lucida Console,Liberation Mono,DejaVu Sans Mono,Bitstream Vera Sans Mono,Courier New,monospace,serif;line-height:15px;margin:0;padding:0;text-align:left}.kint-rich{margin:calc(var(--spacing)*2) 0}.kint-rich dt,.kint-rich dl{width:auto}.kint-rich dt,.kint-rich div.access-path{background:var(--main-background);border:var(--border);color:var(--text-color);display:block;font-weight:bold;list-style:none outside none;overflow:auto;padding:var(--spacing)}.kint-rich dt:hover,.kint-rich div.access-path:hover{border-color:var(--border-color-hover)}.kint-rich>dl dl{padding:0 0 0 calc(var(--spacing)*3)}.kint-rich dt.kint-parent>nav,.kint-rich>footer>nav{background:var(--caret-image) no-repeat scroll 0 0/var(--nav-size) 75px rgba(0,0,0,0);cursor:pointer;display:inline-block;height:var(--nav-size);width:var(--nav-size);margin-right:3px;vertical-align:middle}.kint-rich dt.kint-parent:hover>nav,.kint-rich>footer>nav:hover{background-position:0 25%}.kint-rich dt.kint-parent.kint-show>nav,.kint-rich>footer.kint-show>nav{background-position:0 50%}.kint-rich dt.kint-parent.kint-show:hover>nav,.kint-rich>footer.kint-show>nav:hover{background-position:0 75%}.kint-rich dt.kint-parent.kint-locked>nav{background-position:0 100%}.kint-rich dt.kint-parent+dd{display:none;border-left:1px dashed var(--border-color);contain:strict}.kint-rich dt.kint-parent.kint-show+dd{display:block;contain:content}.kint-rich var,.kint-rich var a{color:var(--variable-type-color);font-style:normal}.kint-rich dt:hover var,.kint-rich dt:hover var a{color:var(--variable-type-color-hover)}.kint-rich dfn{font-style:normal;font-family:monospace;color:var(--variable-name-color)}.kint-rich pre{color:var(--text-color);margin:0 0 0 calc(var(--spacing)*3);padding:5px;overflow-y:hidden;border-top:0;border:var(--border);background:var(--main-background);display:block;word-break:normal}.kint-rich .kint-access-path-trigger,.kint-rich .kint-folder-trigger,.kint-rich .kint-search-trigger{background:color-mix(in srgb, var(--text-color) 80%, transparent);border-radius:3px;padding:2px;height:var(--nav-size);width:var(--nav-size);font-size:var(--nav-size);margin-left:5px;font-weight:bold;text-align:center;line-height:1;float:right !important;cursor:pointer;position:relative;overflow:hidden}.kint-rich .kint-access-path-trigger::before,.kint-rich .kint-folder-trigger::before,.kint-rich .kint-search-trigger::before{display:block;content:"";width:100%;height:100%;background:var(--main-background);mask:center/contain no-repeat alpha}.kint-rich .kint-access-path-trigger:hover,.kint-rich .kint-folder-trigger:hover,.kint-rich .kint-search-trigger:hover{background:var(--main-background)}.kint-rich .kint-access-path-trigger:hover::before,.kint-rich .kint-folder-trigger:hover::before,.kint-rich .kint-search-trigger:hover::before{background:var(--text-color)}.kint-rich .kint-access-path-trigger::before{mask-image:var(--ap-image)}.kint-rich .kint-folder-trigger::before{mask-image:var(--folder-image)}.kint-rich .kint-search-trigger::before{mask-image:var(--search-image)}.kint-rich input.kint-search{display:none;border:var(--border);border-top-width:0;border-bottom-width:0;padding:var(--spacing);float:right !important;margin:calc(var(--spacing)*-1) 0;color:var(--variable-name-color);background:var(--secondary-background);height:calc(var(--nav-size) + var(--spacing)*2);width:calc(var(--nav-size)*10);position:relative;z-index:100}.kint-rich input.kint-search.kint-show{display:block}.kint-rich .kint-search-root ul.kint-tabs>li:not(.kint-search-match){background:var(--secondary-background);filter:saturate(0);opacity:.5}.kint-rich .kint-search-root dl:not(.kint-search-match){opacity:.5}.kint-rich .kint-search-root dl:not(.kint-search-match)>dt{background:var(--main-background);filter:saturate(0)}.kint-rich .kint-search-root dl:not(.kint-search-match) dl,.kint-rich .kint-search-root dl:not(.kint-search-match) ul.kint-tabs>li:not(.kint-search-match){opacity:1}.kint-rich div.access-path{background:var(--secondary-background);display:none;margin-top:5px;padding:4px;white-space:pre}.kint-rich div.access-path.kint-show{display:block}.kint-rich footer{padding:0 3px 3px;font-size:9px;background:rgba(0,0,0,0)}.kint-rich footer>.kint-folder-trigger{background:rgba(0,0,0,0)}.kint-rich footer>.kint-folder-trigger::before{background:var(--text-color)}.kint-rich footer nav{height:10px;width:10px;background-size:10px 50px}.kint-rich footer>ol{display:none;margin-left:32px}.kint-rich footer.kint-show>ol{display:block}.kint-rich a{color:var(--text-color);text-shadow:none;text-decoration:underline}.kint-rich a:hover{color:var(--variable-name-color);border-bottom:1px dotted var(--variable-name-color)}.kint-rich ul{list-style:none;padding-left:calc(var(--spacing)*3)}.kint-rich ul:not(.kint-tabs) li{border-left:1px dashed var(--border-color)}.kint-rich ul:not(.kint-tabs) li>dl{border-left:none}.kint-rich ul.kint-tabs{margin:0 0 0 calc(var(--spacing)*3);padding-left:0;background:var(--main-background);border:var(--border);border-top:0}.kint-rich ul.kint-tabs>li{background:var(--secondary-background);border:var(--border);cursor:pointer;display:inline-block;height:calc(var(--spacing)*6);margin:calc(var(--spacing)/2);padding:0 calc(2px + var(--spacing)*2.5);vertical-align:top}.kint-rich ul.kint-tabs>li:hover,.kint-rich ul.kint-tabs>li.kint-active-tab:hover{border-color:var(--border-color-hover);color:var(--variable-type-color-hover)}.kint-rich ul.kint-tabs>li.kint-active-tab{background:var(--main-background);border-top:0;margin-top:-1px;height:27px;line-height:24px}.kint-rich ul.kint-tabs>li:not(.kint-active-tab){line-height:calc(var(--spacing)*5)}.kint-rich ul.kint-tabs li+li{margin-left:0}.kint-rich ul.kint-tab-contents>li{display:none;contain:strict}.kint-rich ul.kint-tab-contents>li.kint-show{display:block;contain:content}.kint-rich dt:hover+dd>ul>li.kint-active-tab{border-color:var(--border-color-hover);color:var(--variable-type-color-hover)}.kint-rich dt>.kint-color-preview{width:var(--nav-size);height:var(--nav-size);display:inline-block;vertical-align:middle;margin-left:10px;border:var(--border);background-color:#ccc;background-image:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2 2"><path fill="%23FFF" d="M0 0h1v2h1V1H0z"/></svg>');background-size:min(20px,100%)}.kint-rich dt>.kint-color-preview:hover{border-color:var(--border-color-hover)}.kint-rich dt>.kint-color-preview>div{width:100%;height:100%}.kint-rich table{border-collapse:collapse;empty-cells:show;border-spacing:0}.kint-rich table *{font-size:12px}.kint-rich table dt{background:none;padding:calc(var(--spacing)/2)}.kint-rich table dt .kint-parent{min-width:100%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.kint-rich table td,.kint-rich table th{border:var(--border);padding:calc(var(--spacing)/2);vertical-align:center}.kint-rich table th{cursor:alias}.kint-rich table td:first-child,.kint-rich table th{font-weight:bold;background:var(--secondary-background);color:var(--variable-name-color)}.kint-rich table td{background:var(--main-background);white-space:pre}.kint-rich table td>dl{padding:0}.kint-rich table pre{border-top:0;border-right:0}.kint-rich table thead th:first-child{background:none;border:0}.kint-rich table tr:hover>td{box-shadow:0 0 1px 0 var(--border-color-hover) inset}.kint-rich table tr:hover var{color:var(--variable-type-color-hover)}.kint-rich table ul.kint-tabs li.kint-active-tab{height:20px;line-height:17px}.kint-rich pre.kint-source{margin-left:-1px}.kint-rich pre.kint-source[data-kint-filename]:before{display:block;content:attr(data-kint-filename);margin-bottom:var(--spacing);padding-bottom:var(--spacing);border-bottom:1px solid var(--secondary-background)}.kint-rich pre.kint-source>div:before{display:inline-block;content:counter(kint-l);counter-increment:kint-l;border-right:1px solid var(--border-color-hover);padding-right:calc(var(--spacing)*2);margin-right:calc(var(--spacing)*2)}.kint-rich pre.kint-source>div.kint-highlight{background:var(--secondary-background)}.kint-rich .kint-microtime-js .kint-microtime-lap{text-shadow:-1px 0 var(--border-color-hover),0 1px var(--border-color-hover),1px 0 var(--border-color-hover),0 -1px var(--border-color-hover);color:var(--main-background);font-weight:bold}.kint-rich{--main-background: #f8f8f8;--secondary-background: #f8f8f8;--variable-type-color: #06f;--variable-type-color-hover: #f00;--border-color: #d7d7d7;--border-color-hover: #aaa;--alternative-background: #fff;--highlight-color: #cfc;--caret-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 150'><path d='M6 7h18l-9 15zm0 30h18l-9 15zm0 45h18l-9-15zm0 30h18l-9-15zm0 12l18 18m-18 0l18-18' fill='%23555'/><path d='M6 126l18 18m-18 0l18-18' stroke-width='2' stroke='%23555'/></svg>")}.kint-rich .kint-focused{box-shadow:0 0 3px 2px var(--variable-type-color-hover)}.kint-rich dt{font-weight:normal}.kint-rich dt.kint-parent{margin-top:4px}.kint-rich dl dl{margin-top:4px;padding-left:25px;border-left:none}.kint-rich>dl>dt{background:var(--secondary-background)}.kint-rich ul{margin:0;padding-left:0}.kint-rich ul:not(.kint-tabs)>li{border-left:0}.kint-rich ul.kint-tabs{background:var(--secondary-background);border:var(--border);border-width:0 1px 1px 1px;padding:4px 0 0 12px;margin-left:-1px;margin-top:-1px}.kint-rich ul.kint-tabs li,.kint-rich ul.kint-tabs li+li{margin:0 0 0 4px}.kint-rich ul.kint-tabs li{border-bottom-width:0;height:calc(var(--spacing)*6 + 1px)}.kint-rich ul.kint-tabs li:first-child{margin-left:0}.kint-rich ul.kint-tabs li.kint-active-tab{border-top:var(--border);background:var(--alternative-background);font-weight:bold;padding-top:0;border-bottom:1px solid var(--alternative-background) !important;margin-bottom:-1px}.kint-rich ul.kint-tabs li.kint-active-tab:hover{border-bottom:1px solid var(--alternative-background)}.kint-rich ul>li>pre{border:var(--border)}.kint-rich dt:hover+dd>ul{border-color:var(--border-color-hover)}.kint-rich pre{background:var(--alternative-background);margin-top:4px;margin-left:25px}.kint-rich .kint-source{margin-left:-1px}.kint-rich .kint-source .kint-highlight{background:var(--highlight-color)}.kint-rich .kint-parent.kint-show>.kint-search{border-bottom-width:1px}.kint-rich table td{background:var(--alternative-background)}.kint-rich table td>dl{padding:0;margin:0}.kint-rich table td>dl>dt.kint-parent{margin:0}.kint-rich table td:first-child,.kint-rich table td,.kint-rich table th{padding:2px 4px}.kint-rich table dd,.kint-rich table dt{background:var(--alternative-background)}.kint-rich table tr:hover>td{box-shadow:none;background:var(--highlight-color)}
</style>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf_test_name" content="c1ffdfc441d0077942fb7433670b8c9b">
    <title>Evacuation Centers | DRMS</title>
    <link rel="icon" type="image/png" sizes="41x43" href="https://drvms.freedev.app/assets/logo/baras_seal_xs.png">
    <link rel="apple-touch-icon" sizes="185x193" href="https://drvms.freedev.app/assets/logo/baras_seal_l.png">

    <!-- AdminLTE 3 + bundled plugins (all local – InfinityFree friendly) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://drvms.freedev.app/assets/adminlte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://drvms.freedev.app/assets/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="https://drvms.freedev.app/assets/adminlte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://drvms.freedev.app/assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://drvms.freedev.app/assets/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://drvms.freedev.app/assets/adminlte/plugins/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="https://drvms.freedev.app/assets/css/drms-admin.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
</head>
<body class="hold-transition sidebar-mini layout-fixed drms-admin-theme">
<div class="wrapper">

    <!-- DEBUG-VIEW START 1 APPPATH/Views/partials/admin/navbar.php -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="https://drvms.freedev.app/" class="nav-link" target="_blank"><i class="fas fa-home mr-1"></i> Public Site</a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown" id="navNotifDropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge" id="navNotifCount" style="display:none">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="navNotifMenu">
                <span class="dropdown-item dropdown-header" id="navNotifHeader">Notifications</span>
                <div class="dropdown-divider"></div>
                <div id="navNotifItems"><span class="dropdown-item text-muted">Loading…</span></div>
            </div>
        </li>
                <li class="nav-item">
            <a class="nav-link" href="https://drvms.freedev.app/profile" title="Profile">
                <img src="https://drvms.freedev.app/assets/adminlte/dist/img/user2-160x160.jpg" alt="" class="img-circle mr-1" style="width:28px;height:28px;object-fit:cover;">
                <span class="d-none d-md-inline">admin</span>
            </a>
        </li>
        <li class="nav-item">
                            <a class="nav-link" href="https://drvms.freedev.app/logout" title="Sign out">Logout</a>
                    </li>
    </ul>
</nav>

<!-- DEBUG-VIEW ENDED 1 APPPATH/Views/partials/admin/navbar.php -->
    <!-- DEBUG-VIEW START 2 APPPATH/Views/partials/admin/sidebar.php -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="https://drvms.freedev.app/admin/dashboard" class="brand-link">
        <img src="https://drvms.freedev.app/assets/logo/baras_seal_xs.png" width="41" height="43" alt="Municipality of Baras seal" class="brand-image drms-municipal-seal elevation-2">
        <span class="brand-text font-weight-light drms-brand-erp">
            DRMS            <small class="d-block drms-brand-erp-sub">Disaster Response &amp; Volunteer Matching System</small>
        </span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="https://drvms.freedev.app/assets/adminlte/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="admin" style="width:2.1rem;height:2.1rem;object-fit:cover;">
            </div>
            <div class="info drms-user-info">
                                    <a href="https://drvms.freedev.app/profile" class="d-block">admin</a>
                    <small class="text-muted">Operations access</small>
                                                </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-header">OPERATIONS</li>
                                <li class="nav-item">
                    <a href="https://drvms.freedev.app/admin/dashboard"
                       class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                                <li class="nav-item">
                    <a href="https://drvms.freedev.app/admin/users"
                       class="nav-link ">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>User Management</p>
                    </a>
                </li>
                                <li class="nav-item">
                    <a href="https://drvms.freedev.app/admin/incidents"
                       class="nav-link ">
                        <i class="nav-icon fas fa-exclamation-triangle"></i>
                        <p>Incidents</p>
                    </a>
                </li>
                                <li class="nav-item">
                    <a href="https://drvms.freedev.app/admin/volunteers"
                       class="nav-link ">
                        <i class="nav-icon fas fa-hands-helping"></i>
                        <p>Volunteers</p>
                    </a>
                </li>
                                <li class="nav-item">
                    <a href="https://drvms.freedev.app/admin/matching"
                       class="nav-link ">
                        <i class="nav-icon fas fa-random"></i>
                        <p>Volunteer Dispatch</p>
                    </a>
                </li>
                                <li class="nav-item">
                    <a href="https://drvms.freedev.app/admin/evacuation-centers"
                       class="nav-link active">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Evacuation Centers</p>
                    </a>
                </li>
                                <li class="nav-item">
                    <a href="https://drvms.freedev.app/admin/inventory"
                       class="nav-link ">
                        <i class="nav-icon fas fa-medkit"></i>
                        <p>Emergency Supplies</p>
                    </a>
                </li>
                                <li class="nav-item">
                    <a href="https://drvms.freedev.app/admin/home-carousel"
                       class="nav-link ">
                        <i class="nav-icon fas fa-images"></i>
                        <p>Homepage Carousel</p>
                    </a>
                </li>
                                <li class="nav-item">
                    <a href="https://drvms.freedev.app/admin/reports"
                       class="nav-link ">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Reports &amp; Analytics</p>
                    </a>
                </li>
                                <li class="nav-item">
                    <a href="https://drvms.freedev.app/admin/audit-trail"
                       class="nav-link ">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Audit Trail</p>
                    </a>
                </li>
                                <li class="nav-header">PUBLIC</li>
                <li class="nav-item">
                    <a href="https://drvms.freedev.app/report" class="nav-link" target="_blank">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>Report incident</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="https://drvms.freedev.app/evac-centers" class="nav-link" target="_blank">
                        <i class="nav-icon fas fa-map-marker-alt"></i>
                        <p>Evacuation centers</p>
                    </a>
                </li>
                <li class="nav-header">SYSTEM</li>
                <li class="nav-item">
                    <a href="https://drvms.freedev.app/profile" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>My Profile</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<!-- DEBUG-VIEW ENDED 2 APPPATH/Views/partials/admin/sidebar.php -->

    <!-- DEBUG-VIEW START 3 APPPATH/Views/partials/admin/demo_banner.php -->

<!-- DEBUG-VIEW ENDED 3 APPPATH/Views/partials/admin/demo_banner.php -->

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Evacuation Centers</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="https://drvms.freedev.app/admin/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="https://drvms.freedev.app/admin/dashboard">Home</a></li>
<li class="breadcrumb-item active">Evacuation centers</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                
<div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
    <p class="text-muted mb-2 mb-md-0">Monitor shelter capacity, occupancy, and locations across Municipality of Baras.</p>
    <div>
        <a href="https://drvms.freedev.app/evac-centers" class="btn btn-sm btn-outline-secondary" target="_blank" rel="noopener">
            <i class="fas fa-external-link-alt"></i> Public page
        </a>
        <button type="button" class="btn btn-sm btn-primary drms-icon-action" id="btnAddEvac" title="Add center">
            <i class="fas fa-plus"></i> Add center
        </button>
    </div>
</div>

<div class="row" id="evacKpiRow">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3 id="evacKpiOpen">3</h3>
                <p>Open centers</p>
            </div>
            <div class="icon"><i class="fas fa-door-open"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id="evacKpiOcc">68 <small>/ 250</small></h3>
                <p>Total occupancy</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3 id="evacKpiFamilies">6</h3>
                <p>Families registered</p>
            </div>
            <div class="icon"><i class="fas fa-people-arrows"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3 id="evacKpiMedical">4</h3>
                <p>Medical needs</p>
            </div>
            <div class="icon"><i class="fas fa-ambulance"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-map mr-1"></i> Center locations</h3>
                <span class="badge badge-light ml-2">3 on map</span>
            </div>
            <div class="card-body p-0">
                <div id="drmsEvacAdminMap" class="drms-evac-admin-map"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Overall occupancy</h3></div>
            <div class="card-body text-center">
                <div class="drms-evac-gauge" style="--pct: 27">
                    <span>27%</span>
                </div>
                <p class="small text-muted mb-0 mt-2">68 of 250 slots used</p>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h3 class="card-title">Status mix</h3></div>
            <div class="card-body">
                <div class="drms-chart-wrap">
                    <canvas id="chartEvacStatus"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Occupancy by center (%)</h3></div>
            <div class="card-body">
                <div class="drms-chart-wrap drms-chart-wrap--medium">
                    <canvas id="chartEvacOcc"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row drms-evac-cards mb-3">
            <div class="col-lg-4 col-md-6">
            <div class="card drms-evac-center-card h-100 "
                 data-evac-id="1">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="mb-0">Municipal gym (demo)</h5>
                        <span class="badge badge-success">Open</span>
                    </div>
                    <div class="small text-muted mb-2">San Juan</div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span>Occupancy</span>
                        <strong>45 / 200</strong>
                    </div>
                    <div class="progress progress-sm mb-2">
                        <div class="progress-bar bg-success" style="width: 23%"></div>
                    </div>
                    <div class="small text-muted">
                        0 families ·
                        155 slots open ·
                        0 medical
                    </div>
                </div>
                <div class="card-footer py-2 bg-white border-top-0">
                    <button type="button" class="btn btn-sm btn-outline-primary btn-evac-families"
                            data-id="1"
                            data-name="Municipal&#x20;gym&#x20;&#x28;demo&#x29;">
                        <i class="fas fa-users"></i> View families
                    </button>
                </div>
            </div>
        </div>
            <div class="col-lg-4 col-md-6">
            <div class="card drms-evac-center-card h-100 "
                 data-evac-id="3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="mb-0">San Juan Evacuation</h5>
                        <span class="badge badge-success">Open</span>
                    </div>
                    <div class="small text-muted mb-2">San Juan</div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span>Occupancy</span>
                        <strong>10 / 30</strong>
                    </div>
                    <div class="progress progress-sm mb-2">
                        <div class="progress-bar bg-success" style="width: 33%"></div>
                    </div>
                    <div class="small text-muted">
                        0 families ·
                        20 slots open ·
                        0 medical
                    </div>
                </div>
                <div class="card-footer py-2 bg-white border-top-0">
                    <button type="button" class="btn btn-sm btn-outline-primary btn-evac-families"
                            data-id="3"
                            data-name="San&#x20;Juan&#x20;Evacuation">
                        <i class="fas fa-users"></i> View families
                    </button>
                </div>
            </div>
        </div>
            <div class="col-lg-4 col-md-6">
            <div class="card drms-evac-center-card h-100 "
                 data-evac-id="2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="mb-0">School BES</h5>
                        <span class="badge badge-success">Open</span>
                    </div>
                    <div class="small text-muted mb-2">San Jose</div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span>Occupancy</span>
                        <strong>13 / 20</strong>
                    </div>
                    <div class="progress progress-sm mb-2">
                        <div class="progress-bar bg-success" style="width: 65%"></div>
                    </div>
                    <div class="small text-muted">
                        6 families ·
                        7 slots open ·
                        4 medical
                    </div>
                </div>
                <div class="card-footer py-2 bg-white border-top-0">
                    <button type="button" class="btn btn-sm btn-outline-primary btn-evac-families"
                            data-id="2"
                            data-name="School&#x20;BES">
                        <i class="fas fa-users"></i> View families
                    </button>
                </div>
            </div>
        </div>
    </div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">All centers</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" id="btnRefreshEvacTable" title="Refresh table">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>
    <div class="card-body table-responsive">
        <table id="tblEvac" class="table table-bordered table-striped table-hover w-100">
            <thead>
                <tr><th>Name</th><th>Barangay</th><th>Occupancy</th><th>Status</th><th>Actions</th></tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="evacModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form id="formEvac" class="modal-content">
            <input type="hidden" name="csrf_test_name" value="c1ffdfc441d0077942fb7433670b8c9b">            <input type="hidden" id="crudRecordId" value="">
            <div class="modal-header">
                <h5 class="modal-title" id="evacModalTitle">Add evacuation center</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Fields marked <span class="text-danger">*</span> are required. Use the sections below so staff and evacuees know who to call and what families must bring.</p>

                <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3"><i class="fas fa-building mr-1"></i> Center identity</h6>
                <div class="form-group">
                    <label for="evacName">Shelter / facility name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="evacName" class="form-control" required placeholder="e.g. San Jose Elementary School — Gymnasium">
                    <small class="form-text text-muted">Official name shown on the public evacuation map and reports.</small>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="evacBarangay">Barangay</label>
                        <select name="barangay_id" id="evacBarangay" class="form-control">
                            <option value="">— Select barangay —</option>
                                                            <option value="2">San Jose</option>
                                                            <option value="1">San Juan</option>
                                                    </select>
                        <small class="form-text text-muted">Used for map pin (barangay coordinates) and filtering.</small>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="evacStatus">Shelter status</label>
                        <select name="status" id="evacStatus" class="form-control">
                            <option value="open">Open — accepting evacuees</option>
                            <option value="full">Full — no new families</option>
                            <option value="closed">Closed — not operating</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="evacAddress">Full street address</label>
                    <textarea name="address" id="evacAddress" class="form-control" rows="2" placeholder="Street, landmark, directions for drivers"></textarea>
                    <small class="form-text text-muted">Physical location of the building entrance families should use.</small>
                </div>

                <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3 mt-2"><i class="fas fa-users mr-1"></i> Capacity &amp; census <small class="font-weight-normal">(staff-maintained)</small></h6>
                <p class="small text-muted">These numbers are <strong>operational counts for this shelter</strong>, not resident phone numbers. Update them as families check in or leave.</p>
                <div class="row">
                    <div class="col-md-6 col-lg-3 form-group">
                        <label for="evacCapacity">Maximum capacity</label>
                        <input type="number" name="capacity" id="evacCapacity" class="form-control" value="0" min="0" required>
                        <small class="form-text text-muted">Total persons (beds/mats) this center can hold.</small>
                    </div>
                    <div class="col-md-6 col-lg-3 form-group">
                        <label for="evacOccupancy">Current headcount</label>
                        <input type="number" name="current_occupancy" id="evacOccupancy" class="form-control" value="0" min="0" required>
                        <small class="form-text text-muted">People physically inside the shelter now.</small>
                    </div>
                    <div class="col-md-6 col-lg-3 form-group">
                        <label for="evacFamilies">Families registered</label>
                        <input type="number" name="families_registered" id="evacFamilies" class="form-control" value="0" min="0">
                        <small class="form-text text-muted">Pre-registered families (online or at desk), including not yet checked in.</small>
                    </div>
                    <div class="col-md-6 col-lg-3 form-group">
                        <label for="evacMedical">Medical needs flagged</label>
                        <input type="number" name="medical_needs_count" id="evacMedical" class="form-control" value="0" min="0">
                        <small class="form-text text-muted">Families/members needing medical attention at this center.</small>
                    </div>
                </div>

                <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3 mt-2"><i class="fas fa-phone mr-1"></i> Center contacts <small class="font-weight-normal">(not evacuee numbers)</small></h6>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="evacContactPerson">On-duty manager / focal person</label>
                        <input type="text" name="contact_person" id="evacContactPerson" class="form-control" placeholder="e.g. Brgy. Captain, Evac Center Manager, MDRRMO duty officer">
                        <small class="form-text text-muted">Name of the staff member or official responsible for this shelter shift.</small>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="evacContactPhone">Center hotline</label>
                        <input type="text" name="contact_phone" id="evacContactPhone" class="form-control" placeholder="e.g. 0917-123-4567">
                        <small class="form-text text-muted">Phone for evacuees and responders to ask about slots, directions, or emergencies at <em>this</em> center — not a resident’s personal mobile.</small>
                    </div>
                </div>

                <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3 mt-2"><i class="fas fa-clipboard-list mr-1"></i> Family intake — procedure &amp; requirements</h6>
                <p class="small text-muted mb-2">Shown to families on the public evacuation page when they select this center. Explain the steps to be allowed inside and what documents to bring.</p>
                <div class="form-group">
                    <label for="evacIntakeProcedures">Check-in procedure for families</label>
                    <textarea name="intake_procedures" id="evacIntakeProcedures" class="form-control" rows="6" placeholder="1. Go only if status is OPEN and slots are available.&#10;2. Report to the registration desk with family head name and member count.&#10;3. Present valid government ID for the family head (and adults if requested).&#10;4. Declare medical needs, pregnancy, infants, and PWDs.&#10;5. Receive your FAM- QR token and keep it for check-in.&#10;6. Wait for staff room/bay assignment; follow center rules."></textarea>
                    <small class="form-text text-muted">Step-by-step instructions from arrival to assignment inside the shelter.</small>
                </div>
                <div class="form-group mb-0">
                    <label for="evacRequiredItems">Required documents &amp; items to bring</label>
                    <textarea name="required_items" id="evacRequiredItems" class="form-control" rows="4" placeholder="Valid ID (family head), FAM QR token after registration, clothes, hygiene kit, prescribed medicines, infant supplies, drinking water, important documents in a sealed bag."></textarea>
                    <small class="form-text text-muted">IDs, tokens, and belongings families must have to be admitted.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save center</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="evacFamiliesModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-0" id="evacFamiliesModalTitle">Registered families</h5>
                    <p class="small text-muted mb-0 mt-1" id="evacFamiliesModalMeta"></p>
                </div>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0">
                <div id="evacFamiliesLoading" class="text-center py-5 text-muted">
                    <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                    <div>Loading families…</div>
                </div>
                <div id="evacFamiliesEmpty" class="text-center py-5 text-muted d-none">
                    <i class="fas fa-users-slash fa-2x mb-2"></i>
                    <div>No families registered at this center yet.</div>
                    <p class="small mb-0">Families can register on the <a href="https://drvms.freedev.app/evac-centers" target="_blank" rel="noopener">public evacuation page</a>.</p>
                </div>
                <div class="table-responsive d-none" id="evacFamiliesTableWrap">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Family head</th>
                                <th>Members</th>
                                <th>Contact</th>
                                <th>Medical</th>
                                <th>Token</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="evacFamiliesTableBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnRefreshEvacFamilies">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="evacFamilyDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="evacFamilyDetailTitle">Family details</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="evacFamilyDetailBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning d-none" id="btnEvacFamilyCheckout">
                    <i class="fas fa-sign-out-alt"></i> Check out family
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
            </div>
        </section>
    </div>

    <!-- DEBUG-VIEW START 4 APPPATH/Views/partials/admin/footer.php -->
<footer class="main-footer">
    <strong>DRMS</strong> &middot; Disaster Response &amp; Volunteer Matching System    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0.0-dev    </div>
</footer>

<!-- DEBUG-VIEW ENDED 4 APPPATH/Views/partials/admin/footer.php -->
</div>

<!-- Scripts -->
<script src="https://drvms.freedev.app/assets/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="https://drvms.freedev.app/assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://drvms.freedev.app/assets/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="https://drvms.freedev.app/assets/adminlte/dist/js/adminlte.min.js"></script>
<script src="https://drvms.freedev.app/assets/adminlte/plugins/chart.js/Chart.min.js"></script>
<script src="https://drvms.freedev.app/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="https://drvms.freedev.app/assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://drvms.freedev.app/assets/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="https://drvms.freedev.app/assets/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="https://drvms.freedev.app/assets/adminlte/plugins/sweetalert2/sweetalert2.min.js"></script>
<script>window.DRMS_API_BASE = "https:\/\/drvms.freedev.app\/api\/v1"; window.DRMS_API_SESSION_ONLY = true;</script>
<script src="https://drvms.freedev.app/assets/js/drms-admin.js"></script>
<!-- DEBUG-VIEW START 5 APPPATH/Views/partials/admin/navbar_scripts.php -->
<script>
$(function () {
    function loadNotifs() {
        $.getJSON('https://drvms.freedev.app/profile/notifications', function (res) {
            var items = res.items || [];
            var count = res.count || 0;
            var $badge = $('#navNotifCount');
            if (count > 0) { $badge.text(count).show(); } else { $badge.hide(); }
            $('#navNotifHeader').text(count + ' unread notification(s)');
            var $box = $('#navNotifItems').empty();
            if (!items.length) {
                $box.append('<span class="dropdown-item text-muted">No new notifications</span>');
                return;
            }
            items.forEach(function (n) {
                var $a = $('<a class="dropdown-item" href="#"></a>');
                $a.attr('href', n.url || '#').html('<strong>' + (n.title || '') + '</strong><br><small>' + (n.body || '') + '</small>');
                $box.append($a).append('<div class="dropdown-divider"></div>');
            });
        });
    }
    loadNotifs();
    setInterval(loadNotifs, 45000);
});
</script>

<!-- DEBUG-VIEW ENDED 5 APPPATH/Views/partials/admin/navbar_scripts.php -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
$(function () {
    var mapCfg = {"center":{"lat":14.517099999999999226929503493010997772216796875,"lng":121.267200000000002546585164964199066162109375},"zoom":12,"bounds":{"sw":{"lat":14.495599999999999596411726088263094425201416015625,"lng":121.243300000000004956746124662458896636962890625},"ne":{"lat":14.6366999999999993775645634741522371768951416015625,"lng":121.32630000000000336513039655983448028564453125}},"maxBounds":{"sw":{"lat":14.4756000000000000227373675443232059478759765625,"lng":121.2233000000000089357854449190199375152587890625},"ne":{"lat":14.6566999999999989512389220180921256542205810546875,"lng":121.3462999999999993860910763032734394073486328125}},"boundaryUrl":"https:\/\/drvms.freedev.app\/assets\/geo\/baras-rizal.geojson","label":"Municipality of Baras, Rizal","municipality":"Municipality of Baras, Rizal"};
    var mapCenters = [{"id":1,"name":"Municipal gym (demo)","latitude":14.5162999999999993150368027272634208202362060546875,"longitude":121.26560000000000627551344223320484161376953125,"capacity":200,"occupancy":45,"available_slots":155,"is_full":false,"status":"open","marker":{"icon":"fa-home","marker_bg":"#2e7d32","border_color":"#1b5e20"}},{"id":3,"name":"San Juan Evacuation","latitude":14.5178999999999991388222042587585747241973876953125,"longitude":121.26560000000000627551344223320484161376953125,"capacity":30,"occupancy":10,"available_slots":20,"is_full":false,"status":"open","marker":{"icon":"fa-home","marker_bg":"#2e7d32","border_color":"#1b5e20"}},{"id":2,"name":"School BES","latitude":14.5220000000000002415845301584340631961822509765625,"longitude":121.2584000000000088448359747417271137237548828125,"capacity":20,"occupancy":13,"available_slots":7,"is_full":false,"status":"open","marker":{"icon":"fa-home","marker_bg":"#2e7d32","border_color":"#1b5e20"}}];

    var table = DRMS.apiDataTable('#tblEvac', '/evacuation-centers', [
        { data: 'name' },
        { data: 'barangay_name', defaultContent: '—' },
        { data: null, orderable: false, render: function (row) {
            var cap = parseInt(row.capacity, 10) || 0;
            var occ = parseInt(row.current_occupancy, 10) || 0;
            var pct = cap > 0 ? Math.round((occ / cap) * 100) : 0;
            var bar = pct >= 90 ? 'bg-danger' : (pct >= 70 ? 'bg-warning' : 'bg-success');
            return '<div class="small">' + occ + ' / ' + cap + ' (' + pct + '%)</div>'
                + '<div class="progress progress-xs"><div class="progress-bar ' + bar + '" style="width:' + Math.min(100, pct) + '%"></div></div>';
        }},
        { data: 'status', render: function (s) {
            var c = { open: 'success', full: 'danger', closed: 'secondary' };
            return '<span class="badge badge-' + (c[s] || 'light') + '">' + (s || '') + '</span>';
        }},
        { data: null, orderable: false, searchable: false, render: function (row) {
            return '<div class="btn-group btn-group-xs">'
                + '<button type="button" class="btn btn-primary btn-evac-families" data-id="' + row.id + '" data-name="' + escHtml(row.name || '') + '" title="Families"><i class="fas fa-users"></i></button>'
                + '<button type="button" class="btn btn-info btn-api-edit" data-id="' + row.id + '" title="Edit"><i class="fas fa-edit"></i></button>'
                + '<button type="button" class="btn btn-danger btn-api-delete" data-id="' + row.id + '" title="Delete"><i class="fas fa-trash"></i></button>'
                + '</div>';
        }}
    ], { order: [[0, 'asc']] });

    var activeFamiliesCenterId = 0;
    var activeFamiliesCenterName = '';
    var cachedFamilies = [];

    function escHtml(s) {
        return $('<div>').text(s == null ? '' : String(s)).html();
    }

    function familyStatusBadge(f) {
        if (f.check_in_status === 'checked_out') {
            return '<span class="badge badge-secondary">Checked out</span>';
        }
        if (f.check_in_status === 'checked_in') {
            return '<span class="badge badge-success">Checked in</span>';
        }
        return '<span class="badge badge-warning">Not checked in</span>';
    }

    function familiesMetaText(meta) {
        meta = meta || {};
        return (meta.count || 0) + ' total · '
            + (meta.checked_in || 0) + ' inside · '
            + (meta.pending || 0) + ' pending · '
            + (meta.checked_out || 0) + ' checked out';
    }

    function checkoutFamily(familyId, familyName) {
        if (!activeFamiliesCenterId) {
            return;
        }
        var label = familyName ? ('Check out ' + familyName + '?') : 'Check out this family?';
        DRMS.confirm(label, 'This frees their slots at the center and marks them as checked out.', function () {
            DRMS.apiPost('/evacuation-centers/' + activeFamiliesCenterId + '/families/' + familyId + '/checkout', {})
                .then(function (res) {
                    DRMS.toast('Checked out', res.message || 'Family checked out.', res.status === 'already' ? 'warning' : 'success');
                    $('#evacFamilyDetailModal').modal('hide');
                    loadFamiliesModal(activeFamiliesCenterId, activeFamiliesCenterName);
                    if (table) {
                        table.ajax.reload(null, false);
                    }
                }).catch(function (err) {
                    DRMS.toast('Error', err.message, 'error');
                });
        });
    }

    function renderFamilyDetail(f) {
        var medical = (f.medical_needs || '').trim();
        var html = '<dl class="row mb-0">';
        html += '<dt class="col-sm-4">Family head</dt><dd class="col-sm-8"><strong>' + escHtml(f.family_head_name) + '</strong></dd>';
        html += '<dt class="col-sm-4">Members</dt><dd class="col-sm-8">' + escHtml(f.members_count) + '</dd>';
        html += '<dt class="col-sm-4">Contact phone</dt><dd class="col-sm-8">' + (f.contact_phone ? escHtml(f.contact_phone) : '<span class="text-muted">—</span>') + '</dd>';
        html += '<dt class="col-sm-4">Medical needs</dt><dd class="col-sm-8">' + (medical ? '<span class="text-danger">' + escHtml(medical) + '</span>' : '<span class="text-muted">None declared</span>') + '</dd>';
        html += '<dt class="col-sm-4">QR token</dt><dd class="col-sm-8"><code>' + escHtml(f.family_qr_token) + '</code></dd>';
        html += '<dt class="col-sm-4">Registered</dt><dd class="col-sm-8">' + escHtml(f.registered_at_display) + '</dd>';
        html += '<dt class="col-sm-4">Checked in</dt><dd class="col-sm-8">' + escHtml(f.checked_in_at_display) + '</dd>';
        html += '<dt class="col-sm-4">Checked out</dt><dd class="col-sm-8">' + escHtml(f.checked_out_at_display) + '</dd>';
        html += '<dt class="col-sm-4">Status</dt><dd class="col-sm-8">' + familyStatusBadge(f) + '</dd>';
        html += '<dt class="col-sm-4">Evacuation center</dt><dd class="col-sm-8">' + escHtml(f.center_name) + '</dd>';
        html += '<dt class="col-sm-4">Barangay</dt><dd class="col-sm-8">' + escHtml(f.center_barangay || '—') + '</dd>';
        html += '<dt class="col-sm-4">Center address</dt><dd class="col-sm-8">' + escHtml(f.center_address || '—') + '</dd>';
        html += '<dt class="col-sm-4">Center hotline</dt><dd class="col-sm-8">' + escHtml(f.center_contact || '—') + '</dd>';
        html += '</dl>';
        return html;
    }

    function showFamilyDetail(familyId) {
        var f = cachedFamilies.find(function (row) { return Number(row.id) === Number(familyId); });
        if (!f) {
            return;
        }
        $('#evacFamilyDetailTitle').text('Family — ' + (f.family_head_name || 'Details'));
        $('#evacFamilyDetailBody').html(renderFamilyDetail(f));
        if (f.check_in_status === 'checked_out') {
            $('#btnEvacFamilyCheckout').addClass('d-none').removeData('family-id');
        } else {
            $('#btnEvacFamilyCheckout').removeClass('d-none').data('family-id', f.id).data('family-name', f.family_head_name || '');
        }
        $('#evacFamilyDetailModal').modal('show');
    }

    function renderFamiliesTable(families) {
        var $body = $('#evacFamiliesTableBody');
        $body.empty();
        families.forEach(function (f) {
            var medical = (f.medical_needs || '').trim();
            var medicalCell = medical
                ? '<span class="text-danger small" title="' + escHtml(medical) + '"><i class="fas fa-notes-medical"></i> Yes</span>'
                : '<span class="text-muted">—</span>';
            var actions = '<button type="button" class="btn btn-xs btn-outline-info btn-evac-family-detail" data-family-id="' + escHtml(f.id) + '">Details</button>';
            if (f.check_in_status !== 'checked_out') {
                actions += ' <button type="button" class="btn btn-xs btn-outline-warning btn-evac-family-checkout" data-family-id="' + escHtml(f.id) + '" data-family-name="' + escHtml(f.family_head_name) + '">Check out</button>';
            }
            var row = '<tr' + (f.check_in_status === 'checked_out' ? ' class="text-muted"' : '') + '>'
                + '<td><strong>' + escHtml(f.family_head_name) + '</strong></td>'
                + '<td>' + escHtml(f.members_count) + '</td>'
                + '<td>' + (f.contact_phone ? escHtml(f.contact_phone) : '<span class="text-muted">—</span>') + '</td>'
                + '<td>' + medicalCell + '</td>'
                + '<td><code class="small">' + escHtml(f.family_qr_token) + '</code></td>'
                + '<td>' + familyStatusBadge(f) + '</td>'
                + '<td class="text-nowrap">' + actions + '</td>'
                + '</tr>';
            $body.append(row);
        });
    }

    function loadFamiliesModal(centerId, centerName) {
        activeFamiliesCenterId = centerId;
        activeFamiliesCenterName = centerName || 'Center';
        $('#evacFamiliesModalTitle').text('Families — ' + activeFamiliesCenterName);
        $('#evacFamiliesModalMeta').text('');
        $('#evacFamiliesLoading').removeClass('d-none');
        $('#evacFamiliesEmpty').addClass('d-none');
        $('#evacFamiliesTableWrap').addClass('d-none');
        $('#evacFamiliesModal').modal('show');

        DRMS.apiGet('/evacuation-centers/' + centerId + '/families')
            .then(function (res) {
                $('#evacFamiliesLoading').addClass('d-none');
                var payload = DRMS.apiUnwrap(res);
                cachedFamilies = payload.families || [];
                var meta = payload.meta || {};
                var center = payload.center || {};
                $('#evacFamiliesModalTitle').text('Families — ' + (center.name || activeFamiliesCenterName));
                $('#evacFamiliesModalMeta').text(familiesMetaText(meta));
                if (!cachedFamilies.length) {
                    $('#evacFamiliesEmpty').removeClass('d-none');
                    return;
                }
                renderFamiliesTable(cachedFamilies);
                $('#evacFamiliesTableWrap').removeClass('d-none');
            })
            .catch(function (err) {
                $('#evacFamiliesLoading').addClass('d-none');
                $('#evacFamiliesEmpty').removeClass('d-none').find('div').first().text(err.message || 'Could not load families.');
            });
    }

    $(document).on('click', '.btn-evac-families', function () {
        loadFamiliesModal($(this).data('id'), $(this).data('name') || '');
    });

    $(document).on('click', '.btn-evac-family-detail', function () {
        showFamilyDetail($(this).data('family-id'));
    });

    $(document).on('click', '.btn-evac-family-checkout', function () {
        checkoutFamily($(this).data('family-id'), $(this).data('family-name') || '');
    });

    $('#btnEvacFamilyCheckout').on('click', function () {
        checkoutFamily($(this).data('family-id'), $(this).data('family-name') || '');
    });

    $('#btnRefreshEvacFamilies').on('click', function () {
        if (activeFamiliesCenterId) {
            loadFamiliesModal(activeFamiliesCenterId, activeFamiliesCenterName);
        }
    });

    DRMS.bindApiCrud({
        resource: '/evacuation-centers',
        tableSelector: '#tblEvac',
        dataTable: table,
        form: '#formEvac',
        modal: '#evacModal',
        addBtn: '#btnAddEvac',
        idField: '#crudRecordId',
        onSaved: function (res, recordId, finish) {
            finish();
            setTimeout(function () { window.location.reload(); }, 600);
        },
        onReset: function () { $('#evacModalTitle').text('Add evacuation center'); },
        onLoad: function (d) {
            $('#evacModalTitle').text('Edit evacuation center');
            $('input[name=name]').val(d.name || '');
            $('select[name=barangay_id]').val(d.barangay_id || '');
            $('textarea[name=address]').val(d.address || '');
            $('input[name=capacity]').val(d.capacity || 0);
            $('input[name=current_occupancy]').val(d.current_occupancy || 0);
            $('input[name=families_registered]').val(d.families_registered || 0);
            $('input[name=medical_needs_count]').val(d.medical_needs_count || 0);
            $('select[name=status]').val(d.status || 'open');
            $('input[name=contact_person]').val(d.contact_person || '');
            $('input[name=contact_phone]').val(d.contact_phone || '');
            $('textarea[name=intake_procedures]').val(d.intake_procedures || '');
            $('textarea[name=required_items]').val(d.required_items || '');
        }
    });

    $('#btnRefreshEvacTable').on('click', function () {
        table.ajax.reload(null, false);
    });

    if (typeof Chart !== 'undefined') {
        new Chart(document.getElementById('chartEvacStatus').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ["Open","Full","Closed"],
                datasets: [{ data: [3,0,0], backgroundColor: ['#28a745', '#dc3545', '#6c757d'] }]
            },
            options: { responsive: true, maintainAspectRatio: false, legend: { position: 'bottom' } }
        });

        new Chart(document.getElementById('chartEvacOcc').getContext('2d'), {
            type: 'horizontalBar',
            data: {
                labels: ["Municipal gym (dem","San Juan Evacuatio","School BES"],
                datasets: [{ data: [23,33,65], backgroundColor: '#1565c0' }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { display: false },
                scales: { xAxes: [{ ticks: { beginAtZero: true, max: 100 } }] }
            }
        });
    }

    if (typeof L !== 'undefined' && document.getElementById('drmsEvacAdminMap')) {
        var map = L.map('drmsEvacAdminMap', { minZoom: 11, maxZoom: 17 }).setView(
            [mapCfg.center.lat, mapCfg.center.lng], mapCfg.zoom || 13
        );
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18, attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var bounds = [];
        var markers = {};
        mapCenters.forEach(function (c) {
            var m = c.marker || {};
            var icon = L.divIcon({
                className: 'drms-evac-marker-wrap',
                html: '<div class="drms-evac-marker" style="background:' + (m.marker_bg || '#2e7d32') + ';border-color:' + (m.border_color || '#1b5e20') + '"><i class="fas ' + (m.icon || 'fa-home') + '"></i></div>',
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });
            var mk = L.marker([c.latitude, c.longitude], { icon: icon })
                .bindPopup('<strong>' + c.name + '</strong><br>' + c.occupancy + ' / ' + c.capacity + '<br>' + c.available_slots + ' slots open')
                .addTo(map);
            markers[c.id] = mk;
            bounds.push([c.latitude, c.longitude]);
        });

        if (bounds.length > 1) {
            map.fitBounds(bounds, { padding: [30, 30], maxZoom: 14 });
        } else if (bounds.length === 1) {
            map.setView(bounds[0], 14);
        }

        $('.drms-evac-center-card').on('click', function () {
            var id = parseInt($(this).data('evac-id'), 10);
            if (markers[id]) {
                map.setView(markers[id].getLatLng(), Math.max(map.getZoom(), 14));
                markers[id].openPopup();
            }
        });

        setTimeout(function () { map.invalidateSize(); }, 300);
    }

    setInterval(function () {
        DRMS.apiGet('/evacuation-centers/summary').then(function (res) {
            var s = DRMS.apiUnwrap(res);
            if (!s) return;
            $('#evacKpiOpen').text(s.open_count || 0);
            $('#evacKpiOcc').html((s.total_occupancy || 0) + ' <small>/ ' + (s.total_capacity || 0) + '</small>');
            $('#evacKpiFamilies').text(s.families_total || 0);
            $('#evacKpiMedical').text(s.medical_needs || 0);
        }).catch(function () {});
    }, 45000);
});
</script>
</body>
</html>

<!-- DEBUG-VIEW ENDED 6 APPPATH/Views/layouts/admin.php -->

<!-- DEBUG-VIEW ENDED 7 APPPATH/Views/admin/evacuation_centers/index.php -->
