webpackJsonp([4],{110:function(e,t,a){"use strict";function l(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var s=a(10),o=(l(s),a(9)),r=l(o),i=a(0),n=l(i),c=a(11),d=a(8),u=l(d),p=a(5),h=l(p);n.default.use(r.default),t.default={data:function(){return{rulesData:[],multipleSelection:[],form:{date:"10:00"}}},computed:(0,c.mapState)({user:function(e){return e.user}}),mounted:function(){var e={status:0};u.default.http(h.default.getRulesData,e,this.then)},methods:{init:function(e,t){this.multipleSelection=[],this.RulesChecked=e,this.form={date:t},console.log("this.form",t,this.form.date),this.toggleSelection()},then:function(e,t){switch(t){case h.default.getRulesData.code:this.rulesData=e.data,this.toggleSelection();break;case h.default.saveRulesForAd.code:u.default.toast("操作成功","msg")}},toggleSelection:function(){var e=this;this.$refs.multipleTable.clearSelection(),this.RulesChecked.length>0&&setTimeout(function(){e.rulesData.forEach(function(t){for(var a in e.RulesChecked)e.RulesChecked[a]==t.id&&e.$refs.multipleTable.toggleRowSelection(t)})},0)},handleSelectionChange:function(e){this.multipleSelection=e},saveRulesForAd:function(e,t){var a=this;this.form.target=t,this.form.target_id=e,this.form.rules_ids=[],console.log(this.multipleSelection),this.multipleSelection.forEach(function(e){a.form.rules_ids.push(e.id)}),u.default.http(h.default.saveRulesForAd,this.form,this.then)}}}},125:function(e,t,a){t=e.exports=a(1)(),t.push([e.i,".el-table__expanded-cell{padding-top:0;padding-bottom:0}.el-table .cell,.el-table th>div{padding-left:3px;padding-right:3px}.el-table th>.cell{overflow:hidden;height:30px}.el-tabs--card>.el-tabs__header .el-tabs__item .el-icon-close{position:relative;font-size:16px;width:auto;height:auto;vertical-align:middle;line-height:100%;overflow:hidden;top:0;right:0;transform-origin:50% 50%}",""])},132:function(e,t,a){t=e.exports=a(1)(),t.push([e.i,".el-table__expanded-cell{padding-top:0;padding-bottom:0}.el-table .cell,.el-table th>div{padding-left:3px;padding-right:3px}.el-table th>.cell{overflow:hidden;height:30px}",""])},161:function(e,t,a){a(200);var l=a(2)(a(110),a(176),null,null);e.exports=l.exports},169:function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"mytable"},[a("v-headerTop"),e._v(" "),a("el-col",{staticStyle:{height:"100%"},attrs:{span:4}},[a("div",{staticClass:"grid-left bg-purple-darkc overflow-y",attrs:{id:"app_left_menu"}},[a("v-leftMenu")],1)]),e._v(" "),a("el-col",{staticStyle:{height:"100%"},attrs:{span:20}},[a("div",{staticStyle:{padding:"10px"}},[a("el-form",{staticClass:"demo-form-inline",attrs:{inline:!0,model:e.formSearch}},[a("el-form-item",{attrs:{label:"类型"}},[a("el-select",{attrs:{placeholder:"请选择"},model:{value:e.formSearch.keyword_type,callback:function(t){e.formSearch.keyword_type=t},expression:"formSearch.keyword_type"}},[a("el-option",{attrs:{label:"系列 ID/名称",value:"campaign"}}),e._v(" "),a("el-option",{attrs:{label:"组 ID/名称",value:"adset"}}),e._v(" "),a("el-option",{attrs:{label:"广告 ID/名称",value:"ad"}})],1)],1),e._v(" "),a("el-form-item",[a("el-input",{attrs:{placeholder:"关键字（支持模糊查询）"},model:{value:e.formSearch.keyword,callback:function(t){e.formSearch.keyword=t},expression:"formSearch.keyword"}})],1),e._v(" "),a("el-form-item",[a("el-button",{attrs:{type:"primary"},on:{click:e.onFormSearch}},[e._v("查询")]),e._v(" "),a("a",{attrs:{href:"javascript://"},on:{click:e.onClearFormSearch}},[e._v("清空条件")])],1)],1),e._v(" "),a("el-tabs",{attrs:{type:"card"},on:{"tab-click":e.handleTabClick},model:{value:e.activeName,callback:function(t){e.activeName=t},expression:"activeName"}},[a("el-tab-pane",{attrs:{name:"getCampaignsData"}},[a("span",{slot:"label"},[e._v("广告系列\n\t\t\t\t\t\t"),a("el-tag",{directives:[{name:"show",rawName:"v-show",value:e.getTabName("checked_campaigns"),expression:"getTabName('checked_campaigns')"}],attrs:{closable:!0},on:{close:function(t){e.clearTabName("checked_campaigns")}}},[e._v(e._s(e.getTabName("checked_campaigns")))])],1),e._v(" "),a("v-ad_table",{attrs:{adsData:e.campaignsData,dataType:"campaign"},on:{searchThatID:e.searchThatID,openRulesDialog:e.openRulesDialog}})],1),e._v(" "),a("el-tab-pane",{attrs:{name:"getAdsetsData"}},[a("span",{slot:"label"},[e._v("广告组\n\t\t\t\t\t\t"),a("el-tag",{directives:[{name:"show",rawName:"v-show",value:e.getTabName("checked_adsets"),expression:"getTabName('checked_adsets')"}],attrs:{closable:!0},on:{close:function(t){e.clearTabName("checked_adsets")}}},[e._v(e._s(e.getTabName("checked_adsets")))])],1),e._v(" "),a("v-ad_table",{attrs:{adsData:e.adsetsData,dataType:"adset"},on:{openRulesDialog:e.openRulesDialog,searchThatID:e.searchThatID}})],1),e._v(" "),a("el-tab-pane",{attrs:{label:"广告",name:"getAdsData"}},[a("v-ad_table",{attrs:{adsData:e.adsData,dataType:"ad"},on:{openRulesDialog:e.openRulesDialog}})],1)],1),e._v(" "),a("div",{attrs:{id:"dialogRules"}},[a("el-dialog",{ref:"refDialog",attrs:{title:"规则列表",visible:e.dialogTableVisible,"close-on-click-modal":!1,"close-on-press-escape":!1},on:{"update:visible":function(t){e.dialogTableVisible=t},close:e.dialogClose,open:e.dialogOpen}},[a("v-rules_list",{ref:"refRules"}),e._v(" "),a("span",{staticClass:"dialog-footer",slot:"footer"},[a("el-button",{on:{click:e.dialogClose}},[e._v("取 消")]),e._v(" "),a("el-button",{attrs:{type:"primary"},on:{click:e.saveRulesForAd}},[e._v("确 定")])],1)],1)],1)],1)])],1)},staticRenderFns:[]}},176:function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("el-table",{ref:"multipleTable",staticStyle:{width:"100%"},attrs:{data:e.rulesData,border:"","max-height":"450"},on:{"selection-change":e.handleSelectionChange}},[a("el-table-column",{attrs:{type:"selection",width:"55"}}),e._v(" "),a("el-table-column",{attrs:{prop:"id",label:"ID",width:"60"}}),e._v(" "),a("el-table-column",{attrs:{prop:"name",label:"规则名称"}}),e._v(" "),a("el-table-column",{attrs:{prop:"type",label:"规则大小",width:"80"},scopedSlots:e._u([{key:"default",fn:function(t){return[2==t.row.type?[e._v("\n\t\t\t\t\t大型片段\n\t\t\t\t")]:1==t.row.type?[e._v("\n\t\t\t\t\t小型片段\n\t\t\t\t")]:[e._v("\n\t\t\t\t\t简单规则\n\t\t\t\t")]]}}])})],1),e._v(" "),a("br"),e._v(" "),a("el-form",{ref:"form",attrs:{model:e.form,"label-width":"80px"}},[a("el-form-item",{attrs:{label:"执行时间"}},[a("el-time-select",{attrs:{"picker-options":{start:"10:00",step:"00:30",end:"22:00"},placeholder:"选择时间",editable:!1,clearable:!1},model:{value:e.form.date,callback:function(t){e.form.date=t},expression:"form.date"}})],1)],1)],1)},staticRenderFns:[]}},193:function(e,t,a){var l=a(125);"string"==typeof l&&(l=[[e.i,l,""]]),l.locals&&(e.exports=l.locals);a(4)("6f923e2a",l,!0)},200:function(e,t,a){var l=a(132);"string"==typeof l&&(l=[[e.i,l,""]]),l.locals&&(e.exports=l.locals);a(4)("84cbf086",l,!0)},62:function(e,t,a){a(193);var l=a(2)(a(90),a(169),null,null);e.exports=l.exports},76:function(e,t,a){"use strict";function l(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var s=a(10),o=(l(s),a(9)),r=l(o),i=a(0),n=l(i),c=a(8),d=(l(c),a(5));l(d);n.default.use(r.default),t.default={data:function(){return{}},props:["adsData","dataType","rulesLog"],methods:{roias:function(e,t){var a=parseFloat(t.WebsitePurchasesConversionValue.replace(/[\$,]+/g,"")),l=t.AmountSpent;if("ROI"==e){var s=a/(0==l?1:l);return s.toFixed(1)}if(0==a)return"X%";var s=l/a;return(100*s).toFixed(2)+"%"},formatROI:function(e){return this.roias("ROI",e)},formatROAS:function(e){return this.roias("ROAS",e)},formatAmountSpent:function(e,t){return"$"+e.AmountSpent},formatChildDate:function(e){return 7==e.Type?"last 7 day":14==e.Type?"last 14 day":e.Date},openRulesDialog:function(e){this.$emit("openRulesDialog",e)},searchThatID:function(e){this.$emit("searchThatID",e,this.dataType)},getSummaries:function(e){var t=e.columns,a=e.data,l=[];return a.length<2?[]:(t.forEach(function(e,t){if(0!==t){if(2===t)return void(l[t]="共计("+a.length+")条");var s=a.map(function(t){return Number(t[e.property]?t[e.property].toString().replace(/[\$,]+/g,""):t[e.property])});s.every(function(e){return isNaN(e)})?l[t]="":(l[t]=s.reduce(function(e,t){var a=Number(t);return isNaN(a)?e:e+t},0),"5"!=t&&"6"!=t&&"8"!=t&&"10"!=t&&"12"!=t&&"15"!=t&&"16"!=t||(l[t]="$"+l[t].toFixed(2)))}}),setTimeout(function(){var e=document.getElementsByClassName("el-table__fixed"),t=document.getElementsByClassName("el-table__fixed-right");for(var a in e)void 0!==e[a]&&void 0!==e[a].style&&(e[a].style.bottom=0),void 0!==t[a]&&void 0!==t[a].style&&(t[a].style.bottom=0)},1e3),l)}},mounted:function(){}}},77:function(e,t,a){t=e.exports=a(1)(),t.push([e.i,"",""])},78:function(e,t,a){a(80);var l=a(2)(a(76),a(79),null,null);e.exports=l.exports},79:function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("el-table",{staticStyle:{width:"100%"},attrs:{data:e.adsData,border:"","max-height":"700","default-sort":{prop:"AmountSpent",order:"descending"},"summary-method":e.getSummaries,"show-summary":""}},[a("el-table-column",{attrs:{type:"expand",fixed:""},scopedSlots:e._u([{key:"default",fn:function(t){return[a("el-table",{staticStyle:{width:"100%"},attrs:{data:t.row.List,border:""}},[a("el-table-column",{attrs:{formatter:e.formatChildDate,label:"日期",width:"150"}}),e._v(" "),"rulesLog"==e.rulesLog?[a("el-table-column",{attrs:{label:"ROI",width:"40",formatter:e.formatROI}}),e._v(" "),a("el-table-column",{attrs:{label:"ROAS",width:"60",formatter:e.formatROAS}})]:e._e(),e._v(" "),a("el-table-column",{attrs:{prop:"WebsiteAddstoCart",label:"Website Adds to Cart",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"CostperWebsiteAddtoCart",label:"Cost per Website Add to Cart",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{formatter:e.formatAmountSpent,prop:"AmountSpent",label:"Amount Spent",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"WebsitePurchases",label:"Website Purchases",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"WebsitePurchasesConversionValue",label:"Website Purchases Conversion Value",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"LinkClicks",label:"Link Clicks",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"CPC",label:"CPC (Cost per Link Click)",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"CTR",label:"CTR (Link Click-Through Rate)",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"CPM1000",label:"CPM (Cost per 1,000 Impressions)",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"Reach",label:"Reach",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"Results",label:"Results",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"CostperResult",label:"Cost per Result",width:"80"}})],2)]}}])}),e._v(" "),a("el-table-column",{attrs:{fixed:"",prop:"Date",label:"Date",width:"100"}}),e._v(" "),a("el-table-column",{attrs:{fixed:"",prop:"Name",label:"Name",width:"200"},scopedSlots:e._u([{key:"default",fn:function(t){return["campaign"!=e.dataType?[a("el-popover",{attrs:{trigger:"hover",placement:"top"}},[a("p",[e._v("Campaign Name: "+e._s(t.row.CampaignName))]),e._v(" "),a("p",[e._v("Adset Name: "+e._s(t.row.AdsetName))]),e._v(" "),"ad"==e.dataType?[a("p",[e._v("Ad Name: "+e._s(t.row.AdName))])]:e._e(),e._v(" "),a("div",{staticClass:"name-wrapper",slot:"reference"},[a("a",{attrs:{href:"javascript://"},on:{click:function(a){e.searchThatID(t.row)}}},[e._v(e._s(t.row.Name))])])],2)]:[a("a",{attrs:{href:"javascript://"},on:{click:function(a){e.searchThatID(t.row)}}},[e._v(e._s(t.row.Name))])]]}}])}),e._v(" "),"rulesLog"==e.rulesLog?[a("el-table-column",{attrs:{label:"ROI",width:"40",formatter:e.formatROI}}),e._v(" "),a("el-table-column",{attrs:{label:"ROAS",width:"60",formatter:e.formatROAS}})]:[a("el-table-column",{attrs:{prop:"Delivery",label:"Delivery",width:"60"}})],e._v(" "),a("el-table-column",{attrs:{prop:"WebsiteAddstoCart",label:"Website Adds to Cart",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"CostperWebsiteAddtoCart",label:"Cost per Website Add to Cart",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{formatter:e.formatAmountSpent,prop:"AmountSpent",label:"Amount Spent",width:"140",sortable:""}}),e._v(" "),a("el-table-column",{attrs:{prop:"WebsitePurchases",label:"Website Purchases",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"WebsitePurchasesConversionValue",label:"Website Purchases Conversion Value",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"LinkClicks",label:"Link Clicks",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"CPC",label:"CPC (Cost per Link Click)",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"CTR",label:"CTR (Link Click-Through Rate)",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"CPM1000",label:"CPM (Cost per 1,000 Impressions)",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"Reach",label:"Reach",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"Results",label:"Results",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"CostperResult",label:"Cost per Result",width:"80"}}),e._v(" "),"ad"==e.dataType?[a("el-table-column",{attrs:{prop:"RelevanceScore",label:"Relevance Score",width:"80"}})]:e._e(),e._v(" "),"adset"==e.dataType?[a("el-table-column",{attrs:{prop:"Budget",label:"Budget",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"Schedule",label:"Schedule",width:"80"}})]:e._e(),e._v(" "),"campaign"==e.dataType?[a("el-table-column",{attrs:{prop:"Ends",label:"Ends",width:"80"}})]:e._e(),e._v(" "),"campaign"==e.dataType?[a("el-table-column",{attrs:{label:"操作",width:"80",fixed:"right"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("el-button",{attrs:{type:"text",size:"small"},on:{click:function(a){e.openRulesDialog(t.row)}}},[e._v("\n\t\t\t\t\t规则\n\t\t\t\t")])]}}])})]:e._e()],2)],1)},staticRenderFns:[]}},80:function(e,t,a){var l=a(77);"string"==typeof l&&(l=[[e.i,l,""]]),l.locals&&(e.exports=l.locals);a(4)("18dfb494",l,!0)},90:function(e,t,a){"use strict";function l(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var s=a(10),o=(l(s),a(9)),r=l(o),i=a(0),n=l(i),c=a(11),d=a(8),u=l(d),p=a(5),h=l(p),m=a(78),f=l(m),b=a(161),v=l(b);n.default.use(r.default),t.default={components:{"v-ad_table":f.default,"v-rules_list":v.default},data:function(){return{activeName:"getCampaignsData",campaignsData:[],adsetsData:[],adsData:[],formSearch:{keyword_type:"",keyword:"",checked_campaigns:[],checked_adsets:[]},dialogTableVisible:!1,RulesChecked:[],RulesCheckedTime:"10:00",target:"",target_id:""}},computed:(0,c.mapState)({user:function(e){return e.user}}),mounted:function(){this.getData()},methods:{getData:function(){var e=this.formSearch;u.default.http(h.default[this.activeName],e,this.then)},then:function(e,t){var a=this;switch(t){case h.default.getCampaignsData.code:this.campaignsData=e.data;break;case h.default.getAdsetsData.code:this.adsetsData=e.data;break;case h.default.getAdsData.code:this.adsData=e.data;break;case h.default.getRulesForAd.code:this.RulesChecked=[],this.RulesCheckedTime="10:00",e.data.forEach(function(e){a.RulesChecked.push(e.id),a.RulesCheckedTime=e.exec_hour_minute}),this.dialogTableVisible=!0}},handleTabClick:function(){this.getData()},openRulesDialog:function(e){this.target_id=e.Id,this.target=this.activeName,u.default.http(h.default.getRulesForAd,{id:e.Id,type:this.activeName},this.then)},dialogClose:function(){this.dialogTableVisible=!1},dialogOpen:function(){var e=this;setTimeout(function(){e.$refs.refRules.init(e.RulesChecked,e.RulesCheckedTime)},100)},saveRulesForAd:function(){this.dialogClose(),this.$refs.refRules.saveRulesForAd(this.target_id,this.target)},onFormSearch:function(){this.getData()},onClearFormSearch:function(){this.formSearch.keyword_type="",this.formSearch.keyword="",this.getData()},searchThatID:function(e,t){var e={id:e.Id,name:e.Name};"adset"==t?(this.formSearch.checked_adsets.push(e),this.activeName="getAdsData",this.getData()):(this.formSearch.checked_campaigns.push(e),this.activeName="getAdsetsData",this.getData())},getTabName:function(e){var t=this.formSearch[e],a=t.length,l="";return a>1?l=a+" selected":1==a&&(l=t[0].name),l},clearTabName:function(e){this.formSearch[e]=[]}}}}});
//# sourceMappingURL=4.build.js.map?4d698a2ffe0fe9958222