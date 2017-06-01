<style lang="stylus" rel="stylesheet/scss">

</style>
<template>
	<div>
		<el-form label-position="top" label-width="80px" :model="form">
			<el-input v-model="form.id" type="hidden"></el-input>
			<el-input v-model="form.code" type="hidden"></el-input>
			<el-input v-model="form.xml" type="hidden"></el-input>
			<el-form-item label="规则名称">
				<el-input v-model="form.name" @change="setxml"></el-input>
			</el-form-item>
			<el-form-item label="规则内容">
				<div id="blocklyDiv" :style="{height: blockly.height+'px', width: blockly.width+'px'}"></div>
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
					code:''
				},
			}
		},
        props: {
            title: {
                type: String,
                default: ''
            }
        },
        methods:{
            setxml:function(){
                var xml = Blockly.Xml.textToDom('<xml xmlns="http://www.w3.org/1999/xhtml"><block type="controls_if" id="U`szQH_6DX+}`!5N|1`)." x="320" y="208"><value name="IF0"><block type="logic_operation" id="^BvpYE2|Km20iQ]|S7#21"><field name="OP">AND</field><value name="A"><block type="data_comparison" id="*}IB^AFE,JE{)i(}:Nq_"><field name="field">Campaign Name</field><field name="expression">&gt;</field><field name="value">value</field></block></value></block></value></block></xml>');
                Blockly.Xml.domToWorkspace(xml, this.workspace);
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
                }
            });

            var xml = Blockly.Xml.textToDom('<xml xmlns="http://www.w3.org/1999/xhtml"><block type="controls_if" id="U`szQH_6DX+}`!5N|`)." x="320" y="108"><value name="IF0"><block type="logic_operation" id="^BvpYE2|Km20iQ]|S7#2"><field name="OP">AND</field><value name="A"><block type="data_comparison" id="*}IB^AFE,JE{)i(}:Nq_"><field name="field">Campaign Name</field><field name="expression">&gt;</field><field name="value">value</field></block></value></block></value></block></xml>');
            Blockly.Xml.domToWorkspace(xml, that.workspace);

            that.workspace.addChangeListener(function(){

            	that.form.code=Blockly.PHP.workspaceToCode(that.workspace);
                var coding_xml_dom=Blockly.Xml.workspaceToDom(that.workspace);
                that.form.xml=Blockly.Xml.domToText(coding_xml_dom);
                console.log(that.form.code);
			});
            Blockly.svgResize(that.workspace);
        },
    }
</script>