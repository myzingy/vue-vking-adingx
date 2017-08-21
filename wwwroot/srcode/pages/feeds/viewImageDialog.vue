<style lang="stylus" rel="stylesheet/scss">
    .viewImageDialog img{
        /*your style here*/
        background-color:#99ccff;
        min-height: 265px;
    }
    img[lazy=loading] {
        /*your style here*/
        background-color:#9fcfff;
    }
    img[lazy=loaded] {
        /*your style here*/
        min-height: 265px;
        border: 1px dashed #999999;
    }
</style>
<template>
    <div :style="'height:'+height+'px;overflow-y:auto;overflow-x:hidden;'" class="viewImageDialog">
        <el-row :gutter="24" v-if="viewImages.length>0" justify="center">
            <el-col :span="6" v-for="src in viewImages" >
                <div class="grid-content bg-purple">
                    <img v-lazy="src"/>
                </div>
            </el-col>
        </el-row>
        <div v-else="">
            <h2>loading...</h2>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import VueLazyload from 'vue-lazyload'
    Vue.use(VueLazyload, {
        preLoad: 0,
        error: 'http://a0.att.hudong.com/77/31/20300542906611142174319458811.jpg',
        //loading: 'http://img.zcool.cn/community/0161f656b0663e6ac7256cb052d31a.gif',
        attempt: 1
    })
    export default {
        props:['viewImages'],
        data:function(){
            return {
                height:window.screen.height -300 ,
            }
        },
        mounted(){
            
        },
        methods: {
        }
    }
</script>