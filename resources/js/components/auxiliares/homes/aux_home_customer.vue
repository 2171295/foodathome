<template>
    <v-container>
        <aux_order_information ref="orderInformation"/>
        <v-row>
            <div  id="h2" style="text-align: center">What do you feel like eating today?</div>
            <v-row>
                <v-col style="text-align: center">
                    <v-card @click="menuHotDishes" elevation="0" >
                        <v-card-text>
                            <photo_hot_dishes/>
                            <p>Hot Dishes</p>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col style="text-align: center">
                    <v-card @click="menuColdDishes" elevation="0" >
                        <v-card-text>
                            <photo_cold_dishes/>
                            <p>Cold Dishes</p>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col style="text-align: center">
                    <v-card @click="menuDesserts" elevation="0" >
                        <v-card-text>
                            <photo_desserts/>
                            <p>Desserts</p>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col style="text-align: center">
                    <v-card @click="menuColdDrinks" elevation="0" >
                        <v-card-text>
                            <photo_cold_drinks/>
                            <p>Drinks</p>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-row>
        <v-row v-if="open_orders.length !== 0">
            <v-col>
                <v-card>
                    <v-toolbar class="d-flex justify-center">
                        <v-toolbar-title>On Going Orders</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <div  v-for="order in open_orders" style="margin-top: 20px; margin-bottom: 50px">
                            <div class="text--black d-flex justify-center align-center" style="font-size: 20px; margin-bottom: 10px;" >
                                Order: #{{ order.id }}
                                <v-spacer></v-spacer>
                                <v-btn icon @click="orderInformation(order)"><v-icon>mdi-information</v-icon></v-btn>
                            </div>
                            <v-stepper v-model="order.status">
                                <v-stepper-header>
                                    <v-stepper-step
                                        :complete="order.status === 'H'"
                                        step="1"
                                    >
                                        Holding...
                                    </v-stepper-step>
                                    <v-divider></v-divider>
                                    <v-stepper-step
                                        :complete="order.status === 'P'"
                                        step="2"
                                    >
                                        Preparing...
                                    </v-stepper-step>
                                    <v-divider></v-divider>
                                    <v-stepper-step step="3" :complete="order.status === 'R'">
                                        Ready for delivery...
                                    </v-stepper-step>
                                    <v-divider></v-divider>
                                    <v-stepper-step step="4" :complete="order.status === 'T'">
                                        In Transit...
                                    </v-stepper-step>
                                </v-stepper-header>
                            </v-stepper>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row v-if="all_orders.length !== 0">
            <v-col>
                <v-card>
                    <v-toolbar class="d-flex justify-center">
                        <v-toolbar-title>Orders history</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-data-table :items="closed_orders" :headers="closed_orders_headers">
                            <template v-slot:item.status="{ item }">
                                <div v-if="item.status === 'D'">
                                    Delivered
                                </div>
                                <div v-if="item.status === 'C'">
                                    Canceled
                                </div>
                            </template>
                            <template v-slot:item.actions="{ item }">
                                <v-btn icon @click="orderInformation(item)">
                                    <v-icon>mdi-information</v-icon>
                                </v-btn>
                            </template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import photo_cold_drinks from "../../images/photo_cold_drinks";
import photo_desserts from "../../images/photo_desserts";
import photo_hot_drinks from "../../images/photo_hot_drinks";
import photo_cold_dishes from "../../images/photo_cold_dishes";
import photo_hot_dishes from "../../images/photo_hot_dishes";
import aux_order_information from "../aux_order_information";

export default {
    name: "aux_home_customer",
    data: () => ({
        all_orders:[],
        closed_orders:[],
        open_orders:[],
        open_orders_headers:[
            {text: 'ID', align: 'start', sortable: true, value: 'id'},
        ],
        closed_orders_headers:[
            {text: 'Order #', align: 'start', sortable: true, value: 'id'},
            {text: 'Status', align: 'start', sortable: true, value: 'status'},
            {text: 'Date', align: 'start', sortable: true, value: 'date'},
            {text: 'Total Price (€)', align: 'start', sortable: true, value: 'total_price'},
            {text: 'More', align: 'start', sortable: true, value: 'actions'},

        ]

    }),
    components: {
        photo_desserts,
        photo_cold_dishes,
        photo_hot_dishes,
        photo_hot_drinks,
        photo_cold_drinks,
        aux_order_information,
    },
    methods: {
        menuHotDishes() {
            this.$store.commit("setPage")
            this.$store.commit("setMenuSearch", "Hot Dish")
            this.$router.push("/menu")

        },
        menuColdDishes() {
            this.$store.commit("setPage")
            this.$store.commit("setMenuSearch", "Cold Dish")
            this.$router.push("/menu")
        },
        menuDesserts() {
            this.$store.commit("setPage")
            this.$store.commit("setMenuSearch", "Dessert")
            this.$router.push("/menu")
        },
        menuColdDrinks() {
            this.$store.commit("setPage")
            this.$store.commit("setMenuSearch", "Drink")
            this.$router.push("/menu")
        },
        getOrders(){
            axios.get('/api/customers/'+this.$store.state.user.id+'/orders').then((response)=>{
                this.open_orders = [];
                this.closed_orders = [];
                this.all_orders = response.data;
                for(var i in response.data){
                    if(response.data[i].status === 'C' || response.data[i].status === 'D'){
                        this.closed_orders.push(response.data[i]);
                    }else{
                        this.open_orders.push(response.data[i]);
                    }
                }
        }).catch((error)=>{
            console.log(error)
            })
        },
        async orderInformation(order) {
            if (await this.$refs.orderInformation.open(order)) {

            }
        },

    },
    sockets:{
        order_assign_cook(user){
            this.getOrders()
        },
        order_cooked(order){
            this.getOrders()
        },
        order_taken_delivery(user) {
            this.getOrders()
        }
    },
    created() {
        this.getOrders();
    }

}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');

@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap');

#h2{
    font-family: "Montserrat";
    font-size: 30px;
    font-weight: bold;
}

</style>

