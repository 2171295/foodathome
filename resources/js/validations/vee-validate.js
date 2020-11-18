import { extend } from 'vee-validate';
import { required} from 'vee-validate/dist/rules';

// Add the required rule
extend('required', {
    ...required,
    message: 'This field is required'
});

