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
                <el-button type="primary" @click="openDialog(-1)" style=" float: right;z-index: 1;position: relative;">
                    <i class="el-icon-plus"></i>Add Feed</el-button>
                <el-tabs v-model="activeName">
                    <el-tab-pane label="Feed Lists" name="getRulesData">
                        <el-table :data="rulesData" border style="width: 100%" max-height="100%">
                            <el-table-column prop="id" label="ID" width="60"></el-table-column>
                            <el-table-column prop="brand" label="品牌"  width="60"></el-table-column>
                            <el-table-column prop="url" label="Feed URL"></el-table-column>
                            <el-table-column prop="count_items" label="商品数" width="60"></el-table-column>
                            <el-table-column prop="uptime" :formatter="upDateFormat" label="更新时间"
                                             width="120"></el-table-column>
                            <el-table-column label="操作" width="120">
                                <template scope="scope">
                                    <el-button @click.native.prevent="openDialog(scope.$index, rulesData)"
                                               type="text"
                                               size="small">
                                        修改
                                    </el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                    </el-tab-pane>
                </el-tabs>
            </div>
        </el-col>
        <el-dialog title="Update Feed" :visible.sync="dialogTableVisible" :close-on-click-modal="false"
                   :close-on-press-escape="false">
            <feedsForm ref="feedsForm" :form="form"></feedsForm>
            <span slot="footer" class="dialog-footer">
                        <el-button @click="closeDialog">取 消</el-button>
                        <el-button type="primary" @click="updateDialog">确 定</el-button>
                      </span>
        </el-dialog>
    </div>
</template>

<script>
    import Vue from 'vue'
    import vk from '../../vk.js';
    import uri from '../../uri.js';
    import feedsForm from './feedsForm.vue';

    export default {
        components:{
            feedsForm:feedsForm,
        },
        data:function(){
            return {
                activeName: 'getRulesData',
                rulesData:[],
                dialogTableVisible:false,
                form:{},
            }
        },
        mounted(){
            this.getData();
        },
        methods:{
            then:function(json,code){
                switch(code){
                    case uri.getFeeds.code:
                        this.rulesData=json.data;
                        break;
                    case uri.setFeeds.code:
                        this.closeDialog();
                        this.getData();
                        break;
                }
            },
            getData(){
                vk.http(uri.getFeeds,{},this.then);
            },
            editFeed(){

            },
            openDialog(index,data){
                if(index>-1){
                    this.form=data[index];
                }else{
                    this.form={
                        brand:"",
                    };
                }
                console.log(this.form);
                this.dialogTableVisible=true;
            },
            closeDialog(){
                this.dialogTableVisible=false;
            },
            updateDialog(){
                var form=this.$refs.feedsForm.getFormData();
                console.log('form',form);
                if(form){
                    vk.http(uri.setFeeds,form,this.then);
                }

            },
            upDateFormat(row){
                if(row.uptime>0){
                    return vk.date('MM-DD HH时',row.uptime);
                }
                return '-.-';
            },
        },
    }
</script>