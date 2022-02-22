<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class CustomerRegistrationRequest extends FormRequest
{
    // protected $stopOnFirstFailure = true;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required',
        ];
    } 

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'   => 'Please enter your name',
            'email.required'  => 'Please enter your email',
            'email.email'     => 'Please enter valid email address',
            'email.unique'    => 'Your email address already exists',
            'password.required' => 'Please enter your address',
        ];
    }

    /**
     * Get the error json response for the defined http response exception
     *
     * @return json
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'status'  => 400,
            'message' => $validator->errors()->first()
        ]));
    }
}
