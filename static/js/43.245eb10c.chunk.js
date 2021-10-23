(this.webpackJsonpmesl=this.webpackJsonpmesl||[]).push([[43],{1056:function(e,a,t){"use strict";t.r(a);var n=t(698),l=t(3),c=t.n(l),r=t(217),s=(t(702),t(707)),i=t(24),m=t(697),o=t(699);a.default=Object(r.b)((function(e){return{claszs:e.claszReducer,school:e.schoolReducer.school}}),{getClaszs:s.c,getClasz:s.b,registerClasz:s.d,updateClasz:s.e,deleteClasz:s.a})((function(e){var a=Object(i.g)(),t=(Object(i.h)().search.match(/page=([0-9]+)/,""),Object(l.useState)(!1)),r=Object(n.a)(t,2),s=r[0],u=r[1],f=Object(l.useState)(null),d=Object(n.a)(f,2),p=d[0],b=d[1],E=Object(l.useState)(""),h=Object(n.a)(E,2),y=h[0],v=h[1],z=Object(l.useState)(""),N=Object(n.a)(z,2),g=N[0],C=N[1],j=Object(l.useState)(),k=Object(n.a)(j,2),O=k[0],S=k[1];Object(l.useEffect)((function(){var a={data:JSON.stringify({schoolid:e.school.id}),cat:"select",table:"claszs",narration:"get claszs"};e.getClaszs(a)}),[e.school.id]),Object(l.useEffect)((function(){if(p&&parseInt(p)>0){var e=y;C(e.name),S(e.abbrv)}else C(""),S("")}),[p]);var x=function(){return b(null)},w=(e.claszs.claszs&&Array.isArray(e.claszs.claszs)?e.claszs.claszs.filter((function(e){return null!==e||void 0!==e})):[]).map((function(t,n){return c.a.createElement("tr",{key:n},c.a.createElement("td",{className:"text-center"},n+1),c.a.createElement("td",null,t.name),c.a.createElement("td",{className:"text-center"},t.abbrv),c.a.createElement("td",{className:"text-center"},c.a.createElement(m.z,{className:"m-0 btn-group "},c.a.createElement(m.E,{color:0===parseInt(t.is_active)?"success":"danger",size:"sm"},c.a.createElement("i",{className:"fa fa-gear"})," Action"),c.a.createElement(m.D,null,c.a.createElement(m.C,{onClick:function(e){return a.push("/claszs/".concat(t.id))}},c.a.createElement("i",{className:"fa fa-list"})," "," Subjects"),c.a.createElement(m.C,{onClick:function(){return function(a,t){var n=0===parseInt(t)?1:0,l=new FormData;l.append("id",a),l.append("is_active",n),e.updateClasz(l)}(t.id,t.is_active)}},c.a.createElement("i",{className:0===parseInt(t.is_active)?"fa fa-thumbs-up":"fa fa-thumb-down"})," ","  ",0===parseInt(t.is_active)?"Deactivate":"Activate"),c.a.createElement(m.C,{onClick:function(){return b((e=t).id),v(e),void u(!0);var e}},c.a.createElement("i",{className:"fa fa-edit"})," ","  Edit"),c.a.createElement(m.C,{onClick:function(){t.id}},c.a.createElement("i",{className:"fa fa-remove"})," ","  Delete")))))}));return c.a.createElement(m.wb,null,c.a.createElement(m.u,null,c.a.createElement(m.j,null,c.a.createElement(m.n,null,c.a.createElement(m.wb,null,c.a.createElement(m.u,{sm:"5"},c.a.createElement("h4",{id:"traffic",className:"card-title mb-0"},"Claszs"),c.a.createElement("div",{className:"small text-muted",style:{textTransform:"capitalize"}},e.school.name)),c.a.createElement(m.u,{sm:"7",className:"d-md-block"},c.a.createElement(m.f,{"data-target":"#formz","data-toggle":"collapse",color:"primary",onClick:function(e){u(!s),e.preventDefault()},className:"float-right"},c.a.createElement("i",{className:"fa fa-plus"}))))),c.a.createElement(m.k,null,c.a.createElement("table",{className:"table table-hover table-outline mb-0  d-sm-table"},c.a.createElement("thead",{className:"thead-light"},c.a.createElement("tr",null,c.a.createElement("th",{className:"text-center"},"SN."),c.a.createElement("th",null,c.a.createElement("i",{className:"fa fa-list"})," Clasz"),c.a.createElement("th",{className:"text-center"}," ",c.a.createElement("i",{className:"fa fa-crosshairs"})," Abbreviate."),c.a.createElement("th",{className:"text-center"},c.a.createElement("i",{className:"fa fa-gear"})," Action"))),c.a.createElement("tbody",null,w))))),c.a.createElement(m.v,{show:s},c.a.createElement(m.u,{xl:12,id:"#formz"},c.a.createElement(m.j,null,c.a.createElement(m.n,{id:"traffic",className:"card-title mb-0"},c.a.createElement(m.wb,null,c.a.createElement(m.u,{sm:"6"},c.a.createElement("h4",null,p&&parseInt(p)>0?"Edit":"Add"," ",c.a.createElement("small",null," Clasz"))),c.a.createElement(m.u,{sm:"6",className:"d-md-block"},c.a.createElement(m.f,{color:"danger",onClick:function(){return u(!1)},className:"float-right"},c.a.createElement("i",{className:"fa fa-remove"}))))),c.a.createElement(m.k,null,c.a.createElement(m.J,{action:"",method:"post"},c.a.createElement(m.K,null,c.a.createElement(m.cb,{htmlFor:"nf-name"},"Clasz"),c.a.createElement(m.S,{type:"text",id:"nf-name",name:"namez",defaultValue:g,onChange:function(e){return C(e.target.value)},placeholder:"Science"}),c.a.createElement(m.L,{className:"help-block"},"Please enter clasz name")),c.a.createElement(m.K,null,c.a.createElement(m.cb,{htmlFor:"nf-abbrv"},"Clasz Abbrv "),c.a.createElement(m.S,{type:"text",id:"nf-abbrv",name:"abbrv",defaultValue:O,onChange:function(e){return S(e.target.value)},placeholder:"SCI"}),c.a.createElement(m.L,{className:"help-block"},"Please enter clasz abbrv (max 6 characters)")))),c.a.createElement(m.l,null,c.a.createElement(m.f,{type:"submit",onClick:function(){if(g.length>0){var a=new FormData;a.append("name",g),a.append("abbrv",O),a.append("table","claszs"),p&&parseInt(p)>0?(a.append("id",p),a.append("cat","update"),e.updateClasz(a)):(a.append("schoolid",1),a.append("cat","insert"),e.registerClasz(a)),x()}},size:"sm",color:"primary"},c.a.createElement(o.a,{name:"cil-scrubber"})," Submit")," ",c.a.createElement(m.f,{type:"reset",onClick:x,size:"sm",color:"danger"},c.a.createElement(o.a,{name:"cil-ban"})," Reset"))))))}))},698:function(e,a,t){"use strict";t.d(a,"a",(function(){return l}));var n=t(219);function l(e,a){return function(e){if(Array.isArray(e))return e}(e)||function(e,a){if("undefined"!==typeof Symbol&&Symbol.iterator in Object(e)){var t=[],n=!0,l=!1,c=void 0;try{for(var r,s=e[Symbol.iterator]();!(n=(r=s.next()).done)&&(t.push(r.value),!a||t.length!==a);n=!0);}catch(i){l=!0,c=i}finally{try{n||null==s.return||s.return()}finally{if(l)throw c}}return t}}(e,a)||Object(n.a)(e,a)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}},707:function(e,a,t){"use strict";t.d(a,"c",(function(){return i})),t.d(a,"b",(function(){return m})),t.d(a,"a",(function(){return o})),t.d(a,"d",(function(){return u})),t.d(a,"e",(function(){return f}));var n=t(700),l=t.n(n),c=t(47),r=t(6),s=r.b,i=function(e){return function(a,t){e.token=r.e,e.table="claszs",a({type:c.h}),l.a.get(s,{params:e},r.l).then((function(e){a({type:c.f,payload:e.data})})).catch((function(e){a({type:c.i,payload:e})}))}},m=function(e){return function(a,t){a({type:c.g,payload:e})}},o=function(e){return function(a,t){l.a.POST(s,{params:e},r.l).then((function(t){a({type:c.d,payload:e.id})})).catch((function(e){a({type:c.c,payload:e})}))}},u=function(e){return function(a){l.a.post(s,e,r.m).then((function(e){a({type:c.k,payload:e.data.data})})).catch((function(e){a({type:c.j,payload:e})}))}},f=function(e){return function(a,t){l.a.post(s,e,r.m).then((function(e){a({type:c.m,payload:e.data.data})})).catch((function(e){a({type:c.l,payload:e})}))}}}}]);
//# sourceMappingURL=43.245eb10c.chunk.js.map