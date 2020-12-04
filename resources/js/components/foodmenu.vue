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
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-navigation-drawer clipped fixed app floating>
            <v-card height="91vh" width="256" class="mx-auto">
                <v-navigation-drawer permanent>
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title class="title" style="text-align: center">
                                Filters
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-divider></v-divider>
                        <v-checkbox v-model="checkboxHotDishes" label="Hot Dishes"></v-checkbox>
                </v-navigation-drawer>
            </v-card>
        </v-navigation-drawer>
        <v-data-iterator
            :items="$store.state.menu_items"
            :page="page"
            :search="search"
            hide-default-footer
        >
            <template v-slot:header>
                <v-text-field
                    v-model="search"
                    prepend-inner-icon="mdi-magnify"
                    label="Search"
                ></v-text-field>
            </template>
            <template v-slot:default="props">
             <v-row>
                <v-col
                v-for="product in props.items"
                :key="product.name"
                cols="4"
                >
                <v-card>
                    <v-img :src="'/storage/products/'+product.photo" max-height="200px"/>
                    <v-card-title>
                        {{ product.name }}
                        <v-spacer/>
                        <v-btn icon @click="showProductInformation(product)"><v-icon>mdi-information-outline</v-icon></v-btn>
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
        checkboxHotDishes:true,
        dialogProductInformation:false,
        detailedProduct:'',
        search:'',
        itemsPerPage: 4,
        page:1,
        keys: [
            'Name',

        ],
        loading:false,
        loading_text:'',
    }
},
    methods:{

        showProductInformation(product){
            this.dialogProductInformation = true;
            this.detailedProduct = product;
        }
    },

    created() {

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
