(this.webpackJsonpmesl=this.webpackJsonpmesl||[]).push([[76],{1343:function(e,a,t){"use strict";t.r(a);var r=t(698),l=t(3),n=t.n(l),c=t(701),o=t.n(c),s=t(697),m=function(e){if("undefined"===typeof e)throw new TypeError("Hex color is not defined");if("transparent"===e)return"#00000000";var a=e.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);if(!a)throw new Error("".concat(e," is not a valid rgb color"));var t="0".concat(parseInt(a[1],10).toString(16)),r="0".concat(parseInt(a[2],10).toString(16)),l="0".concat(parseInt(a[3],10).toString(16));return"#".concat(t.slice(-2)).concat(r.slice(-2)).concat(l.slice(-2))},u=function(){var e=Object(l.useState)("rgb(255, 255, 255)"),a=Object(r.a)(e,2),t=a[0],c=a[1],o=Object(l.createRef)();return Object(l.useEffect)((function(){var e=o.current.parentNode.firstChild,a=window.getComputedStyle(e).getPropertyValue("background-color");c(a)}),[o]),n.a.createElement("table",{className:"table w-100",ref:o},n.a.createElement("tbody",null,n.a.createElement("tr",null,n.a.createElement("td",{className:"text-muted"},"HEX:"),n.a.createElement("td",{className:"font-weight-bold"},m(t))),n.a.createElement("tr",null,n.a.createElement("td",{className:"text-muted"},"RGB:"),n.a.createElement("td",{className:"font-weight-bold"},t))))},i=function(e){var a=e.className,t=e.children,r=o()(a,"theme-color w-75 rounded mb-3");return n.a.createElement(s.u,{xl:"2",md:"4",sm:"6",xs:"12",className:"mb-4"},n.a.createElement("div",{className:r,style:{paddingTop:"75%"}}),t,n.a.createElement(u,null))};a.default=function(){return n.a.createElement(n.a.Fragment,null,n.a.createElement("div",{className:"card"},n.a.createElement("div",{className:"card-header"},"Theme colors"),n.a.createElement("div",{className:"card-body"},n.a.createElement(s.wb,null,n.a.createElement(i,{className:"bg-primary"},n.a.createElement("h6",null,"Brand Primary Color")),n.a.createElement(i,{className:"bg-secondary"},n.a.createElement("h6",null,"Brand Secondary Color")),n.a.createElement(i,{className:"bg-success"},n.a.createElement("h6",null,"Brand Success Color")),n.a.createElement(i,{className:"bg-danger"},n.a.createElement("h6",null,"Brand Danger Color")),n.a.createElement(i,{className:"bg-warning"},n.a.createElement("h6",null,"Brand Warning Color")),n.a.createElement(i,{className:"bg-info"},n.a.createElement("h6",null,"Brand Info Color")),n.a.createElement(i,{className:"bg-light"},n.a.createElement("h6",null,"Brand Light Color")),n.a.createElement(i,{className:"bg-dark"},n.a.createElement("h6",null,"Brand Dark Color"))))),n.a.createElement("div",{className:"card"},n.a.createElement("div",{className:"card-header"},"Grays"),n.a.createElement("div",{className:"card-body"},n.a.createElement(s.wb,{className:"mb-3"},n.a.createElement(i,{className:"bg-gray-100"},n.a.createElement("h6",null,"Gray 100 Color")),n.a.createElement(i,{className:"bg-gray-200"},n.a.createElement("h6",null,"Gray 200 Color")),n.a.createElement(i,{className:"bg-gray-300"},n.a.createElement("h6",null,"Gray 300 Color")),n.a.createElement(i,{className:"bg-gray-400"},n.a.createElement("h6",null,"Gray 400 Color")),n.a.createElement(i,{className:"bg-gray-500"},n.a.createElement("h6",null,"Gray 500 Color")),n.a.createElement(i,{className:"bg-gray-600"},n.a.createElement("h6",null,"Gray 600 Color")),n.a.createElement(i,{className:"bg-gray-700"},n.a.createElement("h6",null,"Gray 700 Color")),n.a.createElement(i,{className:"bg-gray-800"},n.a.createElement("h6",null,"Gray 800 Color")),n.a.createElement(i,{className:"bg-gray-900"},n.a.createElement("h6",null,"Gray 900 Color"))))))}},698:function(e,a,t){"use strict";t.d(a,"a",(function(){return l}));var r=t(219);function l(e,a){return function(e){if(Array.isArray(e))return e}(e)||function(e,a){if("undefined"!==typeof Symbol&&Symbol.iterator in Object(e)){var t=[],r=!0,l=!1,n=void 0;try{for(var c,o=e[Symbol.iterator]();!(r=(c=o.next()).done)&&(t.push(c.value),!a||t.length!==a);r=!0);}catch(s){l=!0,n=s}finally{try{r||null==o.return||o.return()}finally{if(l)throw n}}return t}}(e,a)||Object(r.a)(e,a)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}}}]);
//# sourceMappingURL=76.dfaa5e0b.chunk.js.map