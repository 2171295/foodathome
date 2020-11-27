<template>
    <v-container>
        <v-snackbar v-model="snackbar" :bottom="y === 'bottom'" :color="color" :left="x === 'left'" :multi-line="mode === 'multi-line'" :timeout="timeout" :top="y === 'top'" :vertical="mode === 'vertical'">
            {{ text }}
            <v-btn dark text @click="snackbar = false">
                <v-icon>{{iconClose}}</v-icon>
            </v-btn>
        </v-snackbar>
        <!--TODO refefinir as validações deste formulário-->
        <p class="subtitle-1 text-center">Register</p>
        <validation-observer ref="observer" v-slot="{ invalid }">
            <form @submit.prevent="submit">
                <validation-provider v-slot="{ errors }" name="Name" rules="required|alpha_spaces">
                    <v-text-field
                        v-model="name"
                        :counter="10"
                        :error-messages="errors"
                        label="Name"
                        required
                    ></v-text-field>
                </validation-provider>
                <validation-provider v-slot="{ errors }" name="Email" rules="required|email">
                    <v-text-field
                        v-model="email"
                        :counter="10"
                        :error-messages="errors"
                        label="Email"
                        required
                    ></v-text-field>
                </validation-provider>
                <validation-provider v-slot="{ errors }" name="Address" rules="required">
                    <v-text-field
                        v-model="address"
                        :counter="10"
                        :error-messages="errors"
                        label="Address"
                        required
                    ></v-text-field>
                </validation-provider>
                <validation-provider v-slot="{ errors }" name="Phone" rules="required|numeric">
                    <v-text-field
                        v-model="phone"
                        :counter="10"
                        :error-messages="errors"
                        label="Phone"
                        required
                    ></v-text-field>
                </validation-provider>
                <validation-provider v-slot="{ errors }" name="Nif" rules="numeric|length:9">
                    <v-text-field
                        v-model="nif"
                        :counter="10"
                        :error-messages="errors"
                        label="NIF"
                    ></v-text-field>
                </validation-provider>
                <!--<v-file-input
                    counter
                    accept="image/png, image/jpeg"
                    label="Pick a photo:"
                    v-model="photo"
                    :rules="rulesPhoto"
                    :prepend-icon="iconCamera"
                ></v-file-input>-->

                <v-btn class="mr-4" type="submit" :disabled="invalid">
                    Submit
                </v-btn>
            </form>
        </validation-observer>
    </v-container>
</template>

<script>
import { required, email, max, alpha_spaces, numeric, length} from 'vee-validate/dist/rules'
import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'
import {mdiCamera, mdiClose} from "@mdi/js";

setInteractionMode('eager')

extend('required', {
    ...required,
    message: '{_field_} can not be empty',
})
extend('max', {
    ...max,
    message: '{_field_} may not be greater than {length} characters',
})
extend('alpha_spaces', {
    ...alpha_spaces,
    message: '{_field_} may only be letters and spaces',
})
extend('email', {
    ...email,
    message: '{_field_} is not valid',
})
extend('numeric', {
    ...numeric,
    message: '{_field_} must contain only numbers',
})
extend('length', {
    ...length,
    message: '{_field_} must contain {length} numbers',
})

export default {

    name: "index",
    data: () => ({
    valid: true,
    // ---- SNACKBAR INFO -----
    color: '',
    mode: '',
    snackbar: false,
    text: '',
    timeout: 4000,
    x: null,
    y: 'top',
    // ---- FORM DATA -----
    name:'',
    email:'',
    address:'',
    phone:'',
    nif:'',
    photo:'',
    rulesPhoto: [
        v => !v || v.size < 2000000 || 'Avatar size should be less than 2 MB!',
    ],
    iconCamera: mdiCamera,
    iconClose: mdiClose,

    }),

    methods:{
        submit: function () {
            if (this.$refs.observer.validate()) {
                axios.post("/api/users/", {
                    "name":this.name,
                    "email":this.email,
                    "address":this.address,
                    "phone":this.phone,
                    "nif":this.nif,
                    "photo_url":this.photo})
                    .then(response => {
                    console.log(response)
                })
            }
        }
    },
    components: {
        ValidationProvider,
        ValidationObserver,
    },

}
</script>

<style scoped>

</style>
