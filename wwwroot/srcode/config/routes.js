import App from '../app.vue'
/**
 * auth true登录才能访问，false不需要登录，默认true
 */

export default [
    {
        path: '/',
        component: App,
        children: [
            {
                path: '/login', //登录
                meta: { auth: false },
                component: resolve => require(['../pages/login/index.vue'], resolve)
            },
            {
                path: '/signout', //退出
                component: resolve => require(['../pages/signout/index.vue'], resolve)
            },
            {
                path: '/home', //个人主页
                component: resolve => require(['../pages/home/index.vue'], resolve)
            },
            {
                path: '/', //首页
                meta: { auth: true },
                component: resolve => require(['../pages/home/index.vue'], resolve)
            },
            {
                path: 'adsList*', //广告列表
                component: resolve => require(['../pages/home/index.vue?adsList'], resolve)
            },
            {
                path: 'rulesList*', //规则列表
                component: resolve => require(['../pages/home/index.vue?rulesList'], resolve)
            },
            {
                path: 'rulesLog*', //规则列表
                component: resolve => require(['../pages/home/index.vue?rulesLog'], resolve)
            },
            {
                path: 'accounts*', //规则列表
                component: resolve => require(['../pages/home/index.vue?accounts'], resolve)
            },
            {
                path: 'users*', //规则列表
                component: resolve => require(['../pages/home/index.vue?users'], resolve)
            },
            {
                path: '*', //其他页面，强制跳转到登录页面
                redirect: '/login'
            },
        ]
    }
]