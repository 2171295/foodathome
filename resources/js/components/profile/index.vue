<template>
    <div>
        <v-snackbar v-model="snackbar" :bottom="y === 'bottom'" :color="color" :left="x === 'left'" :multi-line="mode === 'multi-line'" :timeout="timeout" :top="y === 'top'" :vertical="mode === 'vertical'">
            {{ text }}
            <v-btn dark text @click="snackbar = false">
                <v-icon>mdi-close</v-icon>
            </v-btn>
        </v-snackbar>
        <aux_edit_password ref="editPassword"/>
        <v-dialog v-model="dialog_edit_user">
            <v-card>
                <v-card-title class="headline">
                    Edit User's Personal Data
                </v-card-title>
                <v-card-text>
                    <validation-observer ref="observer" v-slot="{ invalid }">
                        <form @submit.prevent="updateUser">
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
                            <div v-if="user.type === 'C'">
                                <validation-provider v-slot="{ errors }" name="Address" rules="">
                                    <v-text-field
                                        v-model="address"
                                        :error-messages="errors"
                                        label="Address"
                                        required
                                    ></v-text-field>
                                </validation-provider>
                                <validation-provider v-slot="{ errors }" name="NIF" rules="numeric|length:9">
                                    <v-text-field
                                        v-model="nif"
                                        :error-messages="errors"
                                        label="NIF"
                                        required
                                    ></v-text-field>
                                </validation-provider>
                                <!--TODO adicionar validação personalizada para o phone-->
                                <validation-provider v-slot="{ errors }" name="Phone" rules="">
                                    <v-text-field
                                        v-model="phone"
                                        :error-messages="errors"
                                        label="Phone"
                                        required
                                    ></v-text-field>
                                </validation-provider>
                            </div>
                            <v-btn class="mr-4" type="submit" :disabled="invalid">
                                Save
                            </v-btn>
                            <v-btn class="mr-4" @click="closeDialogEditUser">
                                Cancel
                            </v-btn>
                        </form>
                    </validation-observer>
                </v-card-text>
            </v-card>
        </v-dialog>
        {{user}}
        <v-row>
            <v-col md="12">
                <v-card>
                    <v-toolbar>{{ vcardTitle }}
                    <v-spacer></v-spacer>
                    <v-btn x-small @click="dialog_edit_user = true">Edit Profile</v-btn>
                    <v-btn x-small @click="editPassword">Change Password</v-btn>
                    </v-toolbar>
                    <v-card-text>
                        <div justify="center" align="center">
                            <v-img :src="'/storage/fotos/'+user.photo_url" max-height="200px" max-width="200px" style="border-radius: 50%;"/>
                        </div>
                        <p><b>Name:</b> {{user.name}}</p>
                        <p><b>Email:</b> {{user.email}}</p>
                        <div v-if="this.user.type === 'C'">
                            <p><b>Address:</b> {{client.address}}</p>
                            <p><b>Phone:</b> {{client.phone}}</p>
                            <p><b>NIF:</b> {{client.nif}}</p>
                        </div>

                    </v-card-text>

                </v-card>

            </v-col>
        </v-row>
    </div>
</template>

