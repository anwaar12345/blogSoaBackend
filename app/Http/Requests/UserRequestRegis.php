<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequestRegis extends FormRequest
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
            'first_name'     => 'required|min:8',
            'last_name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'phone' => 'required|regex:/[0-9]{9}/|max:12',
            'password' => 'required|min:8',
            'image' => 'required'
         ];
    }
    public function messages()
    {
     return [
         'first_name.required' => 'The :attribute field can not be blank',
         'first_name.min' => 'The :attribute should be minimum 8 characters',
         'email.required' => 'The :attribute field can not be blank',
         'email.email' => 'The :attribute should be a valid email',
         'email.unique' => 'The :attribute should be unique',
         'phone.required' => 'The :attribute field can not be blank',
         'phone.regex' => 'The :attribute should be valid',
         'phone.max' => 'The :attribute should not be greater than 12'
     ];   
    }
}
