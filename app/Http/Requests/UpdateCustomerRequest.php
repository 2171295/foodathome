<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'address' => 'required|min:3',
            //'address' => 'nullable|min:3',
            'phone' => 'required|min:9',
            //'phone' => 'nullable|min:9',
            'nif' => 'nullable|min:9|regex:/^[0-9]{9}$/',
        ];
    }

    public function messages() {
        return [
            'nif.regex' => 'NIF needs to have 9 digits',
        ];
    }
}
