<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PricingPlanRequest extends FormRequest
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
            'section_id' => ['required', Rule::exists('sections', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'billing_period' => ['required', Rule::in(['monthly', 'yearly', 'one-time', 'custom']),],
            'billing_period_label' => ['nullable', 'string', 'max:50'],
            'featured' => ['boolean'],
            'active' => ['boolean'],
            'order' => ['nullable', 'integer'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_type' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'section_url' => ['nullable', 'string', 'max:255'],
            'page_id' => ['nullable', Rule::exists('pages', 'id')],
            'features' => ['nullable', 'array'],
            'features.*.name' => ['nullable', 'string', 'max:255'],
            'features.*.is_included' => ['boolean'],
            'features.*.order' => ['nullable', 'integer'],
        ];
    }
    
    public function updateRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'billing_period' => ['required', Rule::in(['monthly', 'yearly', 'one-time', 'custom']),],
            'billing_period_label' => ['nullable', 'string', 'max:50'],
            'featured' => ['boolean'],
            'active' => ['boolean'],
            'order' => ['nullable', 'integer'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_type' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'section_url' => ['nullable', 'string', 'max:255'],
            'page_id' => ['nullable', Rule::exists('pages', 'id'),],
            'features' => ['nullable', 'array'],
            'features.*.name' => ['nullable', 'string', 'max:255'],
            'features.*.is_included' => ['boolean'],
            'features.*.order' => ['nullable', 'integer'],
        ];
    }
}
