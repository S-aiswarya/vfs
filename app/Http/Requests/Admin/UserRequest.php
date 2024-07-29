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
            'phone_number' => "required|max:20|unique:users,phone_number,{$ignoreId},id,deleted_at,NULL",
            'role_id' => "required",
            'center_id' => "required_if:role_id=guard->role_id",
        ];

        if(!$ignoreId)
            $rules['password'] = 'required|same:confirm_password';
        else
            $rules['password'] = 'nullable|same:confirm_password';

        return $rules;
    }
}
