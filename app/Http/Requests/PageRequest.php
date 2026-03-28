<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
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
            'title' => ['required', 'string', Rule::unique('pages', 'title')],
            'parent_id' => ['nullable', Rule::exists('pages', 'id')],
            'page_type_id' => ['nullable', Rule::exists('page_types', 'id')],
            'description' => ['nullable', 'string'],
            'banner_style' => ['nullable', 'string'],
//            'banner_background_type' => ['nullable', 'string'],
            'banner_background_color' => ['nullable', 'string'],
            'published' => ['boolean'],
        ];
    }
    
    public function updateRules(): array
    {
        return [
            'title' => ['required', 'string', Rule::unique('pages', 'title')->ignore($this->page)],
            'slug' => ['nullable', 'string', Rule::unique('pages', 'slug')->ignore($this->page)],
            'parent_id' => ['nullable', Rule::exists('pages', 'id')],
            'page_type_id' => ['nullable', Rule::exists('page_types', 'id')],
            'description' => ['nullable', 'string'],
            'banner_style' => ['nullable', 'string'],
//            'banner_background_type' => ['nullable', 'string'],
            'banner_background_color' => ['nullable', 'string'],
            'published' => ['boolean'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'title.unique' => 'The title has already been taken.',
            'slug.unique' => 'The slug has already been taken. It must be unique.',
            'slug.string' => 'The slug must be a string.',
            'description.string' => 'The description must be a string.',
            'menu_order.integer' => 'The menu order must be an integer.',
            'menu_order.min' => 'The menu order must be a positive number.',
            'published.boolean' => 'The published status must be a boolean.',
        ];
    }
}
