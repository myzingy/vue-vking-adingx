<style lang="stylus" rel="stylesheet/scss">
    .overflow-y{ overflow-y: auto;}
    .header .el-select:hover .el-input__inner{
        background: #222;
        color:#fff;
    }
    .header .el-input__icon+.el-input__inner{
        background: #222;
        color:#fff;
    }
    .header .el-input__inner{
        border: 0;
    }
    .header-select .el-select-dropdown__wrap {
        max-height: 100%;
    }
    .mytable .el-table__expanded-cell
        padding-top 0
        padding-bottom 0
    .mytable .el-table .cell, .mytable .el-table th>div
        padding-left 3px
        padding-right 3px
    .mytable .el-table th>.cell
        overflow hidden
        height 30px
    .mytable .el-tabs--card>.el-tabs__header .el-tabs__item .el-icon-close
        position relative
        font-size 16px
        width auto
        height auto
        vertical-align middle
        line-height 100%
        overflow hidden
        top 0
        right 0
        -ms-transform-origin 50% 50%
        transform-origin 50% 50%
</style>
<template>
    <v-header title="Statistics">
        <router-link slot="left" to="?">
            <el-select v-model="ac_idx" placeholder="Select Account Start" @change="acChecked" popper-class="header-select">
                <el-option
                        v-for="item in acs"
                        :key="item.account_id"
                        :label="item.account_name"
                        :value="item.account_id">
                    <span style="float: left">{{ item.account_name }}</span>
                    <span style="float: right; font-size: 10px; padding-left: 20px;">{{
                        item.account_id }}</span>
                </el-option>
            </el-select>
        </router-link>
        <div slot="right">
            <router-link  to="/signout">{{user.name}}, Logout</router-link>
        </div>

    </v-header>
</template>
<script>
    import Vue from 'vue'
    import { mapState,mapActions } from 'vuex'
    import { CKECKED_AC } from '../store/data.js'
    import vk from '../vk.js';
    import uri from '../uri.js';
    import ElementUI from 'element-ui'
    import 'element-ui/lib/theme-default/index.css'

    Vue.use(ElementUI)

    var App={
        data:function(){
            return {
                acs:[],
                ac_idx:!vk.isProduction(),
                contentHash:"",
            }
        },
        computed: mapState({
            user: state => state.user,
            ac_id: state => state.data?state.data.ac_id:"",
        }),
        mounted(){
            vk.loading(false);
            console.log('store.state.data',this.ac_id);

            this.getAcsList();
            this.setContentHash(location.hash);
        },
        methods:{
            ...mapActions([CKECKED_AC]),
            acChecked:function(ac_id){
                var old_id=this.ac_id;
                this.CKECKED_AC({ac_id:ac_id});
                this.ac_idx=ac_id;
                if(old_id!=ac_id)
                    location.reload();
            },
            then(json,code){
                switch(code){
                    case uri.getAcsList.code:
                        this.acs=json.data;
                        this.ac_idx=this.ac_id;
                        break;
                    case uri.getFBAccounts.code:
                        var acs=[];
                        json.data.forEach(row=>{
                            acs.push({
                                account_id:row.account_id,
                                account_name:row.name
                            });
                        });
                        this.acs=acs;
                        this.ac_idx=this.ac_id;
                        break;
                }
            },
            getAcsList(){
                //vk.http(uri.getAcsList,{},this.then);
                vk.http(uri.getFBAccounts,{},this.then);
            },
            setContentHash(hash){
                hash=hash.replace('#/','');
                //if(!hash) return;
                console.log('setContentHash',hash);
                this.contentHash=hash.replace('#/','');
            }
        },
    }
    export default App
</script>