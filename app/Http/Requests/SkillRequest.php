<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SkillRequest extends FormRequest
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
            'title' => ['required', 'string', Rule::unique('skills', 'title')],
            'details' => ['required', 'string'],
            'active' => ['required', 'boolean'],
        ];
    }
    
    public function updateRules(): array
    {
        return [
            'title' => ['required', 'string', Rule::unique('skills', 'title')->ignore($this->skill)],
            'details' => ['required', 'string'],
            'active' => ['required', 'boolean'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'title.required' => 'Title is required.',
            'title.string' => 'Title must be a string.',
            'title.unique' => 'Title already exists.',
            'Details.required' => 'Percentage is required.',
        ];
    }
}
