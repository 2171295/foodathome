<template>
    <div>
        <aux_snackbar :text="text" :snackbar="snackbar" :color="color"/>

        <aux_dialog_confirmacao ref="confirm"/>
        <aux_edit_product ref="updateProduct"/>
        <aux_create_product ref="createProduct"/>

        <v-toolbar class="d-flex justify-center align-center" style="margin-bottom: 20px;">
            <v-toolbar-title>Products</v-toolbar-title>
        </v-toolbar>
        <v-card>
            <v-toolbar style="margin-bottom: 10px;">
                <v-text-field label="Search:" v-model="search"></v-text-field>
                <v-spacer></v-spacer>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on"  icon @click="createProduct()" >
                            <v-icon>mdi-plus</v-icon>
                        </v-btn>
                    </template>
                    <span>Create New Product</span>
                </v-tooltip>
            </v-toolbar>
            <v-card-text>
                <v-data-table
                    :headers="headers"
                    :items="products"
                    :items-per-page="10"
                    class="elevation-1"
                    :search="search"
                >
                    <template v-slot:item.img="{ item }">
                        <v-img :src="'/storage/products/'+item.photo" width="100px" height="100px"
                               style="border-radius: 50%"/>
                    </template>
                    <template v-slot:item.actions="{item}">
                        <v-tooltip bottom >
                            <template v-slot:activator="{ on }">
                                <v-btn v-on="on" icon @click="updateProduct(item)">
                                    <v-icon>mdi-account-edit</v-icon>
                                </v-btn>
                            </template>
                            <span>Edit Product</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn v-on="on" icon @click="deleteProduct(item)">
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </template>
                            <span>Remove Product</span>
                        </v-tooltip>
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
import Aux_snackbar from "../auxiliares/aux_snackbar";
import Aux_dialog_confirmacao from "../auxiliares/aux_dialog_confirmacao";
import Aux_edit_product from "../auxiliares/products/aux_edit_product";
import Aux_create_product from "../auxiliares/products/aux_create_product";
export default {
    name: "index",
    components: {Aux_create_product, Aux_edit_product, Aux_dialog_confirmacao, Aux_snackbar},
    data: () => {
        return {
            search:'',
            products: [],
            headers: [
                {text: '', value: 'img'},
                {text: 'Name', align: 'start', sortable: true, value: 'name',},
                {text: 'Type', align: 'start', sortable: true, value: 'type',},
                {text: 'Price', sortable: true, value: 'price'},
                {text: 'Description', sortable: false, value: 'description'},
                {text: 'Actions', align: 'start', sortable: false, value: 'actions',},
            ],

            // ---- SNACKBAR INFO -----
            color: '',
            snackbar: false,
            text: '',
            // ------------------------

            product:'',
        }
    },
    methods: {
        getProducts(){
            axios.get("api/products")
            .then((response) => {
                this.products = response.data.data
            })
        },
        async createProduct(){
            if (await this.$refs.createProduct.open()) {
                this.getProducts()
            }else{
                return null;
            }
        },
        async updateProduct(item){
            if (await this.$refs.updateProduct.open(item)) {
                this.getProducts()
            }else{
                return null;
            }
        },
        async deleteProduct(item){
            if(await this.$refs.confirm.open(
                "Remove Product", "Are you sure you want to remove this product?"
            )) {
                axios.delete("api/products/" + item.id)
                    .then(() => {
                        this.snackbar = true;
                        this.text = "Product successfully deleted."
                        this.color = "green"
                        setTimeout(() => {
                            this.snackbar = false;
                        }, 2000);
                    })
                    .catch(() => {
                        this.snackbar = true;
                        this.text = "Error deleting product."
                        this.color = "error"
                        setTimeout(() => {
                            this.snackbar = false;
                            this.user = '';
                        }, 2000);
                    })
            }else{
                return null;
            }
        },
    },
    created(){
        this.getProducts()
    }
}
</script>

<style scoped>

</style>
