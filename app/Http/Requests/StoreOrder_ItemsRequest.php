<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrder_ItemsRequest extends FormRequest
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
            'order_id' => 'required',
            'product_id' => 'required',
            'quantity'=>'required|numeric|min:1',
            'unit_price' => 'required|numeric|between:0,999.99|min:1',
            'sub_total_price' => 'required|numeric|between:0,999.99|min:1',
        ];
    }
}
