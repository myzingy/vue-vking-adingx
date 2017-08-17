<style lang="stylus" rel="stylesheet/scss">
    .canvas-view{ border: 1px solid #ccc; overflow: hidden;}
    .canvas-view-feeds .el-select{width:100%;}
    #text-controls {
        display: inline-block;
        vertical-align: top;
        border: 1px dotted #ccc;
        padding: 10px;
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
    .popover-shape div{
        margin-bottom: 10px;
    }
</style>
<template>
    <div>
        <el-form label-position="left" ref="form" :model="form" label-width="120px" :rules="rules">
            <el-form-item label="Mark Name" prop="name">
                <el-input v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item label="Feeds" prop="fid" class="canvas-view-feeds">
                <el-select v-model="form.fid" placeholder="请选择Feed" @change="feedChange">
                    <el-option
                            v-for="item in feeds"
                            :key="item.id"
                            :label="item.url"
                            :value="item.id">
                    </el-option>
                </el-select>
            </el-form-item>
            <div class="canvas-view">
                <div class="grid-content bg-purple" style="background-color:#cffffc;
                padding:5px; ">
                    <el-button type="primary" @click="addTextbox" icon="edit">Add Text</el-button>
                    <el-button type="primary" icon="picture">
                        Add Image
                    </el-button>
                    <input type="file" id="file"
                           @change="getFilePath" style="filter:alpha(opacity=0);opacity:0;width: 122px;
                           height: 25px;border:
                            1px solid #ccc; overflow: hidden; margin-left: -125px; position:relative; top:8px;"/>
                    <el-popover
                            ref="popover_shape"
                            placement="bottom"
                            width="400"
                            trigger="click">
                        <el-row :gutter="20" class="popover-shape">
                            <el-col :span="6">
                                <el-button type="primary" @click="addShapeLine">直 线</el-button>
                            </el-col>
                            <el-col :span="6">
                                <el-button type="primary" @click="addShapeRect">四边形</el-button>
                            </el-col>
                            <el-col :span="6">
                                <el-button type="primary" @click="addShapeCircle">圆 形</el-button>
                            </el-col>
                            <el-col :span="6">
                                <el-button type="primary" @click="addShapeTriangle">三角形</el-button>
                            </el-col>
                            <!--
                            <el-col :span="6"><el-button type="primary" @click="addTextbox(2)">女1</el-button></el-col>
                            <el-col :span="6"><el-button type="primary" @click="addTextbox(141)">女2</el-button></el-col>
                            <el-col :span="6"><el-button type="primary" @click="addTextbox(142)">女3</el-button></el-col>
                            <el-col :span="6"><el-button type="primary" @click="addTextbox(140)">女4</el-button></el-col>
                            <el-col :span="6"><el-button type="primary" @click="addTextbox(99)">分割线</el-button></el-col>
                            <el-col :span="6"><el-button type="primary" @click="addTextbox(98)">分割线</el-button></el-col>
                            <el-col :span="6"><el-button type="primary" @click="addTextbox(93)">分割线</el-button></el-col>
                            <el-col :span="6"><el-button type="primary" @click="addTextbox(59)">彩带</el-button></el-col>
                            <el-col :span="6"><el-button type="primary" @click="addTextbox(115)">花藤</el-button></el-col>
                            <el-col :span="6"><el-button type="primary" @click="addTextbox(91)">曲线树</el-button></el-col>
                            -->
                        </el-row>
                    </el-popover>
                    <el-button type="primary" icon="star-on" v-popover:popover_shape>
                        Add Shape
                    </el-button>
                </div>
                <el-row :gutter="24">
                    <el-col :span="10">
                        <div class="grid-content bg-purple">
                            <canvas :height="image.height" :width="image.width" :style="imageStyle" id="tu"></canvas>
                        </div>
                        <el-button type="text" icon="caret-right" v-show="imageStyle" @click="flushImage">换个图看看效果</el-button>
                    </el-col>
                    <el-col :span="14">
                        <div class="tab-content" style="height: 300px; padding: 5px; overflow-y: auto;">
                            <feedsMarkFormScope ref="feedsMarkFormScope" :canvas="canvas"></feedsMarkFormScope>
                        </div>
                    </el-col>
                </el-row>
            </div>
        </el-form>
    </div>
</template>

<script>
    import Vue from 'vue'
    import vk from '../../vk.js';
    import uri from '../../uri.js';
    import feedsMarkFormScope from './feedsMarkFormScope.vue';
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//cdnjs.cloudflare.com/ajax/libs/fabric.js/1.7.17/fabric.min.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'fabric-jssdk'));
    export default {
        props:['form','feeds'],
        components:{
            feedsMarkFormScope:feedsMarkFormScope,
        },
        data:function(){
            return {
                initCanvasInterval:null,
                rules:{
                    fid:[
                        { required: true, message: '请选择Feed', trigger: 'blur' }
                    ],
                    name:[
                        { required: true, message: '请填写Mark Name', trigger: 'blur' }
                    ],
                },
                canvas:null,
                object:{text:""},
                image:{
                    width:200,
                    height:200,
                    url:"",
                },
                imageStyle:"",
            }
        },
        mounted(){
            this.mountedInitCanvas();
            if(this.form.fid){
                vk.http(uri.getFeedsImageInfo, {fid: this.form.fid}, this.then);
            }
        },
        methods: {
            mountedInitCanvas(clear=true){
                var that=this;
                if (typeof fabric == 'undefined'){
                    this.initCanvasInterval=setInterval(function(){
                        that.initCanvas(clear);
                    },500);
                }else{
                    that.initCanvas(clear);
                }

            },
            then: function (json, code) {
                switch (code) {
                    case uri.getFeedsImageInfo.code:
                        this.image = json.data;
                        this.imageStyle = 'background-image: url(' + json.data.url + ');width:' + json.data.width + 'px;height:' + json.data.height + 'px;';
                        this.mountedInitCanvas(false);
                        break;
                }
            },
            initPage(){
                console.log('feedsMarkForm.vue','initPage',this.form);
                if(!this.form.fid){
                    this.image = {
                        width:200,
                        height:200,
                        url:"",
                    };
                    this.imageStyle="";
                    this.mountedInitCanvas();
                }else{
                    vk.http(uri.getFeedsImageInfo, {fid: this.form.fid}, this.then);
                }
                this.$refs.feedsMarkFormScope.initPage();
            },
            initCanvas(clear){
                console.log('initCanvasInterval...');
                if (typeof fabric != 'undefined') {
                    clearInterval(this.initCanvasInterval);
                    this.initCanvasInterval = null;

                } else {
                    console.log('initCanvasInterval...');
                    return;
                }
                if(!this.canvas){
                    this.canvas = new fabric.Canvas('tu');
                    this.watchCanvas();
                }
                this.canvas.setHeight(this.image.height);
                this.canvas.setWidth(this.image.width);
                if(clear){this.canvas.clear()};

                this.canvas.renderAll();
                if(this.form.mark_object){
                    var that=this;
                    this.canvas.loadFromJSON(this.form.mark_object, function(){
                        that.canvas.renderAll();
                    });
                }
            },
            flushImage(){
                this.form.mark_object=JSON.stringify(this.canvas);
                vk.http(uri.getFeedsImageInfo, {fid: this.form.fid}, this.then);
            },
            getFormData(){
                var flag = false;
                this.$refs['form'].validate((valid, err) => {
                    if (valid) {
                        flag = true;
                    }
                });
                this.form['json']=JSON.stringify(this.canvas);
                this.form['image_base64']=this.toImage();
                if (flag) {
                    return this.form;
                }
                vk.toast('请完善信息');
                return false;
            },
            feedChange(fid){
                if(!fid) return;
                vk.http(uri.getFeedsImageInfo, {fid: fid}, this.then);
            },
            pad(str, length) {
                while (str.length < length) {
                    str = '0' + str;
                }
                return str;
            },
            getRandomColor() {
                var getRandomInt = fabric.util.getRandomInt;
                return (
                    this.pad(getRandomInt(0, 255).toString(16), 2) +
                    this.pad(getRandomInt(0, 255).toString(16), 2) +
                    this.pad(getRandomInt(0, 255).toString(16), 2)
                );
            },
            addTextbox() {
                var text =
                    '请输入内容,\n请输入内容';
                var getRandomInt = fabric.util.getRandomInt;
                var textSample = new fabric.Textbox(text, {
                    fontSize: 20,
                    left: getRandomInt(0, this.image.width/2),
                    top: getRandomInt(0, this.image.height/2),
                    fontFamily: 'helvetica',
                    angle: 0,
                    fill: '#' + this.getRandomColor(),
                    fontWeight: '',
                    originX: 'left',
                    width: parseInt(this.image.width/2),
                    hasRotatingPoint: true,
                    centerTransform: true
                });

                this.canvas.add(textSample);
            },
            watchCanvas() {
                var that=this;
                this.canvas
                    .on('object:selected', this.$refs.feedsMarkFormScope.updateScope)
                    .on('group:selected', this.$refs.feedsMarkFormScope.updateScope)
                    .on('path:created', this.$refs.feedsMarkFormScope.updateScope)
                    .on('selection:cleared', this.$refs.feedsMarkFormScope.updateScope);
            },
            toImage () {
                if (!fabric.Canvas.supports('toDataURL')) {
                    vk.alert('This browser doesn\'t provide means to serialize canvas to an image');
                }
                else {
                    var data = this.canvas.toDataURL({ multiplier: 1, format: 'png' });
                    return data;
                }
            },
            getFilePath(input){
                console.log(arguments);
                input=input.target;
                var file=input.files[0];
                if(!file) return;
                
                var that=this;
                var reader = new FileReader();

                reader.onload=function(res){
                    //console.log('reader.onload',res.target);
                    fabric.Image.fromURL(res.target.result,function(image){
                        //console.log('fabric.Image.fromURL.onload',image);
                        if(image.width<1 || image.height<1) return;
                        var scale=1;
                        if(that.image.width<image.width){
                            scale=that.image.width/image.width;
                        }
                        image.set({
                            left: 0,
                            top: 0,
                            angle: 0
                        }).scale(scale).setCoords();
                        that.canvas.add(image);
                    });
                };
                reader.readAsDataURL(file);

            },
            addShapeRect() {
                this.canvas.add(new fabric.Rect({
                    left: 0,
                    top: parseInt(this.canvas.height/3),
                    fill: '#' + this.getRandomColor(),
                    width: this.canvas.width,
                    height: this.canvas.height/3,
                    opacity: 1
                }));
            },
            addShapeLine() {
                this.canvas.add(new fabric.Line([ 0, parseInt(this.canvas.height/2), this.canvas.width, parseInt(this.canvas.height/2)], {
                    left: 0,
                    top: parseInt(this.canvas.height/2),
                    stroke: '#' + this.getRandomColor(),
                    strokeWidth:5,
                }));
            },
            addShapeCircle() {
                this.canvas.add(new fabric.Circle({
                    left: 0,
                    top: 0,
                    fill: '#' + this.getRandomColor(),
                    radius: parseInt(this.canvas.width/3),
                    opacity: 1
                }));
            },
            addShapeTriangle() {
                this.canvas.add(new fabric.Triangle({
                    left: parseInt(this.canvas.width/3),
                    top: parseInt(this.canvas.height/3),
                    fill: '#' + this.getRandomColor(),
                    width: parseInt(this.canvas.width/3),
                    height: parseInt(this.canvas.height/3),
                    opacity: 1
                }));
            },
        }
    }
</script>