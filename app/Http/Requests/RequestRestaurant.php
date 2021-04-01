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

        //Try to get ID
        try{$id = $this->request->get('id');
        }catch(\Exception $e){ $id = ''; }
        
        return [
            'name'    => 'required',
            'email'   => "required|email|unique:admins|unique:deliveries|unique:restaurants,email,{$this->request->get('id')}",
            'phone_number'  => "required|regex:/[0-9]{10}/|unique:deliveries|unique:restaurants,phone_number,{$this->request->get('id')}",
            'address' => 'required|max:200',
            'rate'    => 'required|integer',
        ];
    }
}
