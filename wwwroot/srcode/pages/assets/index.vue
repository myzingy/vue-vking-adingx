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
            <el-form-item style="width:100px;">
                <el-select v-model="formSearch.brand" placeholder="全部品牌" @change="onFormSearch">
                    <el-option label="全部品牌" value=""></el-option>
                    <el-option label="Jeulia" value="jeulia"></el-option>
                    <el-option label="Gnoce" value="gnoce"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item>
                <el-input style="width:300px;" v-model="formSearch.keyword"
                          placeholder="AccountId / Author / SKU / Filename"></el-input>
            </el-form-item>
            <el-popover
                    ref="popoverDate"
                    placement="bottom"
                    width="600"
                    trigger="click">
                <el-row :gutter="24">
                    <el-col :span="20">
                        <el-form-item>
                            <el-select v-model="formSearch.dataType" placeholder="选择日期" @change="onDataTypeChangeOne">
                                <el-option label="Lifetime" value="lifetime"></el-option>
                                <el-option label="Last 7 Day" value="last_7day"></el-option>
                                <el-option label="Last 14 Day" value="last_14day"></el-option>
                                <el-option label="自定义日期" value="custom"></el-option>
                            </el-select>
                            <el-date-picker
                                    :disabled="dateOne"
                                    :editable="false"
                                    v-model="formSearch.dateOne"
                                    type="daterange"
                                    align="right"
                                    placeholder="选择日期范围"
                                    :picker-options="dateChoice">
                            </el-date-picker>
                        </el-form-item>
                        <el-form-item>
                            <el-select v-model="formSearch.dataTypeTwo" placeholder="选择日期" @change="onDataTypeChangeTwo">
                                <el-option label="Lifetime" value="lifetime"></el-option>
                                <el-option label="Last 7 Day" value="last_7day"></el-option>
                                <el-option label="Last 14 Day" value="last_14day"></el-option>
                                <el-option label="自定义日期" value="custom"></el-option>
                            </el-select>
                            <el-date-picker
                                    :disabled="dateTwo"
                                    :editable="false"
                                    v-model="formSearch.dateTwo"
                                    type="daterange"
                                    align="right"
                                    placeholder="选择日期范围"
                                    :picker-options="dateChoice"
                                    @change="pkFlagSetting">
                            </el-date-picker>
                        </el-form-item>
                    </el-col>
                    <el-col :span="4">
                        <el-button style="height: 100px;" type="primary" @click="onFormSearch" icon="search">查 询
                        </el-button>
                    </el-col>
                </el-row>
            </el-popover>
            <el-button type="primary" icon="date" v-popover:popoverDate>日期</el-button>
            <el-form-item>
                <el-button type="primary" @click="onFormSearch" icon="search">查询</el-button>
                <a href="javascript://" @click="onClearFormSearch">清空条件</a>
            </el-form-item>
            <el-form-item>
                <el-radio-group v-model="formSearch.assetType" @change="onFormSearch">
                    <el-radio-button label="">All Assets</el-radio-button>
                    <el-radio-button label="0">Images</el-radio-button>
                    <el-radio-button label="1">Videos</el-radio-button>
                </el-radio-group>
            </el-form-item>
        </el-form>
        <el-table :data="rulesLog" border style="width: 100%" max-height="700" :default-sort =
                "{prop: 'amountspent', order: 'descending'}" :summary-method="getSummaries"
                  show-summary>
            <el-table-column type="expand" fixed>
                <template scope="props">
                    <span>Updated Time:<span class="val">{{props.row.updated_time}}</span></span>
                    <span>Size:<span class="val">{{props.row.original_width}} x {{props.row.original_height}}</span></span>
                    <el-table :data="props.row.list" border style="width: 100%">
                        <el-table-column prop="account_id" label="Ad Account" width="150"></el-table-column>
                        <el-table-column :formatter="numberFormatInt" columnKey="websiteaddstocart" label="Website Adds to Cart" width="80"></el-table-column>
                        <el-table-column :formatter="moneyFormat" columnKey="costperwebsiteaddtocart" label="Cost per Website Add to Cart" width="80"></el-table-column>
                        <el-table-column :formatter="moneyFormat" columnKey="amountspent" label="Spent"
                                         width="80"></el-table-column>
                        <el-table-column :formatter="numberFormatInt" columnKey="websitepurchases" label="Website Purchases" width="80"></el-table-column>
                        <el-table-column :formatter="moneyFormat" columnKey="websitepurchasesconversionvalue" label="Website Purchases Conversion Value" width="80"></el-table-column>
                        <el-table-column :formatter="numberFormatInt" columnKey="linkclicks" label="Link Clicks" width="80"></el-table-column>
                        <el-table-column :formatter="moneyFormat" columnKey="cpc" label="CPC (Cost per Link Click)" width="80"></el-table-column>
                        <el-table-column :formatter="numberFormatPer" columnKey="ctr" label="CTR (Link Click-Through Rate)" width="80"></el-table-column>
                        <el-table-column :formatter="moneyFormat" columnKey="cpm1000" label="CPM (Cost per 1,000 Impressions)"
                                         width="80"></el-table-column>
                        <el-table-column :formatter="numberFormatInt" columnKey="reach" label="Reach" width="80"></el-table-column>
                        <el-table-column :formatter="numberFormatInt" columnKey="results" label="Results" width="80"></el-table-column>
                        <el-table-column :formatter="CostperResult" columnKey="costperresult" label="Cost per Result" width="80"></el-table-column>
                        <el-table-column :formatter="numberFormatInt" columnKey="impressions" label="Impressions"
                                         width="80"></el-table-column>
                        <el-table-column :formatter="numberFormat" columnKey="frequency" label="Frequency"
                                         width="80"></el-table-column>
                        <el-table-column :formatter="numberFormat" columnKey="relevance_score" label="Relevent Score"
                                         width="80"></el-table-column>
                        <el-table-column prop="positive_feedback" label="Positive Feedback"
                                         width="80"></el-table-column>
                        <el-table-column prop="negative_feedback" label="Negative Feedback"
                                         width="80"></el-table-column>
                        <el-table-column :formatter="formatAdsNumView" prop="ads_num" columnKey="ads_num" label="广告数"
                                         width="250" sortable></el-table-column>
                        <el-table-column :formatter="numberFormatPer" columnKey="conversion_rate" prop="conversion_rate" label="转化率"
                                         width="70"></el-table-column>
                        <el-table-column :formatter="numberFormatPer" columnKey="roas" prop="roas" label="ROAS"
                                         width="70"></el-table-column>
                    </el-table>
                </template>
            </el-table-column>
            <el-table-column width="110" label="Thumb" fixed>
                <template scope="scope">
                    <el-popover placement="right" title="" width="400" trigger="hover">
                        <div>
                            <span v-if="scope.row.type == '1'">Video:</span><span v-else>Image:</span>
                            <h1 v-if="scope.row.updated_time == 'Object with ID does not exist'">
                                <span class="val">{{scope.row.updated_time}}</span>
                            </h1>
                            <span v-else="">Updated Time:<span class="val">{{scope.row.updated_time}}</span></span>
                            <span>Size:<span class="val">{{scope.row.original_width}} x {{scope.row.original_height}}</span></span>
                            <br>
                            <img width="400" :src="scope.row.permalink_url">
                            <div class="debug">
                                <p>
                                    http://www.vking.com/facebook_ads/wwwroot/apido/asyn.getAssetForAd?debug=true&ac_id={{scope.row.account_id}}&ad_id={{scope.row.ad_id}}
                                </p>
                                <p v-if="scope.row.type == '1'">
                                    http://www.vking.com/facebook_ads/wwwroot/apido/asyn.getAssetsVideoInfo?debug=true&ac_id={{scope.row.account_id}}&video_id={{scope.row.id}}
                                </p>
                                <p v-else="">
                                    http://www.vking.com/facebook_ads/wwwroot/apido/asyn.getAssetsImageInfo?debug=true&ac_id={{scope.row.account_id}}&hashes={{scope.row.hash}}
                                </p>
                            </div>
                        </div>
                        <span slot="reference"
                           v-if="scope.row.updated_time == 'Object with ID does not exist'">
                            <img height="100" :src="scope.row.permalink_url" v-if="scope.row.permalink_url"/>
                            <span v-else="">{{scope.row.name}}</span>
                        </span>
                        <a :href="scope.row.url" target="_blank" slot="reference"
                           v-else="">
                            <img height="100" :src="scope.row.permalink_url" v-if="scope.row.permalink_url"/>
                            <span v-else="">{{scope.row.name}}</span>
                        </a>
                    </el-popover>
                </template>
            </el-table-column>
            <el-table-column columnKey="websiteaddstocart" prop="websiteaddstocart"
                             label="Website Adds to Cart"
                             width="80" className="autotooltip" sortable>
                <template scope="scope">
                    <div>{{numberFormatInt(scope.row,{columnKey:'websiteaddstocart'})}}</div>
                    <div class="pk-val" v-show="pkFlag">{{numberFormatInt(scope.row,{columnKey:'websiteaddstocart'},
                        'PK')}}</div>
                </template>
            </el-table-column>
            <el-table-column columnKey="costperwebsiteaddtocart" prop="costperwebsiteaddtocart"
                             label="Cost per Website Add to Cart" width="80" sortable>
                <template scope="scope">
                    <div>{{moneyFormat(scope.row,{columnKey:'costperwebsiteaddtocart'})}}</div>
                    <div class="pk-val" v-show="pkFlag">{{moneyFormat(scope.row,{columnKey:'costperwebsiteaddtocart'},'PK')}}</div>
                </template>
            </el-table-column>
            <el-table-column columnKey="websiteaddstocartconversionvalue" prop="websiteaddstocartconversionvalue"
                             label="Website Adds to Cart Conversion Value" width="80" sortable>
                <template scope="scope">
                    <div>{{moneyFormat(scope.row,{columnKey:'websiteaddstocartconversionvalue'})}}</div>
                    <div class="pk-val" v-show="pkFlag">{{moneyFormat(scope.row,{columnKey:'websiteaddstocartconversionvalue'},'PK')}}</div>
                </template>
            </el-table-column>
            <el-table-column columnKey="amountspent" prop="amountspent" label="Spent"
                             width="80"
                             sortable>
                <template scope="scope">
                    <div>{{moneyFormat(scope.row,{columnKey:'amountspent'})}}</div>
                    <div class="pk-val" v-show="pkFlag">{{moneyFormat(scope.row,{columnKey:'amountspent'},'PK')}}</div>
                </template>
            </el-table-column>
            <el-table-column columnKey="websitepurchases" prop="websitepurchases" label="Website Purchases"
                             width="80" sortable>
                <template scope="scope">
                    <div>{{numberFormatInt(scope.row,{columnKey:'websitepurchases'})}}</div>
                    <div class="pk-val" v-show="pkFlag">{{numberFormatInt(scope.row,{columnKey:'websitepurchases'},'PK')}}</div>
                </template>
            </el-table-column>
            <el-table-column columnKey="websitepurchasesconversionvalue" prop="websitepurchasesconversionvalue"
                             label="Website Purchases Conversion Value" width="80" sortable>
                <template scope="scope">
                    <div>{{moneyFormat(scope.row,{columnKey:'websitepurchasesconversionvalue'})}}</div>
                    <div class="pk-val" v-show="pkFlag">{{moneyFormat(scope.row,{columnKey:'websitepurchasesconversionvalue'},'PK')}}</div>
                </template>
            </el-table-column>
            <el-table-column columnKey="linkclicks"
                             prop="linkclicks" label="Link Clicks"
                             width="80" sortable>
                <template scope="scope">
                    <div>{{numberFormatInt(scope.row,{columnKey:'linkclicks'})}}</div>
                    <div class="pk-val" v-show="pkFlag">{{numberFormatInt(scope.row,{columnKey:'linkclicks'},'PK')}}</div>
                </template>
            </el-table-column>
            <el-table-column columnKey="cpc" prop="cpc" label="CPC (Cost per Link Click)"
                             width="80"
                             sortable
            >
                <template scope="scope">
                    <div>{{moneyFormat(scope.row,{columnKey:'cpc'})}}</div>
                    <div class="pk-val" v-show="pkFlag">{{moneyFormat(scope.row,{columnKey:'cpc'},'PK')}}</div>
                </template>
            </el-table-column>
            <el-table-column columnKey="ctr" prop="ctr"
                             label="CTR (Link Click-Through Rate)"
                             width="80" sortable>
                <template scope="scope">
                    <div>{{numberFormatPer(scope.row,{columnKey:'ctr'})}}</div>
                    <div class="pk-val" v-show="pkFlag">{{numberFormatPer(scope.row,{columnKey:'ctr'},'PK')}}</div>
                </template>
            </el-table-column>
            <el-table-column columnKey="cpm1000" prop="cpm1000"
                             label="CPM (Cost per 1,000 Impressions)"
                             width="80" sortable>
                <template scope="scope">
                    <div>{{moneyFormat(scope.row,{columnKey:'cpm1000'})}}</div>
                    <div class="pk-val" v-show="pkFlag">{{moneyFormat(scope.row,{columnKey:'cpm1000'},'PK')
                        }}</div>
                </template>
            </el-table-column>
            <el-table-column columnKey="reach" prop="reach" label="Reach" width="80"
                             sortable>
                <template scope="scope">
                    <div>{{numberFormatInt(scope.row,{columnKey:'reach'})}}</div>
                    <div class="pk-val" v-show="pkFlag">{{numberFormatInt(scope.row,{columnKey:'reach'},'PK')}}</div>
                </template>
            </el-table-column>
            <el-table-column columnKey="results" prop="results" label="Results" width="80"
                             sortable
            >
                <template scope="scope">
                    <div>{{numberFormatInt(scope.row,{columnKey:'results'})}}</div>
                    <div class="pk-val" v-show="pkFlag">{{numberFormatInt(scope.row,{columnKey:'results'},'PK')}}</div>
                </template>
            </el-table-column>
            <el-table-column columnKey="costperresult" prop="costperresult" label="Cost per Result"
                             width="80" sortable>
                <template scope="scope">
                    <div>{{CostperResult(scope.row,{columnKey:'costperresult'})}}</div>
                    <div class="pk-val" v-show="pkFlag">{{CostperResult(scope.row,{columnKey:'costperresult'},'PK')}}</div>
                </template>
            </el-table-column>
            <el-table-column columnKey="impressions" prop="impressions" label="Impressions"
                             width="80" sortable>
                <template scope="scope">
                    <div>{{numberFormatInt(scope.row,{columnKey:'impressions'})}}</div>
                    <div class="pk-val" v-show="pkFlag">{{numberFormatInt(scope.row,{columnKey:'impressions'},'PK')}}</div>
                </template>
            </el-table-column>
            <el-table-column columnKey="frequency"  prop="frequency" label="Frequency"
                             width="80" sortable>
                <template scope="scope">
                    <div>{{numberFormat(scope.row,{columnKey:'frequency'})}}</div>
                    <div class="pk-val" v-show="pkFlag">{{numberFormat(scope.row,{columnKey:'frequency'},'PK')}}</div>
                </template>
            </el-table-column>
            <el-table-column columnKey="relevance_score"  prop="relevance_score"
                             label="Relevent Score"
                             width="80" sortable>
                <template scope="scope">
                    <div>{{numberFormat(scope.row,{columnKey:'relevance_score'})}}</div>
                    <div class="pk-val" v-show="pkFlag">{{numberFormat(scope.row,{columnKey:'relevance_score'},'PK')}}</div>
                </template>
            </el-table-column>
            <el-table-column prop="positive_feedback" label="Positive Feedback"
                             width="80"></el-table-column>
            <el-table-column prop="negative_feedback" label="Negative Feedback"
                             width="80"></el-table-column>
            <el-table-column prop="ads_num" columnKey="ads_num" label="广告数"
                             width="70" sortable></el-table-column>
            <el-table-column columnKey="conversion_rate" prop="conversion_rate" label="转化率"
                             width="70" sortable>
                <template scope="scope">
                    <div>{{numberFormatPer(scope.row,{columnKey:'conversion_rate'})}}</div>
                    <div class="pk-val" v-show="pkFlag">{{numberFormatPer(scope.row,{columnKey:'conversion_rate'},'PK')}}</div>
                </template>
            </el-table-column>
            <el-table-column columnKey="roas" prop="roas" label="ROAS"
                             width="70" sortable>
                <template scope="scope">
                    <div>{{numberFormatPer(scope.row,{columnKey:'roas'})}}</div>
                    <div class="pk-val" v-show="pkFlag">{{numberFormatPer(scope.row,{columnKey:'roas'},'PK')}}</div>
                </template>
            </el-table-column>
            <el-table-column label="Author"  width="100">
                <template scope="scope" >
                    <el-select
                            v-if="editor"
                            v-model="scope.row.author"
                            filterable
                            allow-create
                            placeholder="请选择" @change="setAuthor(scope.row.author,scope.row)"
                            @visible-change="setAuthorFlag">
                        <el-option
                                v-for="item in authors"
                                :key="item"
                                :label="item"
                                :value="item">
                        </el-option>
                    </el-select>
                    <span v-else="">{{scope.row.author}}</span>
                </template>
            </el-table-column>
            <el-table-column label="SKUS" width="250">
                <template scope="scope">
                    <div v-if="editor">
                    <el-tag style=" margin: 3px;"
                            :key="tag"
                            v-for="tag in scope.row.skus"
                            :closable="true"
                            :close-transition="false"
                            @close="handleCloseTag(scope.row.id,tag)"
                    >
                        {{tag}}
                    </el-tag>
                    <el-input
                            class="input-new-tag"
                            v-if="!!scope.row.inputVisible"
                            v-model="inputTagValue"
                            ref="saveTagInput"
                            size="mini"
                            @keyup.enter.native="handleInputConfirm(scope.row.id)"
                            @blur="handleInputConfirm(scope.row.id)"
                    >
                    </el-input>
                    <el-button v-else class="button-new-tag" size="small" @click="showTagInput(scope.row.id)">+
                        Sku</el-button>
                    </div>
                    <el-tag v-else="" style=" margin: 3px;"
                            :key="tag"
                            v-for="tag in scope.row.skus">
                        {{tag}}
                    </el-tag>
                </template>
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
                    dataType:'lifetime',
                    assetType:"",
                    brand:"",
                    dateOne:"",
                    dateTwo:"",
                    dataTypeTwo:"custom",
                },
                authors:[],
                authors_flag:false,
                dateChoice:date_choice,
                dateOne:true,
                dateTwo:false,
                pkFlag:false,
            }
        },
        computed: mapState({ user: state => state.user }),
        mounted(){
            this.editor=location.hash.indexOf('editor')>0; 
            this.getData();
        },
        methods:{
            getData(){
                this.authors_flag=false;
                this.rulesLogPK=[];
                var formSearch={};
                Object.assign(formSearch,this.formSearch);
                formSearch.dateOne=formSearch.dateOne.toString();
                vk.http(uri.assetsGetData,formSearch,this.then);
                if(this.pkFlag){
                    var formSearch={};
                    Object.assign(formSearch,this.formSearch);
                    formSearch.dateOne=formSearch.dateTwo.toString();
                    formSearch.dataType=formSearch.dataTypeTwo;
                    vk.http(uri.assetsGetDataTwo,formSearch,this.then);
                }
            },
            then:function(json,code){
                switch(code){
                    case uri.assetsGetData.code:
                        this.rulesLog=json.data;
                        this.total=parseInt(json.total);
                        break;
                    case uri.assetsGetDataTwo.code:
                        this.rulesLogPK=json.data;
                        break;
                    case uri.assetsSetAuthor.code:
                        break;
                    case uri.assetsSetSkus.code:
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
                return vk.numberFormat(row[columnKey],2,'')+'%';
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
                        if([2,6,8,12,13,19].indexOf(index)>-1){//int
                            sums[index] = vk.numberFormat(sums[index],0,'');
                        }else if([5,7].indexOf(index)>-1){
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
                this.formSearch.offset=(page-1)*this.formSearch.limit;
                this.getData();
            },
            setAuthorFlag(){
                this.authors_flag=true;
            },
            setAuthor(author,obj){
                if(!this.authors_flag) return;
                vk.http(uri.assetsSetAuthor,{
                    'author':author,
                    'id':obj.id,
                },this.then)
            },
            handleCloseTag(id,tag) {
                //console.log('handleCloseTag',id,tag);
                var that=this;
                this.rulesLog.map(item=>{
                    if(item.id==id){
                        console.log('handleCloseTag-item',item.id);
                        item.skus.splice(item.skus.indexOf(tag),1);
                        vk.http(uri.assetsSetSkus,{
                            'skus':item.skus.join(','),
                            'id':id,
                        },that.then);
                        return item;
                    }
                });
                //this.dynamicTags.splice(this.dynamicTags.indexOf(tag), 1);
            },

            showTagInput(id) {
                this.rulesLog.map(item=>{
                    if(item.id==id){
                        console.log('showTagInput-item',item.id);
                        item.inputVisible=true;
                        return item;
                    }
                });
//                this.$nextTick(_ => {
//                    this.$refs.saveTagInput.$refs.input.focus();
//                });
            },

            handleInputConfirm(id) {
                var that=this;
                let inputValue = this.inputTagValue;
                this.rulesLog.map(item=>{
                    if(item.id==id){
                        console.log('handleInputConfirm-item',item.id,inputValue);
                        if (inputValue) {
                            item.skus.push(inputValue);
                            vk.http(uri.assetsSetSkus,{
                                'skus':item.skus.join(','),
                                'id':id,
                            },that.then);
                        }
                        item.inputVisible = false;
                        this.inputTagValue = '';
                        return item;
                    }
                });
            },
            onClearFormSearch(){
                this.formSearch.keyword="";
                this.getData();
            },
            onFormSearch(){
                this.getData();
            },
            onDataTypeChangeOne(val){
                this.dateOne=true;
                if(val=='custom'){
                    this.dateOne=false;
                }
            },
            onDataTypeChangeTwo(val){
                this.dateTwo=true;
                if(val=='custom'){
                    this.pkFlag=false;
                    this.dateTwo=false;
                }else{
                    this.pkFlag=true;
                }
            },
            pkFlagSetting(val){
                if(val){
                    this.pkFlag=true;
                }else{
                    this.pkFlag=false;
                }
            },
            getRowPK(row){
                var id=row.id;
                for(var i in this.rulesLogPK){
                    if(id==this.rulesLogPK[i].id){
                        return this.rulesLogPK[i];
                    }
                }
                return false;
            }
        }
    }
</script>