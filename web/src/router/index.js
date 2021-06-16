// Vue defaults
import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '@/store'

// Auth pages
const LogIn = () => import('../views/LogIn.vue');

// Messenger related pages
const Messenger = () => import('../views/Messenger.vue');

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        name: 'LogIn',
        component: LogIn,
        beforeEnter: (to, from, next) => {
            if (store.getters['auth/authenticated']) {
                return next({
                    name: 'Messenger'
                });
            }

            document.title = 'Log In';
            next();
        }
    },

    {
        path: '/messenger',
        name: 'Messenger',
        component: Messenger,
        props: true,
        beforeEnter: (to, from, next) => {
            if (!store.getters['auth/authenticated']) {
                return next({
                    name: 'LogIn'
                });
            }

            document.title = 'Messenger';
            next();
        }
    },
];

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
});

export default router