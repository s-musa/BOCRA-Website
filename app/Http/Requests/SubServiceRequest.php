<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubServiceRequest extends FormRequest
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
            'service_id' => ['required'],
            'title' => ['required'],
            'details' => ['required'],
            'has_image' => ['boolean'],
            'media' => ['nullable'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'service_id.required' => 'Service ID is required.',
            'title.required' => 'Title is required.',
            'details.required' => 'Description is required.',
        ];
    }
}
