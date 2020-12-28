import { extend } from 'vee-validate';
import { required} from 'vee-validate/dist/rules';
import { min} from 'vee-validate/dist/rules';
import {target} from "vuelidate/lib/params";

// Add the required rule
extend('required', {
    ...required,
    message: 'This field is required'
});
extend('password', {
    params: ['target'],
    validate(value,  {target} ) {
        return value === target;
    },
    message: 'Passwords dont match.'
});
extend('min', {
    ...min,
    message: "Minimal value of characteres wasn't been reached"
});

