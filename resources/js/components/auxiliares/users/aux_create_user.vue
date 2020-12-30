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
                        <form @submit.prevent="submit">
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
<!--                            <v-file-input-->
<!--                                counter-->
<!--                                accept="image/png, image/jpeg"-->
<!--                                label="Pick a photo:"-->
<!--                                v-model="photo_url"-->
<!--                                :rules="rulesPhoto"-->
<!--                                prepend-icon="mdi-camera"-->
<!--                            ></v-file-input>-->
                            <input type="file" class="custom-file-input" id="customFile" ref="file" @change="handleFileObject()">
                            <v-btn class="mr-4" type="submit" :disabled="invalid">
                                Submit
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
            avatar: null,
            avatarName: null,
            type:'',
            users_types: [
                {name: 'Cooker', type_value: 'EC'},
                {name: 'Deliveryman', type_value: 'ED'},
                {name: 'Manager', type_value: 'EM'},
            ],
            rulesPhoto: [
                v => !v || v.size < 2000000 || 'Avatar size should be less than 2 MB!',
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
                        let data = new FormData();
                        data.append("name",this.name)
                        data.append("email",this.email)
                        data.append("password",this.password)
                        data.append("password_confirmation",this.password_confirmation)
                        data.append("photo_url",this.avatar)
                        data.append("type",this.type)
                        console.log(data)
                        axios.post("api/users", data, {
                            headers: {
                                'Content-Type': "multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2)
                            }
                        })
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
