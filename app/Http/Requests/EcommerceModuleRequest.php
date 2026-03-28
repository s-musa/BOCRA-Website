<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EcommerceModuleRequest extends FormRequest
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
            'module_id' => ['required', Rule::exists('modules', 'id')],
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string'],
            'website' => ['nullable', 'string'],
            'business_name' => ['required', 'string'],
            'country_id' => ['required', Rule::exists('countries', 'id')],
            'line1' => ['required', 'string'],
            'line2' => ['nullable', 'string'],
            'city' => ['required', 'string'],
            'state' => ['nullable', 'string'],
            'postal_code' => ['nullable', 'string'],
            'shop_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5020'],
            'currency_id' => ['required', Rule::exists('currencies', 'id')],
            'enable_product_reviews' => ['nullable', 'string'],
            'enable_product_coupons' => ['nullable', 'string'],
            'enable_inventory_tracking' => ['nullable', 'string'],
            'enable_shipment_tracking' => ['nullable', 'string'],
        ];
    }
    
    public function updateRules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string'],
            'website' => ['nullable', 'string'],
            'business_name' => ['required', 'string'],
            'country_id' => ['required', 'exists:countries,id'],
            'line1' => ['required', 'string'],
            'line2' => ['nullable', 'string'],
            'city' => ['required', 'string'],
            'state' => ['nullable', 'string'],
            'postal_code' => ['nullable', 'string'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'enable_product_reviews' => ['nullable', 'string'],
            'enable_product_coupon' => ['nullable', 'string'],
            'enable_inventory_tracking' => ['nullable', 'string'],
            'enable_shipment_tracking' => ['nullable', 'string'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'name.required' => 'The contact name is required.',
            'name.string' => 'The contact name must be a valid text.',
            
            'email.required' => 'An email address is required.',
            'email.email' => 'Please provide a valid email address.',
            
            'phone.required' => 'A phone number is required.',
            'phone.string' => 'The phone number must be valid text.',
            
            'website.string' => 'The website must be a valid URL or text.',
            
            'business_name.required' => 'The business name is required.',
            'business_name.string' => 'The business name must be valid text.',
            
            'country_id.required' => 'Please select a country.',
            'country_id.exists' => 'The selected country is invalid.',
            
            'line1.required' => 'The primary address line is required.',
            'line1.string' => 'The address must be valid text.',
            
            'line2.string' => 'The secondary address must be valid text.',
            
            'city.required' => 'The city is required.',
            'city.string' => 'The city must be valid text.',
            
            'state.string' => 'The state must be valid text.',
            
            'postal_code.string' => 'The postal code must be valid text.',
            
            'currency_id.required' => 'Please select a currency.',
            'currency_id.exists' => 'The selected currency is invalid.',
            
            'shop_logo.image' => 'The store logo must be an image file.',
            'shop_logo.mimes' => 'The store logo must be a file of type: jpeg, png, jpg, gif, or svg.',
        ];
    }
    
}
