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
			<el-tab-pane label="规则列表" name="getRulesData">
				<el-table :data="rulesData" border style="width: 100%" max-height="100%">
					<el-table-column type="expand">
						<template scope="props">
							<el-table :data="props.row.List" border style="width: 100%">
								<el-table-column prop="CTR" label="关联的广告会显示在这里"></el-table-column>
							</el-table>
						</template>
					</el-table-column>
					<el-table-column prop="id" label="ID" width="60"></el-table-column>
					<el-table-column prop="name" label="规则名称"  ></el-table-column>
					<el-table-column prop="type" label="规则大小" width="80">
						<template scope="scope">
							<template v-if=" scope.row.type == 2 ">
								大型片段
							</template>
							<template v-else-if=" scope.row.type == 1 ">
								小型片段
							</template>
							<template v-else>
								简单规则
							</template>
						</template>
					</el-table-column>
					<el-table-column prop="status" label="规则状态" width="80">
						<template scope="scope">
							<template v-if=" scope.row.status == 0 ">
								<el-button @click.native.prevent="deleteRow(scope.$index, rulesData)"
										   type="success"
										   size="small">
									启用中
								</el-button>
							</template>
							<template v-else>

								<el-button @click.native.prevent="deleteRow(scope.$index, rulesData)"
										   type="button"
										   size="small">
									已禁用
								</el-button>
							</template>

						</template>
					</el-table-column>
					<el-table-column label="操作" width="120">
						<template scope="scope">
							<el-button @click.native.prevent="deleteRow(scope.$index, rulesData)"
									   type="text"
									   size="small">
								移除
							</el-button>
							<el-button @click.native.prevent="editRule(scope.$index, rulesData)"
									   type="text"
									   size="small">
								修改
							</el-button>
							<el-button @click.native.prevent="append(scope.$index, rulesData)"
									   type="text"
									   size="small">
								轮子
							</el-button>
						</template>
					</el-table-column>
				</el-table>
			</el-tab-pane>
			<el-tab-pane label="编辑规则" name="updateRule">
				<v-rule ref="rule" @showRulesView="showRulesView"></v-rule>
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
                activeName: 'updateRule',
                rulesData:[],
			}
		},
        computed: mapState({ user: state => state.user }),
        mounted(){
        },
        methods:{
            then:function(json,code){
                switch(code){
					case uri.getRulesData.code:
                        this.rulesData=json.data;
                        break;
				}
			},
            handleTabClick:function(dom){
                var uriKey=dom.name;
                var params={};
                if(uriKey=='getRulesData') {
                    vk.http(uri[uriKey],params,this.then);
				}
			},
            editRule:function(index,rulesData){
                this.activeName= 'updateRule';
                //console.log(index,rulesData[index].xml)
				this.$refs.rule.editInfo(rulesData[index]);
			},
            append:function(index,rulesData){
                this.activeName= 'updateRule';
                //console.log(index,rulesData[index].xml)
                this.$refs.rule.appendXML(rulesData[index].xml);
            },
			showRulesView:function(){
                this.activeName='getRulesData';
                vk.http(uri.getRulesData,{},this.then);
			}
		}
    }
</script>