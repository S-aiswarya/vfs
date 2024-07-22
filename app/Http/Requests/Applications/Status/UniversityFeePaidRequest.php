<?php

namespace App\Http\Requests\Applications\Status;

use Illuminate\Foundation\Http\FormRequest;

class UniversityFeePaidRequest extends FormRequest
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
            'id' => 'required',
            'fee_receipt' => 'sometimes|max:'.(int)ini_get("upload_max_filesize")*1024
        ];
    }
}
