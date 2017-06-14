<style lang="stylus" rel="stylesheet/scss">
	 .overflow-y{ overflow-y: auto;}
	 .header .el-select:hover .el-input__inner{
		 background: #222;
	 }
	 .header .el-input__icon+.el-input__inner{
		 background: #222;
	 }
	.header .el-input__inner{
		border: 0;
	}
</style>
<template>

	<div>
		<v-header title="首页">
			<router-link slot="left" to="/home">
				<el-select v-model="value6" placeholder="请选择">
					<el-option
							v-for="item in cities"
							:key="item.value"
							:label="item.label"
							:value="item.value">
						<span style="float: left">{{ item.label }}</span>
						<span style="float: right; color: #8492a6; font-size: 13px">{{ item.value }}</span>
					</el-option>
				</el-select>
			</router-link>
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