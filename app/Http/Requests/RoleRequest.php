<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
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
            'name' => ['required', 'min:3', Rule::unique('roles', 'name')],
            'description' => ['nullable'],
            'permissions' => ['required', 'array'],
        ];
    }
    
    public function updateRules(): array
    {
        return [
            'name' => ['required', 'min:3', Rule::unique('roles', 'name')->ignore($this->role)],
            'description' => ['nullable'],
            'permissions' => ['required', 'array'],
        ];
    }
}
