import axios from 'axios'
import router from '../router'

export default {
    namespaced: true,
    state: {
        token: null,
        user: null,
    },

    getters: {
        authenticated (state) {
            return state.token && state.user;
        },

        user (state) {
            return state.user;
        },
    },

    mutations: {
        SET_TOKEN (state, token) {
            state.token = token;
        },

        SET_USER (state, data) {
            state.user = data;
        },
    },

    actions: {
        async login ({ dispatch }, credentials) {
            let response = await axios.post('/auth/login', credentials);
            return dispatch('attempt', response.data.token);
        },

        async attempt ({ commit, state }, token) {
            if (token) {
                commit('SET_TOKEN', token);
            }

            if (!state.token) {
                return;
            }

            try {
                // Fetch user main data
                let user = await axios.get('/get/user/data');

                commit('SET_USER', user.data);

            } catch (e) {
                commit('SET_TOKEN', null);
                commit('SET_USER', null);
            }
        },

        logout ({ commit }) {
            if (router.history.current.path != '/') {
            router.push('/').then(() => {
                    return axios.post('/auth/logout').then(() => {
                        commit('SET_TOKEN', null);
                        commit('SET_USER', null);
                    });
                });

            } else {
                return axios.post('/auth/logout').then(() => {
                    commit('SET_TOKEN', null);
                    commit('SET_USER', null);
                });
            }
        },
    }
}