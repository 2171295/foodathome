require('./bootstrap');
import Vue from "vue";
import VueRouter from 'vue-router';
import store from "./stores/global-store";
import Vuex from 'vuex'
Vue.use(Vuex)
Vue.use(VueRouter)
// Components:

import Register from './components/register.vue';
const register = Vue.component('register',Register);
import Index from './components/index.vue';
const index = Vue.component('index',Index);
import Login from './components/auth/login';
const login = Vue.component('login',Login);
import Home from './components/home';
const home = Vue.component('home',Home);
import FoodMenu from './components/foodmenu';
const foodMenu = Vue.component('foodMenu',FoodMenu);
import Profile from './components/profile/index';
const profile = Vue.component('profile', Profile);

const routes = [
    {
        path: '/',
        component: index
    },
    {
        path: '/register',
        component: register
    },
    {
        path: '/login',
        name: "login",
        component: login
    },
    {
        path: '/home',
        name: "home",
        component: home,
        meta: {requiresAuth: true}
    },
    {
        path: '/menu',
        component: foodMenu,
    },
    {
        path:  '/profile',
        component: profile,
    }
];

const router = new VueRouter({
    routes:routes
});

router.beforeEach((to,from,next) => {
    console.log("Entrou aqui Router")
    console.log(store.state.user)
    if(to.matched.some(record => record.meta.requiresAuth)){
        if(!store.state.user){
            next({
                path:'/login',
                query: {redirect: to.fullPath}
            })
        }else{
            next();
        }
    }else{
        next();
    }
});

export default router;
