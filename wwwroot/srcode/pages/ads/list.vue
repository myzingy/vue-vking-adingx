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
				<v-ad_table v-bind:adsData="campaignsData" dataType="campaign"></v-ad_table>
			</el-tab-pane>
			<el-tab-pane label="广告组" name="getAdsetsData">
				<v-ad_table v-bind:adsData="adsetsData" dataType="adset"></v-ad_table>
			</el-tab-pane>
			<el-tab-pane label="广告" name="getAdsData">
				<v-ad_table v-bind:adsData="adsData" dataType="ad"></v-ad_table>
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
                activeName: 'getCampaignsData',
                campaignsData:[],
                adsetsData:[],
                adsData:[],
				form:{
                    yestoday:1,
				},

			}
		},
        computed: mapState({ user: state => state.user }),
        mounted(){
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
				}


			},
            handleTabClick:function(dom){
                var uriKey=dom.name;
                var params={};
                vk.http(uri[uriKey],params,this.then);
			}
		}
    }
</script>