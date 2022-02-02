<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'first_name'    => 'string|max:255',
            'middle_name'   => 'string|max:255',
            'last_name'     => 'string|max:255',
            'email'         => 'email|unique:users,email',
            'phone_number'  => 'regex:/^([0-9\s\+\(\)]*)$/',
            'password'      => 'string|min:10',
            'picture_url'   => 'string|nullable',
            'is_disabled'   => 'string|nullable',
        ];
    }
}
