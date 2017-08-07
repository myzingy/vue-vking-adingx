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
    tth:hover div.cell{
        position: fixed;
        z-index: 99;
        color:#33f;
        margin-top: -15px;
        left:15px;
    }
    .el-table .caret-wrapper {
        position: initial;
    }
    .el-table th > .cell {
        text-indent:15px;
    }
    .debug{ display:none;}
    .pk-val{
        border-top: 1px #d0d0d0 dashed;
        padding-top: 8px;
        color: #ff3333;
        margin-top: 5px;
    }
    .pk-val:after {
        content: "VS";
        color: #d0d0d0;
        margin-top: -20px;
        position: absolute;
        right: 3px;
        font-size: 9px;
    }
</style>
<template>
    <div class="keyword-ac">
        <el-table :data="rulesLog" border style="width: 100%" max-height="500"
                  @sort-change="sortChange">
            <el-table-column type="expand" fixed>
                <template scope="props">
                    <keywordsAD :scope="props" :formSearch="formSearch"
                                style="min-height:300px;width:1000px;"></keywordsAD>
                </template>
            </el-table-column>
            <el-table-column columnKey="account_id" prop="account_id"
                             label="Account ID"
                             width="160">
            </el-table-column>
            <!--
            <el-table-column columnKey="id" prop="id"
                             label="Keyword Id"
                             width="120">
            </el-table-column>
            -->
            <el-table-column columnKey="spend" prop="spend" label="Spend"
                             width="100"
                             sortable="custom" :formatter="moneyFormat">
            </el-table-column>
            <el-table-column columnKey="cpc" prop="cpc" label="cpc"
                             width="60"
                             sortable="custom" :formatter="moneyFormat">
            </el-table-column>
            <el-table-column columnKey="cpm" prop="cpm" label="cpm"
                             width="60"
                             sortable="custom" :formatter="moneyFormat">
            </el-table-column>
            <el-table-column columnKey="ctr" prop="ctr" label="ctr"
                             width="60"
                             sortable="custom" :formatter="numberFormatPer">
            </el-table-column>
            <el-table-column columnKey="cpp" prop="cpp" label="cpp"
                             width="60"
                             sortable="custom" :formatter="numberFormat">
            </el-table-column>
            <el-table-column columnKey="clicks" prop="clicks" label="Clicks"
                             width="80"
                             sortable="custom" :formatter="numberFormatInt">
            </el-table-column>
            <el-table-column columnKey="add_to_cart" prop="add_to_cart" label="AddToCart"
                             width="80"
                             sortable="custom" :formatter="numberFormatInt">
            </el-table-column>
            <el-table-column columnKey="frequency" prop="frequency" label="Frequency"
                             width="100"
                             sortable="custom" :formatter="numberFormat">
            </el-table-column>
            <el-table-column columnKey="impressions" prop="impressions" label="Impressions"
                             width="100"
                             sortable="custom" :formatter="numberFormatInt">
            </el-table-column>
            <el-table-column columnKey="reach" prop="reach" label="Reach"
                             width="80"
                             sortable="custom" :formatter="numberFormatInt">
            </el-table-column>
            <el-table-column columnKey="ads_num" prop="ads_num" label="广告数"
                             width="80"
                             sortable="custom" :formatter="numberFormatInt">
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
    import keywordsAD from './keywords-ad.vue';

    Vue.use(ElementUI)
    export default {
        props: ['scope','formSearch'],
        components:{
            keywordsAD:keywordsAD,
        },
        data:function(){
            return {
                rulesLog:[],
            }
        },
        computed: mapState({ user: state => state.user }),
        mounted(){
            this.getData();
        },
        methods:{
            getData(){
                this.formSearch.dateOne=this.formSearch.dateOne.toString();
                this.formSearch.request='ACCOUNT';
                this.formSearch.name=this.scope.row.name;
                vk.http(uri.getKeywords,this.formSearch,this.then);
            },
            then:function(json,code){
                switch(code){
                    case uri.getKeywords.code:
                        this.rulesLog=json.data;
                        this.total=parseInt(json.total);
                        break;
                }
            },
            numberFormat:function(row, column,pkFlag){
                if(pkFlag=='PK'){
                    if(!this.pkFlag) return;
                    row=this.getRowPK(row);
                    if(!row) return '--';
                }
                var columnKey=arguments[1].columnKey;
                return vk.numberFormat(row[columnKey],2,'');
            },
            numberFormatPer:function(row, column,pkFlag){
                if(pkFlag=='PK'){
                    if(!this.pkFlag) return;
                    row=this.getRowPK(row);
                    if(!row) return '--';
                }
                var columnKey=arguments[1].columnKey;
                if(!isFinite(row[columnKey])) return row[columnKey];
                return vk.numberFormat(row[columnKey]*100,2,'')+'%';
            },
            numberFormatInt:function(row, column,pkFlag){
                if(pkFlag=='PK'){
                    if(!this.pkFlag) return;
                    row=this.getRowPK(row);
                    if(!row) return '--';
                }
                var columnKey=arguments[1].columnKey;
                return vk.numberFormat(row[columnKey],0,'');
            },
            moneyFormat:function(row, column,pkFlag){
                if(pkFlag=='PK'){
                    if(!this.pkFlag) return;
                    row=this.getRowPK(row);
                    if(!row) return '--';
                }
                var columnKey=arguments[1].columnKey;
                return vk.numberFormat(row[columnKey]);
            },
            CostperResult:function(row, column,pkFlag){
                if(pkFlag=='PK'){
                    if(!this.pkFlag) return;
                    row=this.getRowPK(row);
                    if(!row) return '--';
                }
                if(row.websitepurchases==0) return 'X';
                var CostperResult=row.amountspent/row.websitepurchases;
                return  vk.numberFormat(CostperResult);
            },
            formatAdsNumView(row, column){
                return  row.ad_ids+' ['+row.ads_num+']';
            },
            sortChange(obj){
                this.formSearch.order=obj.prop;
                this.formSearch.sort=obj.order=='ascending'?'asc':'desc';
                this.getData();
            }
        }
    }
</script>