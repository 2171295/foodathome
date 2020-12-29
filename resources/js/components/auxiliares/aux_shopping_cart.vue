<template>
    <div>
        <aux_snackbar :text="text" :snackbar="snackbar" :color="color"/>
        <aux_dialog_confirmacao ref="confirmDialog"/>
        <v-dialog v-model="display" @keydown.esc="!display">
            <v-card>
                <v-toolbar class="d-flex justify-center">
                    <v-toolbar-title>Shopping Cart</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-tooltip bottom >
                        <template v-slot:activator="{ on, attrs }">
                            <div v-on="on">
                                <v-btn icon v-bind="attrs" v-on="on" @click="display = false">
                                    <v-icon>mdi-close</v-icon>
                                </v-btn>
                            </div>
                        </template>
                        <span>Close</span>
                    </v-tooltip>
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
                                                    <p>({{product.unit_price}}€/unit)</p>
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
                    <div class="d-flex justify-end black--text v-size--large">Total value: {{ total_value }}€</div>
                    <div class="d-flex justify-end black--text">
                        <v-btn @click="submit">Checkout</v-btn>
                    </div>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import {mdiCamera} from "@mdi/js";
import aux_snackbar from "./aux_snackbar";
import aux_dialog_confirmacao from "./aux_dialog_confirmacao";

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
        total_value:'',
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
            this.total_value = JSON.parse(JSON.stringify(this.$store.state.totalValueCart))
        },
        clearCart(){
            this.$store.commit('clearCart')
            this.order_item = [];
            this.total_value = null;
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
        async submit() {
            if (await this.$refs.confirmDialog.open(
                "Place order",
                "Are you sure about placing this order?")
            ) {

            }
        },
    },
    components: {
        aux_snackbar,
        aux_dialog_confirmacao
    },
}
</script>

<style scoped>

</style>
