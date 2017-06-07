<style lang="stylus" rel="stylesheet/scss">

</style>
<template>
	<el-table :data="adsData" border style="width: 100%" max-height="750" :default-sort =
			"{prop: 'AmountSpent', order: 'descending'}">
		<el-table-column type="expand" fixed>
			<template scope="props">
				<el-table :data="props.row.List" border style="width: 100%">
					<el-table-column :formatter="formatChildDate" label="日期" width="150"></el-table-column>
					<el-table-column prop="WebsiteAddstoCart" label="Website Adds to Cart" width="80"></el-table-column>
					<el-table-column prop="CostperWebsiteAddtoCart" label="Cost per Website Add to Cart" width="80"></el-table-column>
					<el-table-column :formatter="formatAmountSpent" prop="AmountSpent" label="Amount Spent" width="80"></el-table-column>
					<el-table-column prop="WebsitePurchases" label="Website Purchases" width="80"></el-table-column>
					<el-table-column prop="WebsitePurchasesConversionValue" label="Website Purchases Conversion Value" width="80"></el-table-column>
					<el-table-column prop="LinkClicks" label="Link Clicks" width="80"></el-table-column>
					<el-table-column prop="CPC" label="CPC (Cost per Link Click)" width="80"></el-table-column>
					<el-table-column prop="CTR" label="CTR (Link Click-Through Rate)" width="80"></el-table-column>
					<el-table-column prop="CPM1000" label="CPM (Cost per 1,000 Impressions)"
									 width="80"></el-table-column>
					<el-table-column prop="Reach" label="Reach" width="80"></el-table-column>
					<el-table-column prop="Results" label="Results" width="80"></el-table-column>
					<el-table-column prop="CostperResult" label="Cost per Result" width="80"></el-table-column>
				</el-table>
			</template>
		</el-table-column>
		<el-table-column fixed prop="Date" label="Date" width="100"></el-table-column>
		<el-table-column fixed prop="Name" label="Name" width="200" >
			<template scope="scope">
				<template v-if=" dataType != 'campaign' ">
					<el-popover trigger="hover" placement="top">
						<p>Campaign Name: {{ scope.row.CampaignName }}</p>
						<p>Adset Name: {{ scope.row.AdsetName }}</p>
						<template v-if=" dataType == 'ad' "><p>Ad Name: {{ scope.row.AdName }}</p> </template>
						<div slot="reference" class="name-wrapper">
							<el-tag>{{ scope.row.Name }}</el-tag>
						</div>
					</el-popover>
				</template>
				<template v-else>
					{{ scope.row.Name }}
				</template>
			</template>
		</el-table-column>
		<el-table-column prop="Delivery" label="Delivery" width="60"></el-table-column>

		<el-table-column prop="WebsiteAddstoCart" label="Website Adds to Cart" width="80"></el-table-column>
		<el-table-column prop="CostperWebsiteAddtoCart" label="Cost per Website Add to Cart" width="80"></el-table-column>
		<el-table-column :formatter="formatAmountSpent" prop="AmountSpent" label="Amount Spent" width="140"
						 sortable></el-table-column>
		<el-table-column prop="WebsitePurchases" label="Website Purchases" width="80"></el-table-column>
		<el-table-column prop="WebsitePurchasesConversionValue" label="Website Purchases Conversion Value" width="80"></el-table-column>
		<el-table-column prop="LinkClicks" label="Link Clicks" width="80"></el-table-column>
		<el-table-column prop="CPC" label="CPC (Cost per Link Click)" width="80"></el-table-column>
		<el-table-column prop="CTR" label="CTR (Link Click-Through Rate)" width="80"></el-table-column>
		<el-table-column prop="CPM1000" label="CPM (Cost per 1,000 Impressions)"
						 width="80"></el-table-column>
		<el-table-column prop="Reach" label="Reach" width="80"></el-table-column>
		<el-table-column prop="Results" label="Results" width="80"></el-table-column>
		<el-table-column prop="CostperResult" label="Cost per Result" width="80"></el-table-column>
		<template v-if=" dataType == 'ad' ">
			<el-table-column prop="RelevanceScore" label="Relevance Score" width="80"></el-table-column>
		</template>
		<template v-if=" dataType == 'adset' ">
			<el-table-column prop="Budget" label="Budget" width="80"></el-table-column>
			<el-table-column prop="Schedule" label="Schedule" width="80"></el-table-column>
		</template>
		<template v-if=" dataType == 'campaign' ">
			<el-table-column prop="Ends" label="Ends" width="80"></el-table-column>
		</template>
		<el-table-column label="操作" width="80" fixed="right">
			<template scope="scope">
				<el-button @click="openRulesDialog"
						   type="text"
						   size="small">
					规则
				</el-button>
				<el-button @click.native.prevent="deleteRow(scope.$index, campaignsData)"
						   type="text"
						   size="small">
					移除
				</el-button>
			</template>
		</el-table-column>
	</el-table>
</template>
<script>
    import Vue from 'vue'
    import ElementUI from 'element-ui'
    import 'element-ui/lib/theme-default/index.css'
    import vk from '../vk.js';
    import uri from '../uri.js';

    Vue.use(ElementUI)

	export default {
        data:function(){
        	return {
                
			}
		},
        props: ['adsData','dataType'],
        methods:{
            formatAmountSpent:function(row, column){
                return '$'+row.AmountSpent;
			},
            formatChildDate:function(row){
                return row.Type==7?'last 7 day':(row.Type==14?'last 14 day':row.Date);
            },
			openRulesDialog:function(){
                this.$emit('openRulesDialog');
			},
		},
        mounted(){
			
        }
    }
</script>