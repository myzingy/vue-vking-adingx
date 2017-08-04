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
    <div>
        <el-form :inline="true" :model="formSearch" class="demo-form-inline">
            <el-form-item>
                <el-input style="width:300px;" v-model="formSearch.keyword"
                          placeholder="AccountId / Keyword"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="onFormSearch" icon="search">查询</el-button>
                <a href="javascript://" @click="onClearFormSearch">清空条件</a>
            </el-form-item>
        </el-form>
        <el-table :data="rulesLog" border style="width: 100%" max-height="700" :summary-method="getSummaries"
                  show-summary @sort-change="sortChange">

            <el-table-column columnKey="name" prop="name"
                             label="Name"
                             width="250">
            </el-table-column>
            <!--
            <el-table-column columnKey="id" prop="id"
                             label="Keyword Id"
                             width="120">
            </el-table-column>
            -->
            <el-table-column columnKey="spend" prop="spend" label="Spend"
                             width="80"
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
        <el-pagination style=" margin: 20px auto; width:300px;"
                @size-change="handleSizeChange"
                @current-change="handleCurrentChange"
                :page-size="formSearch.limit"
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
    import date_choice from '../../date_choice.js';

    Vue.use(ElementUI)
    export default {
        data:function(){
            return {
                activeName: 'getRulesLog',
                rulesLog:[],
                rulesLogPK:[],
                popover_img_src:"",
                total:0,
                //limit:30,
                //offset:0,
                inputTagValue:'',
                editor:false,
                formSearch:{
                    keyword:'',
                    limit:30,
                    offset:0,
                    order:"",
                    sort:"desc",
                },
            }
        },
        computed: mapState({ user: state => state.user }),
        mounted(){
            this.getData();
        },
        methods:{
            getData(){
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
            handleTabClick:function(dom){
                var uriKey=dom.name;
                var params={};
                if(uriKey=='getRulesLog') {
                    vk.http(uri[uriKey],params,this.then);
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
            getSummaries(param){
                const { columns, data } = param;
                const sums = [];
                if(data.length<2) return [];
                columns.forEach((column, index) => {
                    if (index === 0) {
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
                        if([1].indexOf(index)>-1){//int
                            sums[index] = vk.numberFormat(sums[index]);
                        }else if([6,8,9].indexOf(index)>-1){
                            sums[index] = vk.numberFormat(sums[index],0,'');
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
                this.formSearch.offset=(page-1)*this.formSearch.limit;
                this.getData();
            },
            onClearFormSearch(){
                this.formSearch.keyword="";
                this.getData();
            },
            onFormSearch(){
                this.getData();
            },
            sortChange(obj){
                this.formSearch.order=obj.prop;
                this.formSearch.sort=obj.order=='ascending'?'asc':'desc';
                this.getData();
            }
        }
    }
</script>