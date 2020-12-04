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
        //meta: {requiresAuth: true}
    },
    {
        path: '/menu',
        component: foodMenu,
    }
];

const router = new VueRouter({
    routes:routes
});

router.beforeEach((to,from,next) => {
    console.log("Entrou aqui Router")
    console.log(store)
    console.log(store.user)
    if(to.matched.some(record => record.meta.requiresAuth)){
        console.log('A')
        if(!store.user){
            console.log('B')
            next({
                path:'/login',
                query: {redirect: to.fullPath}
            })
        }else{
            console.log('C')
            next();
        }
    }else{
        console.log('D')
        next();
    }
    console.log('E')
});

export default router;
