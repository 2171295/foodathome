<template>
    <v-container>
        <aux_snackbar :text="text" :snackbar="snackbar" :color="color"/>
        <v-dialog v-model="display" max-width="700px">
            <v-card style="margin-top: 10px">
                <v-toolbar>
                    <v-toolbar-title>
                        Create New Product
                    </v-toolbar-title>
                </v-toolbar>
                <v-card-text style="margin-top: 20px">
                    <validation-observer ref="observer" v-slot="{ invalid }">
                        <form @submit.prevent="submit">
                            <validation-provider v-slot="{ errors }" name="Name" rules="required|max:50">
                                <v-text-field
                                    v-model="name"
                                    :error-messages="errors"
                                    label="Name"
                                ></v-text-field>
                            </validation-provider>
                            <validation-provider v-slot="{ errors }" name="Price" rules="required">
                                <v-text-field
                                    v-model.number="price"
                                    :error-messages="errors"
                                    label="Price"
                                ></v-text-field>
                            </validation-provider>
                            <validation-provider v-slot="{ errors }" name="Description" rules="required">
                                <v-text-field
                                    v-model="description"
                                    :error-messages="errors"
                                    label="Description"
                                ></v-text-field>
                            </validation-provider>
                            <v-select
                                v-model="type"
                                :items="product_types"
                                item-text="name"
                                item-value="type_value"
                                label="Select Product Type"
                                single-line
                            ></v-select>
                            <!--                            <v-file-input-->
                            <!--                                counter-->
                            <!--                                accept="image/png, image/jpeg"-->
                            <!--                                label="Pick a photo:"-->
                            <!--                                v-model="photo_url"-->
                            <!--                                :rules="rulesPhoto"-->
                            <!--                                prepend-icon="mdi-camera"-->
                            <!--                            ></v-file-input>-->
                            <v-btn class="mr-4" type="submit" :disabled="invalid">
                                Submit
                            </v-btn>
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
    name: "aux_create_product",
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
            name: '',
            type: '',
            description: '',
            price: '',
            photo: null,

            product_types: [
                {name: 'Drink', type_value: 'drink'},
                {name: 'Hot Dish', type_value: 'hot dish'},
                {name: 'Dessert', type_value: 'dessert'},
                {name: 'Cold Dish', type_value: 'cold dish'},
            ],
        }
    },
    methods: {
        open() {
            this.display = true;
            this.valid = true
            return new Promise((resolve, reject) => {
                this.resolve = resolve;
                this.reject = reject;
            });
        },
        cancel() {
            this.resolve(false);
            this.display = false;
        },
        submit() {
            if (this.$refs.observer.validate()) {
                axios.post('api/products/',{
                    name: this.name,
                    type: this.type,
                    price: this.price,
                    description: this.description,
                    photo_url: this.photo,
                }) .then(() => {
                    this.color = 'success';
                    this.text = "Product successfully created."
                    this.snackbar = true;
                    this.resolve(true);
                    this.display = false;
                    setTimeout(() => {
                        this.snackbar = false;
                    }, 2000);
                })
                    .catch(() => {
                        this.color = 'red';
                        this.text = "Error creating product."
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
