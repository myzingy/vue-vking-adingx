<style lang="stylus" rel="stylesheet/scss">
	.el-table__expanded-cell
		padding-top 0
		padding-bottom 0
	.el-table .cell, .el-table th>div
		padding-left 3px
		padding-right 3px
	 .el-table th>.cell
		 overflow hidden
		 height 30px
	 
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
                <el-form :inline="true" :model="formSearch" class="demo-form-inline">
                    <el-form-item label="视频">
                        <el-select v-model="formSearch.type" placeholder="请选择">
                            <el-option label="所有" value=""></el-option>
                            <el-option label="已绑视频" value="yes"></el-option>
                            <el-option label="未绑视频" value="no"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item>
                        <el-input v-model="formSearch.keyword" placeholder="SKU/Product Name"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="onFormSearch">查询</el-button>
                        <a href="javascript://" @click="onClearFormSearch">清空条件</a>
                    </el-form-item>
                </el-form>
                <el-tabs v-model="activeName" @tab-click="handleTabClick">
                    <el-tab-pane label="Products Video" name="getRulesLog">
                        <el-table :data="rulesLog" border style="width: 100%" max-height="700">
                            <el-table-column prop="id" label="ID" width="180"></el-table-column>
                            <el-table-column label="名称">
                                <template scope="scope">
                                    <a :href="scope.row.url" target="_blank">
                                        {{scope.row.retailer_id}},
                                        [{{scope.row.custom_label_4}}]
                                        {{scope.row.name}}
                                        ({{scope.row.price}})
                                    </a>
                                </template>
                            </el-table-column>
                            <el-table-column prop="video_ids" label="视频" width="180" >
                                <template scope="scope">
                                    <div v-if=" scope.row.video_ids=='[]' ">
                                        <a :href="scope.row.video_url" :title="scope.row.video_url" target="_blank">Video File</a>
                                        &nbsp;
                                        <el-button
                                                size="small"
                                                @click="bindProductVideo(scope.$index, scope.row)"
                                                type="primary">
                                            绑定视频
                                        </el-button>
                                    </div>

                                    <span v-else="">
                                        <el-button
                                                size="small"
                                                @click="unBindProductVideo(scope.$index, scope.row)"
                                                type="primary">
                                            解绑 {{scope.row.video_ids}}
                                        </el-button>
                                    </span>
                                </template>
                            </el-table-column>
                        </el-table>
                        <el-pagination style=" margin: 20px auto; width:300px;"
                                       @current-change="handleCurrentChange"
                                       :page-size="formSearch.limit"
                                       layout="total, prev, pager, next"
                                       :total="total">
                        </el-pagination>
                    </el-tab-pane>
                </el-tabs>
            </div>
        </el-col>
    </div>
</template>
<script>
    import Vue from 'vue'
    import { mapState } from 'vuex'
    import ElementUI from 'element-ui'
    import 'element-ui/lib/theme-default/index.css'
	import vk from '../../vk.js';
    import uri from '../../uri.js';
    import ad_table from '../ads/ad_table.vue';
    Vue.use(ElementUI)
    export default {
        components:{
            'v-ad_table':ad_table,
        },
        data:function(){
            return {
                activeName: 'getRulesLog',
                rulesLog:[],
                total:0,
                formSearch:{
                    limit:30,
                    offset:0,
                    keyword:"",
                    type:"",
                },
			}
		},
        computed: mapState({ user: state => state.user }),
        mounted(){
            this.getData();
        },
        methods:{
            getData(){
                vk.http(uri.getProducts,this.formSearch,this.then);
            },
            then:function(json,code){
                switch(code){
					case uri.getProducts.code:
					    this.rulesLog=json.data;
                        this.total=json.total;
                        break;
                    case uri.bindProductVideo.code:
                    case uri.unBindProductVideo.code:
                        this.getData();
                        break;
				}


			},
            handleTabClick:function(dom){
			},
            handleCurrentChange(page){
                this.formSearch.offset=(page-1)*this.formSearch.limit;
                this.getData();
            },
            bindProductVideo(index,row){
                console.log(arguments);
                vk.http(uri.bindProductVideo,{productId:row.id},this.then);
            },
            unBindProductVideo(index,row){
                console.log(arguments);
                vk.http(uri.unBindProductVideo,{productId:row.id},this.then);
            },
            onFormSearch(){
                this.getData();
            },
            onClearFormSearch(){
                this.formSearch.keyword="";
                this.getData();
            },
		}
    }
</script>