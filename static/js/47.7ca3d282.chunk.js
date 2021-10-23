(this.webpackJsonpmesl=this.webpackJsonpmesl||[]).push([[47],{1045:function(e,t,a){"use strict";a.r(t);var n=a(698),l=a(3),c=a.n(l),r=a(217),s=a(702),o=a.n(s),i=a(728),m=a(24),u=a(697),d=a(699);t.default=Object(r.b)((function(e){return{sessions:e.sessionReducer,school:e.schoolReducer.school}}),{getSessions:i.c,getSession:i.b,registerSession:i.d,updateSession:i.e,deleteSession:i.a})((function(e){var t=Object(m.g)(),a=(Object(m.h)().search.match(/page=([0-9]+)/,""),Object(l.useState)(!1)),r=Object(n.a)(a,2),s=r[0],i=r[1],f=Object(l.useState)(""),E=Object(n.a)(f,2),p=E[0],b=E[1],h=Object(l.useState)(""),y=Object(n.a)(h,2),N=y[0],S=y[1],g=Object(l.useState)(""),v=Object(n.a)(g,2),j=v[0],O=v[1],k=Object(l.useState)(),C=Object(n.a)(k,2),x=C[0],w=C[1],A=Object(l.useState)(),Y=Object(n.a)(A,2),z=Y[0],D=Y[1];Object(l.useEffect)((function(){var t={data:JSON.stringify({schoolid:e.school.id}),cat:"select",table:"sessions",narration:"get sessions"};e.getSessions(t)}),[e.school.id]),Object(l.useEffect)((function(){if(p&&parseInt(p)>0){var e=N;O(e.name),w(e.started),D(e.ended)}else O(""),w(""),D("")}),[p]);var M=function(){return b(null)},I=(e.sessions.sessions&&Array.isArray(e.sessions.sessions)?e.sessions.sessions.filter((function(e){return null!==e||void 0!==e})):[]).map((function(e,a){return c.a.createElement("tr",{key:a},c.a.createElement("td",{className:"text-center"},a+1),c.a.createElement("td",null,e.name),c.a.createElement("td",{className:"text-center"},o()(e.started).format("MMM D, YYYY")),c.a.createElement("td",{className:"text-center"},o()(e.ended).format("MMM D, YYYY")),c.a.createElement("td",{className:"text-center"},c.a.createElement(u.z,{className:"m-0 btn-group "},c.a.createElement(u.E,{color:"success",size:"sm"},c.a.createElement("i",{className:"fa fa-gear"})," Action"),c.a.createElement(u.D,null,c.a.createElement(u.C,{onClick:function(a){return t.push("/sessions/".concat(e.id))}},c.a.createElement("i",{className:"fa fa-list"})," "," Terms"),c.a.createElement(u.C,{onClick:function(){return e.id,b((t=e).id),S(t),void i(!0);var t}},c.a.createElement("i",{className:"fa fa-edit"})," ","  Edit"),c.a.createElement(u.C,{onClick:function(){e.id}},c.a.createElement("i",{className:"fa fa-remove"})," ","  Delete"),c.a.createElement(u.A,null),c.a.createElement(u.C,null,c.a.createElement("i",{className:"fa fa-database"})," "," Backup"),c.a.createElement(u.C,null,c.a.createElement("i",{className:"fa fa-upload"})," ","Restore")))))}));return c.a.createElement(u.wb,null,c.a.createElement(u.u,null,c.a.createElement(u.j,null,c.a.createElement(u.n,null,c.a.createElement(u.wb,null,c.a.createElement(u.u,{sm:"5"},c.a.createElement("h4",{id:"traffic",className:"card-title mb-0"},"Sessions"),c.a.createElement("div",{className:"small text-muted"},e.school.name)),c.a.createElement(u.u,{sm:"7",className:"d-md-block"},c.a.createElement(u.f,{"data-target":"#formz","data-toggle":"collapse",color:"primary",onClick:function(e){i(!s),e.preventDefault()},className:"float-right"},c.a.createElement("i",{className:"fa fa-plus"}))))),c.a.createElement(u.k,null,c.a.createElement("table",{className:"table table-hover table-outline mb-0  d-sm-table"},c.a.createElement("thead",{className:"thead-light"},c.a.createElement("tr",null,c.a.createElement("th",{className:"text-center"},"SN."),c.a.createElement("th",null,c.a.createElement("i",{className:"fa fa-list"})," Session"),c.a.createElement("th",{className:"text-center"}," ",c.a.createElement("i",{className:"fa fa-calendar"})," Start"),c.a.createElement("th",{className:"text-center"},c.a.createElement("i",{className:"fa fa-calendar"})," End"),c.a.createElement("th",{className:"text-center"},c.a.createElement("i",{className:"fa fa-gear"})," Action"))),c.a.createElement("tbody",null,I))))),c.a.createElement(u.v,{show:s},c.a.createElement(u.u,{xl:12,id:"#formz"},c.a.createElement(u.j,null,c.a.createElement(u.n,{id:"traffic",className:"card-title mb-0"},c.a.createElement(u.wb,null,c.a.createElement(u.u,{sm:"6"},c.a.createElement("h4",null,p&&parseInt(p)>0?"Edit":"Add"," ",c.a.createElement("small",null," Session"))),c.a.createElement(u.u,{sm:"6",className:"d-md-block"},c.a.createElement(u.f,{color:"danger",onClick:function(){return i(!1)},className:"float-right"},c.a.createElement("i",{className:"fa fa-remove"}))))),c.a.createElement(u.k,null,c.a.createElement(u.J,{action:"",method:"post"},c.a.createElement(u.K,null,c.a.createElement(u.cb,{htmlFor:"nf-name"},"Session"),c.a.createElement(u.S,{type:"text",id:"nf-name",name:"name",defaultValue:j,onChange:function(e){return O(e.target.value)},placeholder:"2020_2021"}),c.a.createElement(u.L,{className:"help-block"},"Please enter session")),c.a.createElement(u.K,null,c.a.createElement(u.cb,{htmlFor:"nf-starts"},"Session Starts "),c.a.createElement(u.S,{type:"date",id:"nf-starts",name:"starts",defaultValue:x,onChange:function(e){return w(e.target.value)},placeholder:"date"}),c.a.createElement(u.L,{className:"help-block"},"Please enter date session starts")),c.a.createElement(u.K,null,c.a.createElement(u.cb,{htmlFor:"nf-ends"},"Session ends "),c.a.createElement(u.S,{type:"date",id:"nf-ends",name:"ends",defaultValue:z,onChange:function(e){return D(e.target.value)},placeholder:"date"}),c.a.createElement(u.L,{className:"help-block"},"Please enter date session ends")))),c.a.createElement(u.l,null,c.a.createElement(u.f,{type:"submit",onClick:function(){if(j.length>0){var t=new FormData;t.append("name",j),t.append("started",x),t.append("ended",z),t.append("table","sessions"),p&&parseInt(p)>0?(t.append("id",p),t.append("cat","update"),e.updateSession(t)):(t.append("schoolid",1),t.append("cat","insert"),e.registerSession(t)),M()}},size:"sm",color:"primary"},c.a.createElement(d.a,{name:"cil-scrubber"})," Submit")," ",c.a.createElement(u.f,{type:"reset",onClick:M,size:"sm",color:"danger"},c.a.createElement(d.a,{name:"cil-ban"})," Reset"))))))}))},698:function(e,t,a){"use strict";a.d(t,"a",(function(){return l}));var n=a(219);function l(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"!==typeof Symbol&&Symbol.iterator in Object(e)){var a=[],n=!0,l=!1,c=void 0;try{for(var r,s=e[Symbol.iterator]();!(n=(r=s.next()).done)&&(a.push(r.value),!t||a.length!==t);n=!0);}catch(o){l=!0,c=o}finally{try{n||null==s.return||s.return()}finally{if(l)throw c}}return a}}(e,t)||Object(n.a)(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}},728:function(e,t,a){"use strict";a.d(t,"c",(function(){return o})),a.d(t,"b",(function(){return i})),a.d(t,"a",(function(){return m})),a.d(t,"d",(function(){return u})),a.d(t,"e",(function(){return d}));var n=a(700),l=a.n(n),c=a(49),r=a(6),s=r.b,o=function(e){return function(t,a){e.token=r.e,e.table="sessions",t({type:c.h}),l.a.get(s,{params:e},r.l).then((function(e){t({type:c.f,payload:e.data})})).catch((function(e){t({type:c.i,payload:e})}))}},i=function(e){return function(t,a){t({type:c.g,payload:e})}},m=function(e){return function(t,a){l.a.POST(s,{params:e},r.l).then((function(a){t({type:c.d,payload:e.id})})).catch((function(e){t({type:c.c,payload:e})}))}},u=function(e){return function(t){l.a.post(s,e,r.m).then((function(e){t({type:c.k,payload:e.data.data})})).catch((function(e){t({type:c.j,payload:e})}))}},d=function(e){return function(t,a){l.a.post(s,e,r.m).then((function(e){t({type:c.m,payload:e.data.data})})).catch((function(e){t({type:c.l,payload:e})}))}}}}]);
//# sourceMappingURL=47.7ca3d282.chunk.js.map