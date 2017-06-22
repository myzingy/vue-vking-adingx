<style lang="stylus" rel="stylesheet/scss">

</style>
<template>
	<div>
	<el-table :data="adsData" border style="width: 100%" max-height="700" :default-sort =
			"{prop: 'AmountSpent', order: 'descending'}" :summary-method="getSummaries"
			  show-summary>
		<el-table-column type="expand" fixed>
			<template scope="props">
				<el-table :data="props.row.List" border style="width: 100%">
					<el-table-column :formatter="formatChildDate" label="日期" width="150"></el-table-column>
					<template v-if=" rulesLog == 'rulesLog' ">
						<el-table-column label="ROI" width="40" :formatter="formatROI"></el-table-column>
						<el-table-column label="ROAS" width="60" :formatter="formatROAS"></el-table-column>
					</template>
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
		<el-table-column fixed prop="Name" label="Name" width="200">
			<template scope="scope">
				<template v-if=" dataType != 'campaign' ">
					<el-popover trigger="hover" placement="top">
						<p>Campaign Name: {{ scope.row.CampaignName }}</p>
						<p>Adset Name: {{ scope.row.AdsetName }}</p>
						<template v-if=" dataType == 'ad' "><p>Ad Name: {{ scope.row.AdName }}</p> </template>
						<div slot="reference" class="name-wrapper">
							<a href="javascript://" @click="searchThatID(scope.row)">{{ scope.row.Name }}</a>
						</div>
					</el-popover>
				</template>
				<template v-else>
					<a href="javascript://" @click="searchThatID(scope.row)">{{ scope.row.Name }}</a>
				</template>
			</template>
		</el-table-column>
		<template v-if=" rulesLog == 'rulesLog' ">
			<el-table-column label="ROI" width="40" :formatter="formatROI"></el-table-column>
			<el-table-column label="ROAS" width="60" :formatter="formatROAS"></el-table-column>
		</template>
		<template v-else>
			<el-table-column prop="Delivery" label="Delivery" width="60"></el-table-column>
		</template>
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
		<template v-if=" dataType == 'campaign' ">
			<el-table-column label="操作" width="80" fixed="right">
				<template scope="scope">
					<el-button @click="openRulesDialog(scope.row)"
							   type="text"
							   size="small">
						规则
					</el-button>
				</template>
			</el-table-column>
		</template>
	</el-table>
	</div>
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
        props: ['adsData','dataType','rulesLog'],
        methods:{
            roias(type,row){
            	var $1=parseFloat(row.WebsitePurchasesConversionValue.replace(/[\$,]+/g,''));
                var $2=row.AmountSpent;
                if(type=='ROI'){
					var n=$1/($2==0?1:$2);
                    return n.toFixed(1);
				}else{
                    if($1==0) return 'X%';
                    var n=$2/$1;
				}
                return (n*100).toFixed(2)+'%';
			},
            formatROI(row){
                return this.roias('ROI',row);
			},
            formatROAS(row){
                return this.roias('ROAS',row);
			},
            formatAmountSpent:function(row, column){
                return '$'+row.AmountSpent;
			},
            formatChildDate:function(row){
                return row.Type==7?'last 7 day':(row.Type==14?'last 14 day':row.Date);
            },
			openRulesDialog:function(data){
                this.$emit('openRulesDialog',data);
			},
            searchThatID(data){
				this.$emit('searchThatID',data,this.dataType);
			},
//            getShowSummary(){
//            	return (this.adsData.length>2);
//			},
            getSummaries(param){
                const { columns, data } = param;
                const sums = [];
                if(data.length<2) return [];
                columns.forEach((column, index) => {
                    if (index === 0) {
						return;
					}
					if (index === 2) {
						sums[index] = '共计('+data.length+')条';
						return;
					}

					const values = data.map(item =>
						Number(item[column.property]?item[column.property].toString().replace(/[\$,]+/g,''):item[column.property]));
					//console.log('values',values);


					if (!values.every(value => isNaN(value))) {
						sums[index] = values.reduce((prev, curr) => {
								const value = Number(curr);
							if (!isNaN(value)) {
								return prev + curr;
							} else {
								return prev;
							}
						}, 0);
						if(index=='5' || index=='6' || index=='8' || index=='10' || index=='12'|| index=='15' ||
							index=='16'){
                            sums[index] = '$'+sums[index].toFixed(2);
						}

					} else {
						sums[index] = '';
					}
				});
                setTimeout(function(){
                    var el=document.getElementsByClassName('el-table__fixed');
                    var el_r=document.getElementsByClassName('el-table__fixed-right');
                    for(var i in el){
                        if(typeof el[i]!='undefined' && typeof el[i].style!='undefined')
                        	el[i].style.bottom=0;
                        if(typeof el_r[i]!='undefined' && typeof el_r[i].style!='undefined')
                        	el_r[i].style.bottom=0;
                    }
                },1000);
                return sums;
			}
		},
        mounted(){

        }
    }
</script>