webpackJsonp([9],{120:function(e,t,a){t=e.exports=a(1)(),t.push([e.i,'.el-table__expanded-cell{padding-top:0;padding-bottom:0}.el-table .cell,.el-table th>div{padding-left:3px;padding-right:3px}.el-table th>.cell{overflow:hidden;height:30px}.val{color:#f33;display:inline-block;padding-right:20px}tth:hover div.cell{position:fixed;z-index:99;color:#33f;margin-top:-15px;left:15px}.el-table .caret-wrapper{position:static}.el-table th>.cell{text-indent:15px}.debug{display:none}.pk-val{border-top:1px dashed #d0d0d0;padding-top:8px;color:#f33;margin-top:5px}.pk-val:after{content:"VS";color:#d0d0d0;margin-top:-20px;position:absolute;right:3px;font-size:9px}',""])},163:function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"mytable"},[a("v-headerTop"),e._v(" "),a("el-col",{staticStyle:{height:"100%"},attrs:{span:4}},[a("div",{staticClass:"grid-left bg-purple-darkc overflow-y",attrs:{id:"app_left_menu"}},[a("v-leftMenu")],1)]),e._v(" "),a("el-col",{staticStyle:{height:"100%"},attrs:{span:20}},[a("div",[a("el-form",{staticClass:"demo-form-inline",attrs:{inline:!0,model:e.formSearch}},[a("el-form-item",{directives:[{name:"show",rawName:"v-show",value:e.brandShow,expression:"brandShow"}],staticStyle:{width:"100px"}},[a("el-select",{attrs:{placeholder:"全部品牌"},on:{change:e.onFormSearch},model:{value:e.formSearch.brand,callback:function(t){e.formSearch.brand=t},expression:"formSearch.brand"}},e._l(e.brands,function(e){return a("el-option",{key:e.value,attrs:{label:e.label,value:e.value}})}))],1),e._v(" "),a("el-form-item",[a("el-input",{staticStyle:{width:"300px"},attrs:{placeholder:"AccountId / Author / SKU / Filename"},model:{value:e.formSearch.keyword,callback:function(t){e.formSearch.keyword=t},expression:"formSearch.keyword"}})],1),e._v(" "),a("el-popover",{ref:"popoverDate",attrs:{placement:"bottom",width:"600",trigger:"click"}},[a("el-row",{attrs:{gutter:24}},[a("el-col",{attrs:{span:20}},[a("el-form-item",[a("el-select",{attrs:{placeholder:"选择日期"},on:{change:e.onDataTypeChangeOne},model:{value:e.formSearch.dataType,callback:function(t){e.formSearch.dataType=t},expression:"formSearch.dataType"}},[a("el-option",{attrs:{label:"Lifetime",value:"lifetime"}}),e._v(" "),a("el-option",{attrs:{label:"Last 7 Day",value:"last_7day"}}),e._v(" "),a("el-option",{attrs:{label:"Last 14 Day",value:"last_14day"}}),e._v(" "),a("el-option",{attrs:{label:"自定义日期",value:"custom"}})],1),e._v(" "),a("el-date-picker",{attrs:{disabled:e.dateOne,editable:!1,type:"daterange",align:"right",placeholder:"选择日期范围","picker-options":e.dateChoice},model:{value:e.formSearch.dateOne,callback:function(t){e.formSearch.dateOne=t},expression:"formSearch.dateOne"}})],1),e._v(" "),a("el-form-item",[a("el-select",{attrs:{placeholder:"选择日期"},on:{change:e.onDataTypeChangeTwo},model:{value:e.formSearch.dataTypeTwo,callback:function(t){e.formSearch.dataTypeTwo=t},expression:"formSearch.dataTypeTwo"}},[a("el-option",{attrs:{label:"Lifetime",value:"lifetime"}}),e._v(" "),a("el-option",{attrs:{label:"Last 7 Day",value:"last_7day"}}),e._v(" "),a("el-option",{attrs:{label:"Last 14 Day",value:"last_14day"}}),e._v(" "),a("el-option",{attrs:{label:"自定义日期",value:"custom"}})],1),e._v(" "),a("el-date-picker",{attrs:{disabled:e.dateTwo,editable:!1,type:"daterange",align:"right",placeholder:"选择日期范围","picker-options":e.dateChoice},on:{change:e.pkFlagSetting},model:{value:e.formSearch.dateTwo,callback:function(t){e.formSearch.dateTwo=t},expression:"formSearch.dateTwo"}})],1)],1),e._v(" "),a("el-col",{attrs:{span:4}},[a("el-button",{staticStyle:{height:"100px"},attrs:{type:"primary",icon:"search"},on:{click:e.onFormSearch}},[e._v("查 询\n                            ")])],1)],1)],1),e._v(" "),a("el-button",{directives:[{name:"popover",rawName:"v-popover:popoverDate",arg:"popoverDate"}],attrs:{type:"primary",icon:"date"}},[e._v("日期")]),e._v(" "),a("el-form-item",[a("el-button",{attrs:{type:"primary",icon:"search"},on:{click:e.onFormSearch}},[e._v("查询")]),e._v(" "),a("a",{attrs:{href:"javascript://"},on:{click:e.onClearFormSearch}},[e._v("清空条件")])],1),e._v(" "),a("el-form-item",[a("el-radio-group",{on:{change:e.onFormSearch},model:{value:e.formSearch.assetType,callback:function(t){e.formSearch.assetType=t},expression:"formSearch.assetType"}},[a("el-radio-button",{attrs:{label:""}},[e._v("All Assets")]),e._v(" "),a("el-radio-button",{attrs:{label:"0"}},[e._v("Images")]),e._v(" "),a("el-radio-button",{attrs:{label:"1"}},[e._v("Videos")])],1)],1)],1),e._v(" "),a("el-table",{staticStyle:{width:"100%"},attrs:{data:e.rulesLog,border:"","max-height":"700","summary-method":e.getSummaries,"show-summary":""},on:{"sort-change":e.sortChange}},[a("el-table-column",{attrs:{type:"expand",fixed:""},scopedSlots:e._u([{key:"default",fn:function(t){return[a("span",[e._v("Updated Time:"),a("span",{staticClass:"val"},[e._v(e._s(t.row.updated_time))])]),e._v(" "),a("span",[e._v("Size:"),a("span",{staticClass:"val"},[e._v(e._s(t.row.original_width)+" x "+e._s(t.row.original_height))])]),e._v(" "),a("el-table",{staticStyle:{width:"100%"},attrs:{data:t.row.list,border:""}},[a("el-table-column",{attrs:{prop:"account_id",label:"Ad Account",width:"150"}}),e._v(" "),a("el-table-column",{attrs:{formatter:e.numberFormatInt,columnKey:"websiteaddstocart",label:"Website Adds to Cart",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{formatter:e.moneyFormat,columnKey:"costperwebsiteaddtocart",label:"Cost per Website Add to Cart",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{formatter:e.moneyFormat,columnKey:"amountspent",label:"Spent",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{formatter:e.numberFormatInt,columnKey:"websitepurchases",label:"Website Purchases",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{formatter:e.moneyFormat,columnKey:"websitepurchasesconversionvalue",label:"Website Purchases Conversion Value",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{formatter:e.numberFormatInt,columnKey:"linkclicks",label:"Link Clicks",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{formatter:e.moneyFormat,columnKey:"cpc",label:"CPC (Cost per Link Click)",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{formatter:e.numberFormatPer,columnKey:"ctr",label:"CTR (Link Click-Through Rate)",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{formatter:e.moneyFormat,columnKey:"cpm1000",label:"CPM (Cost per 1,000 Impressions)",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{formatter:e.numberFormatInt,columnKey:"reach",label:"Reach",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{formatter:e.numberFormatInt,columnKey:"results",label:"Results",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{formatter:e.CostperResult,columnKey:"costperresult",label:"Cost per Result",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{formatter:e.numberFormatInt,columnKey:"impressions",label:"Impressions",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{formatter:e.numberFormat,columnKey:"frequency",label:"Frequency",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{formatter:e.numberFormat,columnKey:"relevance_score",label:"Relevent Score",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"positive_feedback",label:"Positive Feedback",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"negative_feedback",label:"Negative Feedback",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{formatter:e.formatAdsNumView,prop:"ads_num",columnKey:"ads_num",label:"广告数",width:"250",sortable:""}}),e._v(" "),a("el-table-column",{attrs:{formatter:e.numberFormatPer,columnKey:"conversion_rate",prop:"conversion_rate",label:"转化率",width:"70"}}),e._v(" "),a("el-table-column",{attrs:{formatter:e.numberFormatPer,columnKey:"roas",prop:"roas",label:"ROAS",width:"70"}})],1)]}}])}),e._v(" "),a("el-table-column",{attrs:{width:"110",label:"Thumb",fixed:""},scopedSlots:e._u([{key:"default",fn:function(t){return[a("el-popover",{attrs:{placement:"right",title:"",width:"400",trigger:"hover"}},[a("div",["1"==t.row.type?a("span",[e._v("Video:")]):a("span",[e._v("Image:")]),e._v(" "),"Object with ID does not exist"==t.row.updated_time?a("h1",[a("span",{staticClass:"val"},[e._v(e._s(t.row.updated_time))])]):a("span",[e._v("Updated Time:"),a("span",{staticClass:"val"},[e._v(e._s(t.row.updated_time))])]),e._v(" "),a("span",[e._v("Size:"),a("span",{staticClass:"val"},[e._v(e._s(t.row.original_width)+" x "+e._s(t.row.original_height))])]),e._v(" "),a("br"),e._v(" "),a("img",{attrs:{width:"400",src:t.row.permalink_url}}),e._v(" "),a("div",{staticClass:"debug"},[a("p",[e._v("\n                                        http://www.vking.com/facebook_ads/wwwroot/apido/asyn.getAssetForAd?debug=true&ac_id="+e._s(t.row.account_id)+"&ad_id="+e._s(t.row.ad_id)+"\n                                    ")]),e._v(" "),"1"==t.row.type?a("p",[e._v("\n                                        http://www.vking.com/facebook_ads/wwwroot/apido/asyn.getAssetsVideoInfo?debug=true&ac_id="+e._s(t.row.account_id)+"&video_id="+e._s(t.row.id)+"\n                                    ")]):a("p",[e._v("\n                                        http://www.vking.com/facebook_ads/wwwroot/apido/asyn.getAssetsImageInfo?debug=true&ac_id="+e._s(t.row.account_id)+"&hashes="+e._s(t.row.hash)+"\n                                    ")])])]),e._v(" "),"Object with ID does not exist"==t.row.updated_time?a("span",{slot:"reference"},[t.row.permalink_url?a("img",{attrs:{height:"100",src:t.row.permalink_url}}):a("span",[e._v(e._s(t.row.name))])]):a("a",{attrs:{href:t.row.url,target:"_blank"},slot:"reference"},[t.row.permalink_url?a("img",{attrs:{height:"100",src:t.row.permalink_url}}):a("span",[e._v(e._s(t.row.name))])])])]}}])}),e._v(" "),a("el-table-column",{attrs:{columnKey:"websiteaddstocart",prop:"websiteaddstocart",label:"Website Adds to Cart",width:"80",className:"autotooltip",sortable:"custom"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",[e._v(e._s(e.numberFormatInt(t.row,{columnKey:"websiteaddstocart"})))]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.pkFlag,expression:"pkFlag"}],staticClass:"pk-val"},[e._v(e._s(e.numberFormatInt(t.row,{columnKey:"websiteaddstocart"},"PK")))])]}}])}),e._v(" "),a("el-table-column",{attrs:{columnKey:"costperwebsiteaddtocart",prop:"costperwebsiteaddtocart",label:"Cost per Website Add to Cart",width:"80",sortable:"custom"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",[e._v(e._s(e.moneyFormat(t.row,{columnKey:"costperwebsiteaddtocart"})))]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.pkFlag,expression:"pkFlag"}],staticClass:"pk-val"},[e._v(e._s(e.moneyFormat(t.row,{columnKey:"costperwebsiteaddtocart"},"PK")))])]}}])}),e._v(" "),a("el-table-column",{attrs:{columnKey:"websiteaddstocartconversionvalue",prop:"websiteaddstocartconversionvalue",label:"Website Adds to Cart Conversion Value",width:"80",sortable:"custom"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",[e._v(e._s(e.moneyFormat(t.row,{columnKey:"websiteaddstocartconversionvalue"})))]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.pkFlag,expression:"pkFlag"}],staticClass:"pk-val"},[e._v(e._s(e.moneyFormat(t.row,{columnKey:"websiteaddstocartconversionvalue"},"PK")))])]}}])}),e._v(" "),a("el-table-column",{attrs:{columnKey:"amountspent",prop:"amountspent",label:"Spent",width:"80",sortable:"custom"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",[e._v(e._s(e.moneyFormat(t.row,{columnKey:"amountspent"})))]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.pkFlag,expression:"pkFlag"}],staticClass:"pk-val"},[e._v(e._s(e.moneyFormat(t.row,{columnKey:"amountspent"},"PK")))])]}}])}),e._v(" "),a("el-table-column",{attrs:{columnKey:"websitepurchases",prop:"websitepurchases",label:"Website Purchases",width:"80",sortable:"custom"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",[e._v(e._s(e.numberFormatInt(t.row,{columnKey:"websitepurchases"})))]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.pkFlag,expression:"pkFlag"}],staticClass:"pk-val"},[e._v(e._s(e.numberFormatInt(t.row,{columnKey:"websitepurchases"},"PK")))])]}}])}),e._v(" "),a("el-table-column",{attrs:{columnKey:"websitepurchasesconversionvalue",prop:"websitepurchasesconversionvalue",label:"Website Purchases Conversion Value",width:"80",sortable:"custom"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",[e._v(e._s(e.moneyFormat(t.row,{columnKey:"websitepurchasesconversionvalue"})))]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.pkFlag,expression:"pkFlag"}],staticClass:"pk-val"},[e._v(e._s(e.moneyFormat(t.row,{columnKey:"websitepurchasesconversionvalue"},"PK")))])]}}])}),e._v(" "),a("el-table-column",{attrs:{columnKey:"linkclicks",prop:"linkclicks",label:"Link Clicks",width:"80",sortable:"custom"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",[e._v(e._s(e.numberFormatInt(t.row,{columnKey:"linkclicks"})))]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.pkFlag,expression:"pkFlag"}],staticClass:"pk-val"},[e._v(e._s(e.numberFormatInt(t.row,{columnKey:"linkclicks"},"PK")))])]}}])}),e._v(" "),a("el-table-column",{attrs:{columnKey:"cpc",prop:"cpc",label:"CPC (Cost per Link Click)",width:"80",sortable:"custom"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",[e._v(e._s(e.moneyFormat(t.row,{columnKey:"cpc"})))]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.pkFlag,expression:"pkFlag"}],staticClass:"pk-val"},[e._v(e._s(e.moneyFormat(t.row,{columnKey:"cpc"},"PK")))])]}}])}),e._v(" "),a("el-table-column",{attrs:{columnKey:"ctr",prop:"ctr",label:"CTR (Link Click-Through Rate)",width:"80",sortable:"custom"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",[e._v(e._s(e.numberFormatPer(t.row,{columnKey:"ctr"})))]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.pkFlag,expression:"pkFlag"}],staticClass:"pk-val"},[e._v(e._s(e.numberFormatPer(t.row,{columnKey:"ctr"},"PK")))])]}}])}),e._v(" "),a("el-table-column",{attrs:{columnKey:"cpm1000",prop:"cpm1000",label:"CPM (Cost per 1,000 Impressions)",width:"80",sortable:"custom"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",[e._v(e._s(e.moneyFormat(t.row,{columnKey:"cpm1000"})))]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.pkFlag,expression:"pkFlag"}],staticClass:"pk-val"},[e._v(e._s(e.moneyFormat(t.row,{columnKey:"cpm1000"},"PK")))])]}}])}),e._v(" "),a("el-table-column",{attrs:{columnKey:"reach",prop:"reach",label:"Reach",width:"80",sortable:"custom"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",[e._v(e._s(e.numberFormatInt(t.row,{columnKey:"reach"})))]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.pkFlag,expression:"pkFlag"}],staticClass:"pk-val"},[e._v(e._s(e.numberFormatInt(t.row,{columnKey:"reach"},"PK")))])]}}])}),e._v(" "),a("el-table-column",{attrs:{columnKey:"results",prop:"results",label:"Results",width:"80",sortable:"custom"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",[e._v(e._s(e.numberFormatInt(t.row,{columnKey:"results"})))]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.pkFlag,expression:"pkFlag"}],staticClass:"pk-val"},[e._v(e._s(e.numberFormatInt(t.row,{columnKey:"results"},"PK")))])]}}])}),e._v(" "),a("el-table-column",{attrs:{columnKey:"costperresult",prop:"costperresult",label:"Cost per Result",width:"80",sortable:"custom"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",[e._v(e._s(e.CostperResult(t.row,{columnKey:"costperresult"})))]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.pkFlag,expression:"pkFlag"}],staticClass:"pk-val"},[e._v(e._s(e.CostperResult(t.row,{columnKey:"costperresult"},"PK")))])]}}])}),e._v(" "),a("el-table-column",{attrs:{columnKey:"impressions",prop:"impressions",label:"Impressions",width:"80",sortable:"custom"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",[e._v(e._s(e.numberFormatInt(t.row,{columnKey:"impressions"})))]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.pkFlag,expression:"pkFlag"}],staticClass:"pk-val"},[e._v(e._s(e.numberFormatInt(t.row,{columnKey:"impressions"},"PK")))])]}}])}),e._v(" "),a("el-table-column",{attrs:{columnKey:"frequency",prop:"frequency",label:"Frequency",width:"80",sortable:"custom"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",[e._v(e._s(e.numberFormat(t.row,{columnKey:"frequency"})))]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.pkFlag,expression:"pkFlag"}],staticClass:"pk-val"},[e._v(e._s(e.numberFormat(t.row,{columnKey:"frequency"},"PK")))])]}}])}),e._v(" "),a("el-table-column",{attrs:{columnKey:"relevance_score",prop:"relevance_score",label:"Relevent Score",width:"80",sortable:"custom"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",[e._v(e._s(e.numberFormat(t.row,{columnKey:"relevance_score"})))]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.pkFlag,expression:"pkFlag"}],staticClass:"pk-val"},[e._v(e._s(e.numberFormat(t.row,{columnKey:"relevance_score"},"PK")))])]}}])}),e._v(" "),a("el-table-column",{attrs:{prop:"positive_feedback",label:"Positive Feedback",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"negative_feedback",label:"Negative Feedback",width:"80"}}),e._v(" "),a("el-table-column",{attrs:{prop:"ads_num",columnKey:"ads_num",label:"广告数",width:"70",sortable:"custom"}}),e._v(" "),a("el-table-column",{attrs:{columnKey:"conversion_rate",prop:"conversion_rate",label:"转化率",width:"70",sortable:"custom"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",[e._v(e._s(e.numberFormatPer(t.row,{columnKey:"conversion_rate"})))]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.pkFlag,expression:"pkFlag"}],staticClass:"pk-val"},[e._v(e._s(e.numberFormatPer(t.row,{columnKey:"conversion_rate"},"PK")))])]}}])}),e._v(" "),a("el-table-column",{attrs:{columnKey:"roas",prop:"roas",label:"ROAS",width:"70",sortable:"custom"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("div",[e._v(e._s(e.numberFormatPer(t.row,{columnKey:"roas"})))]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.pkFlag,expression:"pkFlag"}],staticClass:"pk-val"},[e._v(e._s(e.numberFormatPer(t.row,{columnKey:"roas"},"PK")))])]}}])}),e._v(" "),a("el-table-column",{attrs:{label:"Author",width:"100"},scopedSlots:e._u([{key:"default",fn:function(t){return[e.editor?a("el-select",{attrs:{filterable:"","allow-create":"",placeholder:"请选择"},on:{change:function(a){e.setAuthor(t.row.author,t.row)},"visible-change":e.setAuthorFlag},model:{value:t.row.author,callback:function(e){t.row.author=e},expression:"scope.row.author"}},e._l(e.authors,function(e){return a("el-option",{key:e,attrs:{label:e,value:e}})})):a("span",[e._v(e._s(t.row.author))])]}}])}),e._v(" "),a("el-table-column",{attrs:{label:"SKUS",width:"250"},scopedSlots:e._u([{key:"default",fn:function(t){return[e.editor?a("div",[e._l(t.row.skus,function(o){return a("el-tag",{key:o,staticStyle:{margin:"3px"},attrs:{closable:!0,"close-transition":!1},on:{close:function(a){e.handleCloseTag(t.row.id,o)}}},[e._v("\n                            "+e._s(o)+"\n                        ")])}),e._v(" "),t.row.inputVisible?a("el-input",{ref:"saveTagInput",staticClass:"input-new-tag",attrs:{size:"mini"},on:{blur:function(a){e.handleInputConfirm(t.row.id)}},nativeOn:{keyup:function(a){if(!("button"in a)&&e._k(a.keyCode,"enter",13))return null;e.handleInputConfirm(t.row.id)}},model:{value:e.inputTagValue,callback:function(t){e.inputTagValue=t},expression:"inputTagValue"}}):a("el-button",{staticClass:"button-new-tag",attrs:{size:"small"},on:{click:function(a){e.showTagInput(t.row.id)}}},[e._v("+\n                            Sku")])],2):e._l(t.row.skus,function(t){return a("el-tag",{key:t,staticStyle:{margin:"3px"}},[e._v("\n                            "+e._s(t)+"\n                        ")])})]}}])})],1),e._v(" "),a("el-pagination",{staticStyle:{margin:"20px auto",width:"300px"},attrs:{"page-size":e.formSearch.limit,layout:"total, prev, pager, next",total:e.total},on:{"size-change":e.handleSizeChange,"current-change":e.handleCurrentChange}})],1)])],1)},staticRenderFns:[]}},186:function(e,t,a){var o=a(120);"string"==typeof o&&(o=[[e.i,o,""]]),o.locals&&(e.exports=o.locals);a(4)("12a72266",o,!0)},65:function(e,t,a){a(186);var o=a(2)(a(94),a(163),null,null);e.exports=o.exports},74:function(e,t,a){"use strict";function o(){return new Date}function r(e,t){return 0==t?(t=11,e--,new Date(e,t,1)):(t--,new Date(e,t,1))}function s(e,t){var a=new Date(e,t,1),o=a.getMonth(),r=a.getFullYear();11==o?(r++,o=0):o++;var s=new Date(r,o,1);return new Date(s.getTime()-864e5).getDate()}function l(){var e=o(),t=e.getDay(),a=(e.getDate(),0!=t?t-1:6),r=new Date(e.getTime()-864e5*a),s=new Date(r.getTime()-864e5);return{start:new Date(s.getTime()-5184e5),end:s}}function n(){var e=o(),t=e.getMonth(),a=e.getFullYear(),l=r(a,t);return{start:l,end:new Date(l.getFullYear(),l.getMonth(),s(l.getFullYear(),l.getMonth()))}}Object.defineProperty(t,"__esModule",{value:!0}),t.default={shortcuts:[{text:"今 天",onClick:function(e){var t=new Date;e.$emit("pick",[t,t])}},{text:"昨 天",onClick:function(e){var t=new Date;t.setTime(t.getTime()-864e5),e.$emit("pick",[t,t])}},{text:"最近一周",onClick:function(e){var t=new Date,a=new Date;a.setTime(a.getTime()-6048e5),e.$emit("pick",[a,t])}},{text:"最近一个月",onClick:function(e){var t=new Date,a=new Date;a.setTime(a.getTime()-2592e6),e.$emit("pick",[a,t])}},{text:"最近三个月",onClick:function(e){var t=new Date,a=new Date;a.setTime(a.getTime()-7776e6),e.$emit("pick",[a,t])}},{text:"上周",onClick:function(e){var t=l();e.$emit("pick",[t.start,t.end])}},{text:"上个月",onClick:function(e){var t=n();e.$emit("pick",[t.start,t.end])}}]}},94:function(e,t,a){"use strict";function o(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var r=a(10),s=(o(r),a(9)),l=o(s),n=a(0),i=o(n),u=a(11),c=a(8),m=o(c),d=a(5),p=o(d),v=a(74),h=o(v);i.default.use(l.default),t.default={data:function(){return{activeName:"getRulesLog",rulesLog:[],rulesLogPK:[],popover_img_src:"",total:0,inputTagValue:"",editor:!1,formSearch:{keyword:"",limit:30,offset:0,dataType:"lifetime",assetType:"",brand:"",dateOne:"",dateTwo:"",dataTypeTwo:"custom",order:"amountspent",sort:"desc"},authors:[],authors_flag:!1,dateChoice:h.default,dateOne:!0,dateTwo:!1,pkFlag:!1,brands:[],brandShow:!1}},computed:(0,u.mapState)({user:function(e){return e.user}}),mounted:function(){this.editor=location.hash.indexOf("editor")>0;var e={all:{label:"全部品牌",value:""},jeulia:{label:"Jeulia",value:"jeulia"},gnoce:{label:"Gnoce",value:"gnoce"},amarley:{label:"Amarley",value:"amarley"}},t=location.hash.match(/brand\/([^\/]+)/);t?(this.formSearch.brand=t[1],this.brands=[e[t[1]]],this.brandShow=!1):(this.brands=e,this.brandShow=!0),console.log("brands",location.hash,e),this.getData()},methods:{getData:function(){this.authors_flag=!1,this.rulesLogPK=[];var e={};if(Object.assign(e,this.formSearch),e.dateOne=e.dateOne.toString(),m.default.http(p.default.assetsGetData,e,this.then),this.pkFlag){var e={};Object.assign(e,this.formSearch),e.dateOne=e.dateTwo.toString(),e.dataType=e.dataTypeTwo,m.default.http(p.default.assetsGetDataTwo,e,this.then)}},then:function(e,t){switch(t){case p.default.assetsGetData.code:this.rulesLog=e.data,this.total=parseInt(e.total);break;case p.default.assetsGetDataTwo.code:this.rulesLogPK=e.data;break;case p.default.assetsSetAuthor.code:case p.default.assetsSetSkus.code:}},handleTabClick:function(e){var t=e.name,a={};"getRulesLog"==t&&m.default.http(p.default[t],a,this.then)},numberFormat:function(e,t,a){if("PK"==a){if(!this.pkFlag)return;if(!(e=this.getRowPK(e)))return"--"}var o=arguments[1].columnKey;return m.default.numberFormat(e[o],2,"")},numberFormatPer:function(e,t,a){if("PK"==a){if(!this.pkFlag)return;if(!(e=this.getRowPK(e)))return"--"}var o=arguments[1].columnKey;return isFinite(e[o])?m.default.numberFormat(e[o],2,"")+"%":e[o]},numberFormatInt:function(e,t,a){if("PK"==a){if(!this.pkFlag)return;if(!(e=this.getRowPK(e)))return"--"}var o=arguments[1].columnKey;return m.default.numberFormat(e[o],0,"")},moneyFormat:function(e,t,a){if("PK"==a){if(!this.pkFlag)return;if(!(e=this.getRowPK(e)))return"--"}var o=arguments[1].columnKey;return m.default.numberFormat(e[o])},CostperResult:function(e,t,a){if("PK"==a){if(!this.pkFlag)return;if(!(e=this.getRowPK(e)))return"--"}if(0==e.websitepurchases)return"X";var o=e.amountspent/e.websitepurchases;return m.default.numberFormat(o)},formatAdsNumView:function(e,t){return e.ad_ids+" ["+e.ads_num+"]"},getSummaries:function(e){var t=e.columns,a=e.data,o=[];return a.length<2?[]:(t.forEach(function(e,t){if(0!==t){if(1===t)return void(o[t]="共计("+a.length+")条");if(e.columnKey){var r=a.map(function(t){return Number(t[e.columnKey]?t[e.columnKey].toString().replace(/[\$,]+/g,""):t[e.columnKey])});r.every(function(e){return isNaN(e)})?o[t]="":(o[t]=r.reduce(function(e,t){var a=Number(t);return isNaN(a)?e:e+t},0),[2,6,8,12,13,19].indexOf(t)>-1?o[t]=m.default.numberFormat(o[t],0,""):[5,7].indexOf(t)>-1?o[t]=m.default.numberFormat(o[t]):o[t]="")}}}),o)},handleSizeChange:function(){console.log(arguments)},handleCurrentChange:function(e){this.formSearch.offset=(e-1)*this.formSearch.limit,this.getData()},setAuthorFlag:function(){this.authors_flag=!0},setAuthor:function(e,t){this.authors_flag&&m.default.http(p.default.assetsSetAuthor,{author:e,id:t.id},this.then)},handleCloseTag:function(e,t){var a=this;this.rulesLog.map(function(o){if(o.id==e)return console.log("handleCloseTag-item",o.id),o.skus.splice(o.skus.indexOf(t),1),m.default.http(p.default.assetsSetSkus,{skus:o.skus.join(","),id:e},a.then),o})},showTagInput:function(e){this.rulesLog.map(function(t){if(t.id==e)return console.log("showTagInput-item",t.id),t.inputVisible=!0,t})},handleInputConfirm:function(e){var t=this,a=this,o=this.inputTagValue;this.rulesLog.map(function(r){if(r.id==e)return console.log("handleInputConfirm-item",r.id,o),o&&(r.skus.push(o),m.default.http(p.default.assetsSetSkus,{skus:r.skus.join(","),id:e},a.then)),r.inputVisible=!1,t.inputTagValue="",r})},onClearFormSearch:function(){this.formSearch.keyword="",this.getData()},onFormSearch:function(){this.getData()},onDataTypeChangeOne:function(e){this.dateOne=!0,"custom"==e&&(this.dateOne=!1)},onDataTypeChangeTwo:function(e){this.dateTwo=!0,"custom"==e?(this.pkFlag=!1,this.dateTwo=!1):this.pkFlag=!0},pkFlagSetting:function(e){this.pkFlag=!!e},getRowPK:function(e){var t=e.id;for(var a in this.rulesLogPK)if(t==this.rulesLogPK[a].id)return this.rulesLogPK[a];return!1},sortChange:function(e){this.formSearch.order=e.prop,this.formSearch.sort="ascending"==e.order?"asc":"desc",this.getData()}}}}});
//# sourceMappingURL=9.build.js.map?d2f30452cd978c33c62d