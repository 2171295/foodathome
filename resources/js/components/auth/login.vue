<template>
    <div>
        <v-snackbar
            v-model="snackbar"
            :bottom="y === 'bottom'"
            :color="color"
            :left="x === 'left'"
            :multi-line="mode === 'multi-line'"
            :timeout="timeout"
            :top="y === 'top'"
            :vertical="mode === 'vertical'">
            {{ text }}
            <v-btn dark text @click="snackbar = false">
                <v-icon>mdi-close</v-icon>
            </v-btn>
        </v-snackbar>
        <v-layout align-center justify-center>
            <v-flex xs12 sm8 md4>
                <v-card class="elevation-12" style="margin-top: auto">
                    <v-toolbar color="orange accent-2" dark flat>
                        <v-toolbar-title>Login form</v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-card-text>
                        <v-form>
                            <v-text-field
                                :prepend-icon="iconHome"
                                label="Email"
                                type="email"
                                v-model.trim="user.email"
                                required
                            ></v-text-field>
                            <v-text-field
                                :prepend-icon="iconLock"
                                id="password"
                                label="Password"
                                type="password"
                                v-model="user.password"
                                required
                                v-on:keyup.enter="onSubmit"
                            ></v-text-field>
                            <p id="link" class="text-center link" >Forgot my password</p>
                            <v-btn color="deep-orange lighten-1" class="white--text" style="margin-left: 35%" @click="onSubmit" >Login</v-btn>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
import {mdiAt} from "@mdi/js";
import {mdiLock} from "@mdi/js";

export default {
    name: "login",
    data:function (){
        return {
            iconHome: mdiAt,
            iconLock: mdiLock,
            // ---- SNACKBAR INFO -----
            color: '',
            mode: '',
            snackbar: false,
            text: '',
            timeout: 4000,
            x: null,
            y: 'top',
            break: false,
            // ------------------------
            user: {
                email: '',
                password: '',
            },
        }
    },
    methods: {
        onSubmit() {
            this.break = false;
            this.$store.commit('clearUserAndToken')
            axios.post("api/login", this.user)
                .then((response) => {
                    if (response.data.errorCode) {
                        this.color = "black";
                        this.text = response.data.msg;
                        this.snackbar = true;
                        this.break = true;
                    } else {
                        this.$store.commit("setToken", response.data.access_token);
                        return axios.get("api/users/me");
                    }
                })
                .then((response) => {
                    if (this.break === false) {
                        axios.put("api/users/" + response.data.data.id + "/logged")
                            .then((response) => {
                                this.$store.commit('setLoggedAt', response.data.data.logged_at)
                                this.$store.dispatch('setUser', response.data.data)
                                    .then(() => {
                                        this.$router.push('/home');
                                    })
                                    .catch(error => {
                                        this.color = "black";
                                        this.text = "Set user in store didn't work."
                                        this.snackbar = true;
                                    })
                            })
                    }
                })
                .catch(() => {
                    this.$store.commit("clearUserAndToken");
                    this.color = "black";
                    this.text = "Your username and/or password do not match."
                    this.snackbar = true;
                });
        },
    },
}
</script>

<style scoped>

</style>
