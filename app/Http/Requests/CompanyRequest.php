<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
{
    public function rules(): array
    {
        if ($this->isMethod('POST')) {
            return $this->createRules();
        }
        
        return $this->updateRules();
    }
    
    public function createRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('companies', 'name')],
            'email' => ['required', 'email', 'max:255', Rule::unique('companies', 'email')],
            'secondary_email' => ['nullable', 'string'],
            'primary_phone' => ['required', 'string'],
            'secondary_phone' => ['nullable', 'string'],
            'primary_whatsapp' => ['nullable', 'string'],
            'secondary_whatsapp' => ['nullable', 'string'],
            'country' => ['required', 'string'],
            'state' => ['required', 'string'],
            'city' => ['required', 'string'],
            'website' => ['nullable'],
            'physical_address' => ['nullable'],
            'postal_address' => ['nullable'],
            'tax_identification_pin' => ['nullable'],
            'mission' => ['nullable'],
            'vision' => ['nullable'],
            'x_profile' => ['nullable', 'url'],
            'fb_profile' => ['nullable'],
            'ig_profile' => ['nullable'],
            'linkedin_profile' => ['nullable'],
            'tiktok_profile' => ['nullable'],
            'youtube_profile' => ['nullable'],
            'pinterest_profile' => ['nullable'],
            'has_footer_logo' => ['nullable', 'boolean'],
        ];
    }
    
    public function updateRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('companies', 'name')->ignore($this->company)],
            'email' => ['required', 'email', 'max:255', Rule::unique('companies', 'email')->ignore($this->company)],
            'secondary_email' => ['nullable', 'string'],
            'primary_phone' => ['required', 'string'],
            'secondary_phone' => ['nullable', 'string'],
            'primary_whatsapp' => ['nullable', 'string'],
            'secondary_whatsapp' => ['nullable', 'string'],
            'country' => ['nullable'],
            'state' => ['nullable'],
            'city' => ['nullable'],
            'website' => ['nullable'],
            'physical_address' => ['nullable'],
            'postal_address' => ['nullable'],
            'tax_identification_pin' => ['nullable'],
            'x_profile' => ['nullable', 'url'],
            'fb_profile' => ['nullable'],
            'ig_profile' => ['nullable'],
            'linkedin_profile' => ['nullable'],
            'tiktok_profile' => ['nullable'],
            'youtube_profile' => ['nullable'],
            'pinterest_profile' => ['nullable'],
            'has_footer_logo' => ['nullable', 'boolean'],
        ];
    }
    
    public function prepareForValidation(): void
    {
        $this->merge([
            'name' => ucwords(strtolower($this->input('name'))),
            'email' => strtolower($this->input('email')),
            'country' => strtoupper($this->input('country')),
            'state' => strtoupper($this->input('state')),
            'city' => strtoupper($this->input('city')),
        ]);
    }
}
