<template>
    <v-container>
        <aux_snackbar :text="text" :snackbar="snackbar" :color="color"/>

        <aux_create_user ref="createUser"/>

        <aux_dialog_confirmacao ref="confirm"/>

        <aux_edit_profile ref="editUser" :user="user"/>
        <aux_edit_photo ref="editUserPhoto" :user="user"/>

        <v-toolbar class="d-flex justify-center align-center" style="margin-bottom: 20px;">
            <v-toolbar-title>Users</v-toolbar-title>
        </v-toolbar>
        <v-card>
            <v-toolbar style="margin-bottom: 10px;">
                <v-text-field label="Search:" v-model="search"></v-text-field>
                <v-spacer></v-spacer>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on"  icon @click="createUser()" >
                            <v-icon>mdi-plus</v-icon>
                        </v-btn>
                    </template>
                    <span>Create User</span>
                </v-tooltip>
            </v-toolbar>
            <v-card-text>
                <v-data-table
                    :headers="headers"
                    :items="users"
                    :items-per-page="20"
                    :item-class= "row_classes"
                    class="elevation-1"
                    :search="search"
                >
                    <template v-slot:item.img="{ item }">
                        <v-img v-if="item.photo_url !== null" :src="'/storage/fotos/'+item.photo_url" width="100px" height="100px" style="border-radius: 50%"/>
                        <v-img v-else :src="'/images/user_no_photo.png'" width="100px" height="100px" style="border-radius: 50%"/>
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <div v-if="item.email !== loggedUser.email && item.deleted_at === null">
                            <v-tooltip bottom v-if="item.type !== 'C'">
                                <template v-slot:activator="{ on }" >
                                    <v-btn v-on="on" icon @click="updateUser(item)">
                                        <v-icon>mdi-account-edit</v-icon>
                                    </v-btn>
                                </template>
                                <span>Edit User</span>
                            </v-tooltip>
                            <v-tooltip bottom >
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon @click="updateUserPhoto(item)">
                                        <v-icon>mdi-image-edit</v-icon>
                                    </v-btn>
                                </template>
                                <span>Edit User Image</span>
                            </v-tooltip>
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }" >
                                    <v-btn v-on="on" icon @click="deleteUser(item)">
                                        <v-icon>mdi-delete</v-icon>
                                    </v-btn>
                                </template>
                                <span>Remove User</span>
                            </v-tooltip>
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }" v-if="item.blocked === 'No'">
                                    <v-btn v-on="on" icon @click="blockUser(item)">
                                        <v-icon>mdi-account-cancel</v-icon>
                                    </v-btn>
                                </template>
                                <span>Block User</span>
                            </v-tooltip>
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }" v-if="item.blocked === 'Yes'">
                                    <v-btn v-on="on" icon @click="unblockUser(item)">
                                        <v-icon>mdi-account-check</v-icon>
                                    </v-btn>
                                </template>
                                <span>Unblock User</span>
                            </v-tooltip>
                        </div>
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>
    </v-container>
</template>

<script>
import Aux_dialog_confirmacao from "../auxiliares/aux_dialog_confirmacao";
import Aux_snackbar from "../auxiliares/aux_snackbar";
import Aux_create_user from "../auxiliares/users/aux_create_user";
import Aux_edit_profile from "../auxiliares/users/aux_edit_profile";
import Aux_edit_photo from "../auxiliares/users/aux_edit_photo";

