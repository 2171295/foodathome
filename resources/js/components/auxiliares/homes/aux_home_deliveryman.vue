<template>
    <v-container>
        <aux_dialog_confirmacao ref="confirm"/>
        <v-row>
            <v-col>
                <v-card>
                    <p><b>Working Time: {{working_time}} minutes</b></p>
                    <v-toolbar class="d-flex justify-center">
                        <v-toolbar-title>Active Order</v-toolbar-title>
                    </v-toolbar>
                    <v-row  v-if="activeOrder === null">
                        <v-card-text class="d-flex align-center justify-center">
                            <p><b>You're not currently delivering an order.</b></p>
                        </v-card-text>
                    </v-row>
                    <v-row v-else>
                        <v-col md="4">
                            <v-card-text >
                                <v-img :src="'/storage/fotos/'+activeOrder.customer.user.photo_url"
                                       max-height="200px"
                                       max-width="200px" style="border-radius: 50%;"/>
                                <p><b>Order Number:</b> {{ activeOrder.id }}</p>
                                <p><b>Customer name:</b> {{ activeOrder.customer.user.name }}</p>
                                <p><b>Customer address:</b> {{ activeOrder.customer.customer.address }}</p>
                                <p><b>Customer email:</b> {{ activeOrder.customer.user.email }}</p>
                                <p><b>Order notes:</b> {{ activeOrder.notes }}</p>
                                <p><b>Delivery started at:</b> {{ activeOrder.current_status_at }}</p>
                                <p><b>Delivery time:</b> {{ delivery_time }} minutes</p>
                            </v-card-text>
                        </v-col>
                        <v-col>
                            <v-card-text>
                                <v-card-text>
                                    <v-data-table
                                        :headers="headers_items"
                                        :items="order_items"
                                        :items-per-page="10"
                                        class="elevation-1"
                                        :search="search"
                                    >
                                        <template v-slot:item.img="{ item }">
                                            <v-img :src="'/storage/products/'+item.product.photo_url" width="100px"
                                                   height="100px"
                                                   style="border-radius: 50%"/>
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                            </v-card-text>
                        </v-col>
                    </v-row>
                    <v-row class="d-flex align-center justify-center" v-if="activeOrder !== null">
                        <v-btn @click="orderDelivered">Delivered</v-btn>
                    </v-row>
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
        order_items:[],
        delivery_time:'',
        total_time:'',
        working_time:'',
        search:'',
        headers:[
            {text: 'ID', align: 'start', sortable: true, value: 'id'},
            {text: 'Address', align: 'start', sortable: true, value: 'customer.customer.address'},
            {text: 'Ready At', align: 'start', sortable: true, value: 'current_status_at'},
            {text: 'Notes', align: 'start', sortable: true, value: 'notes'},
            {text: 'Actions', align: 'start', sortable: true, value: 'actions'},
        ],
        headers_items: [
            {text: '', value: 'img'},
            {text: 'Name', align: 'start', sortable: true, value: 'product.name',},
            {text: 'Type', align: 'start', sortable: true, value: 'product.type',},
            {text: 'Quantity', align: 'start', sortable: true, value: 'quantity',},
            {text: 'Description', align: 'start', sortable: false, value: 'product.description',},
        ],
    }),
    methods:{
        getOrders(){
            axios.get('/api/orders/ready').then((response)=>{
                this.ordersReady = response.data.data;
            })
        },
        getMyOrder() {
            this.activeOrder = null;
            axios.get('/api/orders/deliveredby/' + this.$store.state.user.id)
                .then((response) => {
                    if (response.data.data) {
                        if(this.$store.state.user.available_at != null){
                            this.delivering();
                        }
                        this.activeOrder = response.data.data;
                        this.timeCounter()
                        axios.get('api/orders_items/order/' + this.activeOrder.id)
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
        },
        async takeOrder(item) {
            if (await this.$refs.confirm.open(
                "Take Order",
                "Are you sure you want to take this order ?")
            ) {
                axios.put('/api/orders/' + item.id + '/delivery_man', {
                    delivery_man: this.$store.state.user
                }).then((response) => {
                    this.activeOrder = item;
                    this.$socket.emit('order_taken_delivery',this.$store.state.user)
                    this.delivering();
                    this.getOrders()
                    this.timeCounter();
                })
            }
        },
        delivering(){
            axios.put('api/users/' + this.$store.state.user.id + '/not_available')
                .then((response) => {
                    console.log("User is delivering")
                    this.$store.dispatch('setUser',response.data.data)
                })
                .catch((error) => {
                    console.log(error);
                })
        },
        timeCounter() {
            let start = new Date(this.activeOrder.current_status_at);
            let now = new Date()
            this.delivery_time = Math.floor((now - start) / (1000 * 60));
            start = new Date(this.activeOrder.opened_at);
            this.total_time = Math.floor((now - start) / (1000 * 60));
            start = new Date(this.$store.state.user_logged_at);
            this.working_time = Math.floor((now - start) / (1000 * 60));
        },
        async orderDelivered () {

            if (await this.$refs.confirm.open(
                "Order Deliveres",
                "Are you sure the order is delivered?")
            ) {
                this.timeCounter();
                axios.put('/api/orders/' + this.activeOrder.id + '/delivered', {
                    delivery_time: this.delivery_time,
                    total_time: this.total_time
                })
                    .then(() => {
                        this.snackbar = true;
                        this.text = "Order finished."
                        this.color = "green"
                        setTimeout(() => {
                            this.snackbar = false;
                        }, 2000);
                        this.$socket.emit('order_delivered', this.activeOrder)
                        axios.put('api/users/' + this.$store.state.user.id + '/available')
                            .then((response) => {
                                this.$socket.emit('user_available', this.$store.state.user)
                                this.$store.dispatch('setUser',response.data.data)
                                this.getOrders()
                                this.getMyOrder()
                            })
                            .catch((error) => {
                                console.log(error)
                                this.color = 'red';
                                this.text = "Error setting you available."
                                this.snackbar = true;
                                setTimeout(() => {
                                    this.snackbar = false;
                                }, 2000);
                            })
                    })
                    .catch((error) => {
                        console.log(error)
                        this.color = 'red';
                        this.text = "Error finishing order."
                        this.snackbar = true;
                        setTimeout(() => {
                            this.snackbar = false;
                        }, 2000);
                    })
            }
        },
    },
    sockets: {
        order_cooked(order){
            this.getOrders()
        },
        order_taken_delivery(user) {
            this.getOrders()
        },
        order_delivered(order){
            this.getOrders()
            this.getMyOrder()
        }
    },
    created() {
        this.getOrders()
        this.getMyOrder()
        setInterval(this.timeCounter,61000)
    }
}
</script>

<style scoped>

</style>
