<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CheckInRequest extends FormRequest
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
        return [
            'name' => 'required|max:250',
            'phonenumber' =>'required|max:250',
             'check_in_type_id' => 'required|exists:check_in_type,id',
            // 'country_id' => 'required|exists:office_countries,id',
            // 'city_id' => 'required|exists:cities,id',
            // 'location_id'=>'required|exists:locations,id',
            // 'center_id'=>'required|exists:centers,id',
            // 'gate_id'=>'required|exists:gates,id',
            
        ];
    }
}
