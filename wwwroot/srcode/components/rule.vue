<style lang="stylus" rel="stylesheet/scss">

</style>
<template>
	<div>
		<el-form label-position="top" label-width="80px" :model="form">
			<input v-model="form.id" type="hidden"></input>
			<input v-model="form.code" type="hidden"></input>
			<input v-model="form.xml" type="hidden"></input>
			<el-form-item label="规则名称（尽量描述清楚规则）">
				<el-input v-model="form.name"></el-input>
			</el-form-item>
			<el-form-item label="规则片段大小(不设定将根据代码字节大小判断)">
				<el-select v-model="form.type" placeholder="请选择片段大小">
					<el-option label="简单规则" value="0"></el-option>
					<el-option label="小型片段" value="1"></el-option>
					<el-option label="大型片段" value="2"></el-option>
				</el-select>
			</el-form-item>
			<el-form-item label="规则内容">
				<div id="blocklyDiv" :style="{height: blockly.height+'px', width: blockly.width+'px'}"></div>
			</el-form-item>
			<el-form-item>
				<el-button type="primary" @click="onSubmit">保存规则</el-button>
			</el-form-item>
		</el-form>
	</div>
</template>
<script>
    import Vue from 'vue'
    import ElementUI from 'element-ui'
    import 'element-ui/lib/theme-default/index.css'
    import Blockly from 'node-blockly/browser';
    import BlocklyLibrary from '../blockly/';
    import vk from '../vk.js';
    import uri from '../uri.js';

    Vue.use(ElementUI)

	export default {
        data:function(){
        	return {
                blockly:{
                    width:1200,
					height:500
				},
                form:{
                    id:'',
                    name:'',
					xml:'',
					code:'',
					type:''
				},
			}
		},
        props: function(){
            return {
				title: {
					type: String,
					default: ''
				}
			}
        },
        methods:{

            onSubmit:function(){
                 vk.http(uri.updateRulesData,this.form,this.then);
			},
			then:function(json,code){
                this.form={
                    id:'',
                    name:'',
                    xml:'',
                    code:'',
                    type:''
				};
                this.workspace.clear();
                this.appendXML('<xml xmlns="http://www.w3.org/1999/xhtml"></xml>');
				vk.toast('操作成功','msg');
				this.$emit('showRulesView');
			},
            editInfo:function (obj) {
                this.workspace.clear();
				this.form.id=obj.id;
                this.form.type=obj.type;
                this.form.name=obj.name;
                this.form.code=obj.code;
				this.appendXML(obj.xml);
            },
            appendXML:function(xml){
			    var that=this;
			    setTimeout(function(){
                    xml=Blockly.Xml.textToDom(xml);
                    Blockly.Xml.domToWorkspace(xml, that.workspace);
				},300);

			}
		},
        mounted(){
            var that=this;
            /////
			
            /////
            Object.keys(BlocklyLibrary).forEach((key) => {
                console.log('BlocklyLibrary',key);
                BlocklyLibrary[key].init();
            });
            var toolbox = '<xml>';
            toolbox +='<category name="限定设置" colour="180">';
            toolbox += '	<block type="budget_limit"></block>';
            toolbox += '</category>';
            toolbox +='<category name="起因" colour="210">';
            toolbox += '	<block type="controls_if"></block>';
            toolbox += '	<block type="logic_operation"></block>';
            toolbox += '	<block type="data_comparison"></block>';
            toolbox += '</category>';
            toolbox +='<category name="执行" colour="120">';
            toolbox += '	<block type="implement"></block>';
            toolbox += '</category>';
            toolbox += '</xml>';
            that.workspace = Blockly.inject('blocklyDiv', {
                toolbox : toolbox,
                collapse : false,
                comments : false,
                disable : false,
                maxBlocks : Infinity,
                trashcan : false,
                horizontalLayout : false,
                toolboxPosition : 'start',
                css : true,
                media : 'https://blockly-demo.appspot.com/static/media/',
                rtl : false,
                scrollbars : true,
                sounds : true,
                oneBasedIndex : true,
                grid : {
                    spacing : 20,
                    length : 1,
                    colour : '#888',
                    snap : false
                },
                zoom:{
                    controls: false,
					wheel: true,
					startScale: 1.0,
					maxScale: 3,
					minScale: 0.0,
					scaleSpeed: 1.2
				}
            });

            //var xml = Blockly.Xml.textToDom('<xml xmlns="http://www.w3.org/1999/xhtml"><block type="controls_if" id="U`szQH_6DX+}`!5N|`)." x="320" y="108"><value name="IF0"><block type="logic_operation" id="^BvpYE2|Km20iQ]|S7#2"><field name="OP">AND</field><value name="A"><block type="data_comparison" id="*}IB^AFE,JE{)i(}:Nq_"><field name="field">Campaign Name</field><field name="expression">&gt;</field><field name="value">value</field></block></value></block></value></block></xml>');
            that.workspace.addChangeListener(function(){

            	that.form.code=Blockly.PHP.workspaceToCode(that.workspace);
                var coding_xml_dom=Blockly.Xml.workspaceToDom(that.workspace);
                that.form.xml=Blockly.Xml.domToText(coding_xml_dom);
                console.log(that.form.code);
			});
            //Blockly.inject();
            Blockly.svgResize(that.workspace);

            //Blockly.Xml.domToWorkspace(xml, that.workspace);
			
        }
    }
</script>