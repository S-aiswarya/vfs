<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadPublicRequest extends FormRequest
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
            'referral_link_id' => 'required|exists:referral_links,id',
            'name' => "required|max:250",
            'email' => "required|email|max:250",
            'phone_country_code' => 'nullable|numeric|digits_between:1,4',
            'phone_number' => 'nullable|digits_between:8,14|numeric',
            'alternate_phone_country_code' => 'nullable|numeric|digits_between:1,4',
            'alternate_phone_number' => 'nullable|numeric|digits_between:8,14',
            'preferred_course' => 'required|max:250',
        ];
    }
}
