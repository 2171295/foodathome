require('./bootstrap');
import Vue from 'vue'
import VueRouter from 'vue-router'
import vuetify from './vuetify'
import Vuex from 'vuex'
Vue.use(Vuex)
import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)

import store from "./stores/global-store";
import router from "./router";

// Vue.use(VueRouter);
// Vue.config.productionTip = false;
// // Components:
//
// import Register from './components/register.vue';
// const register = Vue.component('register',Register);
// import Index from './components/index.vue';
// const index = Vue.component('index',Index);
// import Login from './components/auth/login';
// const login = Vue.component('login',Login);
// import Home from './components/home';
// const home = Vue.component('home',Home);
// import FoodMenu from './components/foodmenu';
// const foodMenu = Vue.component('foodMenu',FoodMenu);
//
//
// const routes = [
//     { path: '/', component: index},
//     {path: '/register', component: register},
//     {path: '/login', component: login},
//     {path: '/home', component: home},
//     {path: '/menu', component: foodMenu},
// ];
//
// const router = new VueRouter({
//     routes:routes
// });


import App from './components/App.vue'
new Vue({
    vuetify,
    router,
    store,
    render: h => h(App),
    methods:{
        getItemsMenu(){
            axios.get("api/products").then((response) => {
                this.$store.commit("setMenuItems", response.data.data)
            })
        }
    },
    created() {
        this.$store.dispatch('rebuildUserFromStorage')
        this.$store.dispatch('rebuildCartFromStorage')
        this.getItemsMenu()
    }
}).$mount('#app');
