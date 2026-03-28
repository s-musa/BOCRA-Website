<?php

namespace App\Http\Requests\Restaurant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RestaurantRequest extends FormRequest
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
        if($this->method() === 'POST') {
            return $this->createRules();
        }
        
        return $this->updateRules();
    }
    
    public function createRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'module_id' => ['required', 'integer', 'exists:modules,id'],
            'slug' => ['nullable', 'string', Rule::unique('rst_restaurants', 'slug')],
            'description' => ['nullable', 'string'],
            'email_primary' => ['required', 'email'],
            'email_secondary' => ['nullable', 'email'],
            'phone_primary' => ['required', 'string', 'max:20'],
            'phone_secondary' => ['nullable', 'string', 'max:20'],
            'website' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'currency_id' => ['required', Rule::exists('currencies', 'id')],
            'opening_time' => ['nullable', 'date_format:H:i'],
            'closing_time' => ['nullable', 'date_format:H:i'],
            'opening_days' => ['nullable', 'array'],
            'terms_and_conditions' => ['nullable', 'string'],
            'cancellation_policy' => ['nullable', 'string'],
            'cuisine_type' => ['nullable', 'string'],
            'status' => ['nullable', 'in:active,inactive,maintenance'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['nullable', Rule::exists('rst_amenities', 'id')],
            'restaurant_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5020'],
        ];
    }
    
    public function updateRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', Rule::unique('rst_restaurants', 'slug')->ignore($this->restaurant)],
            'description' => ['nullable', 'string'],
            'email_primary' => ['required', 'email'],
            'email_secondary' => ['nullable', 'email'],
            'phone_primary' => ['required', 'string', 'max:20'],
            'phone_secondary' => ['nullable', 'string', 'max:20'],
            'website' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'currency_id' => ['required', Rule::exists('currencies', 'id')],
            'opening_time' => ['nullable', 'date_format:H:i'],
            'closing_time' => ['nullable', 'date_format:H:i'],
            'opening_days' => ['nullable', 'array'],
            'terms_and_conditions' => ['nullable', 'string'],
            'cancellation_policy' => ['nullable', 'string'],
            'cuisine_type' => ['nullable', 'string'],
            'status' => ['nullable', 'in:active,inactive,maintenance'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['nullable', Rule::exists('rst_amenities', 'id')],
        ];
    }
}
