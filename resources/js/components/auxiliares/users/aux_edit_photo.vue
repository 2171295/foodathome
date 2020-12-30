<template>
    <div>
        <aux_snackbar :text="text" :snackbar="snackbar" :color="color"/>
        <v-dialog v-model="display" max-width="700px">
            <v-card>
                <v-toolbar class="d-flex justify-center align-center">
                    Edit Profile Photo
                </v-toolbar>
                <v-card-text>
                    <form @submit="formSubmit" enctype="multipart/form-data">
                        <div>
                            <v-file-input
                                counter
                                accept="image/png, image/jpeg"
                                label="Pick a new photo:"
                                v-model="file"
                                prepend-icon="mdi-camera"
                                v-on:change="onFileChange"
                            />
                        </div>
                        <v-btn class="mr-4" type="submit">
                            Upload
                        </v-btn>
                        <v-btn class="mr-4" @click="cancel">
                            Cancel
                        </v-btn>
                    </form>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import {mdiCamera} from "@mdi/js";
import aux_snackbar from "../aux_snackbar";
export default {
    name: "aux_edit_photo",
    props:['user'],
    data: () => ({
        iconCamera: mdiCamera,
        file: null,
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
        cancel() {
            this.resolve(false);
            this.display = false;
        },
        onFileChange(e){
            console.log(e.target.files[0]);
            this.file = e.target.files[0];
        },
        formSubmit(e) {
            e.preventDefault();

            const config = {
                headers: { 'content-type': 'multipart/form-data' }
            }

            let formData = new FormData();
            formData.append('photo', this.file);

            axios.post('api/users/'+this.user.id+'/update_photo', formData, config)
                .then((response)=> {
                    console.log(response)
                    this.color = 'success';
                    this.text = "New user's photo successfuly uploaded."
                    this.snackbar = true;
                    this.resolve(true);
                    this.display = false;
                    setTimeout(() => {
                        this.snackbar = false;
                    }, 2000);
                })
                .catch(function (error) {
                    this.color = 'red';
                    this.text = "Error uploading new image."
                    this.snackbar = true;
                    setTimeout(() => {
                        this.snackbar = false;
                    }, 2000);
                });
        }
    },
    components: {
        aux_snackbar
    },
}
</script>

<style scoped>

</style>
