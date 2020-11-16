<template>
    <div>
        <v-snackbar
            v-model="snackbar"
            :bottom="y === 'bottom'"
            :color="color"
            :left="x === 'left'"
            :multi-line="mode === 'multi-line'"
            :timeout="timeout"
            :top="y === 'top'"
            :vertical="mode === 'vertical'">
            {{ text }}
            <v-btn dark text @click="snackbar = false">
                <v-icon>mdi-close</v-icon>
            </v-btn>
        </v-snackbar>
        <!--TODO refefinir as validações deste formulário-->
            <p class="subtitle-1 text-center">Register</p>
                <form @submit.prevent="submit">
                <v-text-field
                    v-model="name"
                    :error-messages="nameErrors"
                    label="Name"
                    required
                    @input="$v.name.$touch()"
                    @blur="$v.name.$touch()"

                ></v-text-field>
            <v-btn  small color="success" class="mr-4" @click="submit">Submeter</v-btn>
            <v-btn small color="error" class="mr-4">
                Cancelar
            </v-btn>
        </form>
    </div>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required, maxLength, email } from 'vuelidate/lib/validators'


export default {
    mixins: [validationMixin],

name: "index",
data: () => ({
    valid: true,
    // ---- SNACKBAR INFO -----
    color: '',
    mode: '',
    snackbar: false,
    text: '',
    timeout: 4000,
    x: null,
    y: 'top',
    // ---- FORM DATA -----
    name:'',

}),
    validations: {
        name: { required, maxLength: maxLength(10) },
    },
    methods:{
        submit(){
            this.$v.$touch()
        }
    },

    computed:{
        nameErrors () {
            const errors = []
            if (!this.$v.name.$dirty) return errors
            !this.$v.name.required && errors.push('Name is required.')
            return errors
        },
    },
}
</script>

<style scoped>

</style>
