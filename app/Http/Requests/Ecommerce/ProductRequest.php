<?php

namespace App\Http\Requests\Ecommerce;

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
            'name' => ['required', 'string', 'max:255', Rule::unique('ecm_products', 'name')],
            'base_price' => ['required', 'numeric', 'min:0'],
            'discounted_price' => ['nullable', 'numeric', 'min:0', 'lte:base_price'], // must be <= base_price
            'sku' => ['nullable', 'string', 'max:100', Rule::unique('ecm_products', 'sku')],
            'barcode' => ['nullable', 'string', 'max:100', Rule::unique('ecm_products', 'barcode')],
            'description' => ['nullable', 'string'],
            'collection_id' => ['nullable', Rule::exists('ecm_collections', 'id')],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', Rule::exists('ecm_categories', 'id')],
            'charge_tax' => ['boolean'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100', 'required_if:charge_tax,true'],
            'track_stock' => ['boolean'],
            'opening_stock' => ['nullable', 'integer', 'min:0', 'required_if:track_stock,true'],
            'status' => ['nullable', 'integer'],
            'variants' => ['nullable', 'array'],
            'variants.*.name' => ['required_with:variants.*.product_variant_id', 'nullable', 'string', 'max:255'],
            'variants.*.product_variant_id' => ['nullable', 'integer', Rule::exists('ecm_product_variants', 'id')],
            'variants.*.price_adjustment' => ['nullable', 'numeric'],
            'media' => ['nullable', 'array'],
            'media.*' => ['file', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
        ];
    }
    
    public function updateRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('ecm_products', 'name')->ignore($this->product)],
            'base_price' => ['required', 'numeric', 'min:0'],
            'discounted_price' => ['nullable', 'numeric', 'min:0', 'lte:base_price'],
            'sku' => ['nullable', 'string', 'max:100', Rule::unique('ecm_products', 'sku')->ignore($this->product)],
            'barcode' => ['nullable', 'string', 'max:100', Rule::unique('ecm_products', 'barcode')->ignore($this->product)],
            'description' => ['nullable', 'string'],
            'collection_id' => ['nullable', Rule::exists('ecm_collections', 'id')],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', Rule::exists('ecm_categories', 'id')],
            'charge_tax' => ['boolean'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100', 'required_if:charge_tax,true'],
            'track_stock' => ['boolean'],
            'opening_stock' => ['nullable', 'integer', 'min:0', 'required_if:track_stock,true'],
            'status' => ['nullable', 'integer'],
            'variants' => ['nullable', 'array'],
            'variants.*.name' => ['required_with:variants.*.product_variant_id', 'nullable', 'string', 'max:255'],
            'variants.*.product_variant_id' => ['nullable', 'integer', Rule::exists('ecm_product_variants', 'id')],
            'variants.*.price_adjustment' => ['nullable', 'numeric'],
        ];
    }
}
