import Vue from "vue"
import Vuex from "vuex"

Vue.use(Vuex);

import axios from "axios"

export default new Vuex.Store({
    state: {
        token: "",
        user: null,
        menu_items:[],
        menu_search:'',
        cart:[],
        loggedUsers:[],
        availableManagers:[],
        availableCookers:[],
        availableDeliveryman:[],
        totalValueCart:'',
    },
    mutations: {
        clearUserAndToken (state) {
            if (state.user) {
                this._vm.$socket.emit('user_logged_out', state.user)
            }
            sessionStorage.removeItem("user");
            sessionStorage.removeItem("token");
            axios.defaults.headers.common.Authorization = undefined;
            state.user = null;
            state.token = "";
        },
        clearUser (state) {
            if (state.user) {
                this._vm.$socket.emit('user_logged_out', state.user)
            }
            state.user = null;
            sessionStorage.removeItem("user");
        },
        clearToken (state) {
            state.token = "";
            sessionStorage.removeItem("token");
            axios.defaults.headers.common.Authorization = undefined;
        },
        setUser (state, user) {
            if (state.user !== user) {
                if (state.user) {
                    this._vm.$socket.emit('user_logged_out', state.user)
                }
                state.user = user
                sessionStorage.setItem("user", JSON.stringify(user));
                if (state.user) {
                    this._vm.$socket.emit('user_logged', state.user)
                }
            }
        },
        setToken (state, token) {
            state.token = token;
            sessionStorage.setItem("token", token);
            axios.defaults.headers.common.Authorization = "Bearer " + token;
        },
        loadTokenAndUserFromSession (state) {
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
        setMenuItems (state, list) {
                state.menu_items = list;
        },
        setMenuSearch (state, type) {
            state.menu_search = type;
        },
        // Mutations to handle Cart
        clearCart (state) {
            state.cart = [];
            state.totalValueCart = null;
            localStorage.removeItem('cart')
            localStorage.removeItem('totalValue')
        },
        setCart (state, cart) {
            state.cart = cart
            localStorage.setItem('cart', JSON.stringify(state.cart))
        },
        setTotalValue (state, value) {
            state.totalValueCart = value
            localStorage.setItem('totalValue', JSON.stringify(state.totalValueCart))
        },
        addItemToCart (state, itemCart) {
            for(var i in state.cart){
                if(state.cart[i].id === itemCart.id){
                    state.cart[i].quantity ++;
                    state.cart[i].total_price =(state.cart[i].quantity * itemCart.unit_price).toFixed(2);
                    localStorage.setItem('cart', JSON.stringify(state.cart))

                    return;
                }
            }
            state.totalValueCart = (Number(state.totalValueCart) + Number(itemCart.unit_price)).toFixed(2);
            state.cart.push(itemCart)
            localStorage.setItem('cart', JSON.stringify(state.cart))
            localStorage.setItem('totalValue', JSON.stringify(state.totalValueCart))
        },
        removeItemFromCart (state, itemCart) {
            for(var i in state.cart){
                if(state.cart[i].id === itemCart.id){
                    state.totalValueCart = (Number(state.totalValueCart) - Number(state.cart[i].total_price)).toFixed(2);

                    state.cart.splice(state.cart.indexOf(i), 1);

                    localStorage.setItem('cart', JSON.stringify(state.cart))
                    localStorage.setItem('totalValue', JSON.stringify(state.totalValueCart))

                    return;
                }
            }
        },
        addQuantityItemToCart(state, itemCart){
            for(var i in state.cart) {
                if (state.cart[i].id === itemCart.id) {
                    state.cart[i].quantity++;
                    state.cart[i].total_price = (state.cart[i].quantity*itemCart.unit_price).toFixed(2);
                    state.totalValueCart = (Number(state.totalValueCart) + Number(itemCart.unit_price)).toFixed(2);
                    localStorage.setItem('cart', JSON.stringify(state.cart))
                    localStorage.setItem('totalValue', JSON.stringify(state.totalValueCart))
                    return;
                }
            }
        },
        removeQuantityItemFromCart(state, itemCart){
            for(var i in state.cart) {
                if (state.cart[i].id === itemCart.id) {
                    if (state.cart[i].quantity === 1) {
                        state.cart.splice(state.cart.indexOf(i), 1);
                    } else {
                        state.cart[i].quantity--;
                        state.cart[i].total_price = (state.cart[i].quantity * itemCart.unit_price).toFixed(2);
                    }
                    state.totalValueCart = (Number(state.totalValueCart) - Number(itemCart.unit_price)).toFixed(2);

                    localStorage.setItem('totalValue', JSON.stringify(state.totalValueCart))

                    localStorage.setItem('cart', JSON.stringify(state.cart))
                    return;
                }
            }
        },
        setLoggedUsers(state, users){
            state.loggedUsers = users;
        },
        setAvailableManagers(state, users){
            state.availableManagers = users;
        },
        setAvailableCookers(state, users){
            state.availableCookers = users;
        },
        setAvailableDeliveryman(state, users){
            state.availableDeliveryman = users;
        },
    },
    actions: {
        rebuildUserFromStorage (context) {
            if (sessionStorage.getItem('token') !== null && sessionStorage.getItem('user') !== null){
                context.commit("loadTokenAndUserFromSession")
            }
        },
        setUser (context,user) {
            context.commit("setUser",user)
        },
        rebuildTotalValueFromStorage (context){
            if (localStorage.getItem('totalValue') === null) {
                context.commit('clearCart')
            } else {
                context.commit('setTotalValue', JSON.parse(localStorage.getItem('totalValue')))
            }

        },
        rebuildCartFromStorage (context) {
            if (localStorage.getItem('cart') === null) {
                context.commit('clearCart')
            } else {
                context.commit('setCart', JSON.parse(localStorage.getItem('cart')))
            }
        },
        loadLoggedUser (context){
            axios.get("api/users/logged_users")
                .then((response)=>{
                    context.commit("setLoggedUsers",response.data)
                })
                .catch((error) =>{
                    console.log("Error getting logged users: "+error)
                })
        },
        loadAvailableCookers (context){
            axios.get("api/users/available_cookers")
                .then((response)=>{
                    context.commit("setAvailableCookers",response.data)
                })
                .catch((error) =>{
                    console.log("Error getting available cookers: "+error)
                })
        },
        loadAvailableDeliveryman (context){
            axios.get("api/users/available_deliveryman")
                .then((response)=>{
                    context.commit("setAvailableDeliveryman",response.data)
                })
                .catch((error) =>{
                    console.log("Error getting available deliveryman: "+error)
                })
        }
    }
});
