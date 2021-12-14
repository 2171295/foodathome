<template>
    <v-container>
        <v-snackbar v-model="snackbar" :bottom="y === 'bottom'" :color="color" :left="x === 'left'" :multi-line="mode === 'multi-line'" :timeout="timeout" :top="y === 'top'" :vertical="mode === 'vertical'">
            {{ text }}
            <v-btn dark text @click="snackbar = false">
                <v-icon>{{iconClose}}</v-icon>
            </v-btn>
        </v-snackbar>
        <p class="subtitle-1 text-center">Register</p>
        <validation-observer ref="observer" v-slot="{ invalid }">
            <form @submit="submit" enctype="multipart/form-data">
                <validation-provider v-slot="{ errors }" name="Name" rules="required|alpha_spaces">
                    <v-text-field
                        v-model="name"
                        :error-messages="errors"
                        label="Name"
                        required
                    ></v-text-field>
                </validation-provider>
                <validation-provider v-slot="{ errors }" name="Email" rules="required|email">
                    <v-text-field
                        v-model="email"
                        :error-messages="errors"
                        label="Email"
                        required
                    ></v-text-field>
                </validation-provider>
                <validation-provider v-slot="{ errors }" name="Password" rules="required|password:@confirmation">
                    <v-text-field
                        v-model="password"
                        :error-messages="errors"
                        label="Password"
                        type="password"
                        required
                    ></v-text-field>
                </validation-provider>
                <validation-provider v-slot="{ errors }" name="confirmation" rules="required">
                    <v-text-field
                        v-model="confirmationPassword"
                        :error-messages="errors"
                        label="Password Confirmation"
                        type="password"
                        required
                    ></v-text-field>
                </validation-provider>
                <validation-provider v-slot="{ errors }" name="Address" rules="required">
                    <v-text-field
                        v-model="address"
                        :error-messages="errors"
                        label="Address"
                        required
                    ></v-text-field>
                </validation-provider>
                <validation-provider v-slot="{ errors }" name="Phone" rules="required|numeric|length:9">
                    <v-text-field
                        v-model="phone"
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
                <v-file-input
                    counter
                    accept="image/png, image/jpeg"
                    label="Pick a photo:"
                    v-model="file"
                    prepend-icon="mdi-camera"
                    v-on:change="onFileChange"
                />

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
        name: '',
        email: '',
        address: '',
        phone: '',
        nif: null,
        file: null,
        password: '',
        confirmationPassword: '',
        rulesPhoto: [
            v => !v || v.size < 2000000 || 'Avatar size should be less than 2 MB!',
        ],
        iconCamera: mdiCamera,
        iconClose: mdiClose,
    }),
    methods: {
        submit(e) {
            e.preventDefault();
            if (this.$refs.observer.validate()) {
                let data = this.gatherFormData();
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }
                axios.post("/api/customers", data,config)
                    .then((response) => {
                        this.snackbar = true;
                        this.text = "Account created succesfully."
                        this.color = "green"
                        setTimeout(() => {
                            this.$router.push('/');
                        }, 1500);

                    }).catch(error => {
                    this.snackbar = true;
                    this.text = "There was an error creating your account. Please try again latter."
                    this.color = "red"
                })
            }
        },
        onFileChange(e) {
            console.log(e);
            this.file = e;
        },
        gatherFormData() {
            let data = new FormData();
            data.append("name", this.name)
            data.append("email", this.email)
            data.append("address", this.address)
            data.append("phone", this.phone)
            data.append("nif", this.nif)
            data.append("password", this.password)
            return data;
        },
    },
    components: {
        ValidationProvider,
        ValidationObserver,
    },

}
</script>

<style scoped>

</style>
