<template>
    <div>
        <aux_snackbar :text="text" :snackbar="snackbar" :color="color"/>
        <aux_edit_password ref="editPassword" :user="user"/>
        <aux_edit_profile ref="editProfile" />
        <aux_edit_photo ref="editPhoto"/>
        <v-row>
            <v-col md="12">
                <v-card>
                    <v-toolbar>{{ vcardTitle }}
                    <v-spacer></v-spacer>
                    <v-btn x-small @click="editProfile">Edit Profile</v-btn>
                    <v-btn x-small @click="editPassword">Change Password</v-btn>
                    </v-toolbar>
                    <v-card-text>
                        <div justify="center" align="center">
                            <v-img v-if="user.photo_url !== null" :src="'/storage/fotos/'+user.photo_url" max-height="200px" max-width="200px" style="border-radius: 50%;"/>
                            <v-img v-else :src="'/images/user_no_photo.png'" max-height="200px" max-width="200px" style="border-radius: 50%;"/>
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on"  icon @click="editPhoto" >
                                        <v-icon>mdi-pencil</v-icon>
                                    </v-btn>
                                </template>
                                <span>Edit profile picture</span>
                            </v-tooltip>
                        </div>
                        <p><b>Name:</b> {{ user.name }}</p>
                        <p><b>Email:</b> {{ user.email }}</p>
                        <p><b>Logged Since:</b> {{ user.logged_at }}</p>
                        <p><b>Status:</b> {{ user.status }}</p>
                        <div v-if="this.user.type === 'C'">
                            <p><b>Address:</b> {{ client.address }}</p>
                            <p><b>Phone:</b> {{ client.phone }}</p>
                            <p><b>NIF:</b> {{ client.nif }}</p>
                        </div>
                    </v-card-text>
                </v-card>

            </v-col>
        </v-row>
    </div>
</template>

<script>
import {ValidationObserver, ValidationProvider} from "vee-validate";
import aux_edit_password from "../auxiliares/users/aux_edit_password";
import aux_edit_profile from "../auxiliares/users/aux_edit_profile";
import aux_edit_photo from "../auxiliares/users/aux_edit_photo";
import Aux_snackbar from "../auxiliares/aux_snackbar";

export default {
    name: "index",
    data: () => {
        return {
            user: '',
            client: '',
            dialog_edit_user: false,
            dialog_password: false,
            vcardTitle: '',


            // Dados para editar password:
            name: '',
            email: '',
            address: '',
            nif: '',
            phone: '',
            // ---- SNACKBAR INFO -----
            color: '',
            snackbar: false,
            text: '',
            // ------------------------
        }
    },
    methods: {
        getUser() {
            //this.user = response.data.data
            this.user = this.$store.state.user
            console.log(this.user)
            this.email = this.user.email;
            this.name = this.user.name;
            if (this.user.type === "C") {
                axios.get('api/customers/' + this.user.id).then((response) => {
                    this.client = response.data.data;
                    this.nif = this.client.nif;
                    this.phone = this.client.phone;
                    this.address = this.client.address;
                })
                this.vcardTitle = "Client's Personal Data";
            } else {
                this.vcardTitle = "Employee's Personal Data";
            }

        },
        async editPassword() {
            if (
                await this.$refs.editPassword.open()
            ) {
                this.getUser()
            }
        },
        async editPhoto() {
            if (
                await this.$refs.editPhoto.open(this.user)
            ) {
                this.getUser()
            }
        },
        async editProfile() {
            if (
                await this.$refs.editProfile.open(this.user, this.client)
            ) {
                this.getUser()
            }
        },

    },
    created() {
        this.getUser();
    },
    components: {
        Aux_snackbar,
        ValidationProvider,
        ValidationObserver,
        aux_edit_photo,
        aux_edit_password,
        aux_edit_profile,
    },
}
</script>

<style scoped>

</style>
