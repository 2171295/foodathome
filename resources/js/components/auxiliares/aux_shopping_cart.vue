<template>
    <div>
        <aux_snackbar :text="text" :snackbar="snackbar" :color="color"/>
        <v-dialog v-model="display">
            <v-card>
                <v-toolbar class="d-flex justify-center">
                    <v-toolbar-title>Shopping Cart</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-btn @click="clearCart" x-small>Clear Cart</v-btn>
                   <v-data-iterator :items="order_item" no-data-text="There are no products added to the shopping cart." disable-pagination :hide-default-footer="true">
                        <template v-slot:default="props">
                            <v-row
                                v-for="product in props.items"
                                :key="product.name">
                                <v-col>
                                    <v-card>
                                        <v-card-text>
                                            <v-row>
                                                <v-col md="2">
                                                    <v-img :src="'/storage/products/'+product.photo" max-height="80px" max-width="80px"/>
                                                </v-col>
                                                <v-col md="2">
                                                    <p>Name</p>
                                                    <p>{{product.name}}</p>
                                                </v-col>
                                                <v-col md="2">
                                                    <p>Quantity</p>
                                                    <p>{{product.quantity}}</p>
                                                    <v-btn icon small @click="removeQuantity(product)"><v-icon>mdi-minus</v-icon></v-btn>
                                                    <v-btn icon small @click="addQuantity(product)"><v-icon>mdi-plus</v-icon></v-btn>
                                                </v-col>
                                                <v-col md="2">
                                                    <p>Price</p>
                                                    <p>{{product.total_price}}€</p>
                                                </v-col>
                                                <v-col md="2">
                                                    <p></p>
                                                    <p> <v-btn icon small @click="removeProduct(product)"><v-icon>mdi-delete</v-icon></v-btn></p>
                                                </v-col>
                                            </v-row>
                                        </v-card-text>
                                    </v-card>
                                </v-col>
                            </v-row>
                        </template>
                    </v-data-iterator>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import {mdiCamera} from "@mdi/js";
import aux_snackbar from "./aux_snackbar";

export default {
    name: "aux_shopping_cart",
    data: () => ({
        display:false,
        // ---- SNACKBAR INFO -----
        color: '',
        snackbar: false,
        text: '',
        // ------------------------
        items:'',
        order_item:[],
        found: false,
    }),
    methods:{
        open() {

            this.getItems()
            this.display = true;
            this.valid = true
            return new Promise((resolve, reject) => {
                this.resolve = resolve;
                this.reject = reject;
            });
        },
        getItems(){
            this.order_item = JSON.parse(JSON.stringify(this.$store.state.cart))
        },
        clearCart(){
            this.$store.commit('clearCart')
            this.order_item = [];
        },
        addQuantity(product){
            this.$store.commit('addQuantityItemToCart', product);
            this.getItems();
        },
        removeQuantity(product){
            this.$store.commit('removeQuantityItemFromCart', product);
            this.getItems();
        },
        removeProduct(product){
            this.$store.commit('removeItemFromCart', product);
            this.getItems();
        },
        cancel(){
            this.resolve(false);
            this.display = false;
        },
        submit() {
        },
    },
    components: {
        aux_snackbar
    },
}
</script>

<style scoped>

</style>
