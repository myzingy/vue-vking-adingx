<template>
  <main id="app">
  <el-form ref="form" :model="form" label-width="80px">
  <el-col :span="10">
    <el-form-item label="用户组">
      <el-select v-model="form.group_selected" placeholder="请选择用户组">
        <el-option
          key=""
          label="全部组"
          value="">
        </el-option>
        <el-option
          v-for="item in form.group"
          :key="item.Id"
          :label="item.Id"
          :value="item.Id">
        </el-option>
      </el-select>
    </el-form-item>
  </el-col>
  <el-col :span="10">
    <el-form-item label="日期周期">
        <el-date-picker
          v-model="form.date"
          type="daterange"
          align="right"
          placeholder="选择日期范围"
          :picker-options="date_choice">
        </el-date-picker>
    </el-form-item>
  </el-col>
  <el-col :span="4">
    <el-form-item>
      <el-button type="primary" @click="load">统计</el-button>
    </el-form-item>
  </el-col>
</el-form>
<figure><chart :options="ticket_day_response" ref="ticket_day_response" theme="ovilia-green" auto-resize></chart></figure>
  </main>
</template>

<style lang="stylus">
@import "./assets/style/main.scss";
</style>

<script>
import URI from './uri'
import Vue from 'vue'
import ECharts from 'vue-echarts/components/ECharts.vue'
import 'echarts/lib/chart/bar'
import 'echarts/lib/chart/line'
import 'echarts/lib/component/tooltip'

import date_choice from './date_choice'
import ticket_day_response from './data/ticket.day.response'
//import chat_time from './data/chat_time'
import chat_time_line from './data/chat_time_line'
import chat_time_response from './data/chat_time_response'

// built-in theme
import 'echarts/theme/dark'

// custom theme
import theme from './theme.json'

// Map of China
//import chinaMap from './china.json'

// registering map data
//ECharts.registerMap('china', chinaMap)

// registering custom theme
ECharts.registerTheme('ovilia-green', theme)

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-default/index.css'
Vue.use(ElementUI)
import vk from './vk'
export default {
  components: {
    chart: ECharts
  },
  //store,
  data () {
    return {
      date_choice,

      ticket_day_response,
      
      seconds: -1,
      asyncCount: false,
      connected: false,
      metricIndex: 0,
      form: {
        group: [],
        group_selected:'',
        date: ["",""]
      }
    }
  },
  computed: {
    // scoreRadar () {
    //   return this.$store.getters.scoreRadar
    // },
    // metrics () {
    //   return this.$store.state.scores.map(({name}) => name)
    // },
    // isMax () {
    //   let {value, max} = this.$store.state.scores[this.metricIndex]
    //   return value === max
    // },
    // isMin () {
    //   return this.$store.state.scores[this.metricIndex].value === 0
    // }
  },
  methods: {
    load () {
      console.log(this.form.group_selected,this.form.date);
      vk.http(URI.GET_GROUPS,{
        stime:(this.form.date[0]?this.form.date[0].getTime():"").toString().substr(0,10),
        etime:(this.form.date[1]?this.form.date[1].getTime():"").toString().substr(0,10),
        group_id:this.form.group_selected
      },this.then);
      // simulating async data from server
      // this.seconds = 3
      // let bar = this.$refs.ticket_day_response
      // bar.showLoading({
      //   text: '正在加载',
      //   color: '#4ea397',
      //   maskColor: 'rgba(255, 255, 255, 0.4)'
      // })
      // let timer = setInterval(() => {
      //   this.seconds--
      //   if (this.seconds === 0) {
      //     clearTimeout(timer)
      //     bar.hideLoading()
      //     bar.mergeOptions(barAsync)
      //   }
      // }, 0)
    },
    convert () {
      let map = this.$refs.map
      let src = map.getDataURL({
        pixelRatio: window.devicePixelRatio || 1
      })
      window.open(`data:text/html,<img src="${src}" width="${map.width}" height="${map.height}">`)
    },
    increase (amount) {
      // if (!this.asyncCount) {
      //   this.$store.commit('increment', {amount, index: this.metricIndex})
      // } else {
      //   this.$store.dispatch('asyncIncrement', {amount, index: this.metricIndex, delay: 500})
      // }
    },
    then(data,code){
      console.log("demo...res",data);
      this.init__ticket_day_response(data);
      // var form=this.form;
      // form.group=data.groups;
      // this.$set('form', form);
      //Vue.set(this,'form.group',data.groups)
    },
    init__ticket_day_response:function(data){
      var ticket_day={xAxis:[],legend:[],series:[]};
      for(var day in data.ticket_day_response.day){
        ticket_day.xAxis.push(day);
      }
      for(var user_id in data.ticket_day_response.val){
        var val=data.ticket_day_response.val[user_id];
        var name=data.operators[user_id]?data.operators[user_id].Fullname:"N/A";
        ticket_day.legend.push(name);
        var one={
            name:name,
            type: 'line',
            data:[],
        };
        for(var day in data.ticket_day_response.day){
          one.data.push(val[day]>0?val[day]:0);
        }
        ticket_day.series.push(one);
      };
      ticket_day_response.init(ticket_day);
    }
  },
  watch: {
    connected: {
      handler (value) {
        ECharts[value ? 'connect' : 'disconnect']('radiance')
      }
    }
  },
  mounted(){
    this.load();
  }
}
</script>
