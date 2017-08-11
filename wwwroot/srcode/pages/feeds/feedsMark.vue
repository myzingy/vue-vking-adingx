<style lang="stylus" rel="stylesheet/scss">
    canvas{ border: 1px solid #ccc;}
</style>
<template>
    <div class="mytable">
        <v-headerTop></v-headerTop>
        <el-col :span="4" style="height:100%;">
            <div class="grid-left bg-purple-darkc overflow-y"
                 id="app_left_menu">
                <v-leftMenu></v-leftMenu>
            </div>
        </el-col>
        <el-col :span="20" style="height:100%;">
            <div style="padding: 10px;">
                <canvas :height="image.height" width="image.width" id="tu"></canvas>
            </div>
        </el-col>
    </div>
</template>

<script>
    import Vue from 'vue'
    import vk from '../../vk.js';
    import uri from '../../uri.js';
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//cdnjs.cloudflare.com/ajax/libs/fabric.js/1.7.17/fabric.min.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'fabric-jssdk'));
    export default {
        data:function(){
            return {
                initCanvasInterval:null,
                image:{
                    width:300,
                    height:500,
                },
            }
        },
        mounted(){
            var that=this;
            this.initCanvasInterval=setInterval(function(){
                that.initCanvas();
            },500);
        },
        methods:{
            initCanvas(){
                console.log('initCanvasInterval...');
                if(typeof fabric!='undefined'){
                    clearInterval(this.initCanvasInterval);
                    this.initCanvasInterval=null;
                }else{
                    console.log('initCanvasInterval...');
                    return;
                }
                var canvas = new fabric.Canvas('tu');
                var rect = new fabric.Rect({
                    top : 50,
                    left : 100,
                    width : 100,
                    height : 70,
                    fill : 'red'
                });
                canvas.add(rect);
            }
        },
    }
</script>