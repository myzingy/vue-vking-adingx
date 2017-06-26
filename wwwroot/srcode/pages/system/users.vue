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
		<el-tabs v-model="activeName">
			<el-tab-pane label="用户列表" name="getRulesLog">
				<el-form :inline="true" :model="form" class="demo-form-inline">
					<el-form-item label="Email">
						<el-input v-model="form.email" placeholder="用户facebook注册email"></el-input>
					</el-form-item>
					<el-form-item label="用户组">
						<el-select v-model="form.group_id" placeholder="请选择">
							<el-option label="Admin" value="0"></el-option>
							<el-option label="Power Editor" value="1"></el-option>
							<el-option label="Designers" value="2"></el-option>
						</el-select>
					</el-form-item>
					<el-form-item>
						<el-button type="primary" @click="addUser">添加用户</el-button>
					</el-form-item>
				</el-form>
				<el-table :data="rulesLog" border style="width: 100%" max-height="750">
					<el-table-column prop="email" label="Email" width="250"></el-table-column>
					<el-table-column :formatter="formatUserdata" label="用户数据" ></el-table-column>
					<el-table-column label="用户组"  width="120">
						<template scope="scope">
						<el-select v-model="scope.row.group_id" placeholder="请选择" @change="changeUser(scope.row.group_id,scope.row)">
							<el-option label="Admin" value="0"></el-option>
							<el-option label="Power Editor" value="1"></el-option>
							<el-option label="Designers" value="2"></el-option>
						</el-select>
						</template>
					</el-table-column>
					<el-table-column label="操作" width="100"  >
						<template scope="scope">
							<el-button type="text" size="small" @click="deleteUser(scope.$index, scope.row)">
								删除
							</el-button>
						</template>
					</el-table-column>
				</el-table>
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
				form:{
                    email:"",
                    group_id:"0"
				},
			}
		},
        computed: mapState({ user: state => state.user }),
        mounted(){
		   this.init();
        },
        methods:{
            init(){
                var params={};
                vk.http(uri.getUsers,params,this.then);
			},
            then:function(json,code){
                switch(code){
					case uri.getUsers.code:
					    this.rulesLog=json.data;
                        break;
					case uri.addUsers.code:
					case uri.delUsers.code:
                        vk.toast('操作成功','msg')
						this.init();
					    break;

				}


			},
            formatUserdata:function(row, column){
                if(row.id){
                    return row.id+','+row.name;
				}
                return 'NO-LOGIN';
            },
            deleteUser(index,row){
                vk.http(uri.delUsers,{email:row.email},this.then);
			},
            addUser(){
                if(/.*@.*\.[a-z]{2,4}/.test(this.form.email)){
                    vk.http(uri.addUsers,this.form,this.then);
                    return;
				}
				vk.toast('email address error');
            },
            changeUser(group_id,row){
                vk.http(uri.updateUsers,{group_id:group_id,user_id:row.id,email:row.email},this.then);
			},
		}
    }
</script>