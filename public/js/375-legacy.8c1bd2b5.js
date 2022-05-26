"use strict";(self["webpackChunkapp_2"]=self["webpackChunkapp_2"]||[]).push([[375],{8955:function(t,e,a){a.d(e,{Z:function(){return c}});var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("table",[t._t("default")],2)},r=[],i={props:{data:Array,headers:Array}},n=i,o=a(1001),l=(0,o.Z)(n,s,r,!1,null,"dc939534",null),c=l.exports},7375:function(t,e,a){a.r(e),a.d(e,{default:function(){return v}});var s=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"results"},[s("h2",{staticClass:"sidebar-title"},[t._v(" Результаты поиска: "+t._s(t.results.length)+" найдено ")]),s("div",{staticClass:"results-content"},[s("router-link",{attrs:{to:"/main/search"}},[s("div",{staticClass:"back"},[s("div",{staticClass:"back-arrow"},[s("img",{attrs:{src:a(5628)}})]),s("p",{staticClass:"back-subtitle"},[t._v("Назад")])])]),s("portal",{attrs:{to:"popup-card"}},[null!=t.card.ind?s("div",{directives:[{name:"show",rawName:"v-show",value:t.card.active,expression:"card.active"}],staticClass:"card"},[s("div",{staticClass:"card-close",on:{click:function(e){return t.onCardClose()}}},[s("svg",{attrs:{width:"12",height:"12",viewBox:"0 0 12 12",fill:"none",xmlns:"http://www.w3.org/2000/svg","svg-inline":"",role:"presentation",focusable:"false",tabindex:"-1"}},[s("path",{attrs:{d:"M11.625 1.982L10.018.375 6 4.393 1.982.375.375 1.982 4.393 6 .375 10.018l1.607 1.607L6 7.607l4.018 4.018 1.607-1.607L7.607 6l4.018-4.018z",fill:"#476D70"}})])]),s("div",{staticClass:"card-title"},[t._v("Информация по объекту")]),s("div",{staticClass:"card-img"},[s("img",{attrs:{src:t.cardData.previewPath,alt:""}})]),s("div",{staticClass:"card-table__wrapper"},[s("table",{staticClass:"card-table"},[s("thead",[s("tr",[s("th",[t._v("Характеристика")]),s("th",[t._v("Значение")])])]),s("tbody",[s("tr",[s("td",[t._v("Идентификатор")]),s("td",[t._v(t._s(t.cardData.name))])]),s("tr",[s("td",[t._v("Виток")]),s("td",[t._v(t._s(t.cardData.round))])]),s("tr",[s("td",[t._v("Маршрут")]),s("td",[t._v(t._s(t.cardData.route))])]),s("tr",[s("td",[t._v("Дата съемки")]),s("td",[t._v(t._s(t.cardData.date))])]),s("tr",[s("td",[t._v("Облачность")]),s("td",[t._v(t._s(t.cardData.cloudiness))])])])])]),s("div",{staticClass:"card-buttons"},[s("button",{staticClass:"button button-g card-button",on:{click:function(e){return t.onPolygonButtonClick(t.card.ind,t.cardData.id,t.cardData.geography)}}},[t._v(" Скрыть контур ")]),s("button",{staticClass:"button button-white card-button",attrs:{type:"white"},on:{click:function(e){return t.onImageButtonClick(t.card.ind,t.cardData.id,t.cardData.previewPath,t.cardData.geography.bbox)}}},[t._v(" Показать изображение ")])])]):t._e()]),s("div",{staticClass:"results-wrapper"},[s("app-table",{staticClass:"results-table"},[s("thead",[s("tr",[s("th"),s("th",[t._v("Идентификатор")]),s("th",[t._v("Виток")]),s("th",[t._v("Маршрут")]),s("th",[t._v("Аппарат")]),s("th",[t._v("Дата съемки")]),s("th",[t._v("Облачность")]),s("th")])]),t.loaded?s("tbody",t._l(t.results,(function(e,a){return s("tr",{key:a},[s("td",{staticClass:"results-table__buttons"},[s("button",{staticClass:"button button-white button-small",class:t.results[a].polygonActive?"active":"",on:{click:function(s){return t.onPolygonButtonClick(a,e.id,e.geography)}}},[s("svg",{class:"icon icon-vector-o",attrs:{width:"18",height:"18",viewBox:"0 0 18 18",fill:"none",xmlns:"http://www.w3.org/2000/svg","svg-inline":"",role:"presentation",focusable:"false",tabindex:"-1"}},[s("rect",{attrs:{x:"2.76",y:"2.76",width:"8.64",height:"8.515",rx:"2",stroke:"#476D70"}}),s("path",{attrs:{d:"M6.6 12.168v.892a2 2 0 002 2h4.64a2 2 0 002-2V8.545a2 2 0 00-2-2h-.88",stroke:"#476D70"}})])]),s("button",{staticClass:"button button-white button-small",class:t.results[a].imageActive?"active":"",on:{click:function(s){return t.onImageButtonClick(a,e.id,e.previewPath,null==e.geography?null:e.geography.bbox)}}},[s("svg",{class:"icon icon-img",attrs:{width:"14",height:"14",viewBox:"0 0 14 14",fill:"none",xmlns:"http://www.w3.org/2000/svg","svg-inline":"",role:"presentation",focusable:"false",tabindex:"-1"}},[s("path",{attrs:{d:"M1.167 3.5A2.333 2.333 0 013.5 1.167h7A2.333 2.333 0 0112.834 3.5v7a2.333 2.333 0 01-2.334 2.334h-7A2.333 2.333 0 011.167 10.5v-7z",stroke:"#476D70","stroke-linecap":"round","stroke-linejoin":"round"}}),s("path",{attrs:{d:"M4.958 6.417a1.458 1.458 0 100-2.917 1.458 1.458 0 000 2.917zM8.473 7.362L3.5 12.833h7.078a2.256 2.256 0 002.255-2.255V10.5c0-.272-.102-.376-.286-.577l-2.35-2.564a1.167 1.167 0 00-1.723.003v0z",stroke:"#476D70","stroke-linecap":"round","stroke-linejoin":"round"}})])])]),s("td",[t._v(t._s(e.name))]),s("td",[t._v(t._s(e.round))]),s("td",[t._v(t._s(e.route))]),s("td",[t._v("Аппарат")]),s("td",[t._v(t._s(e.date))]),s("td",[t._v(t._s(e.cloudiness))]),s("td",{staticClass:"results-table__buttons"},[s("button",{staticClass:"button button-white button-small",class:t.results[a].cardActive?"active":"",on:{click:function(e){return t.onCardButtonClick(a)}}},[s("svg",{class:"icon icon-open",attrs:{width:"16",height:"16",viewBox:"0 0 16 16",fill:"none",xmlns:"http://www.w3.org/2000/svg","svg-inline":"",role:"presentation",focusable:"false",tabindex:"-1"}},[s("path",{attrs:{d:"M4.5 3A1.5 1.5 0 003 4.5v7A1.5 1.5 0 004.5 13h7a1.5 1.5 0 001.5-1.5V9.27a.5.5 0 011 0v2.23a2.5 2.5 0 01-2.5 2.5h-7A2.5 2.5 0 012 11.5v-7A2.5 2.5 0 014.5 2h2.23a.5.5 0 110 1H4.5zm4.27-.5a.5.5 0 01.5-.5h4.23a.5.5 0 01.5.5v4.23a.5.5 0 01-1 0V3.708L9.623 7.084a.5.5 0 11-.707-.707L12.293 3H9.269a.5.5 0 01-.5-.5h.001z",fill:"#476D70"}})])])])])})),0):t._e()])],1)],1)])},r=[],i=a(3019),n=a(4665),o=a(8955),l={components:{AppTable:o.Z},data:function(){return{loaded:!1,buttons:[],card:{active:!1,ind:null}}},computed:(0,i.Z)((0,i.Z)({},(0,n.Se)("results",{results:"getResults"})),{},{cardData:function(){return null!=this.card.ind?this.results[this.card.ind]:null}}),methods:(0,i.Z)((0,i.Z)((0,i.Z)({},(0,n.nv)("map",["addGeoJsonPolygon","removeGeoJsonPolygon","addImage","removeImage"])),(0,n.nv)("results",["setResultProperty"])),{},{onPolygonButtonClick:function(t,e,a){this.results[t].polygonActive?(this.removeGeoJsonPolygon(e),this.setResultProperty({index:t,property:"polygonActive",value:!1})):(this.addGeoJsonPolygon({id:e,json:a}),this.setResultProperty({index:t,property:"polygonActive",value:!0}))},onImageButtonClick:function(t,e,a,s){this.results[t].imageActive?(this.removeImage(e),this.setResultProperty({index:t,property:"imageActive",value:!1})):(this.addImage({id:e,img:a,bounds:s}),this.setResultProperty({index:t,property:"imageActive",value:!0}))},onCardButtonClick:function(t){if(this.results[t].cardActive)this.card.active=!1,this.setResultProperty({index:t,property:"cardActive",value:!1});else{this.card.ind=t;for(var e=0;e<this.results.length;e++)this.setResultProperty({index:e,property:"cardActive",value:!1});this.setResultProperty({index:t,property:"cardActive",value:!0}),this.card.active=!0,this.card.data=(0,i.Z)({},this.results[t])}},onCardClose:function(){for(var t=0;t<this.results.length;t++)this.setResultProperty({index:t,property:"cardActive",value:!1});this.card.active=!1}}),created:function(){this.loaded=!0}},c=l,d=a(1001),u=(0,d.Z)(c,s,r,!1,null,null,null),v=u.exports},5628:function(t,e,a){t.exports=a.p+"img/arrow.9947a5be.svg"}}]);
//# sourceMappingURL=375-legacy.8c1bd2b5.js.map