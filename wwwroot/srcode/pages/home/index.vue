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
				
			</div>
		</el-col>
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
    //import statistical from './statistical.vue';
    Vue.use(ElementUI)
    export default {
//        components:{
//            statistical:statistical,
//		},
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