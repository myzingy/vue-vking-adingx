webpackJsonp([2],{100:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var i in n)Object.prototype.hasOwnProperty.call(n,i)&&(e[i]=n[i])}return e},o=n(8),r=n(16);t.default={data:function(){return{btn:!1,form:{id:"",name:""}}},methods:i({},(0,o.mapActions)([r.USER_SIGNIN]),{submit:function(){this.btn=!0,this.form.id&&this.form.name&&(this.USER_SIGNIN(this.form),this.$router.replace({path:"/home"}))}})}},106:function(e,t,n){t=e.exports=n(1)(),t.push([e.i,".login{padding:50px;text-align:center}.login .line{padding:5px}.login .line input{padding:0 10px;line-height:28px}.login button{padding:0 20px;margin-top:20px;line-height:28px}",""])},114:function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[n("v-header",{attrs:{title:"登录"}},[n("router-link",{attrs:{to:"/"},slot:"left"},[e._v("返回")])],1),e._v(" "),n("form",{staticClass:"login",on:{submit:function(t){t.preventDefault(),e.submit(t)}}},[n("div",{staticClass:"line"},[n("div",{directives:[{name:"show",rawName:"v-show",value:e.btn&&!e.form.id,expression:"btn && !form.id"}]},[e._v("id不能为空")]),e._v(" "),n("input",{directives:[{name:"model",rawName:"v-model",value:e.form.id,expression:"form.id"}],attrs:{type:"number",placeholder:"输入你的id"},domProps:{value:e.form.id},on:{input:function(t){t.target.composing||(e.form.id=t.target.value)},blur:function(t){e.$forceUpdate()}}})]),e._v(" "),n("div",{staticClass:"line"},[n("div",{directives:[{name:"show",rawName:"v-show",value:e.btn&&!e.form.name,expression:"btn && !form.name"}]},[e._v("用户名不能为空")]),e._v(" "),n("input",{directives:[{name:"model",rawName:"v-model",value:e.form.name,expression:"form.name"}],attrs:{type:"text",placeholder:"输入你的用户名"},domProps:{value:e.form.name},on:{input:function(t){t.target.composing||(e.form.name=t.target.value)}}})]),e._v(" "),n("button",[e._v("登录")])])],1)},staticRenderFns:[]}},121:function(e,t,n){var i=n(106);"string"==typeof i&&(i=[[e.i,i,""]]),i.locals&&(e.exports=i.locals);n(3)("16577384",i,!0)},93:function(e,t,n){n(121);var i=n(2)(n(100),n(114),null,null);e.exports=i.exports}});
//# sourceMappingURL=2.build.js.map?d231960ba638feea347b