import { extend } from 'vee-validate';
import { required} from 'vee-validate/dist/rules';

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

