<template>
    <div>
        <aux_dialog_confirmacao ref="confirm"/>
        <aux_snackbar :text="text" :snackbar="snackbar" :color="color"/>
        <v-row>
            <v-col md="6">
                <p><b>Working Time: {{working_time}} minutes</b></p>
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
                        >
                            <template v-slot:item.actions="{ item }">
                                <v-tooltip bottom>
                                        <template v-slot:activator="{ on }" >
                                            <v-btn v-on="on" icon @click="cancelOrder(item)">
                                                <v-icon>mdi-cancel</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Cancel Order</span>
                                    </v-tooltip>
                            </template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script>

import Aux_dialog_confirmacao from "../aux_dialog_confirmacao";
import Aux_snackbar from "../aux_snackbar";
export default {
    name: "aux_home_manager",
    components: {Aux_snackbar, Aux_dialog_confirmacao},
    sockets: {
        user_logged(){
            console.log("Entrou no user logged")
            this.refreshLoggedUsers();
        },
        user_logged_out(){
            this.refreshLoggedUsers();
        },
        order_assign_cook(){
            this.getOpenOrder();
        },
        order_cooked(order){
            this.getOrders()
        },
        order_taken_delivery(user) {
            this.getOpenOrder();
        },
        order_delivered(order){
            this.getOpenOrder();
        },
        order_canceled(order){
            this.getOpenOrder();
        },
        order_created(){
            this.getOpenOrder();
        }
    },
    data: () => {
        return {
            // ---- SNACKBAR INFO -----
            color: '',
            snackbar: false,
            text: '',
            // ------------------------

            logged_users: [],
            open_orders:[],
            headers_users: [
                {text: 'Name', align: 'start', sortable: true, value: 'name', },
                {text: 'Status', align: 'start', sortable: true, value: 'status', },
                {text: 'Type', align: 'start', sortable: true, value: 'type',},
                {text: 'Logged Since', sortable: true, value: 'logged_at'},
            ],
            headers_orders: [
                {text: 'ID', align: 'start', sortable: true, value: 'id', },
                {text: 'Status', align: 'start', sortable: true, value: 'status',},
                {text: 'Cooker', sortable: true, value: 'cooker.name'},
                {text: 'Deliveryman', sortable: true, value: 'deliveryman.name'},
                {text: 'Current Status At', sortable: true, value: 'current_status_at'},
                {text: 'Actions', sortable: false, value: 'actions'},
            ],
            working_time:'',
        }
    },
    methods: {
        refreshLoggedUsers(){
            this.loadLoggedUser();
            // this.$store.dispatch("loadAvailableCookers");
            // this.$store.dispatch("loadAvailableDeliveryman");
        },
        loadLoggedUser (){
            axios.get("api/users/logged_users")
                .then((response)=>{
                    this.logged_users = response.data.data;
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
        async cancelOrder(item){
            if (await this.$refs.confirm.open(
                "Cancel order",
                "Are you sure that you want to cancel the order?")
            ) {
                axios.put('api/orders/' + item.id + '/canceled')
                    .then((response) => {
                        this.snackbar = true;
                        this.text = "Order canceled."
                        this.color = "green"
                        setTimeout(() => {
                            this.snackbar = false;
                        }, 2000);
                        this.$socket.emit('order_canceled', item);
                        this.getOpenOrder();
                    })
                .catch((error) => {
                    console.log(error)
                    this.color = 'red';
                    this.text = "Error canceling order."
                    this.snackbar = true;
                    setTimeout(() => {
                        this.snackbar = false;
                    }, 2000);
                })
            }
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
        },
        timeCounters() {
            let now = new Date()
            let start = new Date(this.$store.state.user_logged_at);
            this.working_time = Math.floor((now - start) / (1000 * 60));
        },
    },
    created() {
        this.loadLoggedUser();
        this.getOpenOrder();
        this.timeCounters();
        setInterval(this.timeCounters,61000)
        // this.$store.dispatch("loadAvailableCookers");
        // this.$store.dispatch("loadAvailableDeliveryman");
    },
}
</script>

<style scoped>

</style>
