<style lang="stylus" rel="stylesheet/scss">
    .viewImageDialog img{
        /*your style here*/
        background-color:#99ccff;
        border: 1px dashed #999999;
        min-height: 265px;
    }
    img[lazy=loading] {
        /*your style here*/
        background-color:#9fcfff;
    }
    img[lazy=loaded] {
        /*your style here*/
        background-color:#ffffff;
    }
    img[lazy=error] {
        /*your style here*/
        background-color:#ff0000;
    }
</style>
<template>
    <div :style="'height:'+height+'px;overflow-y:auto;overflow-x:hidden;'" class="viewImageDialog">
        <el-row :gutter="24" v-if="viewImages.length>0" justify="center">
            <el-col :span="6" v-for="src in viewImages" key="key" >
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
        preLoad: 1.3,
        error: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJAQMAAADaX5RTAAAAA3NCSVQICAjb4U/gAAAABlBMVEX///+ZmZmOUEqyAAAAAnRSTlMA/1uRIrUAAAAJcEhZcwAACusAAArrAYKLDVoAAAAWdEVYdENyZWF0aW9uIFRpbWUAMDkvMjAvMTIGkKG+AAAAHHRFWHRTb2Z0d2FyZQBBZG9iZSBGaXJld29ya3MgQ1M26LyyjAAAAB1JREFUCJljONjA8LiBoZyBwY6BQQZMAtlAkYMNAF1fBs/zPvcnAAAAAElFTkSuQmCC',
        loading: 'http://img.zcool.cn/community/0161f656b0663e6ac7256cb052d31a.gif',
        attempt: 0,
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