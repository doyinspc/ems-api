(this.webpackJsonpmesl=this.webpackJsonpmesl||[]).push([[42,41],{730:function(e,t,a){"use strict";a.d(t,"c",(function(){return c})),a.d(t,"b",(function(){return s})),a.d(t,"a",(function(){return m})),a.d(t,"d",(function(){return u})),a.d(t,"e",(function(){return p}));var n=a(700),l=a.n(n),r=a(51),i=a(6),o=i.b,c=function(e){return function(t,a){e.token=i.e,e.table="admissions",t({type:r.h}),l.a.get(o,{params:e},i.l).then((function(e){t({type:r.f,payload:e.data})})).catch((function(e){t({type:r.i,payload:e})}))}},s=function(e){return function(t,a){t({type:r.g,payload:e})}},m=function(e){return function(t,a){l.a.POST(o,{params:e},i.l).then((function(a){t({type:r.d,payload:e.id})})).catch((function(e){t({type:r.c,payload:e})}))}},u=function(e){return function(t){l.a.post(o,e,i.m).then((function(e){t({type:r.k,payload:e.data.data})})).catch((function(e){t({type:r.j,payload:e})}))}},p=function(e){return function(t,a){l.a.post(o,e,i.m).then((function(e){t({type:r.m,payload:e.data.data})})).catch((function(e){t({type:r.l,payload:e})}))}}},753:function(e,t,a){"use strict";a.d(t,"a",(function(){return o}));var n=a(3),l=a.n(n),r=a(217),i=a(697),o=function(e){return console.log(e.user),l.a.createElement(i.wb,{xs:12,style:{backgroundColor:"white",height:"100%"}},l.a.createElement(i.u,{xs:"3",style:{marginTop:"2px",marginBottom:"2px"}},l.a.createElement("img",{src:"/ems/avatars/logo3.png",className:"m-0 p-0",width:"150px",height:"100px",alt:"admission",onError:function(e){e.target.onerror=null,e.target.src="avatars/1.png"}})),l.a.createElement(i.u,{xs:"8",style:{marginTop:"1px",marginBottom:"4px"}},l.a.createElement("div",{className:"my-1"},l.a.createElement("h3",{className:"pull-left"},l.a.createElement("b",null,"MESL Staff School Kainji & Jebba Hydro Power Plant",l.a.createElement("br",null),l.a.createElement("small",null,"07035992972 (Jebba) 07035839707 (Kainji)"))))))};Object(r.b)((function(e){return{user:e.userReducer.activeschool}}),{})(o)},868:function(e,t,a){"use strict";a.r(t);var n=a(3),l=a.n(n),r=a(217),i=a(702),o=a.n(i),c=a(697),s=a(24),m=a(730),u=a(753);t.default=Object(r.b)((function(e){return{admission:e.admissionReducer.admission}}),{getAdmission:m.b})((function(e){var t=Object(s.i)().admit;Object(n.useEffect)((function(){e.getAdmission(t)}),[t]);var a=e.admission||{},r=a.id,i=a.abbrv,m=a.surname,p=a.firstname,d=a.middlename,f=a.cclass,g=(a.schoolid,a.schoolname),h=a.session,E=a.address,y=a.status,b=a.signed;return l.a.createElement("div",{className:"m-0 p-0 container-fluid",style:{margin:"0px",padding:"0px",height:"859px"}},l.a.createElement("br",null),l.a.createElement("br",null),l.a.createElement("div",{style:{marginLeft:"auto",marginRight:"auto",marginBottom:"5px",backgroundColor:"white",height:"859px"}},l.a.createElement(c.w,{style:{height:"859px"}},l.a.createElement(u.a,null),l.a.createElement(c.wb,{xs:12},l.a.createElement(c.u,{className:"m-0 p-1",xs:12},l.a.createElement("br",null),l.a.createElement("div",{className:"headBar pull-right",style:{fontSize:"20px",marginTop:"10px",marginBotom:"10px"}},l.a.createElement("strong",null," REF:",i,h,"/",r),l.a.createElement("br",null),l.a.createElement("br",null),o()(new Date).format("MMMM DD, YYYY")),l.a.createElement("br",null),l.a.createElement("br",null),l.a.createElement("div",{className:"headBar",style:{marginTop:"80px",marginBotTom:"10px",fontSize:"25px",textAlign:"left",lineHeight:"100%"}},l.a.createElement("strong",null,"".concat(m," ").concat(p," ").concat(d)),l.a.createElement("br",null),E),l.a.createElement("div",{className:"addressBar",style:{marginTop:"100px",marginBottom:"50px"}},l.a.createElement("p",{className:"h1"},l.a.createElement("u",null,"Admission Letter"))),l.a.createElement("div",{className:"titleBar"},l.a.createElement("p",{style:{marginTop:"20px",fontSize:"25px",textAlign:"justify",lineHeight:"200%"}},"Following your performance at our ",l.a.createElement("strong",null,h)," Academic Session entrance examination and interveiw exercise, we are pleased to inform you that you have been offered ",l.a.createElement("strong",null,y)," into ",l.a.createElement("strong",null,f)," class at",l.a.createElement("strong",null," ",g)," ."),l.a.createElement("p",{style:{marginTop:"25px",fontSize:"25px",textAlign:"justify",lineHeight:"200%"}},"Attached to this letter you will find a full admission package along with specific details on how you can accept this offer. We ask that you respond to this offer within two (2) weeks effective from the date of issuance of this letter as indicated above. Failure to do so will result in the immediate withdrawal of this offer."),l.a.createElement("p",{style:{marginTop:"25px",fontSize:"25px",textAlign:"justify",lineHeight:"200%"}},"Once again, congratulations. We hope to hear from you soon.")),l.a.createElement("br",null),l.a.createElement("br",null),l.a.createElement("div",{className:"footerBar",style:{marginTop:"25px",fontSize:"25px",textAlign:"justify",lineHeight:"200%"}},l.a.createElement(c.wb,null,l.a.createElement(c.u,null,"Yours Sincerely,",l.a.createElement("br",null),l.a.createElement("br",null))),l.a.createElement(c.wb,null,l.a.createElement(c.u,null,l.a.createElement("strong",null,b),l.a.createElement("br",null),"for : School Management"))))))))}))}}]);
//# sourceMappingURL=42.b424cd5d.chunk.js.map