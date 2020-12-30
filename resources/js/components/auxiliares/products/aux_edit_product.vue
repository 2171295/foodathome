<template>
    <v-container>
        <aux_snackbar :text="text" :snackbar="snackbar" :color="color"/>
        <v-dialog v-model="display" max-width="700px">
            <v-card style="margin-top: 10px">
                <v-toolbar>
                    <v-toolbar-title>
                        Edit Product
                    </v-toolbar-title>
                </v-toolbar>
                <v-card-text style="margin-top: 20px">
                    <validation-observer ref="observer" v-slot="{ invalid }">
                        <form @submit="submit" enctype="multipart/form-data">
                            <validation-provider v-slot="{ errors }" name="Name" rules="required|max:50">
                                <v-text-field
                                    v-model="aux_product.name"
                                    :error-messages="errors"
                                    label="Name"
                                ></v-text-field>
                            </validation-provider>
                            <validation-provider v-slot="{ errors }" name="Price" rules="required">
                                <v-text-field
                                    v-model.number="aux_product.price"
                                    :error-messages="errors"
                                    label="Price"
                                ></v-text-field>
                            </validation-provider>
                            <validation-provider v-slot="{ errors }" name="Description" rules="required">
                                <v-text-field
                                    v-model="aux_product.description"
                                    :error-messages="errors"
                                    label="Price"
                                ></v-text-field>
                            </validation-provider>
                            <v-select
                                v-model="aux_product.type"
                                :value="aux_product.type"
                                :items="product_types"
                                item-text="name"
                                item-value="type_value"
                                label="Select Product Type"
                                single-line
                            ></v-select>
                            <v-img :src="'/storage/products/'+aux_product.photo" max-height="200px" max-width="200px" style="border-radius: 50%;"/>
                            <v-file-input
                                counter
                                accept="image/png, image/jpeg"
                                label="Pick a new photo:"
                                v-model="file"
                                prepend-icon="mdi-camera"
                                v-on:change="onFileChange"
                            />
                            <div>
                                <v-btn class="mr-4" type="submit" :disabled="invalid">
                                    Submit
                                </v-btn>
                                <v-btn class="mr-4" @click="cancel">
                                    Cancel
                                </v-btn>
                            </div>
                        </form>
                    </validation-observer>
                </v-card-text>
            </v-card>
        </v-dialog>

    </v-container>
</template>

<script>

import {ValidationObserver, ValidationProvider} from "vee-validate";
import Aux_snackbar from "../aux_snackbar";

export default {
    name: "aux_edit_product",
    components: {
        Aux_snackbar,
        ValidationProvider,
        ValidationObserver,
    },
    data: () => {
        return {
            // ---- SNACKBAR INFO -----
            color: '',
            snackbar: false,
            text: '',
            // ------------------------

            display: false,
            aux_product: '',
            file: null,

            product_types: [
                {name: 'Drink', type_value: 'drink'},
                {name: 'Hot Dish', type_value: 'hot dish'},
                {name: 'Dessert', type_value: 'dessert'},
                {name: 'Cold Dish', type_value: 'cold dish'},
            ],
        }
    },
    methods: {
        open(item) {
            this.display = true;
            this.valid = true
            this.aux_product = JSON.parse(JSON.stringify(item))
            return new Promise((resolve, reject) => {
                this.resolve = resolve;
                this.reject = reject;
            });
        },
        cancel() {
            this.resolve(false);
            this.display = false;
        },
        onFileChange(e){
            console.log(e);
            this.file = e;
        },
        gatherFormData(){
            let data = new FormData();
            data.append("name",this.aux_product.name)
            data.append("type",this.aux_product.type)
            data.append("price",this.aux_product.price)
            data.append("description",this.aux_product.description)
            data.append("photo_url",this.file)
            return data;
        },
        submit() {
            if (this.$refs.observer.validate()) {
                axios.put('api/products/'+this.aux_product.id,{
                    id: this.aux_product.id,
                    name: this.aux_product.name,
                    type: this.aux_product.type,
                    price: this.aux_product.price,
                    description: this.aux_product.description,
                    photo_url: this.aux_product.photo,

                }) .then(() => {
                    this.color = 'success';
                    this.text = "Product successfully updated."
                    this.snackbar = true;
                    this.resolve(true);
                    this.display = false;
                    setTimeout(() => {
                        this.snackbar = false;
                    }, 2000);
                })
                    .catch(() => {
                        this.color = 'red';
                        this.text = "Error updating product."
                        this.snackbar = true;
                        setTimeout(() => {
                            this.snackbar = false;
                        }, 2000);
                    })
            }
        },
    },
}
</script>

<style scoped>

</style>
