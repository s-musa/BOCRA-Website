<?php

namespace App\Http\Requests;

use App\Models\Section;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SectionRequest extends FormRequest
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
            'page_id' => ['required', Rule::exists('pages', 'id')],
            'spa_section_name' => ['nullable', 'string'],
            'type' => ['required', 'string'],
            'title' => ['nullable', 'string'],
            'sub_title' => ['nullable', 'string'],
            'component_type' => ['nullable', 'string'],
            'details' => ['nullable', 'string'],
            'include_contact_cards' => ['boolean'],
            'section_has_image' => ['boolean'],
            'section_image_first' => ['boolean'],
            'media' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5020'],
            'has_cta_buttons' => ['boolean'],
            'cta_buttons' => ['nullable', 'array', 'min:1'],
            'cta_buttons.*.page' => ['nullable'],
            'cta_buttons.*.cta_button_text' => ['nullable', 'string'],
            'cta_buttons.*.cta_button_type' => ['nullable'],
            'section_cards' => ['nullable', 'array', 'min:1'],
            'section_cards.*.title' => ['required', 'string'],
            'section_cards.*.icon' => ['nullable', 'string'],
            'section_cards.*.details' => ['required'],
            'hero_slides' => ['nullable', 'array', 'min:1'],
            'hero_slides.*.title' => ['nullable'],
            'hero_slides.*.sub_title' => ['nullable'],
            'hero_slides.*.details' => ['nullable'],
            'hero_slides.*.page' => ['nullable'],
            'hero_slides.*.cta_button_text' => ['nullable', 'string'],
            'hero_slides.*.cta_button_type' => ['nullable'],
            'hero_slides.*.slide_image' => ['nullable'],
            'icon_boxes' => ['nullable', 'array', 'min:1'],
            'icon_boxes.*.title' => ['required', 'string'],
            'icon_boxes.*.icon' => ['nullable', 'string'],
            'icon_boxes.*.details' => ['nullable'],
            'section_faqs' => ['nullable', 'array', 'min:1'],
            'section_faqs.*.question' => ['nullable', 'string'],
            'section_faqs.*.answer' => ['nullable'],
            'section_testimonials' => ['nullable', 'array', 'min:1'],
            'section_testimonials.*.name' => ['nullable', 'string'],
            'section_testimonials.*.position' => ['nullable'],
            'section_testimonials.*.details' => ['nullable'],
            'section_has_map' => ['boolean'],
            'sliding_hero_section' => ['boolean'],
            'section_has_bg' => ['boolean'],
            'section_background_type' => ['nullable', 'string'],
            'section_background_color' => ['nullable', 'string'],
            'background_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5020'],
            'section_style' => ['nullable', 'string'],
            'width_style' => ['nullable', 'string'],
            'width' => ['nullable', 'string'],
            'form_title' => ['nullable', 'string'],
            'form_sub_title' => ['nullable', 'string'],
            'form_button_text' => ['nullable', 'string'],
            'video_link' => ['nullable', 'string'],
            'map_link' => ['nullable', 'string'],
            'category_id' => ['nullable'],
        ];
    }
    
    public function updateRules(): array
    {
        return [
            'page_id' => ['required', Rule::exists('pages', 'id')],
            'spa_section_name' => ['nullable', 'string'],
            'type' => ['required', 'string'],
            'title' => ['nullable', 'string'],
            'sub_title' => ['nullable', 'string'],
            'component_type' => ['nullable', 'string'],
            'details' => ['nullable', 'string'],
            'include_contact_cards' => ['boolean'],
            'section_has_image' => ['boolean'],
            'section_image_first' => ['boolean'],
            'has_cta_buttons' => ['boolean'],
            'section_has_map' => ['boolean'],
            'sliding_hero_section' => ['boolean'],
            'section_has_bg' => ['boolean'],
            'section_background_type' => ['nullable', 'string'],
            'section_background_color' => ['nullable', 'string'],
            'section_style' => ['nullable', 'string'],
            'width_style' => ['nullable', 'string'],
            'width' => ['nullable', 'string'],
            'form_title' => ['nullable', 'string'],
            'form_sub_title' => ['nullable', 'string'],
            'form_button_text' => ['nullable', 'string'],
            'video_link' => ['nullable', 'string'],
            'map_link' => ['nullable', 'string'],
            'category_id' => ['nullable'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'page_id.required' => 'The page field is required.',
            'page_id.exists' => 'The selected page does not exist.',
            
            'type.required' => 'The section type is required.',
            'type.string' => 'The section type must be a string.',
            
            'title.required' => 'The section title is required.',
            'title.string' => 'The section title must be a string.',
            
            'sub_title.required' => 'The section subtitle is required.',
            'sub_title.string' => 'The section subtitle must be a string.',
            
            'details.required' => 'The section details are required.',
            'details.string' => 'The section details must be a string.',
            
            'section_image_first.boolean' => 'The section image alignment value must be true or false.',
            
            'media.image' => 'The section image must be an image file.',
            'media.mimes' => 'The section image must be a file of type: jpeg, png, jpg.',
            'media.max' => 'The section image may not be greater than 5MB.',
            
            'has_cta_buttons.boolean' => 'The CTA button toggle must be true or false.',
            
            'cta_buttons.array' => 'CTA buttons must be provided as an array.',
            'cta_buttons.min' => 'You must add at least one CTA button.',
            
            'cta_buttons.*.cta_button_text.string' => 'The CTA button text must be a string.',
        ];
    }
    
//    public function prepareForValidation(): void
//    {
//        $this->merge([
//            'title' => ucwords(strtolower($this->input('title'))),
//            'sub_title' => ucwords(strtolower($this->input('sub_title'))),
//        ]);
//    }
}
