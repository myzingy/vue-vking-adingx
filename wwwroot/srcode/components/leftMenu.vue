<style lang="stylus" rel="stylesheet/scss">

</style>
<template>
  <div class="leftMenu">
    <el-row class="tac">
      <el-menu default-active="2" class="el-menu-vertical-demo" @open="handleOpen" @close="handleClose" @select="handleSelect" router="router">
        <!--
        <el-submenu index="1">
          <template slot="title"><i class="el-icon-message"></i>导航一</template>
          <el-menu-item-group>
            <template slot="title">分组一</template>
            <el-menu-item index="1-1">选项1</el-menu-item>
            <el-menu-item index="1-2">选项2</el-menu-item>
          </el-menu-item-group>
          <el-menu-item-group title="分组2">
            <el-menu-item index="1-3">选项3</el-menu-item>
          </el-menu-item-group>
          <el-submenu index="1-4">
            <template slot="title">选项4</template>
            <el-menu-item index="1-4-1">选项1</el-menu-item>
          </el-submenu>
        </el-submenu>
        -->
        <el-menu-item index="adsList" dataa="a" data-b="b"><i class="el-icon-menu"></i>广告管理</el-menu-item>
        <el-menu-item index="rulesList" ><i class="el-icon-setting"></i>规则管理</el-menu-item>
        <el-menu-item index="rulesLog" ><i class="el-icon-setting"></i>优化记录</el-menu-item>
      </el-menu>
    </el-row>
  </div>
</template>



<script>
import Vue from 'vue'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-default/index.css'

Vue.use(ElementUI)
export default {

  data () {
    return {

    };
  },
  methods: {
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
