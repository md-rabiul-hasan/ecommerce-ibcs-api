<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class OrderStoreRequest extends FormRequest
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
            'amount'           => 'required',
            'shipping_address' => 'required',
            'items'            => 'required|array|min:1'
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
            'amount.required'           => 'Total order amount must be required',
            'shipping_address.required' => 'Please enter order shipping address',
            'items.required'            => 'Please select your product',
            'items.min'                 => 'Your cart is empty',
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
