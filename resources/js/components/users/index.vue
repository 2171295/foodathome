<template>
    <div>
        <v-toolbar class="d-flex justify-center align-center" style="margin-bottom: 20px;">
            <v-toolbar-title>Users</v-toolbar-title>
        </v-toolbar>
        <v-card>
            <v-toolbar class="d-flex" style="margin-bottom: 10px;">
                <v-btn @click="">Create User</v-btn>
            </v-toolbar>
            <v-card-text>
                <v-data-table
                    :headers="headers"
                    :items="users"
                    :items-per-page="10"
                    :item-class= "row_classes"
                    class="elevation-1"
                >
                    <template v-slot:item.img="{ item }">
                        <v-img :src="'/storage/fotos/'+item.photo_url" width="100px" height="100px" style="border-radius: 50%"/>
                    </template>
                    <template v-slot:item.actions="{item}">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn v-on="on"  icon @click="" >
                                    <v-icon>mdi-account-edit</v-icon>
                                </v-btn>
                            </template>
                            <span>Edit User</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn v-on="on"  icon @click="">
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </template>
                            <span>Remove User</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }" v-if="item.blocked === 'No'">
                                <v-btn v-on="on"  icon @click="">
                                    <v-icon>mdi-account-cancel</v-icon>
                                </v-btn>
                            </template>
                            <span>Block User</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }" v-if="item.blocked === 'Yes'">
                                <v-btn v-on="on"  icon @click="">
                                    <v-icon>mdi-account-check</v-icon>
                                </v-btn>
                            </template>
                            <span>Unblock User</span>
                        </v-tooltip>
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
export default {
    name: "index",
    data: () => {
        return {
            users: [],
            headers: [
                {text: '', sortable: false, value: 'img'},
                {text: 'Name', align: 'start', sortable: true, value: 'name',},
                {text: 'Email', sortable: true, value: 'email'},
                {text: 'Type', align: 'start', sortable: true, value: 'type',},
                {text: 'Blocked', align: 'start', sortable: true, value: 'blocked',},
                {text: 'Actions', align: 'start', sortable: false, value: 'actions',},
            ],
        }
    },
    methods: {
        row_classes(item) {
            if (item.blocked === 'Yes') {
                return "orange";
            }
        },
        getUsers(){
            axios.get("api/users/with_trash")
                .then((response) => {
                    this.users = response.data.data
                })
        }
    },
    created() {
        this.getUsers()
    }
}
</script>

<style scoped>
</style>
