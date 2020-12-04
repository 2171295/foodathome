<template>
    <div>
        <v-dialog v-model="dialog_password" max-width="490">
            <v-card>
                <v-card-title class="headline">
                    Alterar a Palavra-Chave
                </v-card-title>
                <v-card-text>
                    <form>
                        <v-text-field label="Palavra-Chave antiga" :error-messages="error_old" v-model="old_password" type="password"></v-text-field>
                        <v-text-field label="Nova Palavra-Chave" :error-messages="error_new" :rules="passwordRules"  v-model="new_password" type="password"></v-text-field>
                        <v-text-field label="Confirmação da nova Palavra-Chave" :error-messages="error_confirmation" :rules="passwordRules"  v-model="confirmation_password" type="password"></v-text-field>
                    </form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" text @click="dialog_password = false">
                        Cancelar
                    </v-btn>
                    <v-btn color="success" text  @click="updatePassword">
                        Guardar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        {{user}}
        <v-row>
            <v-col md="12">
                <v-card>
                    <v-toolbar>User's Personal Dat
                    <v-spacer></v-spacer>
                    <v-btn x-small to="/user/perfil/editar">Edit Profile</v-btn>
                    <v-btn x-small @click.stop="dialog_password = true">Change Password</v-btn>
                    </v-toolbar>
                    <v-card-text>
                        <v-row justify="center" align="center">
                            <v-img :src="'/storage/fotos/'+user.photo_url" max-height="200px" max-width="200px" style="border-radius: 50%;"/>
                        </v-row>
                        <v-row justify="center" align="center">
                            <p><b>Name:</b> {{user.name}}</p> <br>

                            <p><b>Email:</b> {{user.email}}</p>

                        </v-row>
                    </v-card-text>

                </v-card>
            </v-col>
            <v-col>

            </v-col>
        </v-row>
    </div>
</template>

<script>
export default {
name: "index",
    data: () => {
        return {
            user:'',
            // Dados para editar password:
            dialog_password: false,
            old_password:'',
            new_password:'',
            confirmation_password:'',
            error_old:'',
            error_new:'',
            error_confirmation:'',
            auxiliarMorada:'',
            rua:'',
            localidade:'',
            codigoPostal:'',


            // ---- SNACKBAR INFO -----
            color: '',
            snackbar: false,
            text: '',
            // ------------------------

            passwordRules:[
                v => !!v || 'Password é um campo obrigatório',
            ],

        }
    },
    methods:{
        getUser(){
            axios.get('api/users/me').then((response) => {
                this.user = response.data.data
            })
        }
    },
    created(){
        this.getUser();
    }
}
</script>

<style scoped>

</style>
