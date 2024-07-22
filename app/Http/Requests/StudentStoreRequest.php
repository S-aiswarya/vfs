<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentStoreRequest extends FormRequest
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
        return [
            'lead_id' => 'required',
            'name' => "required|max:250",
            'email' => "required|email|max:250|unique:users,email,NULL,id,deleted_at,NULL",
            'phone_number' => "required|digits_between:8,16|numeric|unique:users,phone_number,NULL,id,deleted_at,NULL",
            'date_of_birth' => 'required',
            'intake_id' => 'required|exists:intakes,id'
        ];
    }
}