<script>
import {ValidationObserver, ValidationProvider} from "vee-validate";
import aux_edit_password from "../auxiliares/aux_edit_password";
export default {
name: "index",
    data: () => {
        return {
            user:'',
            client:'',
            dialog_edit_user:false,
            dialog_password:false,


            // Dados para editar password:
            name:'',
            email:'',
            address:'',
            nif:'',
            phone:'',
            // ---- SNACKBAR INFO -----
            color: '',
            snackbar: false,
            text: '',
            mode: '',
            timeout: 2000,
            x: null,
            y: 'top',
            // ------------------------
        }
    },
    methods:{
        resetDialogInfo(){
            this.email = this.user.email;
            this.name = this.user.name;
        },
        closeDialogEditUser(){
            this.dialog_edit_user = false;
            this.resetDialogInfo();
        },
        getUser(){
            axios.get('api/users/me').then((response) => {
                console.log(response.data.data)
                this.user = response.data.data
                this.email = this.user.email;
                this.name = this.user.name;
                if(this.user.type === "C"){
                    axios.get('api/customers/'+this.user.id).then((response) => {
                        this.client = response.data.data;
                        this.nif = this.client.nif;
                        this.phone = this.client.phone;
                        this.address = this.client.address;
                    })
                    this.vcardTitle  = "Client's Personal Data";
                }else{
                    this.vcardTitle  = "Employee's Personal Data";
                }
            })
        },
        async editPassword() {
            if (
                await this.$refs.editPassword.open()
            ) {
                this.getUser()
            }
        },
        updateUser(){
            if (this.$refs.observer.validate()) {
                if(this.user.type == 'C'){
                    if(this.email === this.user.email){
                      // Updates the rest of the customer's fields
                        axios.put('api/customers/'+this.user.id, {
                            "name":this.name,
                            "nif":this.nif,
                            "phone":this.phone,
                            "address":this.address,
                        }).then((response) => {
                            this.getUser();
                            this.color = 'success';
                            this.text = "customer's personal data edited successfully."
                            this.snackbar = true;
                            this.closeDialogEditUser();
                        }).catch(error =>{
                            this.color = 'red';
                            this.text = "Error updating customer's personal data."
                            this.snackbar = true;
                        })
                    }else{
                        axios.get('api/users/emailavailable?email='+this.email).then((response) => {
                            if (response.data === false) {
                                this.color = 'red';
                                this.text = "Email's already taken."
                                this.snackbar = true;
                                return;
                            } else {
                                axios.put('api/customers/' + this.user.id, {
                                    "name": this.name,
                                    "email": this.email,
                                    "nif":this.nif,
                                    "phone":this.phone,
                                    "address":this.address,
                                    }
                                ).then((response) => {
                                    this.getUser();
                                    this.color = 'success';
                                    this.text = "Customer's personal data edited successfully."
                                    this.snackbar = true;
                                    this.closeDialogEditUser();
                                }).catch(error => {
                                    this.color = 'red';
                                    this.text = "Error updating customer's personal data."
                                    this.snackbar = true;
                                })
                            }
                        })
                    }
                }else{
                    if(this.email === this.user.email){
                        axios.put('api/users/'+this.user.id, {
                                "name":this.name,
                            }
                        ).then((response) => {
                            this.getUser();
                            this.color = 'success';
                            this.text = "User's personal data edited successfuly."
                            this.snackbar = true;
                            this.closeDialogEditUser();
                        }).catch(error =>{
                            this.color = 'red';
                            this.text = "Error updating user's personal data."
                            this.snackbar = true;
                        })
                    }else{
                        //New email validation:
                        axios.get('api/users/emailavailable?email='+this.email).then((response) => {
                            if(response.data === false){
                                this.color = 'red';
                                this.text = "Email's already taken."
                                this.snackbar = true;
                                return;
                            }else{
                                axios.put('api/users/'+this.user.id, {
                                        "name":this.name,
                                        "email":this.email,
                                    }
                                ).then((response) => {
                                    this.getUser();
                                    this.color = 'success';
                                    this.text = "User's personal data edited successfuly."
                                    this.snackbar = true;
                                    this.closeDialogEditUser();
                                }).catch(error =>{
                                    this.color = 'red';
                                    this.text = "Error updating user's personal data."
                                    this.snackbar = true;
                                })
                            }
                        })
                    }
                }

                //TODO validar se o email, caso seja alterado, está disponivel


            }
        },
        updatePassword(){

        }
    },
    created(){
        this.getUser();
    },
    components: {
        ValidationProvider,
        ValidationObserver,
        aux_edit_password,
    },
}
</script>

<style scoped>

</style>
