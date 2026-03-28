<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
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
            'title' => ['required', 'string', Rule::unique('services', 'title')],
            'details' => ['required', 'string'],
            'short_description' => ['nullable', 'string'],
            'active' => ['boolean'],
            'media' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:10240'],
        ];
    }
    
    public function updateRules(): array
    {
        return [
            'title' => ['required', 'string', Rule::unique('services', 'title')->ignore($this->service)],
            'slug' => ['nullable', 'string', Rule::unique('services', 'slug')->ignore($this->service)],
            'details' => ['required', 'string'],
            'short_description' => ['nullable', 'string'],
            'active' => ['boolean'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'title.required' => 'Service title is required.',
            'title.string' => 'Service title must be a string.',
            'title.unique' => 'Service title already exists.',
            'media.image' => 'Service media must be a image.',
            'media.mimes' => 'Service media must be a file of type: jpeg, png, jpg.',
            'media.max' => 'Service media must be less than 5MB.',
        ];
    }
}
