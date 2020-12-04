<template>
    <div>
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
        <v-row>
            <v-col
                v-for="product in products"
                :key="product"
                class="d-flex child-flex"
                cols="4"
            >
                <v-card>
                    <v-card-title>
                        {{ product.name }}
                    </v-card-title>
                </v-card>
            </v-col>
        </v-row>
    </div>

</template>

<script>
export default {
name: "foodmenu",
data:function () {
    return {
        products:'',
        checkboxHotDishes:true,
    }
},
    methods:{
        getItemsMenu(){
            axios.get("api/products").then((response) =>{
                this.products = response.data.data
            })
        }
    },
    created() {
       /* this.getItemsMenu()*/
    }
}
</script>

<style scoped>

</style>
