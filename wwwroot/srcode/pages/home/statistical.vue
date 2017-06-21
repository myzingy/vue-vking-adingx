<style lang="stylus" rel="stylesheet/scss">

</style>
<template>
	<div style="width:100%;">
		<figure><chart :options="ticket_day_response" ref="ticket_day_response" theme="ovilia-green" auto-resize></chart></figure>
	</div>
</template>
<script>
    import Vue from 'vue'
    import { mapState,mapActions } from 'vuex'
    import { CKECKED_AC } from '../../store/data.js'
    import vk from '../../vk.js';
    import uri from '../../uri.js';


    import ECharts from 'vue-echarts/components/ECharts.vue'
    import 'echarts/lib/chart/bar'
    import 'echarts/lib/chart/line'
    import 'echarts/lib/chart/radar'
    import 'echarts/lib/component/tooltip'
    import 'echarts/lib/component/legend'
    import 'echarts/lib/component/title'
    import 'echarts/theme/dark'
    import theme from './theme.json'
    ECharts.registerTheme('ovilia-green', theme);
    import ticket_day_response from './data/ticket.day.response'

    import ElementUI from 'element-ui'
    import 'element-ui/lib/theme-default/index.css'
    Vue.use(ElementUI)

    export default {
        components: {
            chart: ECharts
        },
        data:function(){
            return {
                ticket_day_response
			}
		},
        mounted(){

        },
        methods:{

        },
    }
</script>