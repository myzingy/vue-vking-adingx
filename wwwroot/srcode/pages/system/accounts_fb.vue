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
		<el-table :data="rulesLog" border style="width: 100%" max-height="450" @selection-change="handleSelectionChange">
			<el-table-column
					type="selection"
					width="55">
			</el-table-column>
			<el-table-column prop="account_id" label="Account ID" width="180"></el-table-column>
			<el-table-column prop="name" label="Account Name"  ></el-table-column>
		</el-table>
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
                rulesLog:[],
				checked:[],
			}
		},
        computed: mapState({ user: state => state.user }),
        mounted(){
            var params={};
            vk.http(uri.getFBAccounts,params,this.then);
        },
        methods:{
            then:function(json,code){
                switch(code){
					case uri.getFBAccounts.code:
					    this.rulesLog=json.data;
                        break;
				}


			},
            handleSelectionChange(checked){
                this.checked=checked;
			}
		}
    }
</script>