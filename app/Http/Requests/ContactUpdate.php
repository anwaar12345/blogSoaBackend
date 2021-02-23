<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactUpdate extends FormRequest
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
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|regex:/[0-9]{9}/|max:12',
        ];
    }
    public function messages()
    {
        return [
            'first_name.required' => 'The :attribute field can not be blank',
            'last_name.required' => 'The :attribute field can not be blank',
            'email.required' => 'The :attribute field can not be blank',
            'phone.required' => 'The :attribute field can not be blank',
            'phone.regex' => 'The :attribute should be a number',
            'phone.max' => 'The :attribute should not be more than 12'
        ];
    }
}
