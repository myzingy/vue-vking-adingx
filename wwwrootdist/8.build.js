webpackJsonp([8],{106:function(t,e,a){"use strict";function l(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var r=a(10),o=(l(r),a(9)),n=l(o),s=a(0),i=l(s),u=a(11),c=a(8),d=l(c),p=a(5),m=l(p),b=a(80),h=l(b);i.default.use(n.default),e.default={components:{"v-ad_table":h.default},data:function(){return{activeName:"getRulesLog",rulesLog:[],total:0,formSearch:{limit:30,offset:0}}},computed:(0,u.mapState)({user:function(t){return t.user}}),mounted:function(){this.getData()},methods:{getData:function(){d.default.http(m.default.getRulesLog,this.formSearch,this.then)},then:function(t,e){switch(e){case m.default.getRulesLog.code:for(var a in t.data)t.data[a].expandTabData=[JSON.parse(t.data[a].target_data)],t.data[a].expandTabDataType=t.data[a].target.toLowerCase();this.rulesLog=t.data,this.total=t.total}},handleTabClick:function(t){},formatExecTarget:function(t,e){return"["+t.target+"]"+t.target_id},formatExecRule:function(t){return"["+t.rule_id+"]"+t.rule_name},handleSizeChange:function(){console.log(arguments)},handleCurrentChange:function(t){this.formSearch.offset=(t-1)*this.formSearch.limit,this.getData()}}}},116:function(t,e,a){e=t.exports=a(1)(),e.push([t.i,".el-table__expanded-cell{padding-top:0;padding-bottom:0}.el-table .cell,.el-table th>div{padding-left:3px;padding-right:3px}.el-table th>.cell{overflow:hidden;height:30px}",""])},159:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"mytable"},[a("v-headerTop"),t._v(" "),a("el-col",{staticStyle:{height:"100%"},attrs:{span:4}},[a("div",{staticClass:"grid-left bg-purple-darkc overflow-y",attrs:{id:"app_left_menu"}},[a("v-leftMenu")],1)]),t._v(" "),a("el-col",{staticStyle:{height:"100%"},attrs:{span:20}},[a("div",[a("el-tabs",{on:{"tab-click":t.handleTabClick},model:{value:t.activeName,callback:function(e){t.activeName=e},expression:"activeName"}},[a("el-tab-pane",{attrs:{label:"优化记录",name:"getRulesLog"}},[a("el-table",{staticStyle:{width:"100%"},attrs:{data:t.rulesLog,border:"","max-height":"700"}},[a("el-table-column",{attrs:{type:"expand",fixed:""},scopedSlots:t._u([{key:"default",fn:function(t){return[a("v-ad_table",{attrs:{adsData:t.row.expandTabData,dataType:t.row.expandTabDataType,rulesLog:"rulesLog"}})]}}])}),t._v(" "),a("el-table-column",{attrs:{formatter:t.formatExecTarget,label:"执行目标",width:"180"}}),t._v(" "),a("el-table-column",{attrs:{prop:"time_format",label:"执行时间",width:"120"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.formatExecRule,label:"执行规则"}}),t._v(" "),a("el-table-column",{attrs:{prop:"rule_exec",label:"执行结果"}})],1),t._v(" "),a("el-pagination",{staticStyle:{margin:"20px auto",width:"300px"},attrs:{"page-size":t.formSearch.limit,layout:"total, prev, pager, next",total:t.total},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)],1)],1)])],1)},staticRenderFns:[]}},182:function(t,e,a){var l=a(116);"string"==typeof l&&(l=[[t.i,l,""]]),l.locals&&(t.exports=l.locals);a(4)("1b7ae6dc",l,!0)},70:function(t,e,a){a(182);var l=a(2)(a(106),a(159),null,null);t.exports=l.exports},76:function(t,e,a){"use strict";function l(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var r=a(10),o=(l(r),a(9)),n=l(o),s=a(0),i=l(s),u=a(8),c=(l(u),a(5));l(c);i.default.use(n.default),e.default={data:function(){return{}},props:["adsData","dataType","rulesLog"],methods:{roias:function(t,e){var a=parseFloat(e.WebsitePurchasesConversionValue.replace(/[\$,]+/g,"")),l=e.AmountSpent;if("ROI"==t){var r=a/(0==l?1:l);return r.toFixed(1)}if(0==a)return"X%";var r=l/a;return(100*r).toFixed(2)+"%"},formatROI:function(t){return this.roias("ROI",t)},formatROAS:function(t){return this.roias("ROAS",t)},formatAmountSpent:function(t,e){return"$"+t.AmountSpent},formatChildDate:function(t){return 7==t.Type?"last 7 day":14==t.Type?"last 14 day":t.Date},openRulesDialog:function(t){this.$emit("openRulesDialog",t)},searchThatID:function(t){this.$emit("searchThatID",t,this.dataType)},getSummaries:function(t){var e=t.columns,a=t.data,l=[];return a.length<2?[]:(e.forEach(function(t,e){if(0!==e){if(2===e)return void(l[e]="共计("+a.length+")条");var r=a.map(function(e){return Number(e[t.property]?e[t.property].toString().replace(/[\$,]+/g,""):e[t.property])});r.every(function(t){return isNaN(t)})?l[e]="":(l[e]=r.reduce(function(t,e){var a=Number(e);return isNaN(a)?t:t+e},0),"5"!=e&&"6"!=e&&"8"!=e&&"10"!=e&&"12"!=e&&"15"!=e&&"16"!=e||(l[e]="$"+l[e].toFixed(2)))}}),setTimeout(function(){var t=document.getElementsByClassName("el-table__fixed"),e=document.getElementsByClassName("el-table__fixed-right");for(var a in t)void 0!==t[a]&&void 0!==t[a].style&&(t[a].style.bottom=0),void 0!==e[a]&&void 0!==e[a].style&&(e[a].style.bottom=0)},1e3),l)}},mounted:function(){}}},78:function(t,e,a){e=t.exports=a(1)(),e.push([t.i,"",""])},80:function(t,e,a){a(84);var l=a(2)(a(76),a(82),null,null);t.exports=l.exports},82:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("el-table",{staticStyle:{width:"100%"},attrs:{data:t.adsData,border:"","max-height":"700","default-sort":{prop:"AmountSpent",order:"descending"},"summary-method":t.getSummaries,"show-summary":""}},[a("el-table-column",{attrs:{type:"expand",fixed:""},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-table",{staticStyle:{width:"100%"},attrs:{data:e.row.List,border:""}},[a("el-table-column",{attrs:{formatter:t.formatChildDate,label:"日期",width:"150"}}),t._v(" "),"rulesLog"==t.rulesLog?[a("el-table-column",{attrs:{label:"ROI",width:"40",formatter:t.formatROI}}),t._v(" "),a("el-table-column",{attrs:{label:"ROAS",width:"60",formatter:t.formatROAS}})]:t._e(),t._v(" "),a("el-table-column",{attrs:{prop:"WebsiteAddstoCart",label:"Website Adds to Cart",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{prop:"CostperWebsiteAddtoCart",label:"Cost per Website Add to Cart",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.formatAmountSpent,prop:"AmountSpent",label:"Amount Spent",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{prop:"WebsitePurchases",label:"Website Purchases",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{prop:"WebsitePurchasesConversionValue",label:"Website Purchases Conversion Value",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{prop:"LinkClicks",label:"Link Clicks",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{prop:"CPC",label:"CPC (Cost per Link Click)",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{prop:"CTR",label:"CTR (Link Click-Through Rate)",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{prop:"CPM1000",label:"CPM (Cost per 1,000 Impressions)",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{prop:"Reach",label:"Reach",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{prop:"Results",label:"Results",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{prop:"CostperResult",label:"Cost per Result",width:"80"}})],2)]}}])}),t._v(" "),a("el-table-column",{attrs:{fixed:"",prop:"Date",label:"Date",width:"100"}}),t._v(" "),a("el-table-column",{attrs:{fixed:"",prop:"Name",label:"Name",width:"200"},scopedSlots:t._u([{key:"default",fn:function(e){return["campaign"!=t.dataType?[a("el-popover",{attrs:{trigger:"hover",placement:"top"}},[a("p",[t._v("Campaign Name: "+t._s(e.row.CampaignName))]),t._v(" "),a("p",[t._v("Adset Name: "+t._s(e.row.AdsetName))]),t._v(" "),"ad"==t.dataType?[a("p",[t._v("Ad Name: "+t._s(e.row.AdName))])]:t._e(),t._v(" "),a("div",{staticClass:"name-wrapper",slot:"reference"},[a("a",{attrs:{href:"javascript://"},on:{click:function(a){t.searchThatID(e.row)}}},[t._v(t._s(e.row.Name))])])],2)]:[a("a",{attrs:{href:"javascript://"},on:{click:function(a){t.searchThatID(e.row)}}},[t._v(t._s(e.row.Name))])]]}}])}),t._v(" "),"rulesLog"==t.rulesLog?[a("el-table-column",{attrs:{label:"ROI",width:"40",formatter:t.formatROI}}),t._v(" "),a("el-table-column",{attrs:{label:"ROAS",width:"60",formatter:t.formatROAS}})]:[a("el-table-column",{attrs:{prop:"Delivery",label:"Delivery",width:"60"}})],t._v(" "),a("el-table-column",{attrs:{prop:"WebsiteAddstoCart",label:"Website Adds to Cart",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{prop:"CostperWebsiteAddtoCart",label:"Cost per Website Add to Cart",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.formatAmountSpent,prop:"AmountSpent",label:"Amount Spent",width:"140",sortable:""}}),t._v(" "),a("el-table-column",{attrs:{prop:"WebsitePurchases",label:"Website Purchases",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{prop:"WebsitePurchasesConversionValue",label:"Website Purchases Conversion Value",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{prop:"LinkClicks",label:"Link Clicks",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{prop:"CPC",label:"CPC (Cost per Link Click)",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{prop:"CTR",label:"CTR (Link Click-Through Rate)",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{prop:"CPM1000",label:"CPM (Cost per 1,000 Impressions)",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{prop:"Reach",label:"Reach",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{prop:"Results",label:"Results",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{prop:"CostperResult",label:"Cost per Result",width:"80"}}),t._v(" "),"ad"==t.dataType?[a("el-table-column",{attrs:{prop:"RelevanceScore",label:"Relevance Score",width:"80"}})]:t._e(),t._v(" "),"adset"==t.dataType?[a("el-table-column",{attrs:{prop:"Budget",label:"Budget",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{prop:"Schedule",label:"Schedule",width:"80"}})]:t._e(),t._v(" "),"campaign"==t.dataType?[a("el-table-column",{attrs:{prop:"Ends",label:"Ends",width:"80"}})]:t._e(),t._v(" "),"campaign"==t.dataType?[a("el-table-column",{attrs:{label:"操作",width:"80",fixed:"right"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-button",{attrs:{type:"text",size:"small"},on:{click:function(a){t.openRulesDialog(e.row)}}},[t._v("\n\t\t\t\t\t规则\n\t\t\t\t")])]}}])})]:t._e()],2)],1)},staticRenderFns:[]}},84:function(t,e,a){var l=a(78);"string"==typeof l&&(l=[[t.i,l,""]]),l.locals&&(t.exports=l.locals);a(4)("18dfb494",l,!0)}});
//# sourceMappingURL=8.build.js.map?e661ed1e4bd31b595717