import moment from 'moment'

export default {
    namespaced: true,
    state: {
        liveSession: {}
    },
    getters: {
        sessionInProgress: state => {
            return (Object.keys(state.liveSession).length > 0)
        },
        liveSessionId: state => {
            return state.liveSession.id
        }
    },
    mutations: {
        ASSIGN_LIVE_SESSION(state, session) {
            state.liveSession = session
        },
        END_LIVE_SESSION(state, session) {
            state.liveSession = {}
        },
        UPDATE_LIVE_SESSION(state, session) {
            state.liveSession = session
        },
    },
    actions: {
        startLiveSession({ commit }, session) {
            return axios.post('/api/cash/live/start', session)
            .then(response => {
                commit('ASSIGN_LIVE_SESSION', response.data.cash_game)
            })
            .catch(error => {
                throw error
            })
        },
        currentLiveSession({ commit }) {
            return axios.get('/api/cash/live/current')
            .then(response => {
                if (response.data.success === true) {
                    commit('ASSIGN_LIVE_SESSION', response.data.cash_game)
                } else {
                    commit('ASSIGN_LIVE_SESSION', {})
                }
            })
            .catch(error => {
                throw error
            })
        },
        updateLiveSession({ commit }, session) {
            return axios.patch('/api/cash/live/update', {
                ...session,
                start_time: moment(session.start_time).format("YYYY-MM-DD HH:mm:ss"),
            })
            .then(response => {
                commit('UPDATE_LIVE_SESSION', response.data.cash_game)
            })
            .catch(error => {
                throw error
            })
        },
        endLiveSession({ commit }, cashOut) {
            return axios.post('/api/cash/live/end', cashOut)
            .then(response => {
                commit('END_LIVE_SESSION', response.data.cash_game)
                commit('cash_games/ADD_CASH_GAME', response.data.cash_game, { root: true})
            })
            .catch(error => {
                throw error
            })
        },

    }
}