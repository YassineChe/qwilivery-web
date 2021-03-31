<?php

namespace App\Http\Requests;

//? Just created another FromRequest to customize my errors JSON! 
//? Instead (use Illuminate\Foundation\Http\FormRequest;)
use App\Http\Requests\Api\FormRequest; 

class RequestRestaurant extends FormRequest
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
            'name'    => 'required',
            'email'   => 'required|email|unique:restaurants|unique:admins|unique:deliveries',
            // 'phone_number' => 'required|max:10|min:10|integer',
            'address' => 'required|max:200',
            'rate'    => 'required|integer',
        ];
    }
}
