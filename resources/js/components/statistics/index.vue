<template>
    <div>
        <aux_order_information ref="orderInformation"/>
        <v-row>
            <v-toolbar class="d-flex justify-center">
                <v-toolbar-title>Statistics</v-toolbar-title>
            </v-toolbar>
        </v-row>
        <v-row>
            <v-col>
                <v-card style="margin-top: 50px">
                    <v-toolbar class="d-flex justify-center">
                        <v-toolbar-title>Orders History</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-text-field v-model="search" append-icon="mdi-magnify" label="Search"></v-text-field>
                        <v-data-table :items="orders" :search="search" :headers="headers">
                            <template v-slot:item.actions="{ item }">
                                <v-btn icon @click="orderInformation(item)">
                                    <v-icon>mdi-information</v-icon>
                                </v-btn>
                            </template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col>
                <v-card style="margin-top: 50px" >
                    <v-toolbar class="d-flex justify-center">
                        <v-toolbar-title>Orders per Status</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <chart_orders style="max-height: 80%; max-width: 80%"></chart_orders>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script>
import aux_order_information from "../auxiliares/aux_order_information";
import chart_orders from "../auxiliares/charts/chart_orders";
import photo_desserts from "../images/photo_desserts";
import photo_cold_dishes from "../images/photo_cold_dishes";
import photo_hot_dishes from "../images/photo_hot_dishes";
import photo_hot_drinks from "../images/photo_hot_drinks";
import photo_cold_drinks from "../images/photo_cold_drinks";
export default {
    name: "index",
    components:{
        chart_orders,
        aux_order_information,
    },
    data: () => ({
        orders:[],
        search:'',
        num_delivered:0,
        num_canceled:0,
        num_ready:0,
        num_transit:0,
        num_preparing:0,
        num_holding:0,
        total_sales:[],
        headers:[
            {text: 'ID', align: 'start', sortable: true, value: 'id', },
            {text: 'Status', align: 'start', sortable: true, value: 'status',},
            {text: 'Date', align: 'start', sortable: true, value: 'date',},
            {text: 'Total Price (€)', align: 'start', sortable: true, value: 'total_price',},
            {text: 'Actions', sortable: false, value: 'actions'},
        ],
    }),
    methods:{
        async getOrders() {
             axios.get('/api/orders').then((response)=>{
                 this.orders = response.data.data
                 this.orders.forEach(value =>
                 {
                     switch (value.status){
                         case 'D':
                             value.status = "Delivered";
                             this.num_delivered ++;
                             break;
                         case 'H':
                             value.status = "Holding";
                             this.num_holding ++;
                             break;
                         case 'C':
                             value.status = "Canceled";
                             this.num_canceled ++;
                             break;
                         case 'T':
                             value.status = "Transit";
                             this.num_transit ++;
                             break;
                         case 'P':
                             value.status = "Preparing";
                             this.num_preparing ++;
                             break;
                         case 'R':
                             value.status = "Ready";
                             this.num_ready ++;
                             break;
                     }
                 })
             });
        },
    },
    created() {
        this.getOrders()
    },
}
</script>

<style scoped>

</style>
