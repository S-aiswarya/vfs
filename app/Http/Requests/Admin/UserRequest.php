<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $ignoreId = ($this->input('id'))?decrypt($this->input('id')):NULL;
        $rules = [
            'name' => "required|max:250",
            'email' => "required|email|max:250|unique:users,email,{$ignoreId},id,deleted_at,NULL",
            'role_id' => "required",
            'center_id' => "required_if:role_id, ==, 6",
            'location_id'=>"required_if:role_id, ==, 6",
            'city_id'=>"required_if:role_id, ==, 6",
            'office_country_id'=>"required_if:role_id, ==, 6",
            'gate_id'=>"required_if:role_id, ==, 6",
        ];

        if(!$ignoreId)
            $rules['password'] = 'required|same:confirm_password';
        else
            $rules['password'] = 'nullable|same:confirm_password';

        return $rules;
    }
}
