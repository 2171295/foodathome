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
                            :headers="headers_users"
                            :items="logged_users"
                            :items-per-page="5"
                            class="elevation-1"
                        ></v-data-table>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col md="6">
                <v-toolbar class="d-flex justify-center align-center" style="margin-bottom: 20px;">
                    <v-toolbar-title>Active Orders</v-toolbar-title>
                </v-toolbar>
                <v-card>
                    <v-card-text>
                        <v-data-table
                            :headers="headers_orders"
                            :items="open_orders"
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
import axios from "axios";

export default {
    name: "aux_home_manager",
    sockets: {
        user_logged(){
            console.log("Entrou no user logged")
            this.refreshLoggedUsers();
        },
        user_logged_out(){
            this.refreshLoggedUsers();
        }
    },
    data: () => {
        return {
            logged_users: [],
            open_orders:[],
            headers_users: [
                {text: 'Name', align: 'start', sortable: true, value: 'name', },
                {text: 'Type', align: 'start', sortable: true, value: 'type',},
                {text: 'Logged Since', sortable: true, value: 'logged_at'},
            ],
            headers_orders: [
                {text: 'ID', align: 'start', sortable: true, value: 'id', },
                {text: 'Status', align: 'start', sortable: true, value: 'status',},
                {text: 'Cooker', sortable: true, value: 'cooker.name'},
                {text: 'Deliveryman', sortable: true, value: 'deliveryman.name'},
                {text: 'Time', sortable: true, value: 'current_status_at'},
                {text: 'Actions', sortable: false, value: 'actions'},
            ],
        }
    },
    methods: {
        refreshLoggedUsers(){
            this.loadLoggedUser();
            this.$store.dispatch("loadAvailableCookers");
            this.$store.dispatch("loadAvailableDeliveryman");
        },
        loadLoggedUser (){
            axios.get("api/users/logged_users")
                .then((response)=>{
                    this.logged_users = response.data;
                    this.toStringType()
                })
                .catch((error) =>{
                    console.log("Error getting logged users: "+error)
                })
        },
        getOpenOrder(){
            axios.get("api/orders/open")
                .then((response) => {
                    this.open_orders=response.data.data;
                    this.toStringStatus()
                })
                .catch((error) =>{
                    console.log("Error getting open orders: "+error)
                })
        },
        toStringStatus(){
            this.open_orders.forEach(value =>
            {
                switch (value.status){
                    case 'D':
                        value.status = "Delivered";
                        break;
                    case 'H':
                        value.status = "Holding";
                        break;
                    case 'C':
                        value.status = "Canceled";
                        break;
                    case 'T':
                        value.status = "Transit";
                        break;
                    case 'P':
                        value.status = "Preparing";
                        break;
                    case 'R':
                        value.status = "Ready";
                        break;
                }
            })
        },
        toStringType(){
            this.logged_users.forEach(value =>
            {
                switch (value.type){
                    case 'EC':
                        value.type = "Cooker";
                        break;
                    case 'EM':
                        value.type = "Manager";
                        break;
                    case 'ED':
                        value.type = "Deliveryman";
                        break;

                }
            })
        }
    },
    created() {
        this.loadLoggedUser();
        this.getOpenOrder();
        this.$store.dispatch("loadAvailableCookers");
        this.$store.dispatch("loadAvailableDeliveryman");
    },
}
</script>

<style scoped>

</style>
