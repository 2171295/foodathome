<template>
    <v-container>
        <aux_dialog_confirmacao ref="confirm"/>
        <v-row>
            <v-col>
                <v-card>
                    <v-toolbar class="d-flex justify-center">
                        <v-toolbar-title>Active Order</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <div v-if="activeOrder === null">
                            You're not currently delivering an order.
                        </div>
                        <div v-else>
                            <p><b>Order Number:</b> {{activeOrder.id}}</p>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-card>
                    <v-toolbar class="d-flex justify-center">
                        <v-toolbar-title>Orders Ready</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-data-table :items="ordersReady" :headers="headers" no-data-text="There are no orders ready for transport">
                            <template v-slot:item.actions="{ item }">

                                <v-tooltip bottom >
                                    <template v-slot:activator="{ on, attrs }">
                                        <div v-on="on">
                                            <v-btn v-if="activeOrder === null" icon @click="takeOrder(item)">
                                                <v-icon>mdi-check</v-icon>
                                            </v-btn>
                                        </div>
                                    </template>
                                    <span>Take Order</span>
                                </v-tooltip>
                            </template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>

</template>

<script>
import aux_dialog_confirmacao from "../aux_dialog_confirmacao";
export default {
    name: "aux_home_deliveryman",
    components:{
        aux_dialog_confirmacao,
    },
    data: () => ({
        activeOrder: null,
        ordersReady:[],
        headers:[
            {text: 'ID', align: 'start', sortable: true, value: 'id'},
            {text: 'Address', align: 'start', sortable: true, value: 'customer.customer.address'},
            {text: 'Notes', align: 'start', sortable: true, value: 'notes'},
            {text: 'Actions', align: 'start', sortable: true, value: 'actions'},
        ],
    }),
    methods:{
        getOrders(){
            axios.get('/api/orders/ready').then((response)=>{
                this.ordersReady = response.data.data;
            })
        },
        getMyOrder(){
            axios.get('/api/orders/deliveredby/'+this.$store.state.user.id).then((response)=>{
                console.log(response)
                if(response.data.data)
                    this.activeOrder = response.data.data;
            })
        },
        async takeOrder(item) {
            if (await this.$refs.confirm.open(
                "Eliminar utilizador",
                "Tem a certeza que quer eliminar o utilizador " + item.email + " ?")
            ) {
                axios.put('/api/orders/' + item.id + '/delivery_man', {
                    delivery_man: this.$store.state.user
                }).then((response) => {
                    this.activeOrder = item;
                    this.$socket.emit('order_taken_delivery',this.$store.state.user)
                    this.getOrders()
                })
            }
        }
    },
    sockets: {
        order_cooked(order){
            this.getOrders()
        },
        order_taken_delivery(user) {
            this.getOrders()
        }
    },
    created() {
        this.getOrders()
        this.getMyOrder()
    }
}
</script>

<style scoped>

</style>
