<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;

class StoreRestaurentRequest extends FormRequest
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
            'restaurant_name' => 'required',
            'email' => 'required|email|unique:restaurants|unique:admins|unique:deliveries',
            'address'         => 'required|max:200',
            'tarif'           => 'required|integer',
        ];
    }
}
