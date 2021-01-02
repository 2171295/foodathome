<template>
    <v-container>
        <aux_snackbar :text="text" :snackbar="snackbar" :color="color"/>
        <v-row>
            <v-col>
                <v-card>
                    <v-toolbar class="d-flex justify-center align-center" style="margin-bottom: 20px;">
                        <v-toolbar-title>Order Being Prepared</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text v-if="order">
                        <v-row>
                            <v-col>
                                <p><b>ID:</b>{{ order.id }}</p>
                                <p><b>Customer:</b>{{ order.customer }}</p>
                                <p><b>Customer Notes:</b>{{ order.notes }}</p>
                            </v-col>
                            <v-col>
                                <p><b>Started At:</b>{{ order.current_status_at }}</p>
                                <p><b>Preparation Time:</b>{{ preparation_time }}</p>
                            </v-col>
                        </v-row>
                        <!--                        Lista com os Items da Order-->
                        <v-toolbar style="margin-bottom: 10px;">
                            <v-text-field label="Search:" v-model="search"></v-text-field>
                        </v-toolbar>
                        <v-card-text>
                            <v-data-table
                                :headers="headers"
                                :items="order_items"
                                :items-per-page="10"
                                class="elevation-1"
                                :search="search"
                            >
                            </v-data-table>
                        </v-card-text>
                        <v-btn @click="finishOrder()">Finish</v-btn>
                    </v-card-text>
                    <v-card-text v-else>
                        <p><b>You have no order assign</b></p>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import Aux_snackbar from "../aux_snackbar";
export default {
    name: "aux_home_cook",
    components: {Aux_snackbar},
    data: () => {
        return {
            // ---- SNACKBAR INFO -----
            color: '',
            snackbar: false,
            text: '',
            // ------------------------

            order:'',
            preparation_time:'',
            order_items: [],

            search:'',
            headers: [
                {text: '', sortable: false, value: 'img'},
                {text: 'Name', align: 'start', sortable: true, value: 'name',},
                {text: 'Type', align: 'start', sortable: true, value: 'type',},
                {text: 'Quantity', align: 'start', sortable: true, value: 'quantity',},
                {text: 'Description', sortable: true, value: 'email'},
                {text: 'Actions', align: 'start', sortable: false, value: 'actions',},
            ],
        }
    },
    methods: {
        getOrderBeingPrepared() {
            axios.get('api/orders/preparedby/' + this.$store.state.user.id)
                .then((response) => {
                    console.log(response);
                    if (response.data) {
                        this.order = response;
                        axios.get('api/orders_items/order/' + this.order.id)
                            .then((response) => {
                                this.order_items = response.data.data;
                            })
                            .catch(() => {
                                this.color = 'red';
                                this.text = "Error getting order items."
                                this.snackbar = true;
                                setTimeout(() => {
                                    this.snackbar = false;
                                }, 2000);
                            })
                    }
                })
                .catch(() => {
                    this.color = 'red';
                    this.text = "Error getting order being prepared."
                    this.snackbar = true;
                    setTimeout(() => {
                        this.snackbar = false;
                    }, 2000);
                })
        },
        finishOrder(){

        }
    },
    created() {
        this.getOrderBeingPrepared();
    },
}
</script>

<style scoped>

</style>
