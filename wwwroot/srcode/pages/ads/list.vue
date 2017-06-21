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
   .el-tabs--card>.el-tabs__header .el-tabs__item .el-icon-close
	   position relative
	   font-size 16px
	   width auto
	   height auto
	   vertical-align middle
	   line-height 100%
	   overflow hidden
	   top 0
	   right 0
	   -ms-transform-origin 50% 50%
	   transform-origin 50% 50%
</style>
<template>
	<div style="padding: 10px;">
		<el-form :inline="true" :model="formSearch" class="demo-form-inline">
			<el-form-item label="类型">
				<el-select v-model="formSearch.keyword_type" placeholder="请选择">
					<el-option label="系列 ID/名称" value="campaign"></el-option>
					<el-option label="组 ID/名称" value="adset"></el-option>
					<el-option label="广告 ID/名称" value="ad"></el-option>
				</el-select>
			</el-form-item>
			<el-form-item>
				<el-input v-model="formSearch.keyword" placeholder="关键字（支持模糊查询）"></el-input>
			</el-form-item>
			<el-form-item>
				<el-button type="primary" @click="onFormSearch">查询</el-button>
				<a href="javascript://" @click="onClearFormSearch">清空条件</a>
			</el-form-item>
		</el-form>
		<el-tabs v-model="activeName" @tab-click="handleTabClick" type="card">
			<el-tab-pane name="getCampaignsData">
				<span slot="label">广告系列
					<el-tag v-show="getTabName('checked_campaigns')" :closable="true"
							@close="clearTabName('checked_campaigns')">{{getTabName
						('checked_campaigns')}}</el-tag>
				</span>
				<v-ad_table v-bind:adsData="campaignsData" dataType="campaign" @searchThatID="searchThatID"
							@openRulesDialog="openRulesDialog"></v-ad_table>
			</el-tab-pane>
			<el-tab-pane name="getAdsetsData">
				<span slot="label">广告组
					<el-tag v-show="getTabName('checked_adsets')" :closable="true" @close="clearTabName('checked_adsets')">{{getTabName('checked_adsets')
						}}</el-tag>
				</span>
				<v-ad_table v-bind:adsData="adsetsData" dataType="adset" @openRulesDialog="openRulesDialog" @searchThatID="searchThatID"
				></v-ad_table>
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
                formSearch:{
                    keyword_type:"",
					keyword:"",
					checked_campaigns:[],
                    checked_adsets:[],
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
            this.getData();
        },
        methods:{
            getData(){
                var params=this.formSearch;
                vk.http(uri[this.activeName],params,this.then);
			},
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
            handleTabClick:function(){
                this.getData();
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
			},
            onFormSearch(){
                this.getData();
			},
            onClearFormSearch(){
                this.formSearch.keyword_type="";
				this.formSearch.keyword="";
                this.getData();
			},
            searchThatID(ad,type){
                var ad={
                    id:ad.Id,
					name:ad.Name
				};
                if(type=='adset'){
                    this.formSearch.checked_adsets.push(ad);
                    this.activeName='getAdsData';
                    this.getData();
				}else{
                    this.formSearch.checked_campaigns.push(ad);
                    this.activeName='getAdsetsData';
                    this.getData();
				}
			},
            getTabName(key){
            	var arr=this.formSearch[key];
            	var len=arr.length;


                var str='';
                if(len>1){
                    str=len+' selected';
                }else if(len==1){
                    str=arr[0].name;
                }
                return str;
			},
            clearTabName(key){
                this.formSearch[key]=[];
			}
		}
    }
</script>