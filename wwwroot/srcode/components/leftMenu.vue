<style lang="stylus" rel="stylesheet/scss">

</style>
<template>
  <div class="leftMenu">
    <el-row class="tac">
      <el-menu default-active="2" class="el-menu-vertical-demo" @open="handleOpen" @close="handleClose" @select="handleSelect" router="router">
        <el-menu-item-group v-show="nav.system">
          <template slot="title">系统管理</template>
          <el-menu-item index="accounts" v-show="nav.system_accounts" ><i class="el-icon-star-on"></i>广告账号</el-menu-item>
          <el-menu-item index="users" v-show="nav.system_users" ><i class="el-icon-document"></i>员工管理</el-menu-item>
        </el-menu-item-group>
        <el-menu-item-group>
          <template slot="title">广告优化</template>
          <el-menu-item index="adsList" v-show="nav.ads" ><i class="el-icon-menu"></i>广告管理</el-menu-item>
          <el-menu-item index="rulesList" v-show="nav.rules" ><i class="el-icon-setting"></i>规则管理</el-menu-item>
          <el-menu-item index="rulesLog" v-show="nav.rules_log" ><i class="el-icon-time"></i>优化记录</el-menu-item>
        </el-menu-item-group>
      </el-menu>
    </el-row>
  </div>
</template>



<script>
import Vue from 'vue'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-default/index.css'
import vk from '../vk.js'
import uri from '../uri.js'
Vue.use(ElementUI)
export default {

  data () {
    return {
        nav:{
            system:true
        }
    };
  },
  mounted(){
      vk.http(uri.getNavList,{},this.then);
  },
  methods: {
      then(json,code){
          this.nav=json.data;
      },
      handleOpen(key, keyPath) {
          console.log(key, keyPath);
      },
      handleClose(key, keyPath) {
          console.log(key, keyPath);
      },
      handleSelect(key, keyPath){
          console.log(key, keyPath);
//          new Vue({
//              el: '#app_right_content',
//              components: {
//                  app_right_content:app_right_content
//              },
//              render: h => h(app_right_content)
//          })
      }
  }
}
</script>
