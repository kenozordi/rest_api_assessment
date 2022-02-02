<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'first_name'    => 'required|string|max:255',
            'middle_name'   => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'phone_number'  => 'required|regex:"^[\\d*#+]+$"',
            'password'      => 'required|string|min:10',
            'picture_url'   => 'string|nullable',
            'is_disabled'   => 'string|nullable',
        ];
    }
}
