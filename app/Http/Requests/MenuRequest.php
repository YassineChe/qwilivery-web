<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;

class MenuRequest extends FormRequest
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
            'meal_id' => "required|integer",
            'name'    => 'required|min:2|max:255',
            'description' => 'required|min:50|max:300',
        ];
    }
}
