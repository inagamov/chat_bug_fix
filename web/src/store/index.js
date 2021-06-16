import Vue from 'vue'
import Vuex from 'vuex'

// Modules
import auth from './auth'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        // Unread messages
        unread_chats_amount: 0,
        unread_support_messages_amount: 0,
    },

    getters: {
        getUnreadChatsAmount (state) {
            return state.unread_chats_amount;
        },

        getUnreadSupportMessagesAmount (state) {
            return state.unread_support_messages_amount;
        },
    },

    mutations: {
        setUnreadChatsAmount (state, amount) {
            state.unread_chats_amount = amount;
        },

        setUnreadSupportMessagesAmount (state, amount) {
            state.unread_support_messages_amount = amount;
        },
    },

    actions: {},

    modules: {
        auth
    }
})
