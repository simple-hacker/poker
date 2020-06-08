// vuex
import Vue from 'vue'
import Vuex from 'vuex'

import createPersistedState from "vuex-persistedstate"

import bankroll from '@modules/bankroll'
import cash_games from '@modules/cash_games'
import tournaments from '@modules/tournaments'
import sessions from '@modules/sessions'
import transactions from '@modules/transactions'
import live from '@modules/live'
import filters from '@modules/filters'
import Axios from 'axios'

Vue.use(Vuex)

const store = new Vuex.Store({
    strict: true,
    modules: {
        bankroll,
        cash_games,
        tournaments,
        sessions,
        transactions,
        live,
        filters,
    },
    plugins: [createPersistedState({
        paths: [
            // 'sessions.sessions',
            'cash_games.cash_games',
            'tournaments.tournaments',
            'sessions.loadSessionState',
        ],
    })],
    state: {
        user: {
            email: "example@email.com",
            default_values: {
                default_stake_id: 3,
                default_limit_id: 2,
                default_variant_id: 3,
                default_table_size_id: 2,
                default_location: 'Las Vegas',
            }
        },
        stakes: [
            { id: 1, stake: "1/1", small_blind: 1, big_blind: 1 },
            { id: 2, stake: "1/2", small_blind: 1, big_blind: 2 },
            { id: 3, stake: "1/3", small_blind: 1, big_blind: 3 },
            { id: 4, stake: "2/4", small_blind: 2, big_blind: 4 },
        ],
        limits: [
            { id: 1, limit: "No Limit"},
            { id: 2, limit: "Pot Limit"},
            { id: 3, limit: "Limit"},
            { id: 4, limit: "Mixed Limit"},
            { id: 5, limit: "Spread Limit"},
        ],
        variants: [
            { id: 1, variant: "Texas Holdem" },
            { id: 2, variant: "Omaha Hi" },
            { id: 3, variant: "Omaha Hi-Lo" },
            { id: 4, variant: "Short Deck" },
            { id: 5, variant: "6+" },
            { id: 6, variant: "Razz" },
            { id: 7, variant: "HORSE" },
            { id: 8, variant: "7-Card Stud" },
            { id: 9, variant: "2-7 Triple Draw" },
            { id: 10, variant: "5-Card Draw" },
            { id: 11, variant: "5-Card Omaha" },
            { id: 12, variant: "Badugi" },
            { id: 13, variant: "Stud 8" },
            { id: 14, variant: "Dealer\'s Choice" },
        ],
        table_sizes: [
            { id: 1, table_size: "Full Ring" },
            { id: 2, table_size: "6 Max" },
            { id: 3, table_size: "8 Max" },
            { id: 4, table_size: "Heads Up" }
        ]
    },
    mutations: {
        SET_EMAIL(state, email) {
            state.user.email = email
        },
        SET_DEFAULT_VALUES(state, default_values) {
            state.user.default_values = default_values
        }
    },
    actions: {
        updateEmailAddress({ commit }, email) {
            return axios.post('/settings/email', { email: email })
            .then(response => {
                if (response.data.success === true) {
                    commit('SET_EMAIL', email)
                } else {
                    reject({response: {data: { message: 'Something went wrong.'}}})
                }
            })
            .catch(error => { throw error })
        },
        updateDefaultValues({ commit }, default_values) {
            return axios.post('/settings/defaults', default_values)
            .then(response => {
                if (response.data.success === true) {
                    commit('SET_DEFAULT_VALUES', default_values)
                } else {
                    reject({response: {data: { message: 'Something went wrong.'}}})
                }
            })
            .catch(error => { throw error })
        }
    }
})

export default store