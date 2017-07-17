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
    .val{
        color:#f33;
        display: inline-block;
        padding-right: 20px;
    }
</style>
<template>
    <div>
        <el-table :data="rulesLog" border style="width: 100%" max-height="700" :default-sort =
                "{prop: 'AmountSpent', order: 'descending'}" :summary-method="getSummaries"
                  show-summary>
            <el-table-column type="expand" fixed>
                <template scope="props">
                    <span>Updated Time:<span class="val">{{props.row.updated_time}}</span></span>
                    <span>Size:<span class="val">{{props.row.original_width}} x {{props.row.original_height}}</span></span>
                    <el-table :data="props.row.List" border style="width: 100%">
                        <el-table-column :formatter="formatChildDate" label="Ad Account" width="150"></el-table-column>
                        <el-table-column prop="websiteaddstocart" label="Website Adds to Cart" width="80"></el-table-column>
                        <el-table-column prop="costperwebsiteaddtocart" label="Cost per Website Add to Cart" width="80"></el-table-column>
                        <el-table-column :formatter="formatAmountSpent" prop="amountspent" label="Amount Spent" width="80"></el-table-column>
                        <el-table-column prop="websitepurchases" label="Website Purchases" width="80"></el-table-column>
                        <el-table-column prop="websitepurchasesconversionvalue" label="Website Purchases Conversion Value" width="80"></el-table-column>
                        <el-table-column prop="linkclicks" label="Link Clicks" width="80"></el-table-column>
                        <el-table-column prop="cpc" label="CPC (Cost per Link Click)" width="80"></el-table-column>
                        <el-table-column prop="ctr" label="CTR (Link Click-Through Rate)" width="80"></el-table-column>
                        <el-table-column prop="cpm1000" label="CPM (Cost per 1,000 Impressions)"
                                         width="80"></el-table-column>
                        <el-table-column prop="reach" label="Reach" width="80"></el-table-column>
                        <el-table-column prop="results" label="Results" width="80"></el-table-column>
                        <el-table-column prop="costperresult" label="Cost per Result" width="80"></el-table-column>
                    </el-table>
                </template>
            </el-table-column>
            <el-table-column fixed label="Name" width="200">
                <template scope="scope">
                    <a :href="scope.row.url" target="_blank">{{ scope.row.name }}</a>
                </template>
            </el-table-column>

            <el-table-column prop="delivery" label="Delivery" width="60"></el-table-column>
            <el-table-column prop="websiteaddstocart" label="Website Adds to Cart" width="80"></el-table-column>
            <el-table-column prop="costperwebsiteaddtocart" label="Cost per Website Add to Cart" width="80"></el-table-column>
            <el-table-column :formatter="formatAmountSpent" prop="amountspent" label="Amount Spent" width="140"
                             sortable></el-table-column>
            <el-table-column prop="websitepurchases" label="Website Purchases" width="80"></el-table-column>
            <el-table-column prop="websitepurchasesconversionvalue" label="Website Purchases Conversion Value" width="80"></el-table-column>
            <el-table-column prop="linkclicks" label="Link Clicks" width="80"></el-table-column>
            <el-table-column prop="cpc" label="CPC (Cost per Link Click)" width="80"></el-table-column>
            <el-table-column prop="ctr" label="CTR (Link Click-Through Rate)" width="80"></el-table-column>
            <el-table-column prop="cpm1000" label="CPM (Cost per 1,000 Impressions)"
                             width="80"></el-table-column>
            <el-table-column prop="reach" label="Reach" width="80"></el-table-column>
            <el-table-column prop="results" label="Results" width="80"></el-table-column>
            <el-table-column prop="costperresult" label="Cost per Result" width="80"></el-table-column>
            <el-table-column label="操作" width="80" fixed="right">
                <template scope="scope">
                    <el-button @click="openRulesDialog(scope.row)"
                               type="text"
                               size="small">
                        编辑
                    </el-button>
                </template>
            </el-table-column>
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
        data:function(){
            return {
                activeName: 'getRulesLog',
                rulesLog:[],
            }
        },
        computed: mapState({ user: state => state.user }),
        mounted(){
            var params={};
            vk.http(uri.assetsGetData,params,this.then);
        },
        methods:{
            then:function(json,code){
                switch(code){
                    case uri.assetsGetData.code:
                        this.rulesLog=json.data;
                        break;
                }


            },
            handleTabClick:function(dom){
                var uriKey=dom.name;
                var params={};
                if(uriKey=='getRulesLog') {
                    vk.http(uri[uriKey],params,this.then);
                }
            },
            formatExecTarget:function(row, column){
                return '['+row.target+']'+row.target_id;
            },
            formatExecRule:function(row){
                return '['+row.rule_id+']'+row.rule_name;
            },
//            expandTab:function(row, expanded){
//            	this.expandTabData=[JSON.parse(row.target_data)];
//                this.expandTabDataType=row.target.toLowerCase();
//                console.log(this.expandTabDataType);
//			}
        }
    }
</script>