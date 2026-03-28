<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventRequest extends FormRequest
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
            'title' => ['required', 'string', Rule::unique('projects', 'title')],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['nullable', Rule::exists('tags', 'id')],
            'date' => ['required', 'date'],
            'category_id' => ['nullable', Rule::exists('categories', 'id')],
            'hosted_by' => ['nullable', 'string'],
            'location' => ['nullable', 'string'],
            'map_url' => ['nullable', 'string'],
            'details' => ['required', 'string'],
            'active' => ['boolean'],
            'media' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:10240'],
        ];
    }
    
    public function updateRules(): array
    {
        return [
            'title' => ['required', 'string', Rule::unique('events', 'title')->ignore($this->event)],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['nullable', Rule::exists('tags', 'id')],
            'date' => ['required', 'date'],
            'category_id' => ['nullable', Rule::exists('categories', 'id')],
            'hosted_by' => ['nullable', 'string'],
            'location' => ['nullable', 'string'],
            'map_url' => ['nullable', 'string'],
            'details' => ['required', 'string'],
            'active' => ['boolean'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'title.required' => 'Project title is required.',
            'title.string' => 'Project title must be a string.',
            'title.unique' => 'Project title already exists.',
            'media.image' => 'Project media must be a image.',
            'media.mimes' => 'Project media must be a file of type: jpeg, png, jpg.',
            'media.max' => 'Project media must be less than 5MB.',
        ];
    }
}
