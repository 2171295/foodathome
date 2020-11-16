<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'status' => 'required|in:H,P',
            'notes' => 'nullable',
            'total_price' => 'required|numeric|between:0,999.99|min:1',
            'date' => 'required|date',
            'current_status_at' => 'require|date'
        ];
    }
}
