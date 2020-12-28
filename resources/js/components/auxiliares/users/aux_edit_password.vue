<template>
    <div>
        <aux_snackbar :text="text" :snackbar="snackbar" :color="color"/>
        <v-dialog v-model="display" max-width="490">
            <v-card>
                <v-card-title class="headline">
                    Change password
                </v-card-title>
                <v-card-text>
                    <validation-observer ref="observer" v-slot="{ invalid }">
                        <form @submit.prevent="submit">
                            <validation-provider v-slot="{ errors }" name="Old Password" rules="required">
                                <v-text-field label="Old password" :error-messages="error_old" v-model="old_password" type="password"></v-text-field>
                            </validation-provider>
                            <validation-provider v-slot="{ errors }" name="Old Password" rules="required|password:@confirm">
                                <v-text-field
                                    label="New password"
                                  :error-messages="errors"
                                    v-model="new_password"
                                    type="password"></v-text-field>
                            </validation-provider>
                            <validation-provider v-slot="{ errors }" name="confirm" rules="required">
                                <v-text-field label="Confirmation password" :error-messages="errors" v-model="confirmation_password" type="password"></v-text-field>
                            </validation-provider>
                            <v-spacer></v-spacer>
                            <v-btn color="error" text @click="display = false">
                                Cancel
                            </v-btn>
                            <v-btn color="success" text  @click="submit" :disabled="invalid">
                                Save
                            </v-btn>
                        </form>
                    </validation-observer>
                </v-card-text>
                <v-card-actions>

                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import {ValidationObserver, ValidationProvider} from "vee-validate";
import {password} from "../../../validations/vee-validate"
import aux_snackbar from "../aux_snackbar";
export default {
    name: "aux_edit_profile",
    props:['user'],
data: () => {
    return {
        display: false,
        new_password:'',
        old_password:'',
        confirmation_password:'',
        vcardTitle:'',
        error_old:'',
        // ---- SNACKBAR INFO -----
        color: '',
        snackbar: false,
        text: '',
        // ------------------------
    }
},methods:{
        open() {
            this.display = true;
            this.valid = true
            return new Promise((resolve, reject) => {
                this.resolve = resolve;
                this.reject = reject;
            });
        },
        cancel(){
            this.resolve(false);
            this.display = false;
        },
        submit() {
            axios.put('api/users/'+this.$store.state.user.id+'/confirm_password', {
                oldPassword: this.old_password
            }).then((response)=>{
                if (response.status === 200){
                    this.error_old=null;
                    /*TODO fazer a alteraçaõ da password*/
                    axios.put('api/users/'+this.user.id+'/update_password', {
                        "oldPassword":this.old_password,
                        "newPassword": this.new_password,
                        "confirmationPassword":this.confirmation_password
                        }
                    ).then((response) => {
                        this.color = 'success';
                        this.text = "Password changed successfully."
                        this.snackbar = true;
                        this.resolve(true);
                        this.display = false;
                        setTimeout(() => {
                            this.snackbar = false;
                        }, 2000);
                    }).catch(error =>{
                        this.color = 'red';
                        this.text = "Error changing password"
                        this.snackbar = true;
                        setTimeout(() => {
                            this.snackbar = false;
                        }, 2000);
                    })
                }
            }).catch((error)=>{
                this.error_old="Old password doesn't match."
            })
        }
},
    components: {
        ValidationProvider,
        ValidationObserver,
        aux_snackbar
    },
}
</script>

<style scoped>

</style>
