<template>
    <div>
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
                            <validation-provider>
                                <v-text-field label="New password" :error-messages="error_new"  v-model="new_password" type="password"></v-text-field>
                            </validation-provider>
                            <validation-provider v-slot="{ errors }" name="Name" rules="required">
                                <v-text-field label="Confirmation password" :error-messages="error_confirmation" v-model="confirmation_password" type="password"></v-text-field>
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
export default {
name: "aux_edit_profile",
data: () => {
    return {
        display: false,
        new_password:'',
        old_password:'',
        vcardTitle:'',
        error_old:'',
        error_new:'',
        error_confirmation:'',
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
            axios.put('api/users/'+this.$store.state.user.id+'/confirmpassword', {
                oldPassword: this.old_password
            }).then((response)=>{
                console.log(response)
            }).catch((error)=>{

            })

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
