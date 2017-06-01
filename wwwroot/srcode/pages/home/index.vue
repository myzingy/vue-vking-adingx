<style lang="stylus" rel="stylesheet/scss">
	 .overflow-y{ overflow-y: auto;}
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
					<div :style="{ height:height_cc +'px' }" class="grid-left bg-purple-darkc overflow-y"
						 id="app_left_menu">
						<v-leftMenu></v-leftMenu>
					</div>
				</el-col>
				<el-col :span="20" style="height:100%;">
					<div :style="{ height:height_cc +'px' }" class="grid-content bg-purple-dark overflow-y"
						 id="app_right_content">
						<v-rightContent></v-rightContent>
					</div>
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
                height_cc:500,
                h_height:80,
			}
		},
        computed: mapState({ user: state => state.user }),
        mounted(){
            this.height=document.body.scrollHeight-(this.h_height);
            this.height_cc=this.height;
        }
    }
</script>