import Vue from 'vue'

export const EDIT_RULE = 'EDIT_RULE' //修改规则

export default {
    state: {},
    mutations: {
        [EDIT_RULE](state, data) {
            Object.assign(state, data)
        },
    },
    actions: {
        [EDIT_RULE]({commit}, data) {
            commit(EDIT_RULE, data)
        }
    }
}