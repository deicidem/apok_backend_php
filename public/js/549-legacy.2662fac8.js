"use strict";(self["webpackChunkapp_2"]=self["webpackChunkapp_2"]||[]).push([[549],{684:function(e,t,s){s.r(t),s.d(t,{default:function(){return S}});var r=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"alert"},[s("h2",{staticClass:"sidebar-title"},[e._v("Мои уведомления")]),s("vuescroll",{attrs:{ops:e.ops}},[s("div",{staticClass:"alert-wrapper"},e._l(e.alerts,(function(t,r){return s("app-alert-card",{key:t.id,attrs:{seen:t.seen,result:t.result,text:t.text,theme:t.theme},on:{delete:function(t){return e.deleteAlert(r)}}})})),1)])],1)},n=[],l=s(3019),a=s(8495),i=s.n(a),c=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"alert-item"},[r("div",{directives:[{name:"show",rawName:"v-show",value:!e.seen,expression:"!seen"}],staticClass:"alert-item__unread unread",class:[e.theme]}),r("div",{staticClass:"alert-item__content"},[r("div",{staticClass:"alert-item__img",class:[e.theme],on:{click:function(t){return e.getTheme(e.theme)}}},[r("img",{attrs:{"inline-svg":"",src:s(5889)}})]),r("div",{staticClass:"alert-item__info"},[r("h2",[e._v(e._s(e.text))]),r("p",[e._v("Посмотреть результат")])])]),r("button",{staticClass:"button button-svg",on:{click:function(t){return e.$emit("delete")}}},[r("img",{attrs:{"inline-svg":"",src:s(5588)}})])])},o=[],u=s(476),p={props:{text:String,icon:String,seen:Boolean,result:String,theme:String},methods:{getTheme:function(e){u.log(e)}}},d=p,m=s(1001),v=(0,m.Z)(d,c,o,!1,null,"33ebfd80",null),g=v.exports,f=s(4665),h={computed:(0,l.Z)({},(0,f.Se)("alerts",{alerts:"getAlerts"})),methods:(0,l.Z)({},(0,f.nv)("alerts",["deleteAlert"])),data:function(){return{ops:{vuescroll:{mode:"native",sizeStrategy:"percent",detectResize:!0,wheelScrollDuration:500},scrollPanel:{scrollingX:!1,speed:300,easing:"easeOutQuad"},rail:{background:"#000",opacity:.1,size:"6px",specifyBorderRadius:!1,gutterOfEnds:null,gutterOfSide:"2px",keepShow:!1},bar:{onlyShowBarOnScroll:!1,keepShow:!0,background:"#476D70"}}}},components:{vuescroll:i(),AppAlertCard:g}},_=h,C=(0,m.Z)(_,r,n,!1,null,"0e38f01a",null),S=C.exports},5889:function(e,t,s){e.exports=s.p+"img/envelope.2cd00957.svg"},5588:function(e,t,s){e.exports=s.p+"img/trash.2eccf689.svg"}}]);
//# sourceMappingURL=549-legacy.2662fac8.js.map