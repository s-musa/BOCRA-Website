<?php

namespace App\Models;

use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use App\Traits\HasYouTubeVideo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Section extends Model implements HasMedia
{
    use HasHashid, HashidRouting, HasYouTubeVideo, InteractsWithMedia;
    
    protected $table = 'sections';
    protected $primaryKey = 'id';
    protected $appends = ['hashid', 'youtube_video_id'];
    protected $casts = [
        'include_contact_cards' => 'boolean',
        'section_has_image' => 'boolean',
        'section_image_first' => 'boolean',
        'has_cta_buttons' => 'boolean',
        'section_has_map' => 'boolean',
        'sliding_hero_section' => 'boolean',
        'section_has_bg' => 'boolean',
        'order' => 'integer',
    ];
    protected $fillable = [
        'page_id', 'type', 'section_type_id', 'spa_section_name', 'spa_section_id', 'title', 'sub_title', 'component_type',
        'details', 'include_contact_cards', 'section_has_image', 'section_image_first', 'has_cta_buttons', 'section_has_map',
        'sliding_hero_section', 'section_has_bg', 'section_background_type', 'section_background_color', 'section_style',
        'width_style', 'width', 'form_title', 'form_sub_title', 'form_button_text',
        'order', 'active', 'map_link', 'video_type', 'video_link'
    ];
    
    public function section_type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SectionType::class, 'section_type_id', 'id');
    }
    
    public function page(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id', 'id');
    }
    
    public function cta_buttons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SectionCtaButton::class);
    }
    
    public function section_cards(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SectionCard::class);
    }
    
    public function icon_boxes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(IconBox::class);
    }
    
    public function hero_slides(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(HeroSlide::class);
    }
    
    public function faqs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SectionFaq::class);
    }
    
    public function testimonials(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SectionTestimonial::class);
    }
    
    public function pricing_plans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PricingPlan::class);
    }
    
    public function section_link(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(SectionLink::class);
    }
    
    // 👇 Add this virtual relationship to simplify access
    public function getLinkableAttribute()
    {
        return $this->sectionLink?->linkable;
    }
    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('section_image')
            ->useDisk('media')
            ->useFallbackPath(public_path('/dummy-image.jpg'))
            ->acceptsFile(function (File $file) {
                return in_array($file->mimeType, [
                    'image/jpg', 'image/jpeg', 'image/png',
                ]);
            })
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(831)
                    ->height(301);
            });
        
        $this->addMediaCollection('section_bg')
            ->useDisk('media')
            ->useFallbackPath(public_path('/dummy-image.jpg'))
            ->acceptsFile(function (File $file) {
                return in_array($file->mimeType, [
                    'image/jpg', 'image/jpeg', 'image/png',
                ]);
            })
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(831)
                    ->height(301);
            });
        
        $this->addMediaCollection('section-gallery')
            ->useDisk('media')
            ->useFallbackPath(public_path('/dummy-image.jpg'))
            ->acceptsFile(function (File $file) {
                return in_array($file->mimeType, [
                    'image/jpg', 'image/jpeg', 'image/png',
                ]);
            })
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(831)
                    ->height(301);
            });
    }
    
    protected static function booted(): void
    {
        static::deleting(function ($section) {
            $section->cta_buttons->each->delete();
            $section->hero_slides->each->delete();
            $section->section_cards->each->delete();
            $section->icon_boxes->each->delete();
            $section->faqs->each->delete();
            $section->testimonials->each->delete();
            
//            $section->pricing_plans->each(function ($plan) {
//                $plan->features()->delete();
//                $plan->delete();
//            });
            
            $section->clearMediaCollection('section_image');
            $section->clearMediaCollection('section_bg');
            $section->clearMediaCollection('section-gallery');
        });
    }
}
