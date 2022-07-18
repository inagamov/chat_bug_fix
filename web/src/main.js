import Vue from 'vue'
import App from './App.vue'
import '../src/registerServiceWorker.js';
import router from './router'
import store from './store'
import axios from 'axios'
require('@/store/subscriber')

// Set defaults for axios
axios.defaults.baseURL = "https://ehhoo.co/api"

// Hide tips
Vue.config.productionTip = false;

// Laravel-echo & Socket.io
localStorage.debug = '*';

import Echo from "laravel-echo"
window.io = require('socket.io-client')

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: 'https://ehhoo.co',
    authEdpoint: "https://ehhoo.co/api/broadcasting/auth",
    auth: {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
    }
});

// Reauthentication
store.dispatch('auth/attempt', localStorage.getItem('token')).then(() => {
    new Vue({
        router,
        store,
        render: h => h(App)
    }).$mount('#app')
});