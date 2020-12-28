<template>
    <div>
        <aux_snackbar :text="text" :snackbar="snackbar" :color="color"/>
        <v-dialog v-model="display">
            <v-card>
                <v-toolbar class="d-flex justify-center">
                    <v-toolbar-title>Shopping Cart</v-toolbar-title>
                </v-toolbar>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import {mdiCamera} from "@mdi/js";
import aux_snackbar from "./aux_snackbar";

export default {
    name: "aux_shopping_cart",
    data: () => ({
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
        },
    },
    components: {
        aux_snackbar
    },
}
</script>

<style scoped>

</style>
