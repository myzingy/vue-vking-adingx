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
    .keyword-ac .el-input__icon+.el-input__inner{
        width:120px;
    }
</style>
<template>
    <div class="mytable">
        <v-headerTop></v-headerTop>
        <el-col :span="4" style="height:100%;">
            <div class="grid-left bg-purple-darkc overflow-y"
                 id="app_left_menu">
                <v-leftMenu></v-leftMenu>
            </div>
        </el-col>
        <el-col :span="20" style="height:100%;">
            <div class="keyword">
                <el-form :inline="true" :model="formSearch" class="demo-form-inline">
                    <el-form-item>
                        <el-select v-model="formSearch.keyword_acid" placeholder="全部账号" @change="onFormSearch"
                                   class="keyword-ac">
                            <el-option value="" label="全部账号"></el-option>
                            <el-option
                                    v-for="item in acs"
                                    :key="item.account_id"
                                    :label="item.name"
                                    :value="item.account_id">
                                <span style="float: left">{{ item.name }}</span>
                                <span style="float: right; font-size: 10px; padding-left: 20px;">{{
                                    item.account_id }}</span>
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item>
                        <el-select v-model="formSearch.delivery" placeholder="全部状态" @change="onFormSearch"
                                   class="keyword-ac">
                            <el-option label="全部状态" value=""></el-option>
                            <el-option label="Active" value="active"></el-option>
                            <el-option label="Inactive" value="inactive"></el-option>
                        </el-select>
                        <el-date-picker
                                :editable="false"
                                v-model="formSearch.dateOne"
                                type="daterange"
                                align="right"
                                placeholder="选择日期范围"
                                :picker-options="dateChoice" @change="onFormSearch">
                        </el-date-picker>
                    </el-form-item>
                    <el-form-item>
                        <el-input style="width:120px;" v-model="formSearch.keyword"
                                  placeholder="Keyword"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="onFormSearch" icon="search">查询</el-button>
                        <a href="javascript://" @click="onClearFormSearch">清空条件</a>
                    </el-form-item>
                </el-form>
                <el-table :data="rulesLog" border style="width: 100%" max-height="700" :summary-method="getSummaries"
                          show-summary @sort-change="sortChange" @expand="expandChange">
                    <el-table-column type="expand" fixed>
                        <template scope="props">
                                <keywordsAC :scope="props" :formSearch="formSearch"
                                            style="min-height:500px;width:1200px;"></keywordsAC>
                        </template>
                    </el-table-column>
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
                <el-pagination style=" margin: 20px auto; width:300px;"
                        @size-change="handleSizeChange"
                        @current-change="handleCurrentChange"
                        :page-size="formSearch.limit"
                        layout="total, prev, pager, next"
                        :total="total">
                </el-pagination>
            </div>
        </el-col>
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
    import keywordsAC from './keywords-ac.vue';

    Vue.use(ElementUI)
    export default {
        components:{
            keywordsAC:keywordsAC,
        },
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
                    keyword_acid:"",
                    dateOne:"",
                    delivery:"",
                },
                acs:[],
                dateChoice:date_choice,
                expands:[],
            }
        },
        computed: mapState({ user: state => state.user }),
        mounted(){
            this.getData();
            vk.http(uri.getFBAccounts,{},this.then);
        },
        methods:{
            getData(){
                var formSearch={};
                Object.assign(formSearch,this.formSearch);
                formSearch.dateOne=formSearch.dateOne.toString();
                vk.http(uri.getKeywords,formSearch,this.then);
            },
            then:function(json,code){
                switch(code){
                    case uri.getFBAccounts.code:
                        this.acs=json.data;
                        break;
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
                        if([2].indexOf(index)>-1){//int
                            sums[index] = vk.numberFormat(sums[index]);
                        }else if([7,8,10,11].indexOf(index)>-1){
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
            },
            expandChange(){
                for(var i in this.expands){
                    console.log('expandChange',this.expands[i]);
                    
                }
                if(arguments[1]){
                    this.expands.push(arguments[0]);
                }
            }
        }
    }
</script>