webpackJsonp([7],{130:function(e,t,l){t=e.exports=l(1)(),t.push([e.i,"canvas{border:1px solid #ccc}",""])},131:function(e,t,l){t=e.exports=l(1)(),t.push([e.i,"canvas{border:1px solid #ccc}",""])},154:function(e,t,l){l(198);var a=l(2)(l(97),l(174),null,null);e.exports=a.exports},174:function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,l=e._self._c||t;return l("el-form",{ref:"form",attrs:{model:e.form,"label-width":"120px",rules:e.rules}},[l("el-form-item",{attrs:{label:"品牌",prop:"brand"}},[l("el-select",{attrs:{placeholder:"请选择品牌"},model:{value:e.form.brand,callback:function(t){e.form.brand=t},expression:"form.brand"}},e._l(e.brands,function(e){return l("el-option",{key:e.value,attrs:{label:e.label,value:e.value}})}))],1),e._v(" "),l("el-form-item",{attrs:{label:"Feed URL",prop:"url"}},[l("el-input",{model:{value:e.form.url,callback:function(t){e.form.url=t},expression:"form.url"}})],1),e._v(" "),l("el-form-item",{attrs:{label:"utm_source"}},[l("el-input",{model:{value:e.form.utm_source,callback:function(t){e.form.utm_source=t},expression:"form.utm_source"}})],1),e._v(" "),l("el-form-item",{attrs:{label:"utm_medium"}},[l("el-input",{model:{value:e.form.utm_medium,callback:function(t){e.form.utm_medium=t},expression:"form.utm_medium"}})],1),e._v(" "),l("el-form-item",{attrs:{label:"utm_campaign"}},[l("el-input",{model:{value:e.form.utm_campaign,callback:function(t){e.form.utm_campaign=t},expression:"form.utm_campaign"}})],1),e._v(" "),l("el-form-item",{attrs:{label:"utm_content"}},[l("el-input",{model:{value:e.form.utm_content,callback:function(t){e.form.utm_content=t},expression:"form.utm_content"}})],1),e._v(" "),l("el-form-item",{attrs:{label:"utm_term"}},[l("el-input",{model:{value:e.form.utm_term,callback:function(t){e.form.utm_term=t},expression:"form.utm_term"}})],1)],1)},staticRenderFns:[]}},175:function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,l=e._self._c||t;return l("div",{staticClass:"mytable"},[l("v-headerTop"),e._v(" "),l("el-col",{staticStyle:{height:"100%"},attrs:{span:4}},[l("div",{staticClass:"grid-left bg-purple-darkc overflow-y",attrs:{id:"app_left_menu"}},[l("v-leftMenu")],1)]),e._v(" "),l("el-col",{staticStyle:{height:"100%"},attrs:{span:20}},[l("div",{staticStyle:{padding:"10px"}},[l("el-button",{staticStyle:{float:"right","z-index":"1",position:"relative"},attrs:{type:"primary"},on:{click:function(t){e.openDialog(-1)}}},[l("i",{staticClass:"el-icon-plus"}),e._v("Add Feed")]),e._v(" "),l("el-tabs",{model:{value:e.activeName,callback:function(t){e.activeName=t},expression:"activeName"}},[l("el-tab-pane",{attrs:{label:"Feed Lists",name:"getRulesData"}},[l("el-table",{staticStyle:{width:"100%"},attrs:{data:e.rulesData,border:"","max-height":"100%"}},[l("el-table-column",{attrs:{prop:"id",label:"ID",width:"60"}}),e._v(" "),l("el-table-column",{attrs:{prop:"brand",label:"品牌",width:"60"}}),e._v(" "),l("el-table-column",{attrs:{prop:"url",label:"Feed URL"}}),e._v(" "),l("el-table-column",{attrs:{prop:"count_items",label:"商品数",width:"60"}}),e._v(" "),l("el-table-column",{attrs:{prop:"uptime",formatter:e.upDateFormat,label:"更新时间",width:"120"}}),e._v(" "),l("el-table-column",{attrs:{label:"操作",width:"120"},scopedSlots:e._u([{key:"default",fn:function(t){return[l("el-button",{attrs:{type:"text",size:"small"},nativeOn:{click:function(l){l.preventDefault(),e.openDialog(t.$index,e.rulesData)}}},[e._v("\n                                    修改\n                                ")])]}}])})],1)],1)],1)],1)]),e._v(" "),l("el-dialog",{attrs:{title:"Update Feed",visible:e.dialogTableVisible,"close-on-click-modal":!1,"close-on-press-escape":!1},on:{"update:visible":function(t){e.dialogTableVisible=t}}},[l("feedsForm",{ref:"feedsForm",attrs:{form:e.form}}),e._v(" "),l("span",{staticClass:"dialog-footer",slot:"footer"},[l("el-button",{on:{click:e.closeDialog}},[e._v("取 消")]),e._v(" "),l("el-button",{attrs:{type:"primary"},on:{click:e.updateDialog}},[e._v("确 定")])],1)],1)],1)},staticRenderFns:[]}},198:function(e,t,l){var a=l(130);"string"==typeof a&&(a=[[e.i,a,""]]),a.locals&&(e.exports=a.locals);l(4)("7caf5594",a,!0)},199:function(e,t,l){var a=l(131);"string"==typeof a&&(a=[[e.i,a,""]]),a.locals&&(e.exports=a.locals);l(4)("777e5bc7",a,!0)},66:function(e,t,l){l(199);var a=l(2)(l(96),l(175),null,null);e.exports=a.exports},96:function(e,t,l){"use strict";function a(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var o=l(0),r=(a(o),l(8)),n=a(r),i=l(5),s=a(i),u=l(154),c=a(u);t.default={components:{feedsForm:c.default},data:function(){return{activeName:"getRulesData",rulesData:[],dialogTableVisible:!1,form:{}}},mounted:function(){this.getData()},methods:{then:function(e,t){switch(t){case s.default.getFeeds.code:this.rulesData=e.data;break;case s.default.setFeeds.code:this.closeDialog(),this.getData()}},getData:function(){n.default.http(s.default.getFeeds,{},this.then)},editFeed:function(){},openDialog:function(e,t){this.form=e>-1?t[e]:{brand:""},console.log(this.form),this.dialogTableVisible=!0},closeDialog:function(){this.dialogTableVisible=!1},updateDialog:function(){var e=this.$refs.feedsForm.getFormData();console.log("form",e),e&&n.default.http(s.default.setFeeds,e,this.then)},upDateFormat:function(e){return e.uptime>0?n.default.date("MM-DD HH时",e.uptime):"-.-"}}}},97:function(e,t,l){"use strict";function a(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var o=l(0),r=(a(o),l(8)),n=a(r),i=l(5);a(i);t.default={props:["form"],data:function(){return{brands:{vkingx:{label:"vkingx",value:"vkingx"},gnoce:{label:"Gnoce",value:"gnoce"},amarley:{label:"Amarley",value:"amarley"}},rules:{brand:[{required:!0,message:"请选择品牌",trigger:"blur"}],url:[{required:!0,message:"请填写url",trigger:"blur"}]}}},mounted:function(){},methods:{getFormData:function(){var e=!1;return this.$refs.form.validate(function(t,l){t&&(e=!0)}),e?this.form:(n.default.toast("请完善信息"),!1)}}}}});
//# sourceMappingURL=7.build.js.map?4d698a2ffe0fe9958222