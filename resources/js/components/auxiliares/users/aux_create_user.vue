<template>
    <div>

        <aux_snackbar :text="text" :snackbar="snackbar" :color="color"/>
        <v-dialog v-model="display" max-width="700px">
            <v-card style="margin-top: 10px">
                <v-toolbar>
                    <v-toolbar-title>
                        Create New Employee
                    </v-toolbar-title>
                </v-toolbar>
                <v-card-text style="margin-top: 20px">
                    <validation-observer ref="observer" v-slot="{ invalid }">
                        <form @submit="submit" enctype="multipart/form-data">
                            <validation-provider v-slot="{ errors }" name="Name" rules="required|alpha_spaces|max:50">
                                <v-text-field
                                    v-model="name"
                                    :error-messages="errors"
                                    label="Name"
                                ></v-text-field>
                            </validation-provider>
                            <validation-provider v-slot="{ errors }" name="Email" rules="required|email">
                                <v-text-field
                                    v-model="email"
                                    :error-messages="errors"
                                    label="Email"
                                ></v-text-field>
                            </validation-provider>
                            <validation-provider v-slot="{ errors }" name="Password" rules="required|password:@Password Confirmation|min:3">
                                <v-text-field
                                    label="Password:"
                                    v-model="password"
                                    :error-messages="errors"
                                    type="password">
                                </v-text-field>
                            </validation-provider>
                            <validation-provider v-slot="{ errors }" name="Password Confirmation" rules="required|min:3">
                                <v-text-field
                                    label="Password Confirmation:"
                                    v-model="password_confirmation"
                                    :error-messages=" errors"
                                    type="password">
                                </v-text-field>
                            </validation-provider>
                            <v-select
                                v-model="type"
                                :items="users_types"
                                item-text="name"
                                item-value="type_value"
                                label="Select User Type"
                                single-line
                            ></v-select>
                            <v-file-input
                                counter
                                accept="image/png, image/jpeg"
                                label="Pick a photo:"
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
    </div>
</template>
<script>


import {ValidationObserver, ValidationProvider} from "vee-validate";
import aux_snackbar from "../aux_snackbar";
import {min} from "../../../validations/vee-validate"

export default {
    name: "aux_create_user",
    components: {
        ValidationObserver: ValidationObserver,
        ValidationProvider: ValidationProvider,
        aux_snackbar
    },
    data: () => {
        return {
            // ---- SNACKBAR INFO -----
            color: '',
            snackbar: false,
            text: '',
            // ------------------------

            display: false,
            name:'',
            email:'',
            password:'',
            password_confirmation:'',
            file: null,
            type:'',
            users_types: [
                {name: 'Cooker', type_value: 'EC'},
                {name: 'Deliveryman', type_value: 'ED'},
                {name: 'Manager', type_value: 'EM'},
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
        onFileChange(e){
            console.log(e);
            this.file = e;
        },
        gatherFormData(){
            let data = new FormData();
            data.append("name",this.name)
            data.append("email",this.email)
            data.append("password",this.password)
            data.append("password_confirmation",this.password_confirmation)
            data.append("photo_url",this.file)
            data.append("type",this.type)
            return data;
        },
        submit(e) {
            e.preventDefault();
            if (this.$refs.observer.validate()) {
                axios.get('api/users/emailavailable?email='+this.email)
                .then((response) => {
                    if (response.data === false) {
                        this.color = 'red';
                        this.text = "Email's already taken."
                        this.snackbar = true;
                        setTimeout(() => {
                            this.snackbar = false;
                        }, 2000);
                    } else {
                        let data = this.gatherFormData();
                        const config = {
                            headers: { 'content-type': 'multipart/form-data' }
                        }

                        axios.post("api/users", data, config)
                            .then(() => {
                                this.color = 'success';
                                this.text = "New employee created successfully."
                                this.snackbar = true;
                                this.resolve(true);
                                this.display = false;
                                setTimeout(() => {
                                    this.snackbar = false;
                                }, 2000);
                            })
                            .catch(() => {
                                this.color = 'red';
                                this.text = "Error creating user."
                                this.snackbar = true;
                                setTimeout(() => {
                                    this.snackbar = false;
                                }, 2000);
                            })
                    }
                })
                .catch(()=>{
                    this.color = 'red';
                    this.text = "Something went wrong."
                    this.snackbar = true;
                    setTimeout(() => {
                        this.snackbar = false;
                    }, 2000);
                })
            }
        },
        handleFileObject() {
            this.avatar = this.$refs.file.files[0]
            this.avatarName = this.avatar.name
        },
    },
    created() {
    },
}
</script>

<style scoped>

</style>
