webpackJsonp([0],{107:function(t,e,r){e=t.exports=r(1)(),e.push([t.i,".login-msg,.msg{padding:50px;text-align:center}.msg{font-size:20px;color:red}",""])},109:function(t,e,r){t.exports=r.p+"logo.png?e1ea82cb1c39656b925012efe60f22ea"},115:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("v-header",{attrs:{title:"首页"}},[t.user.id?r("router-link",{attrs:{to:"/home"},slot:"right"},[t._v(t._s(t.user.name))]):t._e()],1),t._v(" "),t.user.id?t._e():r("div",{staticClass:"login-msg"},[r("router-link",{attrs:{to:"/login"}},[t._v("你还未登录，请先登录")])],1),t._v(" "),t.user.id?r("div",{staticClass:"msg"},[r("img",{attrs:{width:"50",src:t.logo,alt:""}}),t._v(" "),r("br"),t._v("\n\t\t哈哈，恭喜你已经入坑Vue2\n\t")]):t._e()],1)},staticRenderFns:[]}},122:function(t,e,r){var n=r(107);"string"==typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);r(3)("3f86dd42",n,!0)},92:function(t,e,r){r(122);var n=r(2)(r(99),r(115),null,null);t.exports=n.exports},99:function(t,e,r){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var n=r(8),s=r(109),o=function(t){return t&&t.__esModule?t:{default:t}}(s);e.default={data:function(){return{logo:o.default}},computed:(0,n.mapState)({user:function(t){return t.user}})}}});
//# sourceMappingURL=0.build.js.map?d231960ba638feea347b