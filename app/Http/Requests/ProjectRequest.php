<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
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
            'category_id' => ['nullable', Rule::exists('categories', 'id')],
            'project_tags' => ['nullable', 'array'],
            'details' => ['required', 'string'],
            'short_description' => ['nullable', 'string'],
            'active' => ['boolean'],
            'media' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:10240'],
        ];
    }
    
    public function updateRules(): array
    {
        return [
            'title' => ['required', 'string', Rule::unique('projects', 'title')->ignore($this->project)],
            'slug' => ['nullable', 'string', Rule::unique('projects', 'slug')->ignore($this->project)],
            'category_id' => ['nullable', Rule::exists('categories', 'id')],
            'project_tags' => ['nullable', 'array'],
            'project_tags.*' => ['nullable', Rule::exists('tags', 'id')],
            'details' => ['required', 'string'],
            'short_description' => ['nullable', 'string'],
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
    
//    public function prepareForValidation(): void
//    {
//        $this->merge([
//            'title' => ucwords(strtolower($this->input('title'))),
//        ]);
//    }
}
