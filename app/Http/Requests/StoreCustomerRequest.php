<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3',
            'photo_url' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'address' => 'required|min:3',
            'phone' => 'required|min:9',
            'nif' => 'nullable|min:9|regex:/^[0-9]{9}$/',
        ];
    }

    public function messages() {
        return [
            'name.regex' => 'Name only accept letters',
            'nif.regex' => 'NIF needs to have 9 digits',
        ];
    }
}
