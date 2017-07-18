webpackJsonp([8],{117:function(t,e,a){a(600);var l=a(2)(a(567),a(591),null,null);t.exports=l.exports},567:function(t,e,a){"use strict";function l(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var r=a(7),o=(l(r),a(6)),n=l(o),s=a(0),u=l(s),i=a(8),m=a(5),c=l(m),d=a(4),b=l(d);u.default.use(n.default),e.default={data:function(){return{activeName:"getRulesLog",rulesLog:[],popover_img_src:"",total:0,limit:30,offset:0}},computed:(0,i.mapState)({user:function(t){return t.user}}),mounted:function(){this.getData()},methods:{getData:function(){var t={limit:this.limit,offset:this.offset};c.default.http(b.default.assetsGetData,t,this.then)},then:function(t,e){switch(e){case b.default.assetsGetData.code:this.rulesLog=t.data,this.total=parseInt(t.total)}},handleTabClick:function(t){var e=t.name,a={};"getRulesLog"==e&&c.default.http(b.default[e],a,this.then)},numberFormat:function(t,e){var a=arguments[1].columnKey;return c.default.numberFormat(t[a],2,"")},numberFormatPer:function(t,e){var a=arguments[1].columnKey;return c.default.numberFormat(t[a],2,"")+"%"},numberFormatInt:function(t,e){var a=arguments[1].columnKey;return c.default.numberFormat(t[a],0,"")},moneyFormat:function(t,e){var a=arguments[1].columnKey;return c.default.numberFormat(t[a])},CostperResult:function(t,e){if(0==t.websitepurchases)return"X";var a=t.amountspent/t.websitepurchases;return c.default.numberFormat(a)},getSummaries:function(t){var e=t.columns,a=t.data,l=[];return a.length<2?[]:(e.forEach(function(t,e){if(0!==e){if(1===e)return void(l[e]="共计("+a.length+")条");if(t.columnKey){var r=a.map(function(e){return Number(e[t.columnKey]?e[t.columnKey].toString().replace(/[\$,]+/g,""):e[t.columnKey])});r.every(function(t){return isNaN(t)})?l[e]="":(l[e]=r.reduce(function(t,e){var a=Number(e);return isNaN(a)?t:t+e},0),[2,5,7].indexOf(e)>-1?l[e]=c.default.numberFormat(l[e],0,""):[4,6].indexOf(e)>-1?l[e]=c.default.numberFormat(l[e]):l[e]="")}}}),l)},handleSizeChange:function(){console.log(arguments)},handleCurrentChange:function(t){this.offset=(t-1)*this.limit,this.getData()}}}},582:function(t,e,a){e=t.exports=a(1)(),e.push([t.i,".el-table__expanded-cell{padding-top:0;padding-bottom:0}.el-table .cell,.el-table th>div{padding-left:3px;padding-right:3px}.el-table th>.cell{overflow:hidden;height:30px}.val{color:#f33;display:inline-block;padding-right:20px}tth:hover div.cell{position:fixed;z-index:99;color:#33f;margin-top:-15px;left:15px}",""])},591:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("el-table",{staticStyle:{width:"100%"},attrs:{data:t.rulesLog,border:"","max-height":"700","default-sort":{prop:"amountspent",order:"descending"},"summary-method":t.getSummaries,"show-summary":""}},[a("el-table-column",{attrs:{type:"expand",fixed:""},scopedSlots:t._u([{key:"default",fn:function(e){return[a("span",[t._v("Updated Time:"),a("span",{staticClass:"val"},[t._v(t._s(e.row.updated_time))])]),t._v(" "),a("span",[t._v("Size:"),a("span",{staticClass:"val"},[t._v(t._s(e.row.original_width)+" x "+t._s(e.row.original_height))])]),t._v(" "),a("el-table",{staticStyle:{width:"100%"},attrs:{data:e.row.list,border:""}},[a("el-table-column",{attrs:{prop:"account_id",label:"Ad Account",width:"150"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.numberFormatInt,columnKey:"websiteaddstocart",label:"Website Adds to Cart",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.moneyFormat,columnKey:"costperwebsiteaddtocart",label:"Cost per Website Add to Cart",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.moneyFormat,columnKey:"amountspent",label:"Spent",width:"60"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.numberFormatInt,columnKey:"websitepurchases",label:"Website Purchases",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.moneyFormat,columnKey:"websitepurchasesconversionvalue",label:"Website Purchases Conversion Value",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.numberFormatInt,columnKey:"linkclicks",label:"Link Clicks",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.moneyFormat,columnKey:"cpc",label:"CPC (Cost per Link Click)",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.numberFormatPer,columnKey:"ctr",label:"CTR (Link Click-Through Rate)",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.moneyFormat,columnKey:"cpm1000",label:"CPM (Cost per 1,000 Impressions)",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.numberFormatInt,columnKey:"reach",label:"Reach",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.numberFormatInt,columnKey:"results",label:"Results",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.CostperResult,columnKey:"costperresult",label:"Cost per Result",width:"80"}})],1)]}}])}),t._v(" "),a("el-table-column",{attrs:{width:"200",label:"Name",showTooltipWhenOverflow:!0},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-popover",{attrs:{placement:"right",title:"",width:"400",trigger:"hover"}},[a("div",["1"==e.row.type?a("span",[t._v("Video:")]):a("span",[t._v("Image:")]),t._v(" "),a("span",[t._v("Updated Time:"),a("span",{staticClass:"val"},[t._v(t._s(e.row.updated_time))])]),t._v(" "),a("span",[t._v("Size:"),a("span",{staticClass:"val"},[t._v(t._s(e.row.original_width)+" x "+t._s(e.row.original_height))])]),t._v(" "),a("br"),t._v(" "),a("img",{attrs:{width:"400",src:e.row.permalink_url}})]),t._v(" "),a("a",{attrs:{href:e.row.url,target:"_blank"},slot:"reference"},[t._v(t._s(e.row.name))])])]}}])}),t._v(" "),a("el-table-column",{attrs:{formatter:t.numberFormatInt,columnKey:"websiteaddstocart",label:"Website Adds to Cart",width:"80",className:"autotooltip"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.moneyFormat,columnKey:"costperwebsiteaddtocart",label:"Cost per Website Add to Cart",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.moneyFormat,columnKey:"amountspent",label:"Spent",width:"80",sortable:""}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.numberFormatInt,columnKey:"websitepurchases",label:"Website Purchases",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.moneyFormat,columnKey:"websitepurchasesconversionvalue",label:"Website Purchases Conversion Value",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.numberFormatInt,columnKey:"linkclicks",label:"Link Clicks",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.moneyFormat,columnKey:"cpc",label:"CPC (Cost per Link Click)",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.numberFormatPer,columnKey:"ctr",label:"CTR (Link Click-Through Rate)",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.moneyFormat,columnKey:"cpm1000",label:"CPM (Cost per 1,000 Impressions)",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.numberFormatInt,columnKey:"reach",label:"Reach",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.numberFormatInt,columnKey:"results",label:"Results",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.CostperResult,columnKey:"costperresult",label:"Cost per Result",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.numberFormat,columnKey:"frequency",label:"Frequency",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{formatter:t.numberFormat,columnKey:"relevance_score",label:"Relevent Score",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{prop:"positive_feedback",label:"Positive Feedback",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{prop:"negative_feedback",label:"Negative Feedback",width:"80"}}),t._v(" "),a("el-table-column",{attrs:{label:"操作",width:"80",fixed:"right"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-button",{attrs:{type:"text",size:"small"},on:{click:function(a){t.openRulesDialog(e.row)}}},[t._v("\n                    编辑\n                ")])]}}])})],1),t._v(" "),a("el-pagination",{staticStyle:{margin:"20px auto",width:"300px"},attrs:{"page-size":t.limit,layout:"total, prev, pager, next",total:t.total},on:{"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}})],1)},staticRenderFns:[]}},600:function(t,e,a){var l=a(582);"string"==typeof l&&(l=[[t.i,l,""]]),l.locals&&(t.exports=l.locals);a(3)("024f632c",l,!0)}});
//# sourceMappingURL=8.build.js.map?3001a97fcfa57fdd3cbd