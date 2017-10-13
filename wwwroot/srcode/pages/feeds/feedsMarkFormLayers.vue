<style lang="stylus" rel="stylesheet/scss">
    .layer-menu{
        z-index: 9998;
        position: absolute;
        right:5px;
        bottom: 5px;
        width:32px;
        height: 30px;
        overflow: hidden;
        padding: 5px;
        background-color: #99ccff;
        cursor: pointer;
        i{
            font-size: 30px;
            color:#fff;
        }
    }
    .layer{
        z-index: 9999;
        position: absolute;
        right:0px;
        bottom: 0px;
        width:330px;
        height:700px;
        padding: 5px;
        display: none;
        overflow: hidden;
        background-color: #99ccff;
        cursor: pointer;
        .layer-header{
            font-size: 30px;
            color:#fff;
        }
        .layer-content{
            height: 620px;
            overflow-x: hidden;
            overflow-y: auto;
            background-color: #90c0f0;
            padding-bottom: 8px;
        }
        &.opened,&:hover{
            display: block;
        }
        .layer-footer{
            position: absolute;
            bottom: 0;
            font-size: 30px;
            color:#fff;
            display: none;
        }
        .layer-item{
            background-color: #99ccff;
            margin:8px 8px 0;
            .image{
                background-size: auto 100%;
            }
            *{padding:8px;}
            max-height: 50px;
            overflow: hidden;
        }
    }
</style>
<template>
    <div>
        <div class="layer-menu" @click="toggleLayer('show')">
            <i class="el-icon-menu" title="layer"></i>
        </div>
        <div :class="layer_css" @mouseover="toggleLayer('hide')" @mouseout="toggleLayer('hide')">
            <div class="layer-header" @click="toggleLayer">
                <i :class="layer_icon_css" title="layer"></i>
                Layers
            </div>
            <div class="layer-content">
                <draggable v-model="layers" :options="{group:'layers'}" @update="datadragEnd">
                    <transition-group>
                        <div class="layer-item" v-for="(layer,layer_id) in layers" :key="layer_id">
                            <feedsMarkFormLayersItem :layer="layer"></feedsMarkFormLayersItem>    
                        </div>
                    </transition-group>
                </draggable>
            </div>
            <div class="layer-footer">
                <draggable :options="{group:'layers'}">
                    <i class="el-icon-delete" title="layer"></i>
                </draggable>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import draggable from 'vuedraggable'
    import feedsMarkFormLayersItem from './feedsMarkFormLayersItem.vue';
    export default {
        components: {
            draggable,feedsMarkFormLayersItem
        },
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
        },
        methods: {
            toggleLayer(type){
                //el-icon-arrow-down
                if(type=='show'){
                    this.layer_css='layer opened';
                    this.setLayers();
                }else{
                    this.layer_css='layer';
                }
            },
            setLayers(){
                this.isSetLayers=false;
                var layers=[];
                Object.assign(layers,this.canvas.getObjects());
                layers.reverse();
                this.layers=layers;
                console.log('this.layers',this.layers);
            },
            datadragEnd(evt){
                var layers=[];
                Object.assign(layers,this.layers);
                //layers[evt.oldIndex]=this.layers[evt.newIndex];
                //layers[evt.newIndex]=this.layers[evt.oldIndex];
                layers.reverse();
                var that=this;
                that.canvas.loadFromJSON(JSON.stringify({'objects':layers}), function(){
                    that.canvas.renderAll();
                });
            },
            getLayerView(layer){
                return "<a>"+layer.type+"</a>"
            },

        }
    }
</script>