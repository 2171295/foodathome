<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => 'required|unique:products|min:3',
            'price' => 'required|numeric|between:0,999.99|min:1',
            'description' => 'required|',
            'photo_url' => 'required|image|mimes:jpeg,jpg',
            'type' => 'required|in:hot dish,cold dish,drink,dessert',
        ];
    }

    public function messages() {
        return [
            'name.regex' => 'Name only accept letters',
        ];
    }
}
