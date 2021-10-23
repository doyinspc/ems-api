(this.webpackJsonpmesl=this.webpackJsonpmesl||[]).push([[48],{1055:function(e,t,a){"use strict";a.r(t);var n=a(698),c=a(3),r=a.n(c),l=a(217),s=a(740),u=a(24),m=a(697),i=a(699);a(226);t.default=Object(l.b)((function(e){return{subjects:e.subjectReducer,departments:e.departmentReducer.departments,school:e.schoolReducer.school}}),{getSubjects:s.c,getSubject:s.b,registerSubject:s.d,updateSubject:s.e,deleteSubject:s.a})((function(e){var t=Object(u.i)().subject,a=Object(u.g)(),l=(Object(u.h)().search.match(/page=([0-9]+)/,""),Object(c.useState)(!1)),s=Object(n.a)(l,2),o=s[0],f=s[1],d=Object(c.useState)(null),b=Object(n.a)(d,2),p=b[0],E=b[1],h=Object(c.useState)(""),j=Object(n.a)(h,2),y=j[0],v=j[1],N=Object(c.useState)(""),g=Object(n.a)(N,2),S=g[0],k=g[1],O=Object(c.useState)(),C=Object(n.a)(O,2),x=C[0],I=C[1];Object(c.useEffect)((function(){var a={data:JSON.stringify({departmentid:t}),cat:"select",table:"subjects",narration:"get subjects"};e.getSubjects(a)}),[t]),Object(c.useEffect)((function(){if(p&&parseInt(p)>0){var e=y;k(e.name),I(e.abbrv)}else k(""),I("")}),[p]);var w=function(){return E(null)},A=e.departments.filter((function(e){return parseInt(e.id)===parseInt(t)&&parseInt(e.id)>0})),z=A.length>0?A[0].name:"None",D=(e.subjects.subjects&&Array.isArray(e.subjects.subjects)?e.subjects.subjects.filter((function(e){return null!==e||void 0!==e})):[]).map((function(n,c){return r.a.createElement("tr",{key:c},r.a.createElement("td",{className:"text-center"},c+1),r.a.createElement("td",null,n.name),r.a.createElement("td",{className:"text-center"},n.abbrv),r.a.createElement("td",{className:"text-center"},r.a.createElement(m.z,{className:"m-0 btn-group "},r.a.createElement(m.E,{color:0===parseInt(n.is_active)?"success":"danger",size:"sm"},r.a.createElement("i",{className:"fa fa-gear"})," Action"),r.a.createElement(m.D,null,r.a.createElement(m.C,{onClick:function(e){return a.push("/department/".concat(t,"/").concat(n.id))}},r.a.createElement("i",{className:"fa fa-list"})," "," Scheme of Work"),r.a.createElement(m.C,{onClick:function(e){return a.push("/department/".concat(t,"/").concat(n.id))}},r.a.createElement("i",{className:"fa fa-bar-chart"})," "," Performance Analysis"),r.a.createElement(m.C,{onClick:function(){return function(t,a){var n=0===parseInt(a)?1:0,c=new FormData;c.append("id",t),c.append("is_active",n),e.updateSubject(c)}(n.id,n.is_active)}},r.a.createElement("i",{className:0===parseInt(n.is_active)?"fa fa-thumbs-up":"fa fa-thumb-down"})," ","  ",0===parseInt(n.is_active)?"Deactivate":"Activate"),r.a.createElement(m.C,{onClick:function(){return E((e=n).id),v(e),void f(!0);var e}},r.a.createElement("i",{className:"fa fa-edit"})," ","  Edit"),r.a.createElement(m.C,{onClick:function(){n.id}},r.a.createElement("i",{className:"fa fa-remove"})," ","  Delete")))))}));return r.a.createElement(m.wb,null,r.a.createElement(m.u,null,r.a.createElement(m.j,null,r.a.createElement(m.n,null,r.a.createElement(m.wb,null,r.a.createElement(m.u,{sm:"5"},r.a.createElement("h4",{id:"traffic",className:"card-title mb-0"},z," Subjects"),r.a.createElement("div",{className:"small text-muted",style:{textTransform:"capitalize"}},e.school.name)),r.a.createElement(m.u,{sm:"7",className:"d-md-block"},r.a.createElement(m.f,{"data-target":"#formz","data-toggle":"collapse",color:"primary",onClick:function(e){f(!o),e.preventDefault()},className:"float-right"},r.a.createElement("i",{className:"fa fa-plus"}))))),r.a.createElement(m.k,null,r.a.createElement("table",{className:"table table-hover table-outline mb-0  d-sm-table"},r.a.createElement("thead",{className:"thead-light"},r.a.createElement("tr",null,r.a.createElement("th",{className:"text-center"},"SN."),r.a.createElement("th",null,r.a.createElement("i",{className:"fa fa-list"})," Subject"),r.a.createElement("th",{className:"text-center"}," ",r.a.createElement("i",{className:"fa fa-crosshairs"})," Abbreviate."),r.a.createElement("th",{className:"text-center"},r.a.createElement("i",{className:"fa fa-gear"})," Action"))),r.a.createElement("tbody",null,D))))),r.a.createElement(m.v,{show:o},r.a.createElement(m.u,{xl:12,id:"#formz"},r.a.createElement(m.j,null,r.a.createElement(m.n,{id:"traffic",className:"card-title mb-0"},r.a.createElement(m.wb,null,r.a.createElement(m.u,{sm:"6"},r.a.createElement("h4",null,p&&parseInt(p)>0?"Edit":"Add"," ",r.a.createElement("small",null," Subject"))),r.a.createElement(m.u,{sm:"6",className:"d-md-block"},r.a.createElement(m.f,{color:"danger",onClick:function(){return f(!1)},className:"float-right"},r.a.createElement("i",{className:"fa fa-remove"}))))),r.a.createElement(m.k,null,r.a.createElement(m.J,{action:"",method:"post"},r.a.createElement(m.K,null,r.a.createElement(m.cb,{htmlFor:"nf-name"},"Subject"),r.a.createElement(m.S,{type:"text",id:"nf-name",name:"namez",defaultValue:S,onChange:function(e){return k(e.target.value)},placeholder:"Chemistry"}),r.a.createElement(m.L,{className:"help-block"},"Please enter subject name")),r.a.createElement(m.K,null,r.a.createElement(m.cb,{htmlFor:"nf-abbrv"},"Subject Abbrv "),r.a.createElement(m.S,{type:"text",id:"nf-abbrv",name:"abbrv",defaultValue:x,onChange:function(e){return I(e.target.value)},placeholder:"CHEM"}),r.a.createElement(m.L,{className:"help-block"},"Please enter subject abbrv (max 6 characters)")))),r.a.createElement(m.l,null,r.a.createElement(m.f,{type:"submit",onClick:function(){if(S.length>0){var a=new FormData;a.append("name",S),a.append("abbrv",x),a.append("table","subjects"),p&&parseInt(p)>0?(a.append("id",p),a.append("cat","update"),e.updateSubject(a)):t&&parseInt(t)>0&&(a.append("departmentid",t),a.append("cat","insert"),e.registerSubject(a)),w()}},size:"sm",color:"primary"},r.a.createElement(i.a,{name:"cil-scrubber"})," Submit")," ",r.a.createElement(m.f,{type:"reset",onClick:w,size:"sm",color:"danger"},r.a.createElement(i.a,{name:"cil-ban"})," Reset"))))))}))},698:function(e,t,a){"use strict";a.d(t,"a",(function(){return c}));var n=a(219);function c(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"!==typeof Symbol&&Symbol.iterator in Object(e)){var a=[],n=!0,c=!1,r=void 0;try{for(var l,s=e[Symbol.iterator]();!(n=(l=s.next()).done)&&(a.push(l.value),!t||a.length!==t);n=!0);}catch(u){c=!0,r=u}finally{try{n||null==s.return||s.return()}finally{if(c)throw r}}return a}}(e,t)||Object(n.a)(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}},740:function(e,t,a){"use strict";a.d(t,"c",(function(){return u})),a.d(t,"b",(function(){return m})),a.d(t,"a",(function(){return i})),a.d(t,"d",(function(){return o})),a.d(t,"e",(function(){return f}));var n=a(700),c=a.n(n),r=a(55),l=a(6),s=l.b,u=function(e){return function(t,a){e.token=l.e,t({type:r.h}),c.a.get(s,{params:e},l.l).then((function(e){t({type:r.f,payload:e.data})})).catch((function(e){t({type:r.i,payload:e})}))}},m=function(e){return function(t,a){t({type:r.g,payload:e})}},i=function(e){return function(t,a){c.a.POST(s,{params:e},l.l).then((function(a){t({type:r.d,payload:e.id})})).catch((function(e){t({type:r.c,payload:e})}))}},o=function(e){return function(t){c.a.post(s,e,l.m).then((function(e){t({type:r.k,payload:e.data.data})})).catch((function(e){t({type:r.j,payload:e})}))}},f=function(e){return function(t,a){c.a.post(s,e,l.m).then((function(e){t({type:r.m,payload:e.data.data})})).catch((function(e){t({type:r.l,payload:e})}))}}}}]);
//# sourceMappingURL=48.bd6ab422.chunk.js.map