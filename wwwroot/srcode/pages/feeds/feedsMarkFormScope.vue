<style lang="stylus" rel="stylesheet/scss">
    /*
    #text-controls {
        display: inline-block;
        vertical-align: top;
        border: 1px dotted #ccc;
        padding: 5%;
        width:88%;
    }


    */
    .el-slider {
        width: 90%;
        margin-left: 0px;
        max-width: 300px;
    }
    #text-cmd-italic {
        font-style: italic;
    }
    #text-cmd-underline {
        text-decoration: underline;
    }
    #text-cmd-linethrough {
        text-decoration: line-through;
    }
    #text-cmd-overline {
        text-decoration: overline;
    }
</style>
<template>
    <div>
        <el-form label-position="top" ref="object" :model="object" v-show="objectSelected">
            <el-form-item label="Fill / Stroke / Background" v-if="object.type!='image'">
                <div>
                    <el-color-picker v-model="object.fill" @change="setValFill"></el-color-picker>
                    <el-color-picker v-model="object.stroke" @change="setValStroke"></el-color-picker>
                    <el-color-picker v-model="object.textBackgroundColor"
                                     @change="setValTextBackgroundColor"></el-color-picker>
                </div>
            </el-form-item>

            <el-form-item label="Opacity">
                <el-slider v-model="object.opacity" :min="0.0" :max="1.0" :step="0.05"
                           :format-tooltip="formatOpacityTooltip"
                           @change="setValOpacity"></el-slider>
            </el-form-item>
            <el-form-item label="Stroke width" v-if="object.type!='image'">
                <el-slider v-model="object.strokeWidth" :min="0" :max="20" :step="1"
                           @change="setValStrokeWidth"></el-slider>
            </el-form-item>
            <el-form-item label="Angle">
                <el-slider v-model="object.angle" :min="0" :max="360" :step="5"
                           @change="setValAngle"></el-slider>
            </el-form-item>

            <div id="text-wrapper" v-if="object.type=='textbox'">
                <div id="text-controls">
                    <p>Text specific controls</p>
                    <el-input type="textarea" v-model="object.text" :autosize="{ minRows: 2, maxRows: 4}"
                              @change="setValText"></el-input>
                    <el-form-item label="Font family">
                        <el-select v-model="object.fontFamily" placeholder="请选择" @change="setValFontFamily">
                            <el-option value="arial">Arial</el-option>
                            <el-option value="helvetica">Helvetica</el-option>
                            <el-option value="myriad pro">Myriad Pro</el-option>
                            <el-option value="delicious">Delicious</el-option>
                            <el-option value="verdana">Verdana</el-option>
                            <el-option value="georgia">Georgia</el-option>
                            <el-option value="courier">Courier</el-option>
                            <el-option value="comic sans ms">Comic Sans MS</el-option>
                            <el-option value="impact">Impact</el-option>
                            <el-option value="monaco">Monaco</el-option>
                            <el-option value="optima">Optima</el-option>
                            <el-option value="hoefler text">Hoefler Text</el-option>
                            <el-option value="plaster">Plaster</el-option>
                            <el-option value="engagement">Engagement</el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Text align">
                        <el-select v-model="object.textAlign" placeholder="请选择" @change="setValTextAlign">
                            <el-option value="left">Left</el-option>
                            <el-option value="center">Center</el-option>
                            <el-option value="right">Right</el-option>
                            <el-option value="justify">Justify</el-option>
                        </el-select>
                    </el-form-item>
                    <div>
                        <label for="text-lines-bg-color">Background text color:</label>
                        <el-color-picker v-model="object.backgroundColor"
                                         @change="setValBackgroundColor"></el-color-picker>
                    </div>
                    <el-form-item label="Font size">
                        <el-slider v-model="object.fontSize" :min="8" :max="70" :step="1"
                                   @change="setValFontSize"></el-slider>
                    </el-form-item>
                    <el-form-item label="Line height">
                        <el-slider v-model="object.lineHeight" :min="0" :max="10" :step="0.1"
                                   @change="setValLineHeight"></el-slider>
                    </el-form-item>
                    <el-form-item label="Char spacing">
                        <el-slider v-model="object.charSpacing" :min="-10" :max="200" :step="5"
                                   @change="setValCharSpacing"></el-slider>
                    </el-form-item>
                    
                    <el-button size="mini" type="primary" @click="toggleTextStyle('fontWeight:bold')">
                        Bold
                    </el-button>
                    <el-button size="mini" type="primary" id="text-cmd-italic"
                               @click="toggleTextStyle('fontStyle:italic')">
                        Italic
                    </el-button>
                    <el-button size="mini" type="primary" id="text-cmd-underline"
                            @click="toggleTextStyle('textDecoration:underline')">
                        Underline
                    </el-button>
                    <el-button size="mini" type="primary" id="text-cmd-linethrough"
                            @click="toggleTextStyle('textDecoration:line-through')">
                        Linethrough
                    </el-button>
                    <el-button size="mini" type="primary" id="text-cmd-overline"
                            @click="toggleTextStyle('textDecoration:overline')">
                        Overline
                    </el-button>
                    <el-button size="mini" type="primary" @click="shadowify">阴 影</el-button>
                </div>
            </div>

            <el-button type="text"
                       @click="removeObject()">
                删除选中的图层
            </el-button>
            <el-button type="text"
                       @click="removeObject('ALL')">
                删除所有的图层
            </el-button>
            
        </el-form>
        <el-form label-position="top" ref="object" :model="background" v-show="!objectSelected && image.url">
            <el-form-item label="素材图缩放">
                <el-slider v-model="background.size" :min="50" :max="150" :step="5"
                           @change="setBackground"></el-slider>
            </el-form-item>
            <el-form-item label="素材图水平位移">
                <el-slider v-model="background.position.x" :min="-50" :max="image.width-0" :step="5"
                           @change="setBackground"></el-slider>
            </el-form-item>
            <el-form-item label="素材图垂直位移">
                <el-slider v-model="background.position.y" :min="-50" :max="image.height-0" :step="5"
                           @change="setBackground"></el-slider>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    var __fields={
        'prop':['text', 'textAlign','fontFamily','backgroundColor','textBackgroundColor'],
        'style':['opacity','fill','fontWeight','textDecoration','stroke','strokeWidth',
            'fontSize', 'lineHeight','charSpacing','fontStyle','angle'],
    };
    var __object={};
    var __objectInit={};
    for(var i in __fields){
        for(var j in __fields[i]){
            var key=__fields[i][j];
            if(['opacity','strokeWidth','fontSize', 'lineHeight','charSpacing','angle'].indexOf(key)>-1){
                __object[key]=0;
                __objectInit[key]=0;
            }else{
                __object[key]="";
                __objectInit[key]="";
            }
        }
    }
    export default {
        props:['canvas','image'],
        data:function(){
            return {
                fields: __fields,
                object:__object,
                objectSelected:false,
                background:{
                    size:100,
                    position:{
                        x:0,
                        y:0,
                    },
                },
            }
        },
        mounted(){
            this.getObjectAll();
        },
        methods: {
            initPage(background){
                console.log('feedsMarkFormScope.vue','initPage...',background);
                Object.assign(this.object,__objectInit);
                if(background){
                    this.background=background;
                }else{
                    this.background={
                        size:100,
                        position:{
                            x:0,
                            y:0,
                        },
                    };
                }
            },
            updateScope() {
                this.canvas.renderAll();
                this.getObjectAll();
            },
            getActiveProp(name) {
                if (!this.canvas) return false;
                var object = this.canvas.getActiveObject();
                if (!object) return '';

                return object[name] || '';
            },
            setActiveProp(name, value) {
                if (!this.canvas) return;
                var object = this.canvas.getActiveObject();
                if (!object) return;
                object.set(name, value).setCoords();
                this.canvas.renderAll();
            },
            getActiveStyle(styleName, object) {
                if(!this.canvas)  return '';
                object = object || this.canvas.getActiveObject();
                if (!object) return '';

                return (object.getSelectionStyles && object.isEditing)
                    ? (object.getSelectionStyles()[styleName] || '')
                    : (object[styleName] || '');
            },

            setActiveStyle(styleName, value, object) {
                if(!this.canvas)  return '';
                object = object || this.canvas.getActiveObject();
                if (!object) return;

                if (object.setSelectionStyles && object.isEditing) {
                    var style = { };
                    style[styleName] = value;
                    object.setSelectionStyles(style);
                    object.setCoords();
                }
                else {
                    object.set(styleName, value);
                }

                object.setCoords();
                this.canvas.renderAll();
            },
            getObjectAll () {
                console.log('getObjectAll....');
                if(!this.canvas)  return;
                var object=this.canvas.getActiveObject();
                if(!object) {
                    this.objectSelected=false;
                    return;
                }
                this.objectSelected=true;
                console.log('canvas.getActiveObject',object);
                for(var i in this.fields){
                    for(var j in this.fields[i]){
                        var key=this.fields[i][j];
                        var val=(i=='prop')?this.getActiveProp(key):this.getActiveStyle(key);
                        this.object[key]=val || __objectInit[key];
                    }
                }
                this.object['type']=object.type;
                console.log('this.object',this.object);
                return this.object;
            },
            formatOpacityTooltip(val){
                return (val*100)+' %';
            },
            ////////////////////
            getVal(key){
                if(this.fields['prop'].indexOf(key)>-1){
                    return this.getActiveProp(key);
                }else{
                    return this.getActiveStyle(key);
                }
            },
            setVal(key,val){
                if(this.fields['prop'].indexOf(key)>-1){
                    this.setActiveProp(key,val);
                }else{
                    this.setActiveStyle(key,val);
                }
            },
            setValOpacity(val){
                this.setVal('opacity',val);
            },
            setValTextBackgroundColor(val){
                this.setVal('textBackgroundColor',val);
            },
            setValStroke(val){
                this.setVal('stroke',val);
            },
            setValFill(val){
                this.setVal('fill',val);
            },
            setValStrokeWidth(val){
                this.setVal('strokeWidth',val);
            },
            setValFontFamily(val){
                this.setVal('fontFamily',val);
            },
            setValTextAlign(val){
                this.setVal('textAlign',val);
            },
            setValBackgroundColor(val){
                this.setVal('backgroundColor',val);
            },
            setValFontSize(val){
                this.setVal('fontSize',val);
            },
            setValLineHeight(val){
                this.setVal('lineHeight',val);
            },
            setValCharSpacing(val){
                this.setVal('charSpacing',val);
            },
            setValText(val){
                try{
                    if(typeof val!='string'){
                        val=val.target.value;
                    }
                }catch(e){
                    val="";
                }
                console.log(arguments);
                this.setVal('text',val);
            },
            setValAngle(val){
                this.setVal('angle',val);
            },
            toggleTextStyle(val){
                val=val.split(':');
                var isf=this.getVal(val[0]);
                if(!isf){
                    this.setVal(val[0],val[1]);
                }else{
                    this.setVal(val[0],"");
                }
            },
            removeObject(all=""){
                if(!this.canvas)  return;
                if (all=="ALL" && confirm('Are you sure?')) {
                    this.canvas.clear();
                }
                this.canvas.remove(this.canvas.getActiveObject())
            },
            shadowify () {
                var obj = this.canvas.getActiveObject();
                if (!obj) return;

                if (obj.shadow) {
                    obj.shadow = null;
                }
                else {
                    obj.setShadow({
                        color: 'rgba(0,0,0,0.3)',
                        blur: 10,
                        offsetX: 10,
                        offsetY: 10
                    });
                }
                this.canvas.renderAll();
            },
            setBackground(){
                this.$emit('setBackground');
            }
        }
    }
</script>