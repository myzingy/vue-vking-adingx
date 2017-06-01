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
				<el-table :data="campaignsData" border style="width: 100%" max-height="100%">
					<el-table-column type="expand" fixed>
						<template scope="props">
							<el-table :data="props.row.List" border style="width: 100%">
								<el-table-column prop="Date" label="日期" width="150"></el-table-column>
								<el-table-column prop="Results" label="Results" width="80"></el-table-column>
								<el-table-column prop="Reach" label="Reach" width="80"></el-table-column>
								<el-table-column prop="CostperResult" label="Cost per Result" width="80"></el-table-column>
								<el-table-column prop="AmountSpent" label="Amount Spent" width="80"></el-table-column>
								<el-table-column prop="LinkClicks" label="Link Clicks" width="80"></el-table-column>
								<el-table-column prop="WebsitePurchases" label="Website Purchases" width="80"></el-table-column>
								<el-table-column prop="ClicksAll" label="Clicks (All)" width="80"></el-table-column>
								<el-table-column prop="CTRAll" label="CTR (All)" width="80"></el-table-column>
								<el-table-column prop="CPCAll" label="CPC (All)" width="80"></el-table-column>
								<el-table-column prop="Impressions" label="Impressions" width="80"></el-table-column>
								<el-table-column prop="CPM1000" label="CPM (Cost per 1,000 Impressions)"
												 width="80"></el-table-column>
								<el-table-column prop="CPC" label="CPC (Cost per Link Click)" width="80"></el-table-column>
								<el-table-column prop="CTR" label="CTR (Link Click-Through Rate)" width="80"></el-table-column>
							</el-table>
						</template>
					</el-table-column>
					<el-table-column fixed prop="Date" label="Date" width="100"></el-table-column>
					<el-table-column fixed prop="CampaignName" label="Campaign Name" width="200" ></el-table-column>
					<el-table-column prop="Delivery" label="Delivery" ></el-table-column>
					<el-table-column prop="Results" label="Results" width="80"></el-table-column>
					<el-table-column prop="Reach" label="Reach" width="80"></el-table-column>
					<el-table-column prop="CostperResult" label="Cost per Result" width="80"></el-table-column>
					<el-table-column prop="AmountSpent" label="Amount Spent" width="80"></el-table-column>
					<el-table-column prop="LinkClicks" label="Link Clicks" width="80"></el-table-column>
					<el-table-column prop="WebsitePurchases" label="Website Purchases" width="80"></el-table-column>
					<el-table-column prop="ClicksAll" label="Clicks (All)" width="80"></el-table-column>
					<el-table-column prop="CTRAll" label="CTR (All)" width="80"></el-table-column>
					<el-table-column prop="CPCAll" label="CPC (All)" width="80"></el-table-column>
					<el-table-column prop="Impressions" label="Impressions" width="80"></el-table-column>
					<el-table-column prop="CPM1000" label="CPM (Cost per 1,000 Impressions)"
									 width="80"></el-table-column>
					<el-table-column prop="CPC" label="CPC (Cost per Link Click)" width="80"></el-table-column>
					<el-table-column prop="CTR" label="CTR (Link Click-Through Rate)" width="80"></el-table-column>
					<el-table-column label="操作" width="60">
						<template scope="scope">
							<el-button @click.native.prevent="deleteRow(scope.$index, campaignsData)"
									   type="text"
									   size="small">
								移除
							</el-button>
						</template>
					</el-table-column>
				</el-table>
			</el-tab-pane>
			<el-tab-pane label="广告组" name="getAdsetsData">
				<el-table :data="adsetsData" border style="width: 100%" max-height="100%">
					<el-table-column type="expand" fixed>
						<template scope="props">
							<el-table :data="props.row.List" border style="width: 100%">
								<el-table-column prop="Date" label="日期" width="150"></el-table-column>
								<el-table-column prop="Results" label="Results" width="80"></el-table-column>
								<el-table-column prop="Reach" label="Reach" width="80"></el-table-column>
								<el-table-column prop="CostperResult" label="Cost per Result" width="80"></el-table-column>
								<el-table-column prop="AmountSpent" label="Amount Spent" width="80"></el-table-column>
								<el-table-column prop="LinkClicks" label="Link Clicks" width="80"></el-table-column>
								<el-table-column prop="WebsitePurchases" label="Website Purchases" width="80"></el-table-column>
								<el-table-column prop="ClicksAll" label="Clicks (All)" width="80"></el-table-column>
								<el-table-column prop="CTRAll" label="CTR (All)" width="80"></el-table-column>
								<el-table-column prop="CPCAll" label="CPC (All)" width="80"></el-table-column>
								<el-table-column prop="Impressions" label="Impressions" width="80"></el-table-column>
								<el-table-column prop="CPM1000" label="CPM (Cost per 1,000 Impressions)"
												 width="80"></el-table-column>
								<el-table-column prop="CPC" label="CPC (Cost per Link Click)" width="80"></el-table-column>
								<el-table-column prop="CTR" label="CTR (Link Click-Through Rate)" width="80"></el-table-column>
							</el-table>
						</template>
					</el-table-column>
					<el-table-column fixed prop="Date" label="Date" width="100"></el-table-column>
					<el-table-column fixed prop="CampaignName" label="Campaign Name" width="200" ></el-table-column>
					<el-table-column prop="Delivery" label="Delivery" ></el-table-column>
					<el-table-column prop="Results" label="Results" width="80"></el-table-column>
					<el-table-column prop="Reach" label="Reach" width="80"></el-table-column>
					<el-table-column prop="CostperResult" label="Cost per Result" width="80"></el-table-column>
					<el-table-column prop="AmountSpent" label="Amount Spent" width="80"></el-table-column>
					<el-table-column prop="LinkClicks" label="Link Clicks" width="80"></el-table-column>
					<el-table-column prop="WebsitePurchases" label="Website Purchases" width="80"></el-table-column>
					<el-table-column prop="ClicksAll" label="Clicks (All)" width="80"></el-table-column>
					<el-table-column prop="CTRAll" label="CTR (All)" width="80"></el-table-column>
					<el-table-column prop="CPCAll" label="CPC (All)" width="80"></el-table-column>
					<el-table-column prop="Impressions" label="Impressions" width="80"></el-table-column>
					<el-table-column prop="CPM1000" label="CPM (Cost per 1,000 Impressions)"
									 width="80"></el-table-column>
					<el-table-column prop="CPC" label="CPC (Cost per Link Click)" width="80"></el-table-column>
					<el-table-column prop="CTR" label="CTR (Link Click-Through Rate)" width="80"></el-table-column>
					<el-table-column label="操作" width="60">
						<template scope="scope">
							<el-button @click.native.prevent="deleteRow(scope.$index, campaignsData)"
									   type="text"
									   size="small">
								移除
							</el-button>
						</template>
					</el-table-column>
				</el-table>
			</el-tab-pane>
			<el-tab-pane label="广告" name="getAdsData">
				<el-table :data="adsData" border style="width: 100%" max-height="100%">
					<el-table-column type="expand" fixed>
						<template scope="props">
							<el-table :data="props.row.List" border style="width: 100%">
								<el-table-column prop="Date" label="日期" width="150"></el-table-column>
								<el-table-column prop="Results" label="Results" width="80"></el-table-column>
								<el-table-column prop="Reach" label="Reach" width="80"></el-table-column>
								<el-table-column prop="CostperResult" label="Cost per Result" width="80"></el-table-column>
								<el-table-column prop="AmountSpent" label="Amount Spent" width="80"></el-table-column>
								<el-table-column prop="LinkClicks" label="Link Clicks" width="80"></el-table-column>
								<el-table-column prop="WebsitePurchases" label="Website Purchases" width="80"></el-table-column>
								<el-table-column prop="ClicksAll" label="Clicks (All)" width="80"></el-table-column>
								<el-table-column prop="CTRAll" label="CTR (All)" width="80"></el-table-column>
								<el-table-column prop="CPCAll" label="CPC (All)" width="80"></el-table-column>
								<el-table-column prop="Impressions" label="Impressions" width="80"></el-table-column>
								<el-table-column prop="CPM1000" label="CPM (Cost per 1,000 Impressions)"
												 width="80"></el-table-column>
								<el-table-column prop="CPC" label="CPC (Cost per Link Click)" width="80"></el-table-column>
								<el-table-column prop="CTR" label="CTR (Link Click-Through Rate)" width="80"></el-table-column>
							</el-table>
						</template>
					</el-table-column>
					<el-table-column fixed prop="Date" label="Date" width="100"></el-table-column>
					<el-table-column fixed prop="CampaignName" label="Campaign Name" width="200" ></el-table-column>
					<el-table-column prop="Delivery" label="Delivery" ></el-table-column>
					<el-table-column prop="Results" label="Results" width="80"></el-table-column>
					<el-table-column prop="Reach" label="Reach" width="80"></el-table-column>
					<el-table-column prop="CostperResult" label="Cost per Result" width="80"></el-table-column>
					<el-table-column prop="AmountSpent" label="Amount Spent" width="80"></el-table-column>
					<el-table-column prop="LinkClicks" label="Link Clicks" width="80"></el-table-column>
					<el-table-column prop="WebsitePurchases" label="Website Purchases" width="80"></el-table-column>
					<el-table-column prop="ClicksAll" label="Clicks (All)" width="80"></el-table-column>
					<el-table-column prop="CTRAll" label="CTR (All)" width="80"></el-table-column>
					<el-table-column prop="CPCAll" label="CPC (All)" width="80"></el-table-column>
					<el-table-column prop="Impressions" label="Impressions" width="80"></el-table-column>
					<el-table-column prop="CPM1000" label="CPM (Cost per 1,000 Impressions)"
									 width="80"></el-table-column>
					<el-table-column prop="CPC" label="CPC (Cost per Link Click)" width="80"></el-table-column>
					<el-table-column prop="CTR" label="CTR (Link Click-Through Rate)" width="80"></el-table-column>
					<el-table-column label="操作" width="60">
						<template scope="scope">
							<el-button @click.native.prevent="deleteRow(scope.$index, campaignsData)"
									   type="text"
									   size="small">
								移除
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