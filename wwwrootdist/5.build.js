webpackJsonp([5],{111:function(e,t,a){"use strict";function l(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var o=a(10),i=(l(o),a(9)),n=l(i),s=a(0),c=l(s),r=a(11),u=a(8),d=l(u),f=a(5),h=l(f),p=a(81),m=l(p);c.default.use(n.default),t.default={components:{accounts_fb:m.default},data:function(){return{activeName:"getRulesLog",dialogTableVisible:!1,rulesLog:[],form:{email:"",group_id:"0"},accountsChecked:[],accountsEmail:""}},computed:(0,r.mapState)({user:function(e){return e.user}}),mounted:function(){this.init()},methods:{init:function(){var e={};d.default.http(h.default.getUsers,e,this.then)},then:function(e,t){switch(t){case h.default.getUsers.code:this.rulesLog=e.data;break;case h.default.addUsers.code:case h.default.delUsers.code:d.default.toast("操作成功","msg"),this.init();break;case h.default.getAccountsForEmail.code:this.accountsChecked=e.data,this.$refs.accounts_fb.initChecked(this.accountsChecked);break;case h.default.setAccountsForEmail.code:this.dialogTableVisible=!1}},formatUserdata:function(e,t){return e.id?e.id+","+e.name:"NO-LOGIN"},deleteUser:function(e,t){var a=this;d.default.confirm("确认要删除此用户吗?",function(){d.default.http(h.default.delUsers,{email:t.email},a.then)})},addUser:function(){if(/.*@.*\.[a-z]{2,4}/.test(this.form.email))return void d.default.http(h.default.addUsers,this.form,this.then);d.default.toast("email address error")},changeUser:function(e,t){d.default.http(h.default.updateUsers,{group_id:e,user_id:t.id,email:t.email},this.then)},openDialog:function(e,t){this.dialogTableVisible=!0,this.accountsEmail=t.email,d.default.http(h.default.getAccountsForEmail,{email:t.email},this.then)},saveAccounts:function(){var e=this.$refs.accounts_fb.checked;d.default.http(h.default.setAccountsForEmail,{email:this.accountsEmail,checked:e},this.then)},dialogClose:function(){this.dialogTableVisible=!1}}}},132:function(e,t,a){t=e.exports=a(1)(),t.push([e.i,".el-table__expanded-cell{padding-top:0;padding-bottom:0}.el-table .cell,.el-table th>div{padding-left:3px;padding-right:3px}.el-table th>.cell{overflow:hidden;height:30px}",""])},175:function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"mytable"},[a("v-headerTop"),e._v(" "),a("el-col",{staticStyle:{height:"100%"},attrs:{span:4}},[a("div",{staticClass:"grid-left bg-purple-darkc overflow-y",attrs:{id:"app_left_menu"}},[a("v-leftMenu")],1)]),e._v(" "),a("el-col",{staticStyle:{height:"100%"},attrs:{span:20}},[a("div",[a("el-tabs",{model:{value:e.activeName,callback:function(t){e.activeName=t},expression:"activeName"}},[a("el-tab-pane",{attrs:{label:"用户列表",name:"getRulesLog"}},[a("el-form",{staticClass:"demo-form-inline",attrs:{inline:!0,model:e.form}},[a("el-form-item",{attrs:{label:"Email"}},[a("el-input",{attrs:{placeholder:"注册 facebook email"},model:{value:e.form.email,callback:function(t){e.form.email=t},expression:"form.email"}})],1),e._v(" "),a("el-form-item",{attrs:{label:"用户组"}},[a("el-select",{attrs:{placeholder:"请选择"},model:{value:e.form.group_id,callback:function(t){e.form.group_id=t},expression:"form.group_id"}},[a("el-option",{attrs:{label:"Admin",value:"0"}}),e._v(" "),a("el-option",{attrs:{label:"Advertisers",value:"1"}})],1)],1),e._v(" "),a("el-form-item",[a("el-button",{attrs:{type:"primary"},on:{click:e.addUser}},[e._v("添加用户")])],1)],1),e._v(" "),a("el-table",{staticStyle:{width:"100%"},attrs:{data:e.rulesLog,border:"","max-height":"750"}},[a("el-table-column",{attrs:{prop:"email",label:"Email",width:"250"}}),e._v(" "),a("el-table-column",{attrs:{formatter:e.formatUserdata,label:"用户数据"}}),e._v(" "),a("el-table-column",{attrs:{label:"用户组",width:"120"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("el-select",{attrs:{placeholder:"请选择"},on:{change:function(a){e.changeUser(t.row.group_id,t.row)}},model:{value:t.row.group_id,callback:function(e){t.row.group_id=e},expression:"scope.row.group_id"}},[a("el-option",{attrs:{label:"Admin",value:"0"}}),e._v(" "),a("el-option",{attrs:{label:"Advertisers",value:"1"}})],1)]}}])}),e._v(" "),a("el-table-column",{attrs:{label:"操作",width:"100"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("el-button",{attrs:{type:"text",size:"small"},on:{click:function(a){e.deleteUser(t.$index,t.row)}}},[e._v("\n                                        删除\n                                    ")])]}}])})],1)],1)],1),e._v(" "),a("el-dialog",{ref:"accountDialog",attrs:{title:"AD账户列表",visible:e.dialogTableVisible,"close-on-click-modal":!1,"close-on-press-escape":!1},on:{"update:visible":function(t){e.dialogTableVisible=t}}},[a("accounts_fb",{ref:"accounts_fb",attrs:{type:"nofb"}}),e._v(" "),a("span",{staticClass:"dialog-footer",slot:"footer"},[a("el-button",{on:{click:e.dialogClose}},[e._v("取 消")]),e._v(" "),a("el-button",{attrs:{type:"primary"},on:{click:e.saveAccounts}},[e._v("确 定")])],1)],1)],1)])],1)},staticRenderFns:[]}},198:function(e,t,a){var l=a(132);"string"==typeof l&&(l=[[e.i,l,""]]),l.locals&&(e.exports=l.locals);a(4)("0ea177ae",l,!0)},73:function(e,t,a){a(198);var l=a(2)(a(111),a(175),null,null);e.exports=l.exports},77:function(e,t,a){"use strict";function l(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var o=a(10),i=(l(o),a(9)),n=l(i),s=a(0),c=l(s),r=a(11),u=a(8),d=l(u),f=a(5),h=l(f);c.default.use(n.default),t.default={props:["type"],data:function(){return{rulesLog:[],checked:[],hashChecked:[]}},computed:(0,r.mapState)({user:function(e){return e.user}}),mounted:function(){var e={};"nofb"==this.type?d.default.http(h.default.getAcsList,e,this.then):d.default.http(h.default.getFBAccounts,e,this.then)},methods:{then:function(e,t){this.rulesLog=e.data,this.toggleSelection()},handleSelectionChange:function(e){this.checked=e},formatAccountName:function(e){return e.name?e.name:e.account_name},initChecked:function(e){this.hashChecked=e,this.toggleSelection()},toggleSelection:function(){var e=this,t=this.hashChecked;this.$refs.multipleAccountsTable.clearSelection(),console.log(this.checked),t.length>0&&setTimeout(function(){e.rulesLog.forEach(function(a){for(var l in t)if(t[l].account_id==a.account_id)try{e.$refs.multipleAccountsTable.toggleRowSelection(a,!0)}catch(e){}})},0)}}}},79:function(e,t,a){t=e.exports=a(1)(),t.push([e.i,".el-table__expanded-cell{padding-top:0;padding-bottom:0}.el-table .cell,.el-table th>div{padding-left:3px;padding-right:3px}.el-table th>.cell{overflow:hidden;height:30px}",""])},81:function(e,t,a){a(85);var l=a(2)(a(77),a(83),null,null);e.exports=l.exports},83:function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("el-table",{ref:"multipleAccountsTable",staticStyle:{width:"100%"},attrs:{data:e.rulesLog,border:"","max-height":"450"},on:{"selection-change":e.handleSelectionChange}},[a("el-table-column",{attrs:{type:"selection",width:"55"}}),e._v(" "),a("el-table-column",{attrs:{prop:"account_id",label:"Account ID",width:"180"}}),e._v(" "),a("el-table-column",{attrs:{formatter:e.formatAccountName,label:"Account Name"}})],1)],1)},staticRenderFns:[]}},85:function(e,t,a){var l=a(79);"string"==typeof l&&(l=[[e.i,l,""]]),l.locals&&(e.exports=l.locals);a(4)("6020f03e",l,!0)}});
//# sourceMappingURL=5.build.js.map?e661ed1e4bd31b595717