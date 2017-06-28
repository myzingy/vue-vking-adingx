<style lang="stylus" rel="stylesheet/scss">
	 .overflow-y{ overflow-y: auto;}
	 .header .el-select:hover .el-input__inner{
		 background: #222;
		 color:#fff;
	 }
	 .header .el-input__icon+.el-input__inner{
		 background: #222;
		 color:#fff;
	 }
	.header .el-input__inner{
		border: 0;
	}
	 .header-select .el-select-dropdown__wrap {
		 max-height: 100%;
	 }
</style>
<template>

	<div>
		<v-header title="首页">
			<router-link slot="left" to="?">
				<el-select v-model="ac_idx" placeholder="请选择AD账号开始" @change="acChecked" popper-class="header-select">
					<el-option
							v-for="item in acs"
							:key="item.account_id"
							:label="item.account_name"
							:value="item.account_id">
						<span style="float: left">{{ item.account_name }}</span>
						<span style="float: right; font-size: 10px; padding-left: 20px;">{{
							item.account_id }}</span>
					</el-option>
				</el-select>
			</router-link>
			<router-link slot="right" to="/signout">{{user.name}} 退出</router-link>
		</v-header>
		<div>
			<el-row :style="{ height:height +'px' }" v-if="ac_idx">
				<el-col :span="4" style="height:100%;">
					<div :style="{ height:height_cc +'px' }" class="grid-left bg-purple-darkc overflow-y"
						 id="app_left_menu">
						<v-leftMenu></v-leftMenu>
					</div>
				</el-col>
				<el-col :span="20" style="height:100%;">
					<div :style="{ height:height_cc +'px' }" class="grid-content bg-purple-dark overflow-y"
						 id="app_right_content">
						<v-rightContent></v-rightContent>
					</div>
				</el-col>
			</el-row>
			<el-row :style="{ height:height +'px' }" v-else>
				<div :style="{ height:height_cc +'px' }" class="grid-content bg-purple-darkc overflow-y"
					 id="app_overview">
					<statistical></statistical>
				</div>
			</el-row>
		</div>
	</div>
</template>
<script>
    import Vue from 'vue'
    import { mapState,mapActions } from 'vuex'
    import ElementUI from 'element-ui'
    import 'element-ui/lib/theme-default/index.css'
    import { CKECKED_AC } from '../../store/data.js'
    import vk from '../../vk.js';
    import uri from '../../uri.js';
    import statistical from './statistical.vue';
    Vue.use(ElementUI)
    export default {
        components:{
            statistical:statistical,
		},
        data:function(){
            return {
                height:500,
                height_cc:500,
                h_height:80,
                acs:[],
                ac_idx:"",
			}
		},
        computed: mapState({ user: state => state.user,ac_id: state => state.data?state.data.ac_id:""}),
        mounted(){
            console.log('store.state.data',this.ac_id);
            this.height=document.body.scrollHeight-(this.h_height);
            this.height_cc=this.height;
            this.getAcsList();
        },
        methods:{
            ...mapActions([CKECKED_AC]),
            acChecked:function(ac_id){
                var old_id=this.ac_id;
                this.CKECKED_AC({ac_id:ac_id});
                this.ac_idx=ac_id;
                if(old_id!=ac_id)
                    location.reload();
            },
			then(json,code){
                switch(code){
                    case uri.getAcsList.code:
                        this.acs=json.data;
                        this.ac_idx=this.ac_id;
					break;
                    case uri.getFBAccounts.code:
                        var acs=[];
                        json.data.forEach(row=>{
                            acs.push({
                                account_id:row.account_id,
                                account_name:row.name
							});
						});
                        this.acs=acs;
                        this.ac_idx=this.ac_id;
                        break;
                }
			},
			getAcsList(){
                //vk.http(uri.getAcsList,{},this.then);
                vk.http(uri.getFBAccounts,{},this.then);
			}
        },
    }
</script>