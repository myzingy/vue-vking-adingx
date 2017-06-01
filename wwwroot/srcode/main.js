import Vue from 'vue'
import VueRouter from 'vue-router'

import routes from './config/routes'
import store from './store/'
import components from './components/' //加载公共组件
import rightComponents from './pages/rightComponents.js' //加载右侧页面


Object.keys(components).forEach((key) => {
    var name = key.replace(/(\w)/, (v) => v.toUpperCase()) //首字母大写
    //console.log(key,name);
    Vue.component(`v${name}`, components[key])
})
Vue.use(VueRouter)

const router = new VueRouter({
    routes
})
router.beforeEach(({meta, path}, from, next) => {
    var { auth = true } = meta
    var isLogin = Boolean(store.state.user.id) //true用户已登录， false用户未登录

    if (auth && !isLogin && path !== '/login') {
        return next({ path: '/login' })
    }
    Object.keys(rightComponents).forEach((key) => {
        var name = 'RightContent';
        console.log("::",key,name,path.indexOf(key));
        if(path.indexOf(key)>-1){
            Vue.component(`v${name}`, rightComponents[key]);
            return;
        }
    })
    next()
})

new Vue({ store, router }).$mount('#app')