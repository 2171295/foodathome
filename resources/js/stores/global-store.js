import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

import axios from "axios"

export default new Vuex.Store({
    state: {
        token: "",
        user: null,
        menu_items:[],
        menu_search:'',
    },
    mutations: {
        clearUserAndToken: state => {

            sessionStorage.removeItem("user");
            sessionStorage.removeItem("token");
            axios.defaults.headers.common.Authorization = undefined;
            state.user = null;
            state.token = "";
        },
        clearUser: state => {
            state.user = null;
            sessionStorage.removeItem("user");
        },
        clearToken: state => {
            state.token = "";
            sessionStorage.removeItem("token");
            axios.defaults.headers.common.Authorization = undefined;
        },
        setUser: (state, user) => {
            state.user = user;
            sessionStorage.setItem("user", JSON.stringify(user));
        },
        setToken: (state, token) => {
            state.token = token;
            sessionStorage.setItem("token", token);
            axios.defaults.headers.common.Authorization = "Bearer " + token;
        },
        loadTokenAndUserFromSession: state => {
            state.token = "";
            state.user = null;
            let token = sessionStorage.getItem("token");
            let user = sessionStorage.getItem("user");
            if (token) {
                state.token = token;
                axios.defaults.headers.common.Authorization = "Bearer " + token;
            } else {
                axios.defaults.headers.common.Authorization = undefined;
            }
            if (user) {
                state.user = JSON.parse(user);
            }
        },
        setMenuItems: (state, list) => {
                state.menu_items = list;
        },
        setMenuSearch: (state, type) => {
            state.menu_search = type;
        }
    },
    actions: {
        rebuildUserFromStorage (context) {
            if (sessionStorage.getItem('token') !== null && sessionStorage.getItem('user') !== null){
                context.commit("loadTokenAndUserFromSession")
            }
        }
    }
});
