<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SectionCardRequest extends FormRequest
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
        if ($this->isMethod('POST')) {
            return $this->createRules();
        }
        
        return $this->updateRules();
    }
    
    public function createRules(): array
    {
        return [
            'section_id' => ['required', Rule::exists('sections', 'id')],
            'title' => ['required', 'string'],
            'icon' => ['nullable', 'string'],
            'details' => ['required', 'string'],
            'card_link_type' => ['nullable', 'string'],
            'custom_url' => ['nullable', 'string'],
            'section_url' => ['nullable', 'string'],
            'page_id' => ['nullable', Rule::exists('pages', 'id')],
        ];
    }
    
    public function updateRules(): array
    {
        return [
            'section_id' => ['required', Rule::exists('sections', 'id')],
            'title' => ['required', 'string'],
            'icon' => ['nullable', 'string'],
            'details' => ['nullable', 'string'],
            'card_link_type' => ['nullable', 'string'],
            'custom_url' => ['nullable', 'string'],
            'section_url' => ['nullable', 'string'],
            'page_id' => ['nullable', Rule::exists('pages', 'id')],
        ];
    }
    
    public function messages(): array
    {
        return [
            'title.required' => 'Title is required.',
            'title.string' => 'Title must be a string.',
            'details.required' => 'Percentage is required.',
        ];
    }
}
