!function(t){function e(r){if(n[r])return n[r].exports;var o=n[r]={i:r,l:!1,exports:{}};return t[r].call(o.exports,o,o.exports,e),o.l=!0,o.exports}var n={};e.m=t,e.c=n,e.d=function(t,n,r){e.o(t,n)||Object.defineProperty(t,n,{configurable:!1,enumerable:!0,get:r})},e.n=function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(n,"a",n),n},e.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},e.p="",e(e.s=11)}([function(t,e){var n=t.exports="undefined"!=typeof window&&window.Math==Math?window:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")();"number"==typeof __g&&(__g=n)},function(t,e){var n=t.exports={version:"2.5.6"};"number"==typeof __e&&(__e=n)},function(t,e){t.exports=function(t){return"object"==typeof t?null!==t:"function"==typeof t}},function(t,e,n){t.exports=!n(6)(function(){return 7!=Object.defineProperty({},"a",{get:function(){return 7}}).a})},function(t,e,n){var r=n(27),o=n(29);t.exports=function(t){return r(o(t))}},function(t,e,n){var r=n(15),o=n(20);t.exports=n(3)?function(t,e,n){return r.f(t,e,o(1,n))}:function(t,e,n){return t[e]=n,t}},function(t,e){t.exports=function(t){try{return!!t()}catch(t){return!0}}},function(t,e){var n={}.hasOwnProperty;t.exports=function(t,e){return n.call(t,e)}},function(t,e){var n=0,r=Math.random();t.exports=function(t){return"Symbol(".concat(void 0===t?"":t,")_",(++n+r).toString(36))}},function(t,e){var n=Math.ceil,r=Math.floor;t.exports=function(t){return isNaN(t=+t)?0:(t>0?r:n)(t)}},function(t,e){t.exports=React},function(t,e,n){n(12),t.exports=n(38)},function(t,e,n){n(13),t.exports=n(1).Object.entries},function(t,e,n){var r=n(14),o=n(24)(!0);r(r.S,"Object",{entries:function(t){return o(t)}})},function(t,e,n){var r=n(0),o=n(1),a=n(5),i=n(21),u=n(22),s=function(t,e,n){var c,l,f,p,d=t&s.F,m=t&s.G,y=t&s.S,h=t&s.P,v=t&s.B,g=m?r:y?r[e]||(r[e]={}):(r[e]||{}).prototype,b=m?o:o[e]||(o[e]={}),S=b.prototype||(b.prototype={});m&&(n=e);for(c in n)l=!d&&g&&void 0!==g[c],f=(l?g:n)[c],p=v&&l?u(f,r):h&&"function"==typeof f?u(Function.call,f):f,g&&i(g,c,f,t&s.U),b[c]!=f&&a(b,c,p),h&&S[c]!=f&&(S[c]=f)};r.core=o,s.F=1,s.G=2,s.S=4,s.P=8,s.B=16,s.W=32,s.U=64,s.R=128,t.exports=s},function(t,e,n){var r=n(16),o=n(17),a=n(19),i=Object.defineProperty;e.f=n(3)?Object.defineProperty:function(t,e,n){if(r(t),e=a(e,!0),r(n),o)try{return i(t,e,n)}catch(t){}if("get"in n||"set"in n)throw TypeError("Accessors not supported!");return"value"in n&&(t[e]=n.value),t}},function(t,e,n){var r=n(2);t.exports=function(t){if(!r(t))throw TypeError(t+" is not an object!");return t}},function(t,e,n){t.exports=!n(3)&&!n(6)(function(){return 7!=Object.defineProperty(n(18)("div"),"a",{get:function(){return 7}}).a})},function(t,e,n){var r=n(2),o=n(0).document,a=r(o)&&r(o.createElement);t.exports=function(t){return a?o.createElement(t):{}}},function(t,e,n){var r=n(2);t.exports=function(t,e){if(!r(t))return t;var n,o;if(e&&"function"==typeof(n=t.toString)&&!r(o=n.call(t)))return o;if("function"==typeof(n=t.valueOf)&&!r(o=n.call(t)))return o;if(!e&&"function"==typeof(n=t.toString)&&!r(o=n.call(t)))return o;throw TypeError("Can't convert object to primitive value")}},function(t,e){t.exports=function(t,e){return{enumerable:!(1&t),configurable:!(2&t),writable:!(4&t),value:e}}},function(t,e,n){var r=n(0),o=n(5),a=n(7),i=n(8)("src"),u=Function.toString,s=(""+u).split("toString");n(1).inspectSource=function(t){return u.call(t)},(t.exports=function(t,e,n,u){var c="function"==typeof n;c&&(a(n,"name")||o(n,"name",e)),t[e]!==n&&(c&&(a(n,i)||o(n,i,t[e]?""+t[e]:s.join(String(e)))),t===r?t[e]=n:u?t[e]?t[e]=n:o(t,e,n):(delete t[e],o(t,e,n)))})(Function.prototype,"toString",function(){return"function"==typeof this&&this[i]||u.call(this)})},function(t,e,n){var r=n(23);t.exports=function(t,e,n){if(r(t),void 0===e)return t;switch(n){case 1:return function(n){return t.call(e,n)};case 2:return function(n,r){return t.call(e,n,r)};case 3:return function(n,r,o){return t.call(e,n,r,o)}}return function(){return t.apply(e,arguments)}}},function(t,e){t.exports=function(t){if("function"!=typeof t)throw TypeError(t+" is not a function!");return t}},function(t,e,n){var r=n(25),o=n(4),a=n(37).f;t.exports=function(t){return function(e){for(var n,i=o(e),u=r(i),s=u.length,c=0,l=[];s>c;)a.call(i,n=u[c++])&&l.push(t?[n,i[n]]:i[n]);return l}}},function(t,e,n){var r=n(26),o=n(36);t.exports=Object.keys||function(t){return r(t,o)}},function(t,e,n){var r=n(7),o=n(4),a=n(30)(!1),i=n(33)("IE_PROTO");t.exports=function(t,e){var n,u=o(t),s=0,c=[];for(n in u)n!=i&&r(u,n)&&c.push(n);for(;e.length>s;)r(u,n=e[s++])&&(~a(c,n)||c.push(n));return c}},function(t,e,n){var r=n(28);t.exports=Object("z").propertyIsEnumerable(0)?Object:function(t){return"String"==r(t)?t.split(""):Object(t)}},function(t,e){var n={}.toString;t.exports=function(t){return n.call(t).slice(8,-1)}},function(t,e){t.exports=function(t){if(void 0==t)throw TypeError("Can't call method on  "+t);return t}},function(t,e,n){var r=n(4),o=n(31),a=n(32);t.exports=function(t){return function(e,n,i){var u,s=r(e),c=o(s.length),l=a(i,c);if(t&&n!=n){for(;c>l;)if((u=s[l++])!=u)return!0}else for(;c>l;l++)if((t||l in s)&&s[l]===n)return t||l||0;return!t&&-1}}},function(t,e,n){var r=n(9),o=Math.min;t.exports=function(t){return t>0?o(r(t),9007199254740991):0}},function(t,e,n){var r=n(9),o=Math.max,a=Math.min;t.exports=function(t,e){return t=r(t),t<0?o(t+e,0):a(t,e)}},function(t,e,n){var r=n(34)("keys"),o=n(8);t.exports=function(t){return r[t]||(r[t]=o(t))}},function(t,e,n){var r=n(1),o=n(0),a=o["__core-js_shared__"]||(o["__core-js_shared__"]={});(t.exports=function(t,e){return a[t]||(a[t]=void 0!==e?e:{})})("versions",[]).push({version:r.version,mode:n(35)?"pure":"global",copyright:"© 2018 Denis Pushkarev (zloirock.ru)"})},function(t,e){t.exports=!1},function(t,e){t.exports="constructor,hasOwnProperty,isPrototypeOf,propertyIsEnumerable,toLocaleString,toString,valueOf".split(",")},function(t,e){e.f={}.propertyIsEnumerable},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});for(var r=n(10),o=(n.n(r),n(39)),a=(n.n(o),n(40)),i=document.getElementsByTagName("TransitionSet"),u=0;u<i.length;u++){var s=i[u],c=s.querySelector('input[ type="hidden" ]'),l=Object(r.createElement)(a.a,{states:JSON.parse(s.getAttribute("states")),transitions:JSON.parse(c.value),input:c.name,step:s.getAttribute("step")||60});Object(o.render)(l,s)}},function(t,e){t.exports=ReactDOM},function(t,e,n){"use strict";function r(t){if(Array.isArray(t)){for(var e=0,n=Array(t.length);e<t.length;e++)n[e]=t[e];return n}return Array.from(t)}function o(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}function a(t,e){if(!t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!e||"object"!=typeof e&&"function"!=typeof e?t:e}function i(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function, not "+typeof e);t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,enumerable:!1,writable:!0,configurable:!0}}),e&&(Object.setPrototypeOf?Object.setPrototypeOf(t,e):t.__proto__=e)}var u=n(10),s=(n.n(u),n(41)),c=n.n(s),l=function(){function t(t,e){var n=[],r=!0,o=!1,a=void 0;try{for(var i,u=t[Symbol.iterator]();!(r=(i=u.next()).done)&&(n.push(i.value),!e||n.length!==e);r=!0);}catch(t){o=!0,a=t}finally{try{!r&&u.return&&u.return()}finally{if(o)throw a}}return n}return function(e,n){if(Array.isArray(e))return e;if(Symbol.iterator in Object(e))return t(e,n);throw new TypeError("Invalid attempt to destructure non-iterable instance")}}(),f=function(){function t(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}return function(e,n,r){return n&&t(e.prototype,n),r&&t(e,r),e}}(),p=function(t){function e(t){o(this,e);var n=a(this,(e.__proto__||Object.getPrototypeOf(e)).call(this,t)),r=t.transitions||[],i=Object.entries(t.states);return n.state={transitions:r.map(function(t){return t.when=new Date(t.when),t})},n.defaultState=i[0][0],n.stateOptions=i.map(function(t){var e=l(t,2),n=e[0],r=e[1];return React.createElement("option",{key:n,value:n},r)}),n}return i(e,t),f(e,[{key:"renderSaved",value:function(t,e){var n=this,r=this.props.states[t.state],o=c()(t.when,"longDate"),a=c()(t.when,"shortTime"),i=function(t){t.preventDefault(),n.setState(function(t){return t.transitions.splice(e,1),t})},u=Drupal.t("Remove transition to @state on @date at @time",{"@state":r,"@date":o,"@time":a}),s=["scheduled-transition"];return React.createElement("div",{className:s.join(" ")},Drupal.t("Change to")," ",React.createElement("b",null,r)," ",Drupal.t("on")," ",o," ",Drupal.t("at")," ",a," ",React.createElement("a",{title:u,href:"#",onClick:i},Drupal.t("remove")))}},{key:"renderForm",value:function(){return Object(u.createElement)("div",{className:"scheduled-transition"},Drupal.t("Change to"),this.renderStateControl(),Drupal.t("on"),this.renderDateControl(),Drupal.t("at"),this.renderTimeControl(),this.renderFormActions())}},{key:"renderStateControl",value:function(){var t=this,e=function(e){var n=e.target;t.setState(function(t){return t.edit.state=n.options[n.selectedIndex].value,t})},n=this.state.edit.state;return React.createElement("label",null,React.createElement("span",{hidden:!0},Drupal.t("Scheduled moderation state")),React.createElement("select",{defaultValue:n,onChange:e},this.stateOptions))}},{key:"renderDateControl",value:function(){var t=this,e=function(e){var n=e.target;n.checkValidity()&&t.setState(function(t){var e,o=n.value.split("-");return o[1]--,(e=t.edit.when).setFullYear.apply(e,r(o)),t})},n=c()(this.state.edit.when,"isoDate");return React.createElement("label",null,React.createElement("span",{hidden:!0},Drupal.t("Scheduled transition date")),React.createElement("input",{required:!0,defaultValue:n,type:"date",onChange:e}))}},{key:"renderTimeControl",value:function(){var t=this,e=function(e){var n=e.target;t.setState(function(t){var e;return(e=t.edit.when).setHours.apply(e,r(n.value.split(":"))),t})},n=void 0;n=this.props.step>=3600?"HH:00":this.props.step>=60?"HH:MM":"isoTime";var o=c()(this.state.edit.when,n);return React.createElement("label",null,React.createElement("span",{hidden:!0},Drupal.t("Scheduled transition time")),React.createElement("input",{required:!0,defaultValue:o,type:"time",onChange:e,step:this.props.step}))}},{key:"renderFormActions",value:function(){var t=this,e=function(e){e.preventDefault(),t.setState(function(t){return t.edit=null,t})},n=function(e){e.preventDefault(),t.setState(function(t){return t.transitions.push(t.edit),t.edit=null,t})};return React.createElement("span",null,React.createElement("button",{className:"button",title:Drupal.t("Save transition"),onClick:n},Drupal.t("Save"))," ",Drupal.t("or")," ",React.createElement("a",{title:Drupal.t("Cancel transition"),href:"#",onClick:e},Drupal.t("cancel")))}},{key:"render",value:function(){var t=this,e=[Object(u.createElement)("input",{type:"hidden",name:this.props.input,value:JSON.stringify(this)}),this.state.transitions.map(function(e,n){return t.renderSaved(e,n)})];return e.push(this.state.edit?this.renderForm():this.renderAddLink()),e}},{key:"toJSON",value:function(){return this.state.transitions.map(function(t){return{when:Math.floor(t.when.getTime()/1e3),state:t.state}})}},{key:"renderAddLink",value:function(){var t=this,e=function(e){e.preventDefault(),t.add()},n=this.state.transitions.length?Drupal.t("add another"):Drupal.t("Schedule a status change");return React.createElement("a",{href:"#",onClick:e},n)}},{key:"add",value:function(){var t=this;this.setState(function(e){e.edit={state:t.defaultState,when:new Date};var n=e.transitions.slice(-1).shift();return n&&e.edit.when.setTime(n.when.getTime()),e})}}]),e}(u.Component);e.a=p},function(t,e,n){var r;!function(o){"use strict";function a(t,e){for(t=String(t),e=e||2;t.length<e;)t="0"+t;return t}function i(t){var e=new Date(t.getFullYear(),t.getMonth(),t.getDate());e.setDate(e.getDate()-(e.getDay()+6)%7+3);var n=new Date(e.getFullYear(),0,4);n.setDate(n.getDate()-(n.getDay()+6)%7+3);var r=e.getTimezoneOffset()-n.getTimezoneOffset();e.setHours(e.getHours()-r);var o=(e-n)/6048e5;return 1+Math.floor(o)}function u(t){var e=t.getDay();return 0===e&&(e=7),e}function s(t){return null===t?"null":void 0===t?"undefined":"object"!=typeof t?typeof t:Array.isArray(t)?"array":{}.toString.call(t).slice(8,-1).toLowerCase()}var c=function(){var t=/d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZWN]|"[^"]*"|'[^']*'/g,e=/\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,n=/[^-+\dA-Z]/g;return function(r,o,l,f){if(1!==arguments.length||"string"!==s(r)||/\d/.test(r)||(o=r,r=void 0),r=r||new Date,r instanceof Date||(r=new Date(r)),isNaN(r))throw TypeError("Invalid date");o=String(c.masks[o]||o||c.masks.default);var p=o.slice(0,4);"UTC:"!==p&&"GMT:"!==p||(o=o.slice(4),l=!0,"GMT:"===p&&(f=!0));var d=l?"getUTC":"get",m=r[d+"Date"](),y=r[d+"Day"](),h=r[d+"Month"](),v=r[d+"FullYear"](),g=r[d+"Hours"](),b=r[d+"Minutes"](),S=r[d+"Seconds"](),M=r[d+"Milliseconds"](),D=l?0:r.getTimezoneOffset(),T=i(r),w=u(r),x={d:m,dd:a(m),ddd:c.i18n.dayNames[y],dddd:c.i18n.dayNames[y+7],m:h+1,mm:a(h+1),mmm:c.i18n.monthNames[h],mmmm:c.i18n.monthNames[h+12],yy:String(v).slice(2),yyyy:v,h:g%12||12,hh:a(g%12||12),H:g,HH:a(g),M:b,MM:a(b),s:S,ss:a(S),l:a(M,3),L:a(Math.round(M/10)),t:g<12?c.i18n.timeNames[0]:c.i18n.timeNames[1],tt:g<12?c.i18n.timeNames[2]:c.i18n.timeNames[3],T:g<12?c.i18n.timeNames[4]:c.i18n.timeNames[5],TT:g<12?c.i18n.timeNames[6]:c.i18n.timeNames[7],Z:f?"GMT":l?"UTC":(String(r).match(e)||[""]).pop().replace(n,""),o:(D>0?"-":"+")+a(100*Math.floor(Math.abs(D)/60)+Math.abs(D)%60,4),S:["th","st","nd","rd"][m%10>3?0:(m%100-m%10!=10)*m%10],W:T,N:w};return o.replace(t,function(t){return t in x?x[t]:t.slice(1,t.length-1)})}}();c.masks={default:"ddd mmm dd yyyy HH:MM:ss",shortDate:"m/d/yy",mediumDate:"mmm d, yyyy",longDate:"mmmm d, yyyy",fullDate:"dddd, mmmm d, yyyy",shortTime:"h:MM TT",mediumTime:"h:MM:ss TT",longTime:"h:MM:ss TT Z",isoDate:"yyyy-mm-dd",isoTime:"HH:MM:ss",isoDateTime:"yyyy-mm-dd'T'HH:MM:sso",isoUtcDateTime:"UTC:yyyy-mm-dd'T'HH:MM:ss'Z'",expiresHeaderFormat:"ddd, dd mmm yyyy HH:MM:ss Z"},c.i18n={dayNames:["Sun","Mon","Tue","Wed","Thu","Fri","Sat","Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],monthNames:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec","January","February","March","April","May","June","July","August","September","October","November","December"],timeNames:["a","p","am","pm","A","P","AM","PM"]},void 0!==(r=function(){return c}.call(e,n,e,t))&&(t.exports=r)}()}]);
//# sourceMappingURL=index.js.map