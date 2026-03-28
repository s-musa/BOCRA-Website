<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
            'name' => ['required', 'string', Rule::unique('products', 'name')],
            'product_type_id' => ['nullable', Rule::exists('product_types', 'id')],
            'details' => ['nullable', 'string'],
            'active' => ['boolean'],
            'media' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:10240'],
        ];
    }
    
    public function updateRules(): array
    {
        return [
            'name' => ['required', 'string', Rule::unique('products', 'name')->ignore($this->product)],
            'slug' => ['nullable', 'string', Rule::unique('products', 'slug')->ignore($this->product)],
            'product_type_id' => ['nullable', Rule::exists('product_types', 'id')],
            'details' => ['nullable', 'string'],
            'active' => ['boolean'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'name.string' => 'Product name must be a string.',
            'name.unique' => 'Product name already exists.',
            'media.image' => 'Product media must be a image.',
            'media.mimes' => 'Product media must be a file of type: jpeg, png, jpg.',
            'media.max' => 'Product media must be less than 5MB.',
        ];
    }
}
