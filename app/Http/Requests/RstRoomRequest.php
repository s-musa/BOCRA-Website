<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RstRoomRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', Rule::unique('rst_rooms', 'name')],
            'description' => ['nullable', 'string'],
            'room_type_id' => ['required', Rule::exists('rst_room_types', 'id'),],
            'capacity' => ['required', 'integer', 'min:1'],
            'min_capacity' => ['nullable', 'integer', 'min:1'],
            'price_per_hour' => ['nullable', 'numeric', 'min:0'],
            'price_per_day' => ['nullable', 'numeric', 'min:0'],
            'booking_deposit' => ['nullable', 'numeric', 'min:0'],
            'area_sqm' => ['nullable', 'numeric', 'min:0'],
            'is_available' => ['nullable', 'boolean'],
            'status' => ['nullable', Rule::in(['active', 'inactive', 'maintenance']),],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['nullable', Rule::exists('rst_amenities', 'id'),],
            'media' => ['nullable', 'array'],
            'media.*' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:5020'],
        ];
    }
    
    public function updateRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('rst_rooms', 'name')->ignore($this->room)],
            'description' => ['nullable', 'string'],
            'room_type_id' => ['required', Rule::exists('rst_room_types', 'id'),],
            'capacity' => ['required', 'integer', 'min:1'],
            'min_capacity' => ['nullable', 'integer', 'min:1'],
            'price_per_hour' => ['nullable', 'numeric', 'min:0'],
            'price_per_day' => ['nullable', 'numeric', 'min:0'],
            'booking_deposit' => ['nullable', 'numeric', 'min:0'],
            'area_sqm' => ['nullable', 'numeric', 'min:0'],
            'is_available' => ['nullable', 'boolean'],
            'status' => ['nullable', Rule::in(['active', 'inactive', 'maintenance']),],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['nullable', Rule::exists('rst_amenities', 'id'),],
        ];
    }
}
