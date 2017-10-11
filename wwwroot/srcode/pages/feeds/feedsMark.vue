<style lang="stylus" rel="stylesheet/scss">
    canvas{ border: 1px solid #ccc;}
    .el-popover div.img-mark{ background-color: #fff; border: 1px dashed #ccc}
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
                <el-button type="primary" @click="openDialog(-1)" style=" float: right;z-index: 1;position: relative;">
                    <i class="el-icon-plus"></i>Add Feed Mark</el-button>
                <el-tabs v-model="activeName">
                    <el-tab-pane label="Feed Mark Lists" name="getRulesData">
                        <el-table :data="rulesData" border style="width: 100%" max-height="100%">
                            <el-table-column width="110" label="Thumb">
                                <template scope="scope">
                                    <el-popover placement="right" title="" trigger="hover">
                                        <div class="img-mark">
                                            <img :src="scope.row.mark_img_path"/>
                                        </div>
                                        <div slot="reference">
                                            <img height="100" :src="scope.row.mark_img_path"/>
                                        </div>
                                    </el-popover>
                                </template>
                            </el-table-column>
                            <el-table-column prop="name" label="Mark Name" width="120"></el-table-column>
                            <el-table-column prop="name" label="Img Size" width="120" :formatter="formatCanvasSize">
                            </el-table-column>
                            <el-table-column prop="mark_url" label="Mark URL"></el-table-column>
                            <el-table-column label="Feed URL">
                                <template scope="scope">
                                    [{{scope.row.brand}}] {{scope.row.url}}
                                </template>
                            </el-table-column>
                            <el-table-column prop="count_items" label="商品数" width="60"></el-table-column>
                            <el-table-column label="操作" width="120">
                                <template scope="scope">
                                    <el-button @click.native.prevent="openDialog(scope.$index, rulesData)"
                                               type="text"
                                               size="small">
                                        修改
                                    </el-button>
                                    <el-button @click.native.prevent="openDialog(scope.$index, rulesData,true)"
                                               type="text"
                                               size="small">
                                        复制
                                    </el-button>
                                    <el-button @click.native.prevent="viewImageDialog(scope.$index, rulesData)"
                                               type="text"
                                               size="small">
                                        预览
                                    </el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                    </el-tab-pane>
                </el-tabs>
            </div>
        </el-col>
        <el-dialog title="Update Feed Mark" :visible.sync="dialogTableVisible" :close-on-click-modal="false"
                   :close-on-press-escape="false" size="large">
            <feedsMarkForm ref="feedsMarkForm" :form="form" :feeds="feeds"></feedsMarkForm>
            <span slot="footer" class="dialog-footer">
                        <el-button @click="closeDialog">取 消</el-button>
                        <el-button type="primary" @click="updateDialog">确 定</el-button>
                      </span>
        </el-dialog>
        <el-dialog title="View Feed Mark" :visible.sync="viewImageDialogVisible" :close-on-click-modal="false"
                   :close-on-press-escape="false" size="full">
            <viewImageDialog ref="viewImageDialog" :viewImages="viewImages"></viewImageDialog>
            <span slot="footer" class="dialog-footer">
                <el-button  @click="closeViewImageDialog">关 闭</el-button>
                        <el-button type="primary" @click="viewImageDialog">换一批</el-button>
                      </span>
        </el-dialog>
    </div>
</template>

<script>
    import Vue from 'vue'
    import vk from '../../vk.js';
    import uri from '../../uri.js';
    import feedsMarkForm from './feedsMarkForm.vue';
    import viewImageDialog from './viewImageDialog.vue';

    export default {
        components:{
            feedsMarkForm:feedsMarkForm,
            viewImageDialog:viewImageDialog,
        },
        data:function(){
            return {
                activeName: 'getRulesData',
                rulesData:[],
                feeds:[],
                dialogTableVisible:false,
                viewImageDialogVisible:false,
                form:{},
                viewImages:[],
            }
        },
        mounted(){
            this.getData();
            vk.http(uri.getFeeds,{},this.then);
        },
        methods:{
            then:function(json,code){
                switch(code){
                    case uri.getFeeds.code:
                        this.feeds=json.data;
                        break;
                    case uri.getFeedsMark.code:
                        this.rulesData=json.data;
                        break;
                    case uri.setFeedsMark.code:
                        this.closeDialog();
                        vk.setCache(uri.getFeedsMark,'');
                        this.getData();
                        break;
                }
            },
            getData(){
                vk.http(uri.getFeedsMark,{},this.then);
            },
            editFeed(){

            },
            openDialog(index,data,iscopy=false){
                if(index>-1){
                    var _d={};
                    Object.assign(_d,data[index]);
                    if(iscopy){
                        _d.id="";
                        _d.name=_d.name+'-[copy]';
                    }
                    this.form=_d;

                }else{
                    this.form={
                        fid:"",
                        background:{
                            position:{x:0,y:0},
                            size:100,
                            canvas_size:"",
                        },

                    };
                }
                console.log('openDialog',this.form);
                var that=this;
                that.dialogTableVisible=true;
                setTimeout(function(){
                    that.$refs.feedsMarkForm.initPage();
                },100);

            },
            closeDialog(){
                this.dialogTableVisible=false;
            },
            updateDialog(){
                var form=this.$refs.feedsMarkForm.getFormData();
                console.log('form',form);
                if(form){
                    vk.http(uri.setFeedsMark,form,this.then);
                }

            },
            upDateFormat(row){
                if(row.uptime>0){
                    return vk.date('MM-DD HH时',row.uptime);
                }
                return '-.-';
            },
            viewImageDialog(index,data){
                var list=[];
                if(data){
                    this.viewImagesSrc=data[index].mark_img_path.replace(/-[^\-]{32}-/,'-');
                    this.viewImagesCount=data[index].count_items;
                }
                this.viewImages=[];
                var max=parseInt(Math.random()*(this.viewImagesCount-100));
                for (var i=max;i<max+100;i++){
                    list.push(this.viewImagesSrc+'/q/'+i+'.jpeg');
                }
                this.viewImageDialogVisible=true;
                var that=this;
                setTimeout(function(){that.viewImages=list;},1000);
            },
            closeViewImageDialog(){
                this.viewImageDialogVisible=false;
            },
            formatCanvasSize(row, column){
                try{
                    return row.background.canvas_size;
                }catch(e){
                    return "";
                }

            }
        },
    }
</script>