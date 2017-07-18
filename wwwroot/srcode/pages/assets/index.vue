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
                "{prop: 'amountspent', order: 'descending'}" :summary-method="getSummaries"
                  show-summary>
            <el-table-column type="expand" fixed>
                <template scope="props">
                    <span>Updated Time:<span class="val">{{props.row.updated_time}}</span></span>
                    <span>Size:<span class="val">{{props.row.original_width}} x {{props.row.original_height}}</span></span>
                    <el-table :data="props.row.List" border style="width: 100%">
                        <el-table-column :formatter="formatChildDate" label="Ad Account" width="150"></el-table-column>
                        <el-table-column prop="websiteaddstocart" label="Website Adds to Cart" width="80"></el-table-column>
                        <el-table-column prop="costperwebsiteaddtocart" label="Cost per Website Add to Cart" width="80"></el-table-column>
                        <el-table-column prop="amountspent" label="Spent"
                                         width="60"></el-table-column>
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
            <el-table-column width="200" label="Name">
                <template scope="scope">
                    <el-popover placement="right" title="" width="400" trigger="hover">
                        <div>
                            <span v-if="scope.row.type == '1'">Video:</span><span v-else>Image:</span>
                            <span>Updated Time:<span class="val">{{scope.row.updated_time}}</span></span>
                            <span>Size:<span class="val">{{scope.row.original_width}} x {{scope.row.original_height}}</span></span>
                            <br>
                            <img width="400" :src="scope.row.permalink_url">
                        </div>
                        <a :href="scope.row.url" target="_blank" slot="reference">{{ scope.row.name }}</a>
                    </el-popover>
                </template>
            </el-table-column>
            <el-table-column :formatter="numberFormatInt" columnKey="websiteaddstocart" label="Website Adds to Cart"
                             width="80"></el-table-column>
            <el-table-column :formatter="moneyFormat" columnKey="costperwebsiteaddtocart" label="Cost per Website Add to Cart" width="80"></el-table-column>
            <el-table-column :formatter="moneyFormat" columnKey="amountspent"
                             label="Spent" width="80"
                             sortable></el-table-column>
            <el-table-column :formatter="numberFormatInt" columnKey="websitepurchases" label="Website Purchases"
                             width="80"></el-table-column>
            <el-table-column :formatter="moneyFormat" columnKey="websitepurchasesconversionvalue" label="Website Purchases Conversion Value" width="80"></el-table-column>
            <el-table-column :formatter="numberFormatInt" columnKey="linkclicks" label="Link Clicks"
                             width="80"></el-table-column>
            <el-table-column :formatter="moneyFormat" columnKey="cpc" label="CPC (Cost per Link Click)" width="80"></el-table-column>
            <el-table-column :formatter="numberFormatPer" columnKey="ctr" label="CTR (Link Click-Through Rate)"
                             width="80"></el-table-column>
            <el-table-column :formatter="moneyFormat" columnKey="cpm1000" label="CPM (Cost per 1,000 Impressions)"
                             width="80"></el-table-column>
            <el-table-column :formatter="numberFormatInt" columnKey="reach" label="Reach" width="80"></el-table-column>
            <el-table-column :formatter="numberFormatInt" columnKey="results" label="Results" width="80"></el-table-column>
            <el-table-column :formatter="CostperResult" columnKey="costperresult" label="Cost per Result"
                             width="80"></el-table-column>
            <el-table-column :formatter="numberFormat" columnKey="frequency" label="Frequency"
                             width="80"></el-table-column>
            <el-table-column :formatter="numberFormat" columnKey="relevance_score" label="Relevent Score"
                             width="80"></el-table-column>
            <el-table-column prop="positive_feedback" label="Positive Feedback"
                             width="80"></el-table-column>
            <el-table-column prop="negative_feedback" label="Negative Feedback"
                             width="80"></el-table-column>
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
        <el-pagination style=" margin: 20px auto; width:300px;"
                @size-change="handleSizeChange"
                @current-change="handleCurrentChange"
                :page-size="limit"
                layout="total, prev, pager, next"
                :total="total">
        </el-pagination>
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
                popover_img_src:"",
                total:0,
                limit:30,
                offset:0,
            }
        },
        computed: mapState({ user: state => state.user }),
        mounted(){
              this.getData();
        },
        methods:{
            getData(){
                var params={
                    limit:this.limit,
                    offset:this.offset,
                };
                vk.http(uri.assetsGetData,params,this.then);
            },
            then:function(json,code){
                switch(code){
                    case uri.assetsGetData.code:
                        this.rulesLog=json.data;
                        this.total=parseInt(json.total);
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
            numberFormat:function(row, column){
                var columnKey=arguments[1].columnKey;
                return vk.numberFormat(row[columnKey],2,'');
            },
            numberFormatPer:function(row, column){
                var columnKey=arguments[1].columnKey;
                return vk.numberFormat(row[columnKey],2,'')+'%';
            },
            numberFormatInt:function(row, column){
                var columnKey=arguments[1].columnKey;
                return vk.numberFormat(row[columnKey],0,'');
            },
            moneyFormat:function(row, column){
                var columnKey=arguments[1].columnKey;
                return vk.numberFormat(row[columnKey]);
            },
            CostperResult:function(row, column){
                if(row.websitepurchases==0) return 'X';
                var CostperResult=row.amountspent/row.websitepurchases;
                return  vk.numberFormat(CostperResult);
            },
            getSummaries(param){
                const { columns, data } = param;
                const sums = [];
                if(data.length<2) return [];
                columns.forEach((column, index) => {
                    if (index === 0) {
                        return;
                    }
                    if (index === 1) {
                        sums[index] = '共计('+data.length+')条';
                        return;
                    }
                    if(!column.columnKey) return;
                    const values = data.map(item =>
                        Number(item[column.columnKey] ? item[column.columnKey].toString().replace(/[\$,]+/g, '') : item[column.columnKey])
                    );
                    if (!values.every(value => isNaN(value))) {
                        sums[index] = values.reduce((prev, curr) => {
                            const value = Number(curr);
                            if (!isNaN(value)) {
                                return prev + curr;
                            } else {
                                return prev;
                            }
                        }, 0);
                        if([2,5,7].indexOf(index)>-1){//int
                            sums[index] = vk.numberFormat(sums[index],0,'');
                        }else if([4,6].indexOf(index)>-1){
                            sums[index] = vk.numberFormat(sums[index]);
                        }else{
                            sums[index] = '';
                        }
                    } else {
                        sums[index] = '';
                    }
                });
                return sums;
            },
            handleSizeChange(){
                console.log(arguments);
            },
            handleCurrentChange(page){
                this.offset=(page-1)*this.limit;
                this.getData();
            },
        }
    }
</script>