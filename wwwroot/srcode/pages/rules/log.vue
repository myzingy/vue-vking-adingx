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
	<div>
		<el-tabs v-model="activeName" @tab-click="handleTabClick">
			<el-tab-pane label="优化记录" name="getRulesLog">
				<el-table :data="rulesLog" border style="width: 100%" max-height="750">
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
			</el-tab-pane>
			<el-tab-pane label="优化统计" name="RulesExecTotal">
				优化统计
			</el-tab-pane>
		</el-tabs>
	</div>
</template>
<script>
    import Vue from 'vue'
    import { mapState } from 'vuex'
    import ElementUI from 'element-ui'
    import 'element-ui/lib/theme-default/index.css'
	import vk from '../../vk.js';
    import uri from '../../uri.js';
    Vue.use(ElementUI)
    export default {
        data:function(){
            return {
                activeName: 'getRulesLog',
                rulesLog:[],
			}
		},
        computed: mapState({ user: state => state.user }),
        mounted(){
            var params={};
            vk.http(uri.getRulesLog,params,this.then);
        },
        methods:{
            then:function(json,code){
                switch(code){
					case uri.getRulesLog.code:
					    for(var i in json.data){
					        json.data[i].expandTabData=[JSON.parse(json.data[i].target_data)];
                            json.data[i].expandTabDataType=json.data[i].target.toLowerCase();
						}
					    this.rulesLog=json.data;
                        break;
				}


			},
            handleTabClick:function(dom){
                var uriKey=dom.name;
                var params={};
                if(uriKey=='getRulesLog') {
                    vk.http(uri[uriKey],params,this.then);
				}
			},
            formatExecTarget:function(row, column){
                return '['+row.target+']'+row.target_id;
            },
            formatExecRule:function(row){
                return '['+row.rule_id+']'+row.rule_name;
			},
//            expandTab:function(row, expanded){
//            	this.expandTabData=[JSON.parse(row.target_data)];
//                this.expandTabDataType=row.target.toLowerCase();
//                console.log(this.expandTabDataType);
//			}
		}
    }
</script>