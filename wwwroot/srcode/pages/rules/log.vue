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
            <div>
                <el-tabs v-model="activeName" @tab-click="handleTabClick">
                    <el-tab-pane label="优化记录" name="getRulesLog">
                        <el-table :data="rulesLog" border style="width: 100%" max-height="700">
                            <el-table-column type="expand" fixed>
                                <template scope="scope">
                                    <v-ad_table :adsData="scope.row.expandTabData" :dataType="scope.row.expandTabDataType"
                                                rulesLog="rulesLog"></v-ad_table>
                                </template>
                            </el-table-column>
                            <el-table-column :formatter="formatExecTarget" label="执行目标" width="180"></el-table-column>
                            <el-table-column prop="time_format" label="执行时间"  width="120"></el-table-column>
                            <el-table-column :formatter="formatExecRule" label="执行规则"  ></el-table-column>
                            <el-table-column prop="rule_exec" label="执行结果"  ></el-table-column>
                        </el-table>
                        <el-pagination style=" margin: 20px auto; width:300px;"
                                       @size-change="handleSizeChange"
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
                },
			}
		},
        computed: mapState({ user: state => state.user }),
        mounted(){
            this.getData();
        },
        methods:{
            getData(){
                vk.http(uri.getRulesLog,this.formSearch,this.then);
            },
            then:function(json,code){
                switch(code){
					case uri.getRulesLog.code:
					    for(var i in json.data){
					        json.data[i].expandTabData=[JSON.parse(json.data[i].target_data)];
                            json.data[i].expandTabDataType=json.data[i].target.toLowerCase();
						}
					    this.rulesLog=json.data;
                        this.total=json.total;
                        break;
				}


			},
            handleTabClick:function(dom){
//                var uriKey=dom.name;
//                var params={};
//                if(uriKey=='getRulesLog') {
//                    vk.http(uri[uriKey],params,this.then);
//				}
			},
            formatExecTarget:function(row, column){
                return '['+row.target+']'+row.target_id;
            },
            formatExecRule:function(row){
                return '['+row.rule_id+']'+row.rule_name;
			},
            handleSizeChange(){
                console.log(arguments);
            },
            handleCurrentChange(page){
                this.formSearch.offset=(page-1)*this.formSearch.limit;
                this.getData();
            },
		}
    }
</script>