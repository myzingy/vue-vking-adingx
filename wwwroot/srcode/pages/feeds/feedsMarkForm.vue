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
                    <el-button type="primary" @click="addTextbox()" icon="edit">Add Text</el-button>
                    <el-button type="primary" @click="addTextbox()" icon="picture">Add Image</el-button>
                </div>
                <el-row :gutter="24">
                    <el-col :span="10"><div class="grid-content bg-purple">
                        <canvas :height="image.height" :width="image.width" :style="imageStyle" id="tu"></canvas>
                    </div></el-col>
                    <el-col :span="14">
                        <div class="tab-content" style="height: 300px; padding: 5px; overflow-y: auto;">
                            <feedsMarkFormScope ref="feedsMarkFormScope" :object="object" :canvas="canvas"></feedsMarkFormScope>
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
                image:{
                    width:200,
                    height:200,
                    url:"",
                },
                imageStyle:"",
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
            }
        },
        mounted(){
            var that=this;
            this.initCanvasInterval=setInterval(function(){
                that.initCanvas();
            },500);
        },
        methods: {
            then: function (json, code) {
                switch (code) {
                    case uri.getFeedsImageInfo.code:
                        this.image = json.data;
                        this.imageStyle = 'background-image: url(' + json.data.url + ');width:' + json.data.width + 'px;height:' + json.data.height + 'px;';
                        this.initCanvas();
                        break;
                }
            },
            initCanvas(){
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
                this.canvas.renderAll();
            },
            getFormData(){
                var flag = false;
                this.$refs['form'].validate((valid, err) => {
                    if (valid) {
                        flag = true;
                    }
                });
                if (flag) {
                    return this.form;
                }
                vk.toast('请完善信息');
                return false;
            },
            feedChange(fid){
                this.image.url = "";
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
                    //angle: getRandomInt(-10, 10),
                    angle: 0,
                    fill: '#' + this.getRandomColor(),
                    fontWeight: '',
                    originX: 'left',
                    width: this.image.width,
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
        }
    }
</script>