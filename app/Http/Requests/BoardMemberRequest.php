<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BoardMemberRequest extends FormRequest
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
            'name' => ['required', 'string', Rule::unique('board_members', 'name')],
            'position' => ['nullable', 'string'],
            'details' => ['nullable', 'string'],
            'active' => ['boolean'],
            'media' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:10240'],
        ];
    }
    
    public function updateRules(): array
    {
        return [
            'name' => ['required', 'string', Rule::unique('board_members', 'name')->ignore($this->board_member)],
            'position' => ['nullable', 'string'],
            'details' => ['nullable', 'string'],
            'active' => ['boolean'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'name.required' => 'Board member name is required.',
            'name.unique' => 'Board member name already exists.',
            'position.string' => 'Board member position must be a string.',
            'media.image' => 'Board member media must be a image.',
            'media.mimes' => 'Board member media must be a file of type: jpeg, png, jpg.',
            'media.max' => 'Board member media must be less than 5MB.',
        ];
    }
    
    public function prepareForValidation(): void
    {
        $this->merge([
            'name' => ucwords(strtolower($this->input('name'))),
            'title' => ucwords(strtolower($this->input('title'))),
        ]);
    }
}
