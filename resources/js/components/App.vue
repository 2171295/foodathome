<template>
    <div>
        <v-app>
            <v-app-bar :clipped-left="true" fixed app color="deep-orange lighten-1" v-if="!this.$store.state.user">
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
            <template v-else>
            <v-navigation-drawer v-model="drawer" :mini-variant="miniVariant" :clipped="clipped" fixed app color="grey lighten-1">
                <v-list>
                    <v-list-item
                        v-for="(item, i) in items"
                        :key="i"
                        :to="item.to"
                        router
                        exact
                        :disabled="item.active"
                    >
                        <v-list-item-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title v-text="item.title" />
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-navigation-drawer>
            <v-app-bar :clipped-left="clipped" fixed app color="deep-orange lighten-1">
                <v-btn @click.stop="drawer = !drawer" >
                    <v-icon>{{iconMenu}}</v-icon>
                </v-btn>
                <v-btn icon @click.stop="miniVariant = !miniVariant">
                    <template v-if="miniVariant">
                        <v-icon>{{iconChevronRight}}</v-icon>
                    </template>
                    <template v-if="!miniVariant">
                        <v-icon>{{iconChevronLeft}}</v-icon>
                    </template>
                </v-btn>
                <v-btn icon to="/">
                    <v-icon>{{ iconHome }}</v-icon>
                </v-btn>

                <v-spacer />
                <v-toolbar-title class="white--text" v-text="title" />
                <v-spacer />
                <v-menu offset-y>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn v-bind="attrs" v-on="on">
                            <v-icon>{{ iconAccount }}</v-icon>
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-item to="">
                            <v-icon>{{iconAccount}}</v-icon>Profile
                        </v-list-item>
                        <v-list-item @click="logout">
                            <v-icon>{{iconLogout}}</v-icon>Logout
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-app-bar>
            </template>
            <v-main>
                <v-container>user:
                    {{this.$store.state.user}}
                    <router-view></router-view>
                </v-container>
            </v-main>
        </v-app>

    </div>
</template>

<script>
import { mdiHome, mdiAccount, mdiLogout, mdiChevronRight, mdiChevronLeft, mdiMenu} from '@mdi/js'
export default {
    name: "App",
    data() {
        return {
            iconHome: mdiHome,
            iconAccount: mdiAccount,
            iconLogout: mdiLogout,
            iconChevronRight: mdiChevronRight,
            iconChevronLeft: mdiChevronLeft,
            iconMenu: mdiMenu,
            title: "Food@Home",
            clipped: true,
            drawer: false,
            fixed: true,
            email:'',
            miniVariant: false,
            right: true,
            rightDrawer: false,
            selected:'',
            items: [
            {
                icon: this.iconHome,
                title: 'Consultar Projetos',
                to: '/projetos/',
            },
            ],
        }
    },methods:{
        redirectRegister(){
            this.$router.push("/register")
        },
        redirectLogin(){
            this.$router.push("/login")
        },
        logout(){

        },
    },

}
</script>

<style scoped>

</style>
