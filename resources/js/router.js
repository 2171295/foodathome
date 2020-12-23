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
import Mensagens from './components/globalMessages';
const mensagens = Vue.component('mensagens', Mensagens);
import IndexProducts from './components/products/index';
const index_produtos = Vue.component('index_produtos',IndexProducts);
import IndexUsers from './components/users/index';
const index_users = Vue.component('index_users',IndexUsers);

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
        path: '/home',
        name: "home",
        component: home,
        meta: {requiresAuth: true}
    },
    {
        path: '/login',
        name: "login",
        component: login
    },
    {
        path: '/menu',
        component: foodMenu,
    },
    {
        path:  '/profile',
        component: profile,
        meta: {requiresAuth: true}
    },
    {
        path:  '/mensagens',
        component: mensagens,
        meta: {requiresAuth: true}
    },
    {
        path:  '/products',
        component: index_produtos,
        meta: {requiresAuth: true}
    },
    {
        path:  '/users',
        component: index_users,
        meta: {requiresAuth: true}
    }
];

const router = new VueRouter({
    //mode: 'history',
    routes:routes
});

router.beforeEach((to,from,next) => {
    if(to.matched.some(record => record.meta.requiresAuth)){
        if(!sessionStorage.getItem("user")){
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
