<style lang="stylus" rel="stylesheet/scss">

</style>
<template>

	<div>
		<v-header title="首页">
			<router-link slot="left" to="/">首页</router-link>
			<router-link slot="right" to="/signout">退出</router-link>
		</v-header>
		<div>
			<el-row :style="{ height:height +'px' }">
				<el-col :span="4" style="height:100%;">
					<div style="height:100%;" class="grid-left bg-purple-darkc" id="app_left_menu">
						<v-leftMenu></v-leftMenu>
					</div>
				</el-col>
				<el-col :span="20" style="height:100%;">
					<div style="height:100%;" class="grid-content bg-purple-dark" id="app_right_content"></div>

				</el-col>
			</el-row>
		</div>
	</div>
</template>
<script>
    import Vue from 'vue'
    import { mapState } from 'vuex'
    import ElementUI from 'element-ui'
    import 'element-ui/lib/theme-default/index.css'
    Vue.use(ElementUI)
    export default {
        data:function(){
            return {
                height:500,
                h_height:36,
			}
		},
        computed: mapState({ user: state => state.user }),
        mounted(){
            this.height=document.body.scrollHeight-(this.h_height);
//            new Vue({
//                el: '#app_left_menu',
//                components: {
//                    app_left_menu:app_left_menu
//                },
//                render: h => h(app_left_menu)
//            })
        }
    }
</script>