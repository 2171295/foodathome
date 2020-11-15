<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => 'required|unique|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'password' => 'required|min:8|confirmed',
            'photo_url' => 'nullable|image|mimes:jpeg,jpg|max:2048',
            'type' => 'required|in:C,EC,ED,EM',
        ];
    }

    public function messages() {
        return [
            'name.regex' => 'Name only accept letters',
        ];
    }
}
