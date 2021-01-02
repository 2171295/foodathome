<template>
    <div>
        <v-dialog v-model="display" max-width="600px">
            <v-card>
                <v-toolbar class="d-flex justify-center">
                    <v-toolbar-title>Order information</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-tooltip bottom >
                        <template v-slot:activator="{ on, attrs }">
                            <div v-on="on">
                                <v-btn icon v-bind="attrs" v-on="on" @click="cancel">
                                    <v-icon>mdi-close</v-icon>
                                </v-btn>
                            </div>
                        </template>
                        <span>Close</span>
                    </v-tooltip>
                </v-toolbar>
                <v-card-text style="margin-top: 20px">
                    <p><b>Order Number:</b> {{order.id}}</p>
                    <template v-if="order.status === 'H'">
                        <p><b>Status:</b> Holding</p>
                    </template>
                    <template v-if="order.status === 'P'">
                        <p><b>Status:</b> Preparing</p>
                    </template>
                    <template v-if="order.status === 'R'">
                        <p><b>Status:</b> Ready</p>
                    </template>
                    <template v-if="order.status === 'T'">
                        <p><b>Status:</b> Transit</p>
                    </template>
                    <template v-if="order.status === 'C'">
                        <p><b>Status:</b> Canceled</p>
                    </template>
                    <template v-if="order.status === 'D'">
                        <p><b>Status:</b> Delivered</p>
                    </template>
                    <p><b>Total price:</b> {{order.total_price}}€</p>
                    <p><b>Ordered at:</b> {{order.created_at}}</p>
                    <div v-if="order.notes !== null"><p><b>Notes:</b> {{ order.notes }}</p></div>
                    <v-data-table :items="orderItems" :headers="headers" hide-default-footer></v-data-table>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
name: "aux_order_information",
    data: () => ({
        display: false,
        reject:null,
        resolve:null,
        order:'',
        orderItems:[],
        headers:[
            {text: 'Name', align: 'start', sortable: true, value: 'name'},
            {text: 'Unit Price (€)', align: 'start', sortable: true, value: 'unit_price'},
            {text: 'Quantity', align: 'start', sortable: true, value: 'quantity'},
            {text: 'Total Price (€)', align: 'start', sortable: true, value: 'sub_total_price'},
        ],
    }),
    methods:{
        open(order) {
            this.display = true;
            this.getOrderItems(order);
            return new Promise((resolve, reject) => {
                this.resolve = resolve;
                this.reject = reject;
                this.order = order;
            });
        },
        cancel(){
            this.resolve(false);
            this.dialog = false;
        },
        agree(){
            this.resolve(true);
            this.dialog = false;
        },
        getOrderItems(order){
            axios.get('/api/orders/'+order.id+'/products').then((response)=>{
                this.orderItems = response.data.data;
            })
        }
    }

}
</script>

<style scoped>

</style>
