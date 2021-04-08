<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;

class DeliveryRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name'  => 'required',
            // 'avatar'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'experience' => 'required',
            'permit'    => 'required|mimes:pdf|max:10000"',
            'email'      => 'required|email|unique:restaurants|unique:admins|unique:deliveries',

        ];
    }
}
