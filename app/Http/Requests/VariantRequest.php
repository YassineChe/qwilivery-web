<?php

namespace App\Http\Requests;

//? Just created another FromRequest to customize my errors JSON! 
//? Instead (use Illuminate\Foundation\Http\FormRequest;)
use App\Http\Requests\Api\FormRequest; 

class VariantRequest extends FormRequest
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
            'name' => 'required|min:2',
            'price' => 'required|numeric',
            'photo' => 'image|mimes:jpeg,png,jpg|max:1048',
            'category_id' => 'required',
        ];
    }
}
