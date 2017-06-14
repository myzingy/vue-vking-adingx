export const CKECKED_AC = 'CKECKED_AC' //checked ac

export default {
    state: JSON.parse(localStorage.getItem('data')) || {},
    mutations: {
        [CKECKED_AC](state, data) {
            localStorage.setItem('data', JSON.stringify(data))
            Object.assign(state, data)
        },
    },
    actions: {
        [CKECKED_AC]({commit}, data) {
            commit(CKECKED_AC, data)
        },
    }
}