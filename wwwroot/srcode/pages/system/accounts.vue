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
		<el-button type="primary" @click="openAccountsFBDialog" style=" float: right;z-index: 1;position: relative;">绑定账号
			<i class="el-icon-setting el-icon--right"></i></el-button>
		<el-tabs v-model="activeName" @tab-click="handleTabClick">
			<el-tab-pane label="已绑定广告账号" name="getRulesLog">
				<el-table :data="rulesLog" border style="width: 100%" max-height="750">
					<el-table-column prop="account_id" label="Account ID" width="180"></el-table-column>
					<el-table-column prop="account_name" label="Account Name"  ></el-table-column>
					<el-table-column label="操作" width="120" >
						<template scope="scope">
							<el-button type="text" size="small" @click="unBindAccount(scope.$index, scope.row)">
								解绑
							</el-button>
						</template>
					</el-table-column>
				</el-table>
			</el-tab-pane>
		</el-tabs>
		<el-dialog title="FB广告账号列表" :visible.sync="dialogTableVisible" :close-on-click-modal="false"
				   :close-on-press-escape="false">
			<accounts_fb ref="accounts_fb"></accounts_fb>
			<span slot="footer" class="dialog-footer">
				<el-button @click="closeDialog">取 消</el-button>
				<el-button type="primary" @click="bindFbAccounts">确 定</el-button>
			  </span>
		</el-dialog>
	</div>
</template>
<script>
    import Vue from 'vue'
    import { mapState } from 'vuex'
    import ElementUI from 'element-ui'
    import 'element-ui/lib/theme-default/index.css'
	import vk from '../../vk.js';
    import uri from '../../uri.js';
    import accounts_fb from './accounts_fb.vue';
    Vue.use(ElementUI)
    export default {
        components:{
            accounts_fb:accounts_fb,
        },
        data:function(){
            return {
                activeName: 'getRulesLog',
                rulesLog:[],
                dialogTableVisible:false,
			}
		},
        computed: mapState({ user: state => state.user }),
        mounted(){
			this.init();
        },
        methods:{
            init(){
                var params={};
                vk.http(uri.getAcsList,params,this.then);
			},
            closeDialog(){
                this.dialogTableVisible=false;
			},
            then:function(json,code){
                switch(code){
					case uri.getAcsList.code:
					    this.rulesLog=json.data;
                        break;
					case uri.addAccounts.code:
					    this.dialogTableVisible=false;
					    vk.toast('操作成功','msg');
                        this.init();
					    break;
					case uri.delAccounts.code:
                        vk.toast('操作成功','msg');
                        this.init();
					    break;
				}
			},
            handleTabClick:function(){

			},
            openAccountsFBDialog(){
                console.log(12323);
                this.dialogTableVisible=true;
			},
            bindFbAccounts(){
                var checked=this.$refs.accounts_fb.checked;
                if(checked.length>0){
                    vk.http(uri.addAccounts,{checked:checked},this.then);
				}
			},
            unBindAccount(index,row){
            	vk.http(uri.delAccounts,{account_id:row.account_id},this.then);
			}
		}
    }
</script>