<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'password' => 'required|min:3|confirmed',
            //'photo_url' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'photo_url' => 'nullable',
            //'type' => 'required|in:C,EC,ED,EM',
            'type' => 'nullable|in:C,EC,ED,EM',
        ];
    }

    public function messages() {
        return [
            'name.regex' => 'Name only accept letters',
        ];
    }
}
