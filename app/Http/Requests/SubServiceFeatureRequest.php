<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubServiceFeatureRequest extends FormRequest
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
            'sub_service_id' => ['required'],
            'title' => ['required'],
            'icon' => ['nullable'],
            'details' => ['required'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'sub_service_id.required' => 'Sub Service ID is required.',
            'title.required' => 'Title is required.',
            'icon.required' => 'Icon is required.',
            'details.required' => 'Description is required.',
        ];
    }
}
