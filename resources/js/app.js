require('./bootstrap');

window.Vue = require('vue')

import Vue from 'vue'
import vuetify from './vuetify'
import Vuex from 'vuex'

Vue.use(Vuex)
import Vuelidate from 'vuelidate'

Vue.use(Vuelidate)

import VueSocketIO from "vue-socket.io"

Vue.use(
    new VueSocketIO({
        debug: true,
        connection: "http://127.0.0.1:8080"
    })
)

import store from "./stores/global-store";
import router from "./router";

import App from './components/App.vue'

new Vue({
    vuetify,
    router,
    store,
    render: h => h(App),
    methods: {
        getItemsMenu() {
            axios.get("api/products").then((response) => {
                this.$store.commit("setMenuItems", response.data.data)
            })
        },
    },
    created() {
        this.$store.dispatch('rebuildUserFromStorage')
        this.$store.dispatch('rebuildCartFromStorage')
        this.getItemsMenu()
    }
}).$mount('#app');
