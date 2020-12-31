<template>
    <div>
        <aux_snackbar :text="text" :snackbar="snackbar" :color="color"/>
        <v-dialog v-model="display" max-width="700px">
            <v-card>
                <v-toolbar class="d-flex justify-center align-center">
                    Edit Profile Photo
                </v-toolbar>
                <v-card-text>
                    <v-img :src="'/storage/products/'+product.photo" width="100px" height="100px" style="border-radius: 50%"/>
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
    name: "aux_edit_product_photo",
    data: () => ({
        iconCamera: mdiCamera,
        product: '',
        file: null,
        display:false,
        // ---- SNACKBAR INFO -----
        color: '',
        snackbar: false,
        text: '',
        // ------------------------
    }),
    methods:{
        open(item) {
            this.display = true;
            this.valid = true
            this.product = JSON.parse(JSON.stringify(item))
            return new Promise((resolve, reject) => {
                this.resolve = resolve;
                this.reject = reject;
            });
        },
        cancel() {
            this.product = '';
            this.file= null;
            this.resolve(false);
            this.display = false;
        },
        onFileChange(e){
            this.file = e;
        },
        formSubmit(e) {
            e.preventDefault();

            const config = {
                headers: { 'content-type': 'multipart/form-data' }
            }

            let formData = new FormData();
            formData.append('photo', this.file);

            axios.post('api/products/'+this.product.id+'/update_photo', formData, config)
                .then((response)=> {
                    this.$socket.emit('products_list_updated', response.data)
                    this.color = 'success';
                    this.text = "New product's photo successfuly uploaded."
                    this.snackbar = true;
                    this.product = '';
                    this.file= null;
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
