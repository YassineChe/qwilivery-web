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

        try{$id = $this->request->get('id');
        }catch(\Exception $e){ $id = ''; }

        return [
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => "required|email|unique:admins|unique:deliveries|unique:restaurants,email,{$this->request->get('id')}",
            'phone_number' => "required|regex:/[0-9]{10}/|unique:deliveries|unique:restaurants,phone_number,{$this->request->get('id')}",
            'experience'   => 'required|numeric',
            'permit' => 'required|mimes:png,jpg,jpeg|max:1024',
        ];
    }
}
