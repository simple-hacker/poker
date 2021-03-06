import moment from 'moment'

export default {
    namespaced: true,
    state: {
        loadSessionState: {},
    },
    getters: {
        sessions: (state, getters, rootState) => {
            return [
                ...rootState.cash_games.cash_games,
                ...rootState.tournaments.tournaments
            ].sort(rootState.filters.sortByDate)
        },
        getSession: (state, getters, rootState, rootGetters) => (id, game_type) => {
            if (game_type === 'cash_game') {
                return rootGetters.cash_games.CashGameById(id)
            } else if (game_type === 'tournament') {
                return rootGetters.tournament.TournamentById(id)
            } else {
                throw new Error('Unknown Game Type')
            }
        },
        numberOfSessions: (state, getters) => {
            return getters.sessions.length
        },
        totalProfit: (state, getters) => {
            return getters.sessions.reduce((total, session) => total + session.locale_profit, 0)
        },
        totalDuration: (state, getters) => {
            return getters.sessions.reduce((total, session) => {
            	const end_time = moment.utc(session.end_time)
            	const start_time = moment.utc(session.start_time)
                return total + end_time.diff(start_time, 'hours', true)
            }, 0)
        },
        averageDuration: (state, getters) => {
            return getters.totalDuration / getters.numberOfSessions
        },
        totalBuyIns: (state, getters) => {
            return getters.sessions.reduce((total, session) => {
                let buyInTotal = session?.buy_in?.locale_amount ?? 0
				let buyInsTotal = (session.buy_ins) ? session.buy_ins.reduce((total, buy_in) => total + buy_in.locale_amount, 0) : 0
				let addOnTotal = (session.add_ons) ? session.add_ons.reduce((total, add_ons) => total + add_ons.locale_amount, 0) : 0
				let rebuyTotal = (session.rebuys) ? session.rebuys.reduce((total, rebuys) => total + rebuys.locale_amount, 0) : 0
				let expenseTotal = (session.expenses) ? session.expenses.reduce((total, expenses) => total + expenses.locale_amount, 0) : 0
				return total + buyInTotal + buyInsTotal + addOnTotal + rebuyTotal + expenseTotal
            }, 0)
        },
        totalCashes: (state, getters) => {
            return getters.sessions.reduce((total, session) => total + (session.cash_out?.locale_amount ?? 0), 0)
        },
        roi: (state, getters) => {
            return (getters.totalProfit / getters.totalBuyIns) * 100
        },
        profitPerHour: (state, getters) => {
            return getters.totalProfit / getters.totalDuration
        },
        profitPerSession: (state, getters) => {
            return getters.totalProfit / getters.numberOfSessions
        },
        sessionsProfitSeries: (state, getters) => {
            // Have to spread operator here because it's actually altering the order of the state when displaying individual sessions sorted by date desc
            // Need to reverse because series needs to be added up over time
            return [...getters.sessions].reverse().reduce((series, session, index) => {
                    // Get the previous profit of the series so we can add on to the runing total.  Default to 0 on invalid index.
                    // y property of the series is profit
                    let runningTotal = series[index -1]?.y ?? 0
                    // Push new object in to series array, where x is the date of the session
                    // and y is the runningTotal adding on the current session's profit.
                    series.push({
                        x: moment.utc(session.start_time).format(),
                        y: runningTotal + session.locale_profit
                    })
                    return series
                }, [])
        },
    },
    mutations: {
        LOAD_SESSION(state, session) {
            state.loadSessionState = session
        },
    },
    actions: {
        saveLoadSession({ commit }, session) {
            commit('LOAD_SESSION', session)
        },
        updateSession({ dispatch, commit }, session) {
            return new Promise((resolve, reject) => {
                if (session.game_type === 'cash_game') {
                    dispatch('cash_games/updateCashGame', session, { root: true }).then(response => resolve(response)).catch(error => reject(error))
                } else if (session.game_type === 'tournament') {
                    dispatch('tournaments/updateTournament', session, { root: true}).then(response => resolve(response)).catch(error => reject(error))
                } else {
                    reject({response: {data: { message: 'Unknown Game Type'}}})
                }
            })
        },
        destroySession({ dispatch, commit }, session) {
            return new Promise((resolve, reject) => {
                if (session.game_type === 'cash_game') {
                    dispatch('cash_games/destroyCashGame', session, { root: true })
                    .then(response => {
                        // If session was successfully destroy then clear load session details in case
                        // user directly visits /session after deleting, it won't load the old deleted session.
                        commit('LOAD_SESSION', {})
                        resolve(response)
                    })
                    .catch(error => reject(error))
                } else if (session.game_type === 'tournament') {
                    dispatch('tournaments/destroyTournament', session, { root: true})
                    .then(response => {
                        // If session was successfully destroy then clear load session details in case
                        // user directly visits /session after deleting, it won't load the old deleted session.
                        commit('LOAD_SESSION', {})
                        resolve(response)
                    })
                    .catch(error => reject(error))
                } else {
                    reject({response: {data: { message: 'Unknown Game Type'}}})
                }
            })
        }
    }
}