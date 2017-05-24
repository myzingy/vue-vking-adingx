import Vue from 'vue'
import layout from './layout.vue'


 new Vue({
     el: '#app',
     components: {
     layout:layout
     },
     render: h => h(layout)
 })
