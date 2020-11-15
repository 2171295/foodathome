<template>
    <div>
        <v-app>
            <v-app-bar :clipped-left="true" fixed app color="deep-orange lighten-1" >
                <v-btn icon to="/">
                    <v-icon>{{ iconHome }}</v-icon>
                </v-btn>
                <v-icon>mdi-account</v-icon>
                <v-menu offset-y>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn v-bind="attrs" v-on="on" depressed small color="deep-orange lighten-1" class="white--text" style="margin-right: 10px;">
                            Products
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-item to="/user/perfil/">
                            <v-icon>mdi-account</v-icon>Hot Dish
                        </v-list-item>
                        <v-list-item>
                            <v-icon>mdi-logout</v-icon>Cold Dish
                        </v-list-item>
                        <v-list-item>
                            <v-icon>mdi-logout</v-icon>Drink
                        </v-list-item>
                        <v-list-item>
                            <v-icon>mdi-logout</v-icon>Dessert
                            <Pudim></Pudim>
                        </v-list-item>
                    </v-list>
                </v-menu>

                <v-spacer />
                <v-toolbar-title class="white--text" v-text="title" />
                <v-spacer />
                <v-btn depressed small color="deep-orange lighten-1" @click="redirectRegister" class="white--text" style="margin-right: 10px;">
                    Register
                </v-btn>
                <v-btn depressed small color="deep-orange lighten-1" @click="redirectLogin" class="white--text" >
                    Login
                </v-btn>
            </v-app-bar>
            <v-main>
                <v-container>
                    <router-view></router-view>
                </v-container>
            </v-main>
        </v-app>
    </div>
</template>

<script>
import { mdiHome} from '@mdi/js'
export default {
    name: "App",
    data() {
        return {
            iconHome: mdiHome,
            title: "Food@Home"
        }
    },methods:{
        redirectRegister(){
            this.$router.push("/register")
        },
        redirectLogin(){
            this.$router.push("/login")
        }
    },

}
</script>

<style scoped>

</style>