export default {
    name: "index",
    components: {Aux_edit_photo, Aux_edit_profile, Aux_create_user, Aux_snackbar, Aux_dialog_confirmacao},
    data: () => {
        return {
            search:'',
            users: [],
            loggedUser: '',
            headers: [
                {text: '', sortable: false, value: 'img'},
                {text: 'ID', align: 'start', sortable: true, value: 'id',},
                {text: 'Name', align: 'start', sortable: true, value: 'name',},
                {text: 'Status', align: 'start', sortable: true, value: 'status',},
                {text: 'Email', sortable: true, value: 'email'},
                {text: 'Type', align: 'start', sortable: true, value: 'type',},
                {text: 'Blocked', align: 'start', sortable: true, value: 'blocked',},
                {text: 'Actions', align: 'start', sortable: false, value: 'actions',},
            ],

            // ---- SNACKBAR INFO -----
            color: '',
            snackbar: false,
            text: '',
            // ------------------------

            user:'',
        }
    },
    methods: {
        row_classes(item) {
            if(item.email === this.loggedUser.email){
                return "yellow";
            }
            if (item.blocked === 'Yes' && item.deleted_at === null) {
                return "grey";
            }
            if (item.blocked === 'Yes' && item.deleted_at !== null) {
                return "red";
            }
        },
        getUsers(){
            axios.get("api/users/with_trash")
                .then((response) => {
                    this.users = response.data.data
                    this.toStringType()
                    this.loggedUser = this.$store.state.user
                })
        },
        async blockUser(item){
            if(await this.$refs.confirm.open(
                "Block User", "Are you sure you want to block this user?"
            )){
                axios.put("api/users/"+item.id+"/block")
                    .then((response) => {
                        this.$socket.emit('user_list_updated',response.data.data)
                        this.$socket.emit('user_blocked',response.data.data)
                        this.getUsers();
                        this.snackbar = true;
                        this.text = "User successfully blocked."
                        this.color = "green"
                        setTimeout(() => {
                            this.snackbar = false;
                        }, 2000);
                    })
                    .catch(() => {
                        this.snackbar = true;
                        this.text = "Error blocking user."
                        this.color = "error"
                        setTimeout(() => {
                            this.snackbar = false;
                        }, 2000);
                    })
            }else{
                return null;
            }
        },
        async unblockUser(item){
            if(await this.$refs.confirm.open(
                "Unblock User", "Are you sure you want to unblock this user?"
            )) {
                axios.put("api/users/" + item.id + "/unblock")
                    .then((response) => {
                        this.$socket.emit('user_list_updated', response.data.data)
                        this.snackbar = true;
                        this.getUsers();
                        this.text = "User successfully unblocked."
                        this.color = "green"
                        setTimeout(() => {
                            this.snackbar = false;
                        }, 2000);
                    })
                    .catch(() => {
                        this.snackbar = true;
                        this.text = "Error unblocking user."
                        this.color = "error"
                        setTimeout(() => {
                            this.snackbar = false;
                        }, 2000);
                    })
            }else{
                return null;
            }
        },
        async deleteUser(item){
            if(await this.$refs.confirm.open(
                "Remove User", "Are you sure you want to remove this user?"
            )){
                axios.delete("api/users/" + item.id)
                    .then((response) => {
                        this.$socket.emit('user_list_updated', response.data.data)
                        this.snackbar = true;
                        this.getUsers();
                        this.text = "User successfully deleted."
                        this.color = "green"
                        setTimeout(() => {
                            this.snackbar = false;
                        }, 2000);
                    })
                    .catch(() => {
                        this.snackbar = true;
                        this.text = "Error deleting user."
                        this.color = "error"
                        setTimeout(() => {
                            this.snackbar = false;
                        }, 2000);
                    })
            }else{
                return null;s
            }
        },
        async createUser(){
            if (await this.$refs.createUser.open()) {
                this.getUsers()
            }else{
                return null;
            }
        },
        async updateUser(item){
            this.user = item;
            if (await this.$refs.editUser.open(item,'')) {
                this.user = '';
                this.getUsers()
            }else{
                this.user = '';
                return null;
            }
        },
        async updateUserPhoto(item){
            if (await this.$refs.editUserPhoto.open(item)) {
                this.user = '';
                this.getUsers()
            }else{
                this.user = '';
                return null;
            }
        },
        // refreshUsers: function (updatedUser) {
        //     console.log(updatedUser)
        //     let userIdx = this.users.findIndex((value) => value.id === updatedUser.id)
        //     if (userIdx >= 0) {
        //         this.$set(this.users, userIdx, updatedUser)
        //     }
        //     this.user = '';
        //     this.getUsers();
        // },
        toStringType(){
            this.users.forEach(value =>
            {
                switch (value.type){
                    case 'EC':
                        value.type = "Cooker";
                        break;
                    case 'EM':
                        value.type = "Manager";
                        break;
                    case 'ED':
                        value.type = "Deliveryman";
                        break;
                }
            })
        },
    },
    sockets: {
        user_list_updated (user_updated) {
            //this.refreshUsers(user_updated)
            this.getUsers();
        },
        user_logged(user){
            this.getUsers();
        },
        user_logged_out(user){
            this.getUsers();
        }
    },
    created() {
        this.getUsers()
    }
}
</script>

<style scoped>
</style>
