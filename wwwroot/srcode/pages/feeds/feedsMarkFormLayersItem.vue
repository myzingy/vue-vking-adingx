<style lang="stylus" rel="stylesheet/scss">

</style>
<template>
    <div>
        <span v-if=" layer.type=='textbox' " :style="{color:layer.fill}">{{layer.text}}</span>
        <div v-if=" layer.type=='image' " class="image"
             :style="{'background-image':'url('+layer.getSrc()+')'}">
            &nbsp;
        </div>
        <div v-if=" layer.type=='line' " :style="{color:'#fff','background-color':layer.stroke}">{{layer.type}}</div>
        <div v-if=" layer.type=='triangle' || layer.type=='circle' || layer.type=='rect' "
             :style="{color:'#fff','background-color':layer.fill}">{{layer
            .type}}</div>
    </div>
</template>

<script>
    import Vue from 'vue'
    export default {
        props:['layer'],
        data:function(){
            return {

            }
        },
        mounted(){
            console.log('feedsMarkFormLayersItem.vue','mounted...',this.layer);
        },
        methods: {
        }
    }
</script>