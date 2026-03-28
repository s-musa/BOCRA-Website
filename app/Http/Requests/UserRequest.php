<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        if($this->isMethod('POST')){
            return $this->storeRules();
        }
        
        return $this->updateRules();
    }
    
    public function storeRules(): array
    {
        return [
            'name' => ['required', Rule::unique('users', 'name')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'role_id' => ['nullable', Rule::exists('roles', 'id')],
            'password' => ['required', 'min:8', 'max:15'],
            'activated' => ['boolean'],
        ];
    }
    
    public function updateRules(): array
    {
        return [
            'name' => ['required', Rule::unique('users', 'name')->ignore($this->user)],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user)],
            'role_id' => ['nullable', Rule::exists('roles', 'id')],
            'password' => ['nullable', 'min:8', 'max:15'],
            'activated' => ['boolean'],
        ];
    }
}
