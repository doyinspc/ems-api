(this.webpackJsonpmesl=this.webpackJsonpmesl||[]).push([[39],{1360:function(t,e,n){"use strict";n.r(e);var a=n(0),r=n(698),c=n(697),u=n(3),o=n.n(u),d=n(217),l=n(777),i=n(708),s=n(702),f=n.n(s),p=n(6),y=p.t,m=function(t){var e=Object(u.useState)("#ccc"),n=Object(r.a)(e,2),a=n[0],c=n[1];return Object(u.useEffect)((function(){if(new Date(t.mydate)<=new Date&&6!==new Date(t.mydate).getDay()&&0!==new Date(t.mydate).getDay())if(t.candidates.split(",").includes(t.client))if(parseInt(t.issue)>0){var e=y.filter((function(e){return parseInt(e.id)===parseInt(t.issue)}));c(e[0].color),t.setData(t.client,parseInt(t.issue))}else c("#cfc"),t.setData(t.client,1);else c("#ccc"),t.setData(t.client,0);return function(){c("#ccc")}}),[t.mydate]),o.a.createElement(o.a.Fragment,null,o.a.createElement("td",{className:"text-center",style:{backgroundColor:a,width:"25px"}}))},h=n(24),g=p.t;e.default=Object(d.b)((function(t){return{students:t.studentReducer.students,studentattendance:t.studentattendanceReducer,user:t.userReducer,studentclasss:t.studentclassReducer}}),{getStudentattendances:l.f,getStudentattendancedailys:l.e,getStudentattendance:l.c,registerStudentattendance:l.g,registerStudentattendancedaily:l.h,updateStudentattendance:l.i,updateStudentattendancedaily:l.j,deleteStudentattendance:l.a,deleteStudentattendancedaily:l.b,getStudents:i.c})((function(t){var e=Object(h.i)().clasz,n=Object(u.useState)(new Date((new Date).getFullYear(),(new Date).getMonth(),-1)),d=Object(r.a)(n,2),l=d[0],i=d[1],s=Object(u.useState)(new Date((new Date).getFullYear(),(new Date).getMonth()+1,0)),p=Object(r.a)(s,2),y=p[0],D=p[1],E=Object(u.useState)({}),b=Object(r.a)(E,2),S=b[0],w=b[1],Y=t.studentclasss.studentclasss.map((function(t,e){return t.id})),v=Array.isArray(Y)&&Y.length>0?Y.join(","):"";Object(u.useEffect)((function(){var e={data:JSON.stringify({ids:v}),cat:"selected",table:"studentx",narration:"get all students"};t.getStudents(e)}),[v]),Object(u.useEffect)((function(){var n={data:JSON.stringify({schoolid:t.user.activeschool.id,clients:e,grp:2,starts:f()(l).format("YYYY-MM-DD"),ends:f()(y).format("YYYY-MM-DD"),ids:v}),cat:"selectedattendance",table:"attendances",narration:"get all students"};t.getStudentattendances(n);var a={data:JSON.stringify({clients:1,schoolid:t.user.activeschool.id,grp:4,starts:f()(l).format("YYYY-MM-DD"),ends:f()(y).format("YYYY-MM-DD")}),cat:"selectedattendance",table:"attendances",narration:"get all attendance"};t.getStudentattendancedailys(a)}),[l,y,v,e]);var O=Array.isArray(t.studentattendance.studentattendancedailys)&&void 0!==t.studentattendance.studentattendancedailys?t.studentattendance.studentattendancedailys:[],j={};O.forEach((function(t){return j[t.dates]=t.reason}));var k=Array.isArray(t.studentattendance.studentattendances)&&void 0!==t.studentattendance.studentattendances?t.studentattendance.studentattendances:[],M={};k.forEach((function(t){return M[t.dates+"_"+t.clients]=t.leaveid}));for(var x=[],N=l,A=y,I=new Date(N),T=N;new Date(T)<new Date(A);){T=I.toISOString().slice(0,10);x.push(T),I.setDate(I.getDate()+1)}var F=x.map((function(t,e){return o.a.createElement("td",{key:e,className:"text-center",style:{width:"25px"}},o.a.createElement("strong",null,f()(new Date(t)).format("dd")))})),C=x.map((function(t,e){return o.a.createElement(o.a.Fragment,null,o.a.createElement("td",{key:e,className:"text-center"},o.a.createElement("strong",null,new Date(t).getDate())))})),J=g.map((function(t,e){return o.a.createElement("td",{key:e,rowSpan:2},o.a.createElement("small",{style:{textOrientation:"upright"}},t.name))})),P=t.studentclasss.studentclasss.map((function(t,e){var n=[];return o.a.createElement("tr",{key:e+"_"+t.id},o.a.createElement("td",{className:"text-right"},o.a.createElement("strong",{className:"text-nowrap"},"".concat(t.surname," ").concat(t.firstname," ").concat(t.middlename))),x.map((function(e,r){var c=j[f()(e).format("YYYY-MM-DD")]?j[f()(e).format("YYYY-MM-DD")]:"",u=M[f()(e).format("YYYY-MM-DD")+"_"+t.id]?M[f()(e).format("YYYY-MM-DD")+"_"+t.id]:0,d=function(t,e,n,a){if(new Date(t)<=new Date&&6!==new Date(t).getDay()&&0!==new Date(t).getDay())return e.split(",").includes(n)?parseInt(a)>0?parseInt(a):1:0}(e,c,t.id,u);return n.push(d),o.a.createElement(o.a.Fragment,null,o.a.createElement(m,{candidates:c,client:t.id,issue:u,mydate:e,key:r,setData:function(t,e){return function(t,e){var n=Object(a.a)({},S);n.hasOwnProperty(t)?(n[t].push(e),w(n)):(n[t]=[],n[t].push(e),w(n))}(t,e)}}))})),g.map((function(t,e){var a=Array.isArray(n)?n.filter((function(e){return parseInt(e)===parseInt(t.id)})).length:0;return o.a.createElement("td",{key:e,className:"text-center"},a)})))}));return o.a.createElement(o.a.Fragment,null,o.a.createElement(c.wb,{className:" d-flex row mb-20"},g.map((function(t,e){return o.a.createElement(c.f,{key:e,className:"text-center",style:{backgroundColor:t.color}},o.a.createElement("strong",null,t.name))}))),o.a.createElement(c.wb,{className:"m-10"},o.a.createElement(c.u,null,o.a.createElement(c.cb,null,o.a.createElement("strong",null,"Start Date")),o.a.createElement(c.S,{name:"starts",type:"date",value:l,onChange:function(t){return i(t.target.value)}})),o.a.createElement(c.u,null,o.a.createElement(c.cb,null,o.a.createElement("strong",null,"End Date")),o.a.createElement(c.S,{name:"ends",type:"date",value:y,onChange:function(t){return D(t.target.value)}}))),o.a.createElement(c.w,{fluid:!0},o.a.createElement(c.wb,{scrolling:!0},o.a.createElement("table",{border:"ipx solid",style:{backgroundColor:"white",marginTop:"50px"}},o.a.createElement("thead",null,o.a.createElement("tr",null,o.a.createElement("th",{className:"text-center"},o.a.createElement("strong",null,"DAYS")),F,J),o.a.createElement("tr",null,o.a.createElement("th",{className:"text-center"},o.a.createElement("strong",null,"STUDENT NAMES")),C)),o.a.createElement("tbody",null,P)))))}))},698:function(t,e,n){"use strict";n.d(e,"a",(function(){return r}));var a=n(219);function r(t,e){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){if("undefined"!==typeof Symbol&&Symbol.iterator in Object(t)){var n=[],a=!0,r=!1,c=void 0;try{for(var u,o=t[Symbol.iterator]();!(a=(u=o.next()).done)&&(n.push(u.value),!e||n.length!==e);a=!0);}catch(d){r=!0,c=d}finally{try{a||null==o.return||o.return()}finally{if(r)throw c}}return n}}(t,e)||Object(a.a)(t,e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}},708:function(t,e,n){"use strict";n.d(e,"c",(function(){return d})),n.d(e,"e",(function(){return l})),n.d(e,"b",(function(){return i})),n.d(e,"a",(function(){return s})),n.d(e,"d",(function(){return f})),n.d(e,"f",(function(){return p}));var a=n(700),r=n.n(a),c=n(37),u=n(6),o=u.b,d=function(t){return function(e,n){t.token=u.e,t.table="students",e({type:c.j}),r.a.get(o,{params:t},u.l).then((function(t){e({type:c.g,payload:t.data})})).catch((function(t){e({type:c.k,payload:t})}))}},l=function(t){return function(e,n){e({type:c.j}),r.a.get(o,{params:t},u.l).then((function(t){e({type:c.i,payload:t.data})})).catch((function(t){e({type:c.k,payload:t})}))}},i=function(t){return function(e,n){e({type:c.h,payload:t})}},s=function(t){return function(e,n){r.a.POST(o,{params:t},u.l).then((function(n){e({type:c.d,payload:t.id})})).catch((function(t){e({type:c.c,payload:t})}))}},f=function(t){return function(e){r.a.post(o,t,u.m).then((function(t){e({type:c.m,payload:t.data.data})})).catch((function(t){e({type:c.l,payload:t})}))}},p=function(t){return function(e,n){r.a.post(o,t,u.m).then((function(t){e({type:c.o,payload:t.data.data})})).catch((function(t){e({type:c.n,payload:t})}))}}},777:function(t,e,n){"use strict";n.d(e,"f",(function(){return d})),n.d(e,"d",(function(){return l})),n.d(e,"e",(function(){return i})),n.d(e,"c",(function(){return s})),n.d(e,"a",(function(){return f})),n.d(e,"b",(function(){return p})),n.d(e,"g",(function(){return y})),n.d(e,"h",(function(){return m})),n.d(e,"i",(function(){return h})),n.d(e,"j",(function(){return g}));var a=n(700),r=n.n(a),c=n(26),u=n(6),o=u.d,d=function(t){return function(e,n){t.token=u.e,e({type:c.k}),r.a.get(o,{params:t},u.l).then((function(t){e({type:c.h,payload:t.data})})).catch((function(t){e({type:c.l,payload:t})}))}},l=function(t){return function(e,n){t.token=u.e,e({type:c.k}),r.a.get(o,{params:t},u.l).then((function(t){e({type:c.j,payload:t.data})})).catch((function(t){e({type:c.l,payload:t})}))}},i=function(t){return function(e,n){t.token=u.e,e({type:c.k}),r.a.get(o,{params:t},u.l).then((function(t){e({type:c.g,payload:t.data})})).catch((function(t){e({type:c.l,payload:t})}))}},s=function(t){return function(e,n){e({type:c.i,payload:t})}},f=function(t){return function(e,n){r.a.POST(o,{params:t},u.l).then((function(n){e({type:c.e,payload:t.id})})).catch((function(t){e({type:c.d,payload:t})}))}},p=function(t){return function(e,n){r.a.POST(o,{params:t},u.l).then((function(n){e({type:c.c,payload:t.id})})).catch((function(t){e({type:c.d,payload:t})}))}},y=function(t){return function(e){r.a.post(o,t,u.m).then((function(t){e({type:c.o,payload:t.data.data})})).catch((function(t){e({type:c.n,payload:t})}))}},m=function(t){return function(e){r.a.post(o,t,u.m).then((function(t){e({type:c.m,payload:t.data.data})})).catch((function(t){e({type:c.n,payload:t})}))}},h=function(t){return function(e,n){r.a.post(o,t,u.m).then((function(t){e({type:c.r,payload:t.data.data})})).catch((function(t){e({type:c.q,payload:t})}))}},g=function(t){return function(e,n){r.a.post(o,t,u.m).then((function(t){e({type:c.p,payload:t.data.data})})).catch((function(t){e({type:c.q,payload:t})}))}}}}]);
//# sourceMappingURL=39.20fb46ad.chunk.js.map