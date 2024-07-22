<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskNoteRequest extends FormRequest
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
            'id' => 'sometimes|exists:task_notes,id',
            'task_id' => 'required|exists:tasks,id',
            'note' => 'required'
        ];
    }
}
