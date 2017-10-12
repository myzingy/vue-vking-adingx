<style lang="stylus" rel="stylesheet/scss">
    .layer{
        z-index: 9999;
        position: absolute;
        right:5px;
        bottom: 5px;
        width:32px;
        height: 30px;
        overflow: hidden;
        padding: 5px;
        background-color: #99ccff;
        i{
            font-size: 30px;
            color:#fff;
        }
        /*&.opened{*/
            /*width:330px;*/
            /*height:500px;*/
        /*}*/
        &:hover{
            width:330px;
            height:500px;
        }
        .layer-footer{
            position: absolute;
            bottom: 0;
            display: none;
        }
        ul{margin: 0;padding: 0; margin-top: 10px;}
        li{ list-style: none; border: 1px solid #999999; padding:8px; margin-bottom: 6px;}
    }
</style>
<template>
    <div :class="layer_css" @mouseover="setLayers">
        <div class="layer-header" @click="toggleLayer">
            <i :class="layer_icon_css" title="layer"></i>
            Layers
        </div>
        <div class="layer-content">
            <ul>
                <li class="layer-item"
                        v-for="(layer,layer_id) in layers"
                    v-dragging="{ item: layer, list: layers, group: 'layer' }"
                        :key="layer_id"
                >{{layer.type}},{{layer.fill}},{{layer.stroke}}
                </li>
            </ul>
        </div>
        <div class="layer-footer">
            footer
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import VueDND from 'awe-dnd'
    Vue.use(VueDND)
    export default {
        props:['canvas'],
        data:function(){
            return {
                layer_css:'layer',
                layer_icon_css:'el-icon-menu',
                layers:[],
            }
        },
        mounted(){
            console.log('feedsMarkFormLayers.vue','mounted...',this.layers);
            var that=this;
            this.$dragging.$on('dragged', ({ value }) => {
                console.log('this.$dragging.$on dragged',value.item,value.list)
                var layers=[];
                Object.assign(layers,value.list);
                layers.reverse();
                that.canvas.loadFromJSON(JSON.stringify({'objects':layers}), function(){
                    that.canvas.renderAll();
                });
            })
            this.$dragging.$on('dragend', () => {

            })
        },
        methods: {
            initPage(background){
                console.log('feedsMarkFormLayers.vue','initPage...',background);
                Object.assign(this.object,__objectInit);
            },
            toggleLayer(){
                //el-icon-arrow-down
                if(this.layer_css=='layer'){
                    this.layer_css='layer opened';
                    this.layer_icon_css='el-icon-close';
                }else{
                    this.layer_css='layer';
                    this.layer_icon_css='el-icon-menu';
                }
            },
            setLayers(){
                var layers=[];
                Object.assign(layers,this.canvas.getObjects());
                layers.reverse();
                this.layers=layers;
                console.log('this.layers',this.layers);
            }

        }
    }
</script>