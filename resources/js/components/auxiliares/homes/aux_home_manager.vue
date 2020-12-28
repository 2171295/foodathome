<template>
    <div>
        <v-row>
            <v-col md="6">
                <v-toolbar class="d-flex justify-center align-center" style="margin-bottom: 20px;">
                    <v-toolbar-title>Logged Employees</v-toolbar-title>
                </v-toolbar>
                <v-card>
                    <v-card-text>
                        <v-data-table
                            :headers="headers"
                            :items="this.$store.state.loggedUsers"
                            :items-per-page="5"
                            class="elevation-1"
                        ></v-data-table>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script>
export default {
    name: "aux_home_manager",
    sockets: {
        user_logged(){
            this.refreshLoggedUsers();
        },
        user_logged_out(){
            this.refreshLoggedUsers();
        }
    },
    data: () => {
        return {
            logged_users: [],
            headers: [
                {text: 'Name', align: 'start', sortable: true, value: 'name', },
                {text: 'Type', align: 'start', sortable: true, value: 'type',},
                {text: 'Logged Since', sortable: true, value: 'logged_at'},
            ],
        }
    },
    methods: {
        refreshLoggedUsers(){
            this.$store.dispatch("loadLoggedUser");
            this.$store.dispatch("loadAvailableCookers");
            this.$store.dispatch("loadAvailableDeliveryman");
        }
    },
    created() {
        this.$store.dispatch("loadLoggedUser");
        this.$store.dispatch("loadAvailableCookers");
        this.$store.dispatch("loadAvailableDeliveryman");
    },
}
</script>

<style scoped>

</style>
