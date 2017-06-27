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
		<el-table :data="rulesLog" border style="width: 100%" max-height="450" ref="multipleAccountsTable" @selection-change="handleSelectionChange">
			<el-table-column
					type="selection"
					width="55">
			</el-table-column>
			<el-table-column prop="account_id" label="Account ID" width="180"></el-table-column>
			<el-table-column :formatter="formatAccountName" label="Account Name"  ></el-table-column>
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
        props:['type'],
        data:function(){
            return {
                rulesLog:[],
				checked:[],
                hashChecked:[],
			}
		},
        computed: mapState({ user: state => state.user }),
        mounted(){
            var params={};
            if(this.type=='nofb'){
                vk.http(uri.getAcsList,params,this.then);
			}else{
                vk.http(uri.getFBAccounts,params,this.then);
			}
        },
        methods:{
            then:function(json,code){
                this.rulesLog=json.data;
                this.toggleSelection();
			},
            handleSelectionChange(checked){
                this.checked=checked;
			},
            formatAccountName(row){
                return row.name?row.name:row.account_name;
			},
			initChecked(checked){
                this.hashChecked=checked;
                this.toggleSelection();
			},
            toggleSelection:function() {
			    var that=this;
			    var checked=this.hashChecked;
                this.$refs.multipleAccountsTable.clearSelection();
                console.log(this.checked);
                if(checked.length>0){
                    setTimeout(function(){
                        that.rulesLog.forEach(row => {
                            for(var i in checked){
                                if(checked[i].account_id == row.account_id){
                                    try{that.$refs.multipleAccountsTable.toggleRowSelection(row,true);}catch(e){}
                                }
                            }
                        })
                    },0);
                }
            },
		}
    }
</script>