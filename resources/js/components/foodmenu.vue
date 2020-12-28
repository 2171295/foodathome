<template>
    <div>
        <v-dialog v-model="dialogProductInformation" max-width="490">
            <v-card class="mx-auto" max-width="4800" >
                <v-btn icon @click="dialogProductInformation = false"><v-icon>mdi-close</v-icon></v-btn>
                <v-img :src="'/storage/products/'+detailedProduct.photo" max-height="200px"/>
                <v-card-title>
                    {{ detailedProduct.name }}
                </v-card-title>
                <v-card-text>
                    <p> <b>Type:</b> {{detailedProduct.type}}</p>
                    <p> <b>Description:</b> {{detailedProduct.description}}</p>
                    <p> <b>Price:</b> {{detailedProduct.price}}€</p>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-data-iterator
            :items="$store.state.menu_items"
            :page="page"
            :search="search"
        >
            <template v-slot:header>
                <v-row>
                    <v-col align="center">
                        <v-text-field
                            v-model="search"
                            prepend-inner-icon="mdi-magnify"
                            label="Search"
                            clearable
                        ></v-text-field>
                    </v-col>
                    <v-col md="2">
                        <v-combobox :items="comboboxItems" v-model="search" label="Type of Product:" chips ></v-combobox>
                    </v-col>
                </v-row>

            </template>
            <template v-slot:default="props">
             <v-row>
                <v-col
                v-for="product in props.items"
                :key="product.name"
                cols="4"
                >
                <v-card>
                    <v-img :src="'/storage/products/'+product.photo" height="200px"/>
                    <v-card-title>
                        {{ product.name }}
                        <v-spacer/>
                        <v-btn icon @click="showProductInformation(product)"><v-icon>mdi-information-outline</v-icon></v-btn>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn icon  v-on="on" v-if="$store.state.user.type === 'C'" @click="addToCart(product)">
                                    <v-icon>mdi-cart</v-icon>
                                </v-btn>
                            </template>
                            <span>Add item to cart</span>
                        </v-tooltip>

                    </v-card-title>
                    <v-card-subtitle>
                        {{product.price}} €
                    </v-card-subtitle>
                </v-card>
            </v-col>
            </v-row>
            </template>
        </v-data-iterator>
    </div>

</template>

<script>
export default {
name: "foodmenu",
data:function () {
    return {
        products:[],
        checkboxHotDishes:false,
        checkboxColdDishes:false,
        dialogProductInformation:false,
        detailedProduct:'',
        search:'',
        itemsPerPage: 4,
        page:1,
        keys: [
            'Name',

        ],
        comboboxItems:['','Hot Dish', 'Cold Dish', 'Drink', 'Dessert'],

    }
},
    methods:{

        showProductInformation(product){
            this.dialogProductInformation = true;
            this.detailedProduct = product;
        },
        searchHotDishes(){
            if(this.checkboxHotDishes){
               this.search = "Hot Dish"
            }else{
                this.search = ""
            }
        },
        searchColdDishes(){
            if(this.checkboxColdDishes){
                this.search = "Cold Dish"
            }else{
                this.search = ""
            }
        },
        addToCart(item){

        }
    },

    created() {
        if(this.$store.state.menu_search !== ""){
            this.search = this.$store.state.menu_search;
            this.$store.commit("setMenuSearch", "")
        }
    },
    computed:{
        filteredKeys () {
            return this.keys.filter(key => key !== 'Name')
        },
    }
}
</script>

<style scoped>

</style>
