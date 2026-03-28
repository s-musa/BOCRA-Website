<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FaqRequest extends FormRequest
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
            'question' => ['required', 'string', Rule::unique('faqs', 'question')],
            'answer' => ['required', 'string'],
            'active' => ['boolean'],
        ];
    }
    
    public function updateRules(): array
    {
        return [
            'question' => ['required', 'string', Rule::unique('faqs', 'question')->ignore($this->faq)],
            'answer' => ['required', 'string'],
            'active' => ['boolean'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'question.required' => 'Question is required',
            'question.string' => 'Question must be string',
            'question.unique' => 'Question already exists',
            'answer.required' => 'Answer is required',
        ];
    }
}
