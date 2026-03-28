<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SectionFaqRequest extends FormRequest
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
        if($this->isMethod('POST')) {
            return $this->createRules();
        }
        
        return $this->updateRules();
    }
    
    public function createRules(): array
    {
        return [
            'section_id' => ['required', Rule::exists('sections', 'id')],
            'question' => ['nullable', 'string'],
            'answer' => ['nullable', 'string'],
        ];
    }
    
    public function updateRules(): array
    {
        return [
            'question' => ['nullable', 'string'],
            'answer' => ['nullable', 'string'],
        ];
    }
}
