<template>
    <div>
        <aux_snackbar :text="text" :snackbar="snackbar" :color="color"/>
        <v-dialog v-model="display">
            <v-card>
                <v-toolbar class="d-flex justify-center align-center">
                    Shopping Cart
                </v-toolbar>>
                <v-card-text>
                    <v-file-input
                    counter
                    accept="image/png, image/jpeg"
                    label="Pick a photo:"
                    v-model="photo"
                    :prepend-icon="iconCamera"
                ></v-file-input>
                    {{photo}}
                <v-btn color="error" text @click="display = false">
                    Cancel
                </v-btn>
                <v-btn color="success" text  @click="submit">
                    Save
                </v-btn>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import {mdiCamera} from "@mdi/js";
import aux_snackbar from "./aux_snackbar";
export default {
    name: "aux_edit_photo",
    props:['user'],
    data: () => ({
        iconCamera: mdiCamera,
        photo:'',
        display:false,
        // ---- SNACKBAR INFO -----
        color: '',
        snackbar: false,
        text: '',
        // ------------------------
    }),
    methods:{
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
            console.log(this.photo)
            axios.put('api/users/'+this.user.id+'/update_photo', {
                "photo":this.photo.name,
                "name":this.user.name,
                "type":this.user.type
            }).then((response) => {
                this.color = 'success';
                this.text = "Photo updated successfully."
                this.snackbar = true;
                setTimeout(() => {
                    this.snackbar = false;
                }, 2000);
                this.resolve(true);
                this.display = false;

            }).catch(error =>{
                this.color = 'red';
                this.text = "Error updating photo."
                this.snackbar = true;
                setTimeout(() => {
                    this.snackbar = false;
                }, 2000);
            })
        },
    },
    components: {
        aux_snackbar
    },
}
</script>

<style scoped>

</style>
