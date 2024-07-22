<?php

namespace App\Http\Requests\Admin;

use App\Rules\ArrayAtLeastOneRequired;
use Illuminate\Foundation\Http\FormRequest;

class UniversityEmailRequest extends FormRequest
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
            'email' => ['required', 'array', new ArrayAtLeastOneRequired()],
            'email.*' => 'nullable|email|max:250',
            'label' => ['required', 'array', new ArrayAtLeastOneRequired()]
        ];
    }
}
