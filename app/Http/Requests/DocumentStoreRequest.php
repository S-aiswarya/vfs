<?php

namespace App\Http\Requests;

use App\Models\DocumentTemplate;
use Illuminate\Foundation\Http\FormRequest;

class DocumentStoreRequest extends FormRequest
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
        if(!$this->input('document_template_id')){
            return [
                'document_template_id' => 'required'
            ];
        }
        else{
            $template = DocumentTemplate::find($this->input('document_template_id'));
            if(!$template){
                return [
                    'document_template_id' => 'required|exists:document_templates,id'
                ];
            }

            $max_file_upload_size = ($template->max_upload_size)?$template->max_upload_size*1024:(int)ini_get("upload_max_filesize")*1024;

            return [
                'title' => 'required|max:250',
                'lead_id' => 'required',
                'file' => 'required|max:'.$max_file_upload_size
            ];
        }
    }
}
