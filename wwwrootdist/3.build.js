webpackJsonp([3],{116:function(t,e,a){e=t.exports=a(1)(),e.push([t.i,"#text-controls{display:inline-block;vertical-align:top;border:1px dotted #ccc;padding:5%;width:88%}#text-cmd-italic{font-style:italic}#text-cmd-underline{text-decoration:underline}#text-cmd-linethrough{text-decoration:line-through}#text-cmd-overline{text-decoration:overline}.el-slider{float:right;width:65%;margin-right:2%}",""])},118:function(t,e,a){e=t.exports=a(1)(),e.push([t.i,".canvas-view{border:1px solid #ccc;overflow:hidden}.canvas-view-feeds .el-select{width:100%}#text-controls{display:inline-block;vertical-align:top;border:1px dotted #ccc;padding:10px}#text-cmd-italic{font-style:italic}#text-cmd-underline{text-decoration:underline}#text-cmd-linethrough{text-decoration:line-through}#text-cmd-overline{text-decoration:overline}.popover-shape div{margin-bottom:10px}",""])},123:function(t,e,a){e=t.exports=a(1)(),e.push([t.i,"canvas{border:1px solid #ccc}.el-popover div.img-mark{background-color:#fff;border:1px dashed #ccc}",""])},143:function(t,e,a){a(174);var i=a(2)(a(96),a(154),null,null);t.exports=i.exports},144:function(t,e,a){a(172);var i=a(2)(a(97),a(152),null,null);t.exports=i.exports},152:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("el-form",{ref:"object",attrs:{"label-position":"left",model:t.object}},[a("label",{attrs:{for:"color"}},[t._v("Fill / Stroke / Background:")]),t._v(" "),a("el-color-picker",{on:{change:t.setValFill},model:{value:t.object.fill,callback:function(e){t.object.fill=e},expression:"object.fill"}}),t._v(" "),a("el-color-picker",{on:{change:t.setValStroke},model:{value:t.object.stroke,callback:function(e){t.object.stroke=e},expression:"object.stroke"}}),t._v(" "),a("el-color-picker",{on:{change:t.setValTextBackgroundColor},model:{value:t.object.textBackgroundColor,callback:function(e){t.object.textBackgroundColor=e},expression:"object.textBackgroundColor"}}),t._v(" "),a("el-form-item",{attrs:{label:"Opacity"}},[a("el-slider",{attrs:{min:0,max:1,step:.05,"format-tooltip":t.formatOpacityTooltip},on:{change:t.setValOpacity},model:{value:t.object.opacity,callback:function(e){t.object.opacity=e},expression:"object.opacity"}})],1),t._v(" "),a("el-form-item",{attrs:{label:"Stroke width"}},[a("el-slider",{attrs:{min:0,max:20,step:1},on:{change:t.setValStrokeWidth},model:{value:t.object.strokeWidth,callback:function(e){t.object.strokeWidth=e},expression:"object.strokeWidth"}})],1),t._v(" "),a("el-form-item",{attrs:{label:"Angle"}},[a("el-slider",{attrs:{min:0,max:360,step:5},on:{change:t.setValAngle},model:{value:t.object.angle,callback:function(e){t.object.angle=e},expression:"object.angle"}})],1),t._v(" "),"textbox"==t.object.type?a("div",{attrs:{id:"text-wrapper"}},[a("div",{attrs:{id:"text-controls"}},[a("p",[t._v("Text specific controls")]),t._v(" "),a("el-input",{attrs:{type:"textarea",autosize:{minRows:2,maxRows:4}},on:{change:t.setValText,blur:t.setValText},model:{value:t.object.text,callback:function(e){t.object.text=e},expression:"object.text"}}),t._v(" "),a("el-form-item",{attrs:{label:"Font family"}},[a("el-select",{attrs:{placeholder:"请选择"},on:{change:t.setValFontFamily},model:{value:t.object.fontFamily,callback:function(e){t.object.fontFamily=e},expression:"object.fontFamily"}},[a("el-option",{attrs:{value:"arial"}},[t._v("Arial")]),t._v(" "),a("el-option",{attrs:{value:"helvetica"}},[t._v("Helvetica")]),t._v(" "),a("el-option",{attrs:{value:"myriad pro"}},[t._v("Myriad Pro")]),t._v(" "),a("el-option",{attrs:{value:"delicious"}},[t._v("Delicious")]),t._v(" "),a("el-option",{attrs:{value:"verdana"}},[t._v("Verdana")]),t._v(" "),a("el-option",{attrs:{value:"georgia"}},[t._v("Georgia")]),t._v(" "),a("el-option",{attrs:{value:"courier"}},[t._v("Courier")]),t._v(" "),a("el-option",{attrs:{value:"comic sans ms"}},[t._v("Comic Sans MS")]),t._v(" "),a("el-option",{attrs:{value:"impact"}},[t._v("Impact")]),t._v(" "),a("el-option",{attrs:{value:"monaco"}},[t._v("Monaco")]),t._v(" "),a("el-option",{attrs:{value:"optima"}},[t._v("Optima")]),t._v(" "),a("el-option",{attrs:{value:"hoefler text"}},[t._v("Hoefler Text")]),t._v(" "),a("el-option",{attrs:{value:"plaster"}},[t._v("Plaster")]),t._v(" "),a("el-option",{attrs:{value:"engagement"}},[t._v("Engagement")])],1)],1),t._v(" "),a("el-form-item",{attrs:{label:"Text align"}},[a("el-select",{attrs:{placeholder:"请选择"},on:{change:t.setValTextAlign},model:{value:t.object.textAlign,callback:function(e){t.object.textAlign=e},expression:"object.textAlign"}},[a("el-option",{attrs:{value:"left"}},[t._v("Left")]),t._v(" "),a("el-option",{attrs:{value:"center"}},[t._v("Center")]),t._v(" "),a("el-option",{attrs:{value:"right"}},[t._v("Right")]),t._v(" "),a("el-option",{attrs:{value:"justify"}},[t._v("Justify")])],1)],1),t._v(" "),a("div",[a("label",{attrs:{for:"text-lines-bg-color"}},[t._v("Background text color:")]),t._v(" "),a("el-color-picker",{on:{change:t.setValBackgroundColor},model:{value:t.object.backgroundColor,callback:function(e){t.object.backgroundColor=e},expression:"object.backgroundColor"}})],1),t._v(" "),a("el-form-item",{attrs:{label:"Font size"}},[a("el-slider",{attrs:{min:8,max:70,step:1},on:{change:t.setValFontSize},model:{value:t.object.fontSize,callback:function(e){t.object.fontSize=e},expression:"object.fontSize"}})],1),t._v(" "),a("el-form-item",{attrs:{label:"Line height"}},[a("el-slider",{attrs:{min:0,max:10,step:.1},on:{change:t.setValLineHeight},model:{value:t.object.lineHeight,callback:function(e){t.object.lineHeight=e},expression:"object.lineHeight"}})],1),t._v(" "),a("el-form-item",{attrs:{label:"Char spacing"}},[a("el-slider",{attrs:{min:-10,max:200,step:5},on:{change:t.setValCharSpacing},model:{value:t.object.charSpacing,callback:function(e){t.object.charSpacing=e},expression:"object.charSpacing"}})],1),t._v(" "),a("el-button",{attrs:{size:"mini",type:"primary"},on:{click:function(e){t.toggleTextStyle("fontWeight:bold")}}},[t._v("\n                    Bold\n                ")]),t._v(" "),a("el-button",{attrs:{size:"mini",type:"primary",id:"text-cmd-italic"},on:{click:function(e){t.toggleTextStyle("fontStyle:italic")}}},[t._v("\n                    Italic\n                ")]),t._v(" "),a("el-button",{attrs:{size:"mini",type:"primary",id:"text-cmd-underline"},on:{click:function(e){t.toggleTextStyle("textDecoration:underline")}}},[t._v("\n                    Underline\n                ")]),t._v(" "),a("el-button",{attrs:{size:"mini",type:"primary",id:"text-cmd-linethrough"},on:{click:function(e){t.toggleTextStyle("textDecoration:line-through")}}},[t._v("\n                    Linethrough\n                ")]),t._v(" "),a("el-button",{attrs:{size:"mini",type:"primary",id:"text-cmd-overline"},on:{click:function(e){t.toggleTextStyle("textDecoration:overline")}}},[t._v("\n                    Overline\n                ")])],1)]):t._e(),t._v(" "),a("div",[a("el-button",{attrs:{type:"primary"},on:{click:t.shadowify}},[t._v("阴 影")])],1),t._v(" "),a("el-button",{attrs:{type:"text"},on:{click:function(e){t.removeObject()}}},[t._v("\n            Remove selected object\n        ")]),t._v(" "),a("el-button",{attrs:{type:"text"},on:{click:function(e){t.removeObject("ALL")}}},[t._v("\n            Remove All objects\n        ")])],1)],1)},staticRenderFns:[]}},154:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("el-form",{ref:"form",attrs:{"label-position":"left",model:t.form,"label-width":"120px",rules:t.rules}},[a("el-form-item",{attrs:{label:"Mark Name",prop:"name"}},[a("el-input",{model:{value:t.form.name,callback:function(e){t.form.name=e},expression:"form.name"}})],1),t._v(" "),a("el-form-item",{staticClass:"canvas-view-feeds",attrs:{label:"Feeds",prop:"fid"}},[a("el-select",{attrs:{placeholder:"请选择Feed"},on:{change:t.feedChange},model:{value:t.form.fid,callback:function(e){t.form.fid=e},expression:"form.fid"}},t._l(t.feeds,function(t){return a("el-option",{key:t.id,attrs:{label:t.url,value:t.id}})}))],1),t._v(" "),a("div",{staticClass:"canvas-view"},[a("div",{staticClass:"grid-content bg-purple",staticStyle:{"background-color":"#cffffc",padding:"5px"}},[a("el-button",{attrs:{type:"primary",icon:"edit"},on:{click:t.addTextbox}},[t._v("Add Text")]),t._v(" "),a("el-button",{attrs:{type:"primary",icon:"picture"}},[t._v("\n                    Add Image\n                ")]),t._v(" "),a("input",{staticStyle:{filter:"alpha(opacity=0)",opacity:"0",width:"122px",height:"25px",overflow:"hidden","margin-left":"-125px",position:"relative",top:"8px"},attrs:{type:"file",id:"file"},on:{change:t.getFilePath}}),t._v(" "),a("el-popover",{ref:"popover_shape",attrs:{placement:"bottom",width:"400",trigger:"click"}},[a("el-row",{staticClass:"popover-shape",attrs:{gutter:20}},[a("el-col",{attrs:{span:6}},[a("el-button",{attrs:{type:"primary"},on:{click:t.addShapeLine}},[t._v("直 线")])],1),t._v(" "),a("el-col",{attrs:{span:6}},[a("el-button",{attrs:{type:"primary"},on:{click:t.addShapeRect}},[t._v("四边形")])],1),t._v(" "),a("el-col",{attrs:{span:6}},[a("el-button",{attrs:{type:"primary"},on:{click:t.addShapeCircle}},[t._v("圆 形")])],1),t._v(" "),a("el-col",{attrs:{span:6}},[a("el-button",{attrs:{type:"primary"},on:{click:t.addShapeTriangle}},[t._v("三角形")])],1)],1)],1),t._v(" "),a("el-button",{directives:[{name:"popover",rawName:"v-popover:popover_shape",arg:"popover_shape"}],attrs:{type:"primary",icon:"star-on"}},[t._v("\n                    Add Shape\n                ")])],1),t._v(" "),a("el-row",{attrs:{gutter:24}},[a("el-col",{attrs:{span:10}},[a("div",{staticClass:"grid-content bg-purple"},[a("canvas",{style:t.imageStyle,attrs:{height:t.image.height,width:t.image.width,id:"tu"}})]),t._v(" "),a("el-button",{directives:[{name:"show",rawName:"v-show",value:t.imageStyle,expression:"imageStyle"}],attrs:{type:"text",icon:"caret-right"},on:{click:t.flushImage}},[t._v("换个图看看效果")])],1),t._v(" "),a("el-col",{attrs:{span:14}},[a("div",{staticClass:"tab-content",staticStyle:{height:"300px",padding:"5px","overflow-y":"auto"}},[a("feedsMarkFormScope",{ref:"feedsMarkFormScope",attrs:{canvas:t.canvas}})],1)])],1)],1)],1)],1)},staticRenderFns:[]}},159:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"mytable"},[a("v-headerTop"),t._v(" "),a("el-col",{staticStyle:{height:"100%"},attrs:{span:4}},[a("div",{staticClass:"grid-left bg-purple-darkc overflow-y",attrs:{id:"app_left_menu"}},[a("v-leftMenu")],1)]),t._v(" "),a("el-col",{staticStyle:{height:"100%"},attrs:{span:20}},[a("div",{staticStyle:{padding:"10px"}},[a("el-button",{staticStyle:{float:"right","z-index":"1",position:"relative"},attrs:{type:"primary"},on:{click:function(e){t.openDialog(-1)}}},[a("i",{staticClass:"el-icon-plus"}),t._v("Add Feed Mark")]),t._v(" "),a("el-tabs",{model:{value:t.activeName,callback:function(e){t.activeName=e},expression:"activeName"}},[a("el-tab-pane",{attrs:{label:"Feed Mark Lists",name:"getRulesData"}},[a("el-table",{staticStyle:{width:"100%"},attrs:{data:t.rulesData,border:"","max-height":"100%"}},[a("el-table-column",{attrs:{width:"110",label:"Thumb"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-popover",{attrs:{placement:"right",title:"",trigger:"hover"}},[a("div",{staticClass:"img-mark",staticStyle:{position:"relative"}},[a("img",{attrs:{src:e.row.mark_bgimg}}),t._v(" "),a("img",{staticStyle:{position:"absolute",top:"0",left:"0"},attrs:{src:e.row.mark_img_path}})]),t._v(" "),a("div",{staticStyle:{position:"relative"},slot:"reference"},[a("img",{attrs:{height:"100",src:e.row.mark_bgimg}}),t._v(" "),a("img",{staticStyle:{position:"absolute",top:"0",left:"0"},attrs:{height:"100",src:e.row.mark_img_path}})])])]}}])}),t._v(" "),a("el-table-column",{attrs:{prop:"name",label:"Mark Name",width:"120"}}),t._v(" "),a("el-table-column",{attrs:{prop:"mark_url",label:"Mark URL"}}),t._v(" "),a("el-table-column",{attrs:{label:"Feed URL"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v("\n                                ["+t._s(e.row.brand)+"] "+t._s(e.row.url)+"\n                            ")]}}])}),t._v(" "),a("el-table-column",{attrs:{prop:"count_items",label:"商品数",width:"60"}}),t._v(" "),a("el-table-column",{attrs:{label:"操作",width:"120"},scopedSlots:t._u([{key:"default",fn:function(e){return[a("el-button",{attrs:{type:"text",size:"small"},nativeOn:{click:function(a){a.preventDefault(),t.openDialog(e.$index,t.rulesData)}}},[t._v("\n                                    修改\n                                ")])]}}])})],1)],1)],1)],1)]),t._v(" "),a("el-dialog",{attrs:{title:"Update Feed Mark",visible:t.dialogTableVisible,"close-on-click-modal":!1,"close-on-press-escape":!1,size:"large"},on:{"update:visible":function(e){t.dialogTableVisible=e}}},[a("feedsMarkForm",{ref:"feedsMarkForm",attrs:{form:t.form,feeds:t.feeds}}),t._v(" "),a("span",{staticClass:"dialog-footer",slot:"footer"},[a("el-button",{on:{click:t.closeDialog}},[t._v("取 消")]),t._v(" "),a("el-button",{attrs:{type:"primary"},on:{click:t.updateDialog}},[t._v("确 定")])],1)],1)],1)},staticRenderFns:[]}},172:function(t,e,a){var i=a(116);"string"==typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);a(4)("2bcf7f64",i,!0)},174:function(t,e,a){var i=a(118);"string"==typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);a(4)("0f18351e",i,!0)},179:function(t,e,a){var i=a(123);"string"==typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);a(4)("904de3d2",i,!0)},67:function(t,e,a){a(179);var i=a(2)(a(95),a(159),null,null);t.exports=i.exports},95:function(t,e,a){"use strict";function i(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var o=a(0),n=(i(o),a(8)),l=i(n),s=a(5),r=i(s),c=a(143),d=i(c);e.default={components:{feedsMarkForm:d.default},data:function(){return{activeName:"getRulesData",rulesData:[],feeds:[],dialogTableVisible:!1,form:{}}},mounted:function(){this.getData(),l.default.http(r.default.getFeeds,{},this.then)},methods:{then:function(t,e){switch(e){case r.default.getFeeds.code:this.feeds=t.data;break;case r.default.getFeedsMark.code:this.rulesData=t.data;break;case r.default.setFeedsMark.code:this.closeDialog(),this.getData()}},getData:function(){l.default.http(r.default.getFeedsMark,{},this.then)},editFeed:function(){},openDialog:function(t,e){this.form=t>-1?e[t]:{fid:""},console.log("openDialog",this.form);var a=this;setTimeout(function(){a.dialogTableVisible=!0,a.$refs.feedsMarkForm.initPage()},100)},closeDialog:function(){this.dialogTableVisible=!1},updateDialog:function(){var t=this.$refs.feedsMarkForm.getFormData();console.log("form",t),t&&l.default.http(r.default.setFeedsMark,t,this.then)},upDateFormat:function(t){return t.uptime>0?l.default.date("MM-DD HH时",t.uptime):"-.-"}}}},96:function(t,e,a){"use strict";function i(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var o=a(0),n=(i(o),a(8)),l=i(n),s=a(5),r=i(s),c=a(144),d=i(c);!function(t,e,a){var i,o=t.getElementsByTagName(e)[0];t.getElementById(a)||(i=t.createElement(e),i.id=a,i.src="//cdnjs.cloudflare.com/ajax/libs/fabric.js/1.7.17/fabric.min.js",o.parentNode.insertBefore(i,o))}(document,"script","fabric-jssdk"),e.default={props:["form","feeds"],components:{feedsMarkFormScope:d.default},data:function(){return{initCanvasInterval:null,rules:{fid:[{required:!0,message:"请选择Feed",trigger:"blur"}],name:[{required:!0,message:"请填写Mark Name",trigger:"blur"}]},canvas:null,object:{text:""},image:{width:200,height:200,url:""},imageStyle:""}},mounted:function(){this.mountedInitCanvas(),this.form.fid&&l.default.http(r.default.getFeedsImageInfo,{fid:this.form.fid},this.then)},methods:{mountedInitCanvas:function(){var t=!(arguments.length>0&&void 0!==arguments[0])||arguments[0],e=this;"undefined"==typeof fabric?this.initCanvasInterval=setInterval(function(){e.initCanvas(t)},500):e.initCanvas(t)},then:function(t,e){switch(e){case r.default.getFeedsImageInfo.code:this.image=t.data,this.imageStyle="background-image: url("+t.data.url+");width:"+t.data.width+"px;height:"+t.data.height+"px;",this.mountedInitCanvas(!1)}},initPage:function(){console.log("feedsMarkForm.vue","initPage",this.form),this.form.fid?l.default.http(r.default.getFeedsImageInfo,{fid:this.form.fid},this.then):(this.image={width:200,height:200,url:""},this.imageStyle="",this.mountedInitCanvas()),this.$refs.feedsMarkFormScope.initPage()},initCanvas:function(t){if(console.log("initCanvasInterval..."),"undefined"==typeof fabric)return void console.log("initCanvasInterval...");if(clearInterval(this.initCanvasInterval),this.initCanvasInterval=null,this.canvas||(this.canvas=new fabric.Canvas("tu"),this.watchCanvas()),this.canvas.setHeight(this.image.height),this.canvas.setWidth(this.image.width),t&&this.canvas.clear(),this.canvas.renderAll(),this.form.mark_object){var e=this;this.canvas.loadFromJSON(this.form.mark_object,function(){e.canvas.renderAll()})}},flushImage:function(){this.form.mark_object=JSON.stringify(this.canvas),l.default.http(r.default.getFeedsImageInfo,{fid:this.form.fid},this.then)},getFormData:function(){var t=!1;return this.$refs.form.validate(function(e,a){e&&(t=!0)}),this.form.json=JSON.stringify(this.canvas),this.form.image_base64=this.toImage(),t?this.form:(l.default.toast("请完善信息"),!1)},feedChange:function(t){t&&l.default.http(r.default.getFeedsImageInfo,{fid:t},this.then)},pad:function(t,e){for(;t.length<e;)t="0"+t;return t},getRandomColor:function(){var t=fabric.util.getRandomInt;return this.pad(t(0,255).toString(16),2)+this.pad(t(0,255).toString(16),2)+this.pad(t(0,255).toString(16),2)},addTextbox:function(){var t=fabric.util.getRandomInt,e=new fabric.Textbox("请输入内容,\n请输入内容",{fontSize:20,left:t(0,this.image.width/2),top:t(0,this.image.height/2),fontFamily:"helvetica",angle:0,fill:"#"+this.getRandomColor(),fontWeight:"",originX:"left",width:parseInt(this.image.width/2),hasRotatingPoint:!0,centerTransform:!0});this.canvas.add(e)},watchCanvas:function(){this.canvas.on("object:selected",this.$refs.feedsMarkFormScope.updateScope).on("group:selected",this.$refs.feedsMarkFormScope.updateScope).on("path:created",this.$refs.feedsMarkFormScope.updateScope).on("selection:cleared",this.$refs.feedsMarkFormScope.updateScope)},toImage:function(){if(fabric.Canvas.supports("toDataURL")){return this.canvas.toDataURL({multiplier:1,format:"png"})}l.default.alert("This browser doesn't provide means to serialize canvas to an image")},getFilePath:function(t){console.log(arguments),t=t.target;var e=t.files[0];if(e){var a=this,i=new FileReader;i.onload=function(t){fabric.Image.fromURL(t.target.result,function(t){if(!(t.width<1||t.height<1)){var e=1;a.image.width<t.width&&(e=a.image.width/t.width),t.set({left:0,top:0,angle:0}).scale(e).setCoords(),a.canvas.add(t)}})},i.readAsDataURL(e)}},addShapeRect:function(){this.canvas.add(new fabric.Rect({left:0,top:parseInt(this.canvas.height/3),fill:"#"+this.getRandomColor(),width:this.canvas.width,height:this.canvas.height/3,opacity:1}))},addShapeLine:function(){this.canvas.add(new fabric.Line([0,parseInt(this.canvas.height/2),this.canvas.width,parseInt(this.canvas.height/2)],{left:0,top:parseInt(this.canvas.height/2),stroke:"#"+this.getRandomColor(),strokeWidth:5}))},addShapeCircle:function(){this.canvas.add(new fabric.Circle({left:0,top:0,fill:"#"+this.getRandomColor(),radius:parseInt(this.canvas.width/3),opacity:1}))},addShapeTriangle:function(){this.canvas.add(new fabric.Triangle({left:parseInt(this.canvas.width/3),top:parseInt(this.canvas.height/3),fill:"#"+this.getRandomColor(),width:parseInt(this.canvas.width/3),height:parseInt(this.canvas.height/3),opacity:1}))}}}},97:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i={prop:["text","textAlign","fontFamily","backgroundColor","textBackgroundColor"],style:["opacity","fill","fontWeight","textDecoration","stroke","strokeWidth","fontSize","lineHeight","charSpacing","fontStyle","angle"]},o={},n={};for(var l in i)for(var s in i[l]){var r=i[l][s];["opacity","strokeWidth","fontSize","lineHeight","charSpacing","angle"].indexOf(r)>-1?(o[r]=1,n[r]=1):(o[r]="",n[r]="")}e.default={props:["canvas"],data:function(){return{fields:i,object:o}},mounted:function(){this.getObjectAll()},methods:{initPage:function(){console.log("feedsMarkFormScope.vue","initPage...",n),Object.assign(this.object,n)},updateScope:function(){this.canvas.renderAll(),this.getObjectAll()},getActiveProp:function(t){if(!this.canvas)return!1;var e=this.canvas.getActiveObject();return e?e[t]||"":""},setActiveProp:function(t,e){if(this.canvas){var a=this.canvas.getActiveObject();a&&(a.set(t,e).setCoords(),this.canvas.renderAll())}},getActiveStyle:function(t,e){return this.canvas?(e=e||this.canvas.getActiveObject(),e?e.getSelectionStyles&&e.isEditing?e.getSelectionStyles()[t]||"":e[t]||"":""):""},setActiveStyle:function(t,e,a){if(!this.canvas)return"";if(a=a||this.canvas.getActiveObject()){if(a.setSelectionStyles&&a.isEditing){var i={};i[t]=e,a.setSelectionStyles(i),a.setCoords()}else a.set(t,e);a.setCoords(),this.canvas.renderAll()}},getObjectAll:function(){if(this.canvas){var t=this.canvas.getActiveObject();if(t){console.log("canvas.getActiveObject",t);for(var e in this.fields)for(var a in this.fields[e]){var i=this.fields[e][a],o="prop"==e?this.getActiveProp(i):this.getActiveStyle(i);this.object[i]=o||""}return this.object.type=t.type,console.log("this.object",this.object),this.object}}},formatOpacityTooltip:function(t){return 100*t+" %"},getVal:function(t){return this.fields.prop.indexOf(t)>-1?this.getActiveProp(t):this.getActiveStyle(t)},setVal:function(t,e){this.fields.prop.indexOf(t)>-1?this.setActiveProp(t,e):this.setActiveStyle(t,e)},setValOpacity:function(t){this.setVal("opacity",t)},setValTextBackgroundColor:function(t){this.setVal("textBackgroundColor",t)},setValStroke:function(t){this.setVal("stroke",t)},setValFill:function(t){this.setVal("fill",t)},setValStrokeWidth:function(t){this.setVal("strokeWidth",t)},setValFontFamily:function(t){this.setVal("fontFamily",t)},setValTextAlign:function(t){this.setVal("textAlign",t)},setValBackgroundColor:function(t){this.setVal("backgroundColor",t)},setValFontSize:function(t){this.setVal("fontSize",t)},setValLineHeight:function(t){this.setVal("lineHeight",t)},setValCharSpacing:function(t){this.setVal("charSpacing",t)},setValText:function(t){this.setVal("text",t)},setValAngle:function(t){this.setVal("angle",t)},toggleTextStyle:function(t){t=t.split(":"),this.getVal(t[0])?this.setVal(t[0],""):this.setVal(t[0],t[1])},removeObject:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";this.canvas&&("ALL"==t&&confirm("Are you sure?")&&this.canvas.clear(),this.canvas.remove(this.canvas.getActiveObject()))},shadowify:function(){var t=this.canvas.getActiveObject();t&&(t.shadow?t.shadow=null:t.setShadow({color:"rgba(0,0,0,0.3)",blur:10,offsetX:10,offsetY:10}),this.canvas.renderAll())}}}}});
//# sourceMappingURL=3.build.js.map?f66d09b43d55d4ed4263