<template>
    <div>
        <v-toolbar class="d-flex justify-center align-center" style="margin-bottom: 20px;">
            <v-toolbar-title>Products</v-toolbar-title>
        </v-toolbar>
        <v-card>
            <v-card-text>
                <v-data-table
                    :headers="headers"
                    :items="products"
                    :items-per-page="10"
                    class="elevation-1"
                >
                    <template v-slot:item.img="{ item }">
                        <v-img :src="'/storage/products/'+item.photo" width="130px" height="130px" style="border-radius: 50%"/>
                    </template>
                </v-data-table>

            </v-card-text>
        </v-card>
        {{products}}
    </div>
</template>

<script>
export default {
    name: "index",
    data: () => {
        return {
            products: [],
            headers: [
                {text: 'Photo', value: 'img'},
                {text: 'Name', align: 'start', sortable: true, value: 'name',},
                {text: 'Type', align: 'start', sortable: true, value: 'type',},
                {text: 'Price', sortable: true, value: 'price'},
                {text: 'Description', sortable: false, value: 'description'},
            ],
        }
    },
    methods: {
        getProducts(){
            axios.get("api/products")
            .then((response) => {
                this.products = response.data.data
            })
        }
    },
    created(){
        this.getProducts()
    }
}
</script>

<style scoped>

</style>
