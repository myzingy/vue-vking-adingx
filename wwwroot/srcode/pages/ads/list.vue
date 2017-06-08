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
		<el-collapse accordion>
			<el-collapse-item>
				<template slot="title">
					过滤条件<i class="header-icon el-icon-information"></i>
				</template>
				<el-form ref="form" :model="form" label-width="120px">
					<el-form-item label="最近">
						<el-col :span="4">
						<span>{{form.yestoday}}天？</span>
						</el-col>
						<el-col :span="20">
						<el-slider
								v-model="form.yestoday"
								:step="1" :max="7" :min="1"
								show-stops>
						</el-slider>
						</el-col>
					</el-form-item>
				</el-form>
			</el-collapse-item>
		</el-collapse>
		<el-tabs v-model="activeName" @tab-click="handleTabClick">
			<el-tab-pane label="广告系列" name="getCampaignsData">
				<v-ad_table v-bind:adsData="campaignsData" dataType="campaign" @openRulesDialog="openRulesDialog"></v-ad_table>
			</el-tab-pane>
			<el-tab-pane label="广告组" name="getAdsetsData">
				<v-ad_table v-bind:adsData="adsetsData" dataType="adset" @openRulesDialog="openRulesDialog"></v-ad_table>
			</el-tab-pane>
			<el-tab-pane label="广告" name="getAdsData">
				<v-ad_table v-bind:adsData="adsData" dataType="ad" @openRulesDialog="openRulesDialog"></v-ad_table>
			</el-tab-pane>
		</el-tabs>
		<div id="dialogRules">
		<el-dialog ref="refDialog" title="规则列表" :visible.sync="dialogTableVisible" :close-on-click-modal="false"
				   :close-on-press-escape="false" @close="dialogClose" @open="dialogOpen">
			<v-rules_list ref="refRules"></v-rules_list>
			<span slot="footer" class="dialog-footer">
				<el-button @click="dialogClose">取 消</el-button>
				<el-button type="primary" @click="saveRulesForAd">确 定</el-button>
			  </span>
		</el-dialog>
		</div>
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
                activeName: 'getCampaignsData',
                campaignsData:[],
                adsetsData:[],
                adsData:[],
				form:{
                    yestoday:1,
				},
                dialogTableVisible:false,
                RulesChecked:[],
                RulesCheckedTime:"10:00",
				target:"",
				target_id:"",

			}
		},
        computed: mapState({ user: state => state.user }),
        mounted(){
            var params={};
            vk.http(uri[this.activeName],params,this.then);
        },
        methods:{
            then:function(json,code){
                switch(code){
					case uri.getCampaignsData.code:
                        this.campaignsData=json.data;
                        break;
					case uri.getAdsetsData.code:
					    this.adsetsData=json.data;
					    break;
					case uri.getAdsData.code:
					    this.adsData=json.data;
					    break;
					case uri.getRulesForAd.code:
                        this.RulesChecked=[];
                        this.RulesCheckedTime="10:00";
                        json.data.forEach(r=>{
                            this.RulesChecked.push(r.id);
                            this.RulesCheckedTime=r.exec_hour_minute;
						});
					    //this.RulesChecked=json.data;
                        this.dialogTableVisible=true;
					    break;
				}


			},
            handleTabClick:function(dom){
                var uriKey=dom.name;
                var params={};
                vk.http(uri[uriKey],params,this.then);
			},
            openRulesDialog:function($data){
                this.target_id=$data.Id;
                this.target=this.activeName;
                vk.http(uri.getRulesForAd,{id:$data.Id,type:this.activeName},this.then);

			},
            dialogClose(){
                this.dialogTableVisible=false;
			},
            dialogOpen(){
                var that=this;
                setTimeout(function(){
                    that.$refs.refRules.init(that.RulesChecked,that.RulesCheckedTime);
				},100);

			},
            saveRulesForAd(){
                this.dialogClose();
                this.$refs.refRules.saveRulesForAd(this.target_id,this.target);
			}
		}
    }
</script>