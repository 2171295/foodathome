<template>
    <div>
        <aux_snackbar :text="text" :snackbar="snackbar" :color="color"/>
        <v-dialog v-model="display">
            <v-card>
                <v-card-title class="headline">
                    Edit User's Personal Data
                </v-card-title>
                <v-card-text>
                    <validation-observer ref="observer" v-slot="{ invalid }">
                        <form @submit.prevent="updateUser">
                            <validation-provider v-slot="{ errors }" name="Name" rules="required|alpha_spaces">
                                <v-text-field
                                    v-model="aux_user.name"
                                    :error-messages="errors"
                                    label="Name"
                                    required
                                ></v-text-field>
                            </validation-provider>
                            <validation-provider v-slot="{ errors }" name="Email" rules="required|email">
                                <v-text-field
                                    v-model="aux_user.email"
                                    :error-messages="errors"
                                    label="Email"
                                    required
                                ></v-text-field>
                            </validation-provider>
                            <div v-if="user.type === 'C'">
                                <validation-provider v-slot="{ errors }" name="Address" rules="">
                                    <v-text-field
                                        v-model="aux_user.address"
                                        :error-messages="errors"
                                        label="Address"
                                        required
                                    ></v-text-field>
                                </validation-provider>
                                <validation-provider v-slot="{ errors }" name="NIF" rules="numeric|length:9">
                                    <v-text-field
                                        v-model="aux_user.nif"
                                        :error-messages="errors"
                                        label="NIF"
                                        required
                                    ></v-text-field>
                                </validation-provider>
                                <!--TODO adicionar validação personalizada para o phone-->
                                <validation-provider v-slot="{ errors }" name="Phone" rules="">
                                    <v-text-field
                                        v-model="aux_user.phone"
                                        :error-messages="errors"
                                        label="Phone"
                                        required
                                    ></v-text-field>
                                </validation-provider>
                            </div>
                            <v-btn class="mr-4" type="submit" :disabled="invalid">
                                Save
                            </v-btn>
                            <v-btn class="mr-4" @click="cancel">
                                Cancel
                            </v-btn>
                        </form>
                    </validation-observer>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import {ValidationObserver, ValidationProvider} from "vee-validate";
import aux_snackbar from "./aux_snackbar";

export default {
    name: "aux_edit_profile",
    props:['user'],
    data: () => {
        return {
            display: false,
            aux_user:'',

            // ---- SNACKBAR INFO -----
            color: '',
            snackbar: false,
            text: '',
            // ------------------------
        }
    },
    methods:{
        open() {
            this.display = true;
            this.valid = true
            this.aux_user = JSON.parse(JSON.stringify(this.user));
            return new Promise((resolve, reject) => {
                this.resolve = resolve;
                this.reject = reject;
            });
        },
        cancel(){
            this.resolve(false);
            this.display = false;
        },
        updateUser(){
            if (this.$refs.observer.validate()) {
                if(this.user.type == 'C'){
                    if(this.aux_user.email === this.user.email){
                        // Updates the rest of the customer's fields
                        axios.put('api/customers/'+this.user.id, {
                            "name":this.aux_user.name,
                            "nif":this.aux_user.nif,
                            "phone":this.aux_user.phone,
                            "address":this.aux_user.address,
                        }).then((response) => {
                            this.color = 'success';
                            this.text = "customer's personal data edited successfully."
                            this.snackbar = true;
                            setTimeout(() => {
                                this.snackbar = false;
                            }, 2000);
                            this.resolve(true);
                            this.display = false;

                        }).catch(error =>{
                            this.color = 'red';
                            this.text = "Error updating customer's personal data."
                            this.snackbar = true;
                            setTimeout(() => {
                                this.snackbar = false;
                            }, 2000);
                        })
                    }else{
                        axios.get('api/users/emailavailable?email='+this.aux_user.email).then((response) => {
                            if (response.data === false) {
                                this.color = 'red';
                                this.text = "Email's already taken."
                                this.snackbar = true;
                                setTimeout(() => {
                                    this.snackbar = false;
                                }, 2000);
                                return;
                            } else {
                                axios.put('api/customers/' + this.user.id, {
                                        "name": this.aux_user.name,
                                        "email": this.aux_user.email,
                                        "nif":this.aux_user.nif,
                                        "phone":this.aux_user.phone,
                                        "address":this.aux_user.address,
                                    }
                                ).then((response) => {
                                    this.getUser();
                                    this.color = 'success';
                                    this.text = "Customer's personal data edited successfully."
                                    this.snackbar = true;
                                    this.resolve(true);
                                    this.display = false;
                                    setTimeout(() => {
                                        this.snackbar = false;
                                    }, 2000);
                                }).catch(error => {
                                    this.color = 'red';
                                    this.text = "Error updating customer's personal data."
                                    this.snackbar = true;
                                    setTimeout(() => {
                                        this.snackbar = false;
                                    }, 2000);
                                })
                            }
                        })
                    }
                }else{
                    if(this.aux_user.email === this.user.email){
                        axios.put('api/users/'+this.user.id, {
                                "name":this.aux_user.name,
                            }
                        ).then((response) => {
                            this.color = 'success';
                            this.text = "User's personal data edited successfuly."
                            this.snackbar = true;
                            this.resolve(true);
                            this.display = false;
                            setTimeout(() => {
                                this.snackbar = false;
                            }, 2000);
                        }).catch(error =>{
                            this.color = 'red';
                            this.text = "Error updating user's personal data."
                            this.snackbar = true;
                            setTimeout(() => {
                                this.snackbar = false;
                            }, 2000);
                        })
                    }else{
                        //New email validation:
                        axios.get('api/users/emailavailable?email='+this.aux_user.email).then((response) => {
                            if(response.data === false){
                                this.color = 'red';
                                this.text = "Email's already taken."
                                this.snackbar = true;
                                setTimeout(() => {
                                    this.snackbar = false;
                                }, 2000);
                                return;
                            }else{
                                axios.put('api/users/'+this.user.id, {
                                        "name":this.aux_user.name,
                                        "email":this.aux_user.email,
                                    }
                                ).then((response) => {
                                    this.color = 'success';
                                    this.text = "User's personal data edited successfuly."
                                    this.snackbar = true;
                                    this.resolve(true);
                                    this.display = false;
                                    setTimeout(() => {
                                        this.snackbar = false;
                                    }, 2000);
                                }).catch(error =>{
                                    this.color = 'red';
                                    this.text = "Error updating user's personal data."
                                    this.snackbar = true;
                                    setTimeout(() => {
                                        this.snackbar = false;
                                    }, 2000);
                                })
                            }
                        })
                    }
                }
            }
        },
    },
    components: {
        ValidationProvider,
        ValidationObserver,
        aux_snackbar,
    },
}
</script>

<style scoped>

</style>
