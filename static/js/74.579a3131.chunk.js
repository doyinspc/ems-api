(this.webpackJsonpmesl=this.webpackJsonpmesl||[]).push([[74],{698:function(e,t,a){"use strict";a.d(t,"a",(function(){return c}));var n=a(219);function c(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"!==typeof Symbol&&Symbol.iterator in Object(e)){var a=[],n=!0,c=!1,o=void 0;try{for(var r,l=e[Symbol.iterator]();!(n=(r=l.next()).done)&&(a.push(r.value),!t||a.length!==t);n=!0);}catch(i){c=!0,o=i}finally{try{n||null==l.return||l.return()}finally{if(c)throw o}}return a}}(e,t)||Object(n.a)(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}},999:function(e,t,a){"use strict";a.r(t);var n=a(1),c=a(698),o=a(3),r=a.n(o),l=a(697);t.default=function(){var e=Object(o.useState)([{position:"static"},{position:"static"},{position:"top-right",autohide:3e3}]),t=Object(c.a)(e,2),a=t[0],i=t[1],u=Object(o.useState)("top-right"),s=Object(c.a)(u,2),m=s[0],h=s[1],b=Object(o.useState)(!0),f=Object(c.a)(b,2),d=f[0],p=f[1],E=Object(o.useState)(5e3),y=Object(c.a)(E,2),k=y[0],v=y[1],g=Object(o.useState)(!0),j=Object(c.a)(g,2),O=j[0],w=j[1],S=Object(o.useState)(!0),N=Object(c.a)(S,2),T=N[0],x=N[1],C=a.reduce((function(e,t){return e[t.position]=e[t.position]||[],e[t.position].push(t),e}),{});return r.a.createElement(l.j,null,r.a.createElement(l.n,null,"Toasts."),r.a.createElement(l.k,null,r.a.createElement(l.w,null,r.a.createElement(l.wb,null,r.a.createElement(l.u,{sm:"12",lg:"6"},r.a.createElement(l.J,null,r.a.createElement("h5",null,"Add toast with following props:"),r.a.createElement(l.K,{variant:"custom-checkbox",className:"my-2 mt-4"},r.a.createElement(l.T,{id:"autohide",checked:d,onChange:function(e){p(e.target.checked)},custom:!0}),r.a.createElement(l.cb,{variant:"custom-checkbox",htmlFor:"autohide"},"Autohide of the toast")),d&&r.a.createElement(l.K,{className:"my-2"},r.a.createElement(l.cb,{htmlFor:"ccyear"},"Time to autohide"),r.a.createElement(l.S,{type:"number",value:k,onChange:function(e){v(Number(e.target.value))}})),r.a.createElement(l.K,{className:"my-2"},r.a.createElement(l.cb,{htmlFor:"ccyear"},"Position"),r.a.createElement("select",{className:"form-control",value:m,onChange:function(e){h(e.target.value)}},["static","top-left","top-center","top-right","top-full","bottom-left","bottom-center","bottom-right","bottom-full"].map((function(e,t){return r.a.createElement("option",{key:t},e)})))),r.a.createElement(l.K,{variant:"custom-checkbox",className:"my-2"},r.a.createElement(l.T,{id:"fade",checked:T,onChange:function(e){x(e.target.checked)},custom:!0}),r.a.createElement(l.cb,{variant:"custom-checkbox",htmlFor:"fade"},"fade")),r.a.createElement(l.K,{variant:"custom-checkbox",className:"my-2"},r.a.createElement(l.T,{id:"close",custom:!0,checked:O,onChange:function(e){w(e.target.checked)}}),r.a.createElement(l.cb,{variant:"custom-checkbox",htmlFor:"close"},"closeButton")),r.a.createElement(l.f,{className:"mr-1 w-25",color:"success",onClick:function(){i([].concat(Object(n.a)(a),[{position:m,autohide:d&&k,closeButton:O,fade:T}]))}},"Add toast"))),r.a.createElement(l.u,{sm:"12",lg:"6"},Object.keys(C).map((function(e){return r.a.createElement(l.Pb,{position:e,key:"toaster"+e},C[e].map((function(t,a){return r.a.createElement(l.Mb,{key:"toast"+a,show:!0,autohide:t.autohide,fade:t.fade},r.a.createElement(l.Ob,{closeButton:t.closeButton},"Toast title"),r.a.createElement(l.Nb,null,"This is a toast in ".concat(e," positioned toaster number ").concat(a+1,".")))})))})))))))}}}]);
//# sourceMappingURL=74.579a3131.chunk.js.map